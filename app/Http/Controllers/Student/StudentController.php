<?php namespace Intranet\Http\Controllers\Student;
use View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Intranet\Models\Student;
use Intranet\Models\TimeTable;
use Intranet\Models\Tutstudent;
use Intranet\Models\User;
use Intranet\Http\Services\TimeTable\TimeTableService;
use Intranet\Models\Score;
use DB;
use Excel;
use Intranet\Http\Services\User\PasswordService;

class StudentController extends BaseController {

	protected $timeTableService;
	protected $passwordService;

	public function __construct() {

		$this->timeTableService = new TimeTableService();
		$this->passwordService = new PasswordService();

	}

	public function load(Request $request)  {
		$data['title'] = 'Carga de Alumnos';
		try {
			$timeTable = $data['timeTable'] = $this->timeTableService->find($request->all());
			//check if there is an uploaded file
			$studentsExist = 0;
			$studentsGroupedByHorario = DB::table('Alumno')->groupBy('IdHorario')->get();
			if ($studentsGroupedByHorario){
				foreach($studentsGroupedByHorario as $stu){
					if ($stu->IdHorario == $timeTable->IdHorario){
						$studentsExist = 1;
						break;
					}
				}
			}
			$data['studentsExist'] = $studentsExist;				
		} catch(\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		return view('students.load',$data);
	}
	public function importExport()
	{
		return view('importExport');
	}
	public function downloadExcel($type)
	{
		$data = Item::get()->toArray();
		return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
			$excel->sheet('mySheet', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}
	public function importExcel(Request $request)
	{
		$idTimeTable =$request['idTimeTable'];
		if(Input::hasFile('import_file')){
			$path = Input::file('import_file')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();

			if(!empty($data) && $data->count()){
				//check if file was already imported
				$studentsGroupedByHorario = DB::table('Alumno')->groupBy('IdHorario')->get();
					if ($studentsGroupedByHorario){
					foreach($studentsGroupedByHorario as $stu){
						if ($stu->IdHorario == $idTimeTable){
							return back()->with('error', 'Ya se realizó la carga de alumnos para este horario');
						}
					}
				}

				$students = [];
				foreach ($data as $key => $value) {
					$value_int = intval($value[1]);
					if ($value_int != 0){ 

						$insert = [
							'Codigo' => $value_int, 
							'Nombre' => $value[2],
							'ApellidoPaterno' => $value[3],
							'ApellidoMaterno' => $value[4],
							// other fields
							'IdHorario' => $idTimeTable,
							'lleva_psp' => 0,
						];


						// Para el curso PSP
						if(isset($request['selectPsp'])){

							if(!empty($value[5]) && $value[5] != null){
								$insert['lleva_psp'] = 1;

								// Buscar alumno en la tabla de tutoria
								$student = Tutstudent::where('codigo', $value_int)->first();

								if($student != null) { //encontro alumno -> obtener su idusuario
									$insert['IdUsuario'] = $student->id_usuario;								 
								}
								else { // no encontro alumno en tutoria -> crear alumno en tutoria y usuario

									$alumnoTut['codigo'] = $insert['Codigo'];
									$alumnoTut['nombre'] = $insert['Nombre'];
									$alumnoTut['ape_paterno'] = $insert['ApellidoPaterno'];
									$alumnoTut['ape_materno'] = $insert['ApellidoMaterno'];

									if($value[5] != null){
										$alumnoTut['correo'] = $value[5];
									}
									else {
										return redirect()->back()->with('warning', 'El formato interno del archivo es incorrecto');
									}

									$user = $this->create_user_tutoria($alumnoTut);	

									if($user != null){
										$insert['IdUsuario'] = $user->IdUsuario;
									}								
								}
							}
							else{								
								return redirect()->back()->with('warning', 'El formato interno del archivo es incorrecto');
							}								
						}
						
						array_push($students, $insert);						
					}else{
						return redirect()->back()->with('warning', 'El formato interno del archivo es incorrecto');
					}
				}

				if(!empty($students)){
					foreach ($students as $student) {
						$alumno = new Student;
						$alumno->Codigo = $student['Codigo']; 
						$alumno->Nombre = $student['Nombre'];
						$alumno->ApellidoPaterno = $student['ApellidoPaterno'];
						$alumno->ApellidoMaterno = $student['ApellidoMaterno'];
						$alumno->IdHorario = $student['IdHorario'];
						
						if($student['lleva_psp'] == 1){
							$alumno->IdUsuario = $student['IdUsuario'];							
						}
						$alumno->lleva_psp = null;																
						$alumno->save();
					}
				}

				$horario = TimeTable::find($alumno->IdHorario);
				
				$horario->TotalAlumnos = $data->count();
				$horario->save();

			}else{
				return redirect()->back()->with('warning', 'Hubo un problema con el archivo de excel');
			}
		}
		return back()->with('success', 'La carga de alumnos se ha realizado exitosamente');
	}

	public function create_user_tutoria($alumnoTut){

		try {
            //se busca un alumno con el mismo codigo
            $u = User::where('Usuario', $alumnoTut['codigo'])->first();
            if($u!=null){
                return $u;
            }     

            // se crea un usuario primero
            $usuario = new User;
            $usuario->Usuario       = $alumnoTut['codigo'];            
            $usuario->Contrasena    = bcrypt(123);
            $usuario->IdPerfil      = 0; //perfil 0 para el alumno
            $usuario->save();

            //se envia el correo para resetear el password
            if ($usuario) {
                $this->passwordService->sendSetPasswordLink($usuario, $alumnoTut['correo']);
            }

            /*crear alumno en tutoria */

            $student = new Tutstudent;
            $student->codigo           = $alumnoTut['codigo'];
            $student->nombre           = $alumnoTut['nombre'];
            $student->ape_paterno      = $alumnoTut['ape_paterno'];
            $student->ape_materno      = $alumnoTut['ape_materno'];
            $student->correo           = $alumnoTut['correo'];
            $student->id_especialidad  = null;
            $student->id_usuario       = $usuario->IdUsuario;

            //se guarda en la tabla Alumnos
            $student->save();
            
            return $usuario;

        } catch (Exception $e) {
            return redirect()->back()->with('warning', 'Ha ocurrido un error');
        }
	}

	public function delete(Request $request)
	{
		try{
			DB::table('Alumno')->where('IdHorario', $request['timeTableId'])->delete();
		} catch (\Exception $e) {
			if (Score::where('IdHorario',$request['timeTableId'])->get() != null){
				return back()->with('error', 'Ya existen alumnos calificados en este horario');
			} else {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
			}
		}
		return back()->with('success', 'La lista de alumnos se ha eliminado exitosamente');
	}
}