<?php 
namespace Intranet\Http\Controllers\Aspect;

use View;
use Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Intranet\Http\Requests\AspectRequest;
use Intranet\Http\Services\Aspect\AspectService;
use Intranet\Http\Services\Criterion\CriterionService;
use Intranet\Http\Services\StudentsResult\StudentsResultService;

class AspectController extends BaseController {

	protected $aspectService;
	protected $criterionService;
	protected $studentsResultService;

	public function __construct() {
		$this->aspectService = new AspectService();
		$this->criterionService = new CriterionService();
		$this->studentsResultService = new StudentsResultService();
	}

	// -------------------------------------  ASPECTO -----------------------------------

	public function index() {
		$data['title'] = 'Aspectos';
		try {			
			$id=Session::get('faculty-code');
			$studentResults= $this->studentsResultService->findByFaculty2($id);
			$data['studentsResults'] = $studentResults;
			$data['aspects'] = $this->aspectService->findByRE($studentResults);
		} catch(\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		return view('aspects.index', $data);
	}

	public function create(AspectRequest $request) {
		try {
			$data['title'] = 'Nuevo Aspecto';
			$data['resultado']=$this->studentsResultService->findById($request->all());
		} catch(\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		//dd($data['resultado']);
		return view('aspects.create', $data);
	}

	public function save(Request $request) {
		try {
			$this->aspectService->create($request->all());
		} catch(\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		return redirect()->route('index.aspects')->with('success', 'El aspecto se ha registrado exitosamente');
	}

	public function view(Request $request) {
		$data['title'] = 'Aspecto';
		try {
			$data['aspect'] = $this->aspectService->findAspect($request->all());
			//$data['criteria'] = $this->aspectService->findCriterias($request->all());
		} catch (\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		return view('aspects.view', $data);
	}

	public function edit(Request $request) {
		$data['title'] = 'Editar Aspecto';
		try {
			$data['aspect'] = $this->aspectService->findAspect($request->all());
			$data['criteria'] = $this->aspectService->findCriterias($request->all());
		} catch (\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		return view('aspects.edit', $data);
	}

	public function update(Request $request) {
		try{
			$this->aspectService->update($request->all());
			//$this->criterionService->createCriterion($request->all());
		} catch (\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		return redirect()->route('index.aspects')->with('success', 'Las modificaciones se han guardado exitosamente');
	}

	public function delete(Request $request) {
		$msg = 1;
		try{
			$msg =$this->aspectService->deleteAspect($request->all());
		} catch (\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		if($msg == 1) {
			return redirect()->route('index.aspects')->with('success', 'El registro ha sido eliminado exitosamente');
		}
		else{
			return redirect()->route('index.aspects')->with('error', 'No se puede eliminar el registro ya que exiten otros registros asociados');
		}
	}

	// ------------------------------------------  AJAX --------------------------------------

	public function getAll() {
		$list = [];
		try{
			$list = $this->aspectService->retrieveAll();
		} catch(\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		return json_encode($list);
	}

	public function getById($aspectCode){
		$asp = [];
		try{
			$asp = $this->aspectService->getAspectById($aspectCode);
		} catch(\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		return json_encode($asp);
	}

	//AJAX for retrieving criteria from aspect

	public function findRE($idStudentsResult) {
		//$data['studentsResult'] = [];
		try {
			$data['studentsResult'] = $this->studentsResultService->getById($idStudentsResult);
		} catch (\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}

		return json_encode($data);
	}

	public function findAspect() {
		$data['aspects'] = [];
		try {
			$studentResults= $this->studentsResultService->findByFacultyPeriod();
			$data['aspects'] = $this->aspectService->findByPeriod($studentResults);
		} catch (\Exception $e) {
			dd($e);
		}

		return json_encode($data);
	}

	public function findAspectByRE($idStudentsResult) {
		$data['aspects'] = [];
		try {
			$data['aspects'] = $this->studentsResultService->findAspect($idStudentsResult);
		} catch (\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}

		return json_encode($data);
	}

	public function indexByRubric($idRubric) {
		$data['aspects'] = [];
		try {
			$data['aspects'] = $this->aspectService->getAspectsByRubric($idRubric);
		} catch (\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}

		return json_encode($data);
	}

	public function getAspectCriteria($aspectCode) {
		$data['aspect'] = [];
		try{
			$data['aspect'] = $this->aspectService->getCriteriaByAspectId($aspectCode);
		} catch(\Exception $e) {
			return redirect()->back()->with('warning', 'Ha ocurrido un error');
		}
		return json_encode($data);
	}

}