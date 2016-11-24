<?php

namespace Intranet\Http\Controllers\API\Psp\Students;
use JWTAuth;
use Illuminate\Http\Request;
use Intranet\Models\Student;
use Intranet\Models\PspDocument;
use Intranet\Models\Supervisor;
use Dingo\Api\Routing\Helpers;
use Intranet\Models\PspStudent;
use Intranet\Models\Studentxinscriptionfiles;
use Illuminate\Routing\Controller as BaseController;
//Tested
class PspStudentsController extends BaseController
{
    use Helpers;
    
    public function getAll()
    {

        $pspstudents = PspStudent::get();


        $students = array();
        foreach ($pspstudents as $pspstudent) {


            $student = Student::where('IdAlumno',  $pspstudent->idalumno)->get()->first();
            array_push($students, $student);
          
        }

   


        return $this->response->array($students);
    }

    public function getSupStudents(){


        $user =  JWTAuth::parseToken()->authenticate();

        $supervisor =  Supervisor::where('iduser', $user->IdUsuario)->first();


                
        $pspstudents = PspStudent::where('idsupervisor',$supervisor->id)->get();

        $data = array();
                foreach ($pspstudents as $pspstudent) {



                        $student = Student::where('IdAlumno',$pspstudent->idalumno)->get()->first();



                        $data[] = $student;
                    
                }


        return $this->response->array($data );


    }
    
  


  public function getDocumentsAll()
    {        
        $documentsById = PspDocument::get();
        return $this->response->array($documentsById->toArray());
    }


  public function getDocumentsById($id)
    {        
        $documentsById = PspDocument::where('idStudent',$id)->get();
        return $this->response->array($documentsById->toArray());
    }



    public function getStudentScore(){

        $user =  JWTAuth::parseToken()->authenticate();

        $supervisor =  Supervisor::where('iduser', $user->IdUsuario)->first();

        $students = PspStudent::where('idsupervisor',
            $supervisor->id)->get();


        $scores = Studentxinscriptionfiles::where('acepta_terminos',1)->get();  








    }

    public function getStudent(){

         $user =  JWTAuth::parseToken()->authenticate();
         $student = Student::where('IdUsuario', $user->IdUsuario)->first();


         return $this->response->array($student->toArray());



    }



    public function getStudentsFinalScore(){

            $user =  JWTAuth::parseToken()->authenticate();

            $supervisor =  Supervisor::where('iduser', $user->IdUsuario)->first();


                
            $pspstudents = PspStudent::where('idsupervisor',$supervisor->id)->get();




            $data = array();
                foreach ($pspstudents as $pspstudent) {

                    

                        $student = Student::where('IdAlumno',$pspstudent->idalumno)->get()->first();


                        $inscriptionFile = Studentxinscriptionfiles::where('idpspstudents', $pspstudent->id)->first();

                        if(count($inscriptionFile) > 0)
                             $student['final_score']  = $inscriptionFile->nota_final;
                         else 
                               $student['final_score']  = -1;




                        $data[] = $student;
                    
                }


        return $this->response->array($data );



    }
    
}