<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function (){
    if (Auth::check())
        Auth::logout();

    return view('welcome');
});

Route::get('/login', function (){

    if (Auth::check())
        Auth::logout();
    return view('welcome');
});

Route::post('/login', 'Auth\AuthController@authenticate');
Route::get('/logout', function(){
    if (Auth::check()){
        Auth::logout();
        Session::forget('user');
    }
    return redirect('/');
});

Route::get('/password_reset/{token}', 'User\UserController@passwordForm');
Route::post('/password_reset', 'User\UserController@resetPassword');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/improvement', function() {
        return view('improvement');
    });

    Route::get('/new', function() {
        return view('newimprovement');
    });

    Route::group(['prefix' => 'myfaculties'], function() {
        Route::get('/', ['as' => 'index.subindex', 'uses' => 'Subindex\SubindexController@index']);
    });

    Route::group(['prefix' => 'home'], function() {
        Route::get('/', ['as' => 'layout.master', 'uses' => 'Home\HomeController@home']);
    });


    //Rubric Routes
    Route::group(['prefix' => 'rubrics'], function() {

        Route::get('/', ['as' => 'index.rubrics', 'uses' => 'Rubric\RubricController@index']);
        Route::get('/createRubric', ['as' => 'createRubric.rubrics', 'uses' => 'Rubric\RubricController@create']);
        Route::post('/save', ['as' => 'saveRubric.rubrics', 'uses' => 'Rubric\RubricController@save']);
        Route::get('/editRubric', ['as' => 'editRubric.rubrics', 'uses' => 'Rubric\RubricController@edit']);
        Route::post('/updateRubric', ['as' => 'updateRubric.rubrics', 'uses' => 'Rubric\RubricController@update']);
        Route::get('/viewRubric', ['as' => 'viewRubric.rubrics', 'uses' => 'Rubric\RubricController@view']);
        Route::get('/chooseRubric', ['as' => 'chooseRubric.rubrics', 'uses' => 'Rubric\RubricController@choose']);
        Route::get('/deleteRubric', ['as' => 'deleteRubric.rubrics', 'uses' => 'Rubric\RubricController@delete']);

        //AJAX routes

        Route::get('/getRubricAspects/{rubricCode}', ['uses' => 'Rubric\RubricController@getRubricAspects']);
        Route::get('/findRE/{idStudentsResult}', ['uses' => 'Rubric\RubricController@findRE']);
        Route::get('/indexByRE/{idStudentsResult}', ['uses' => 'Rubric\RubricController@indexByRE']);
    });


    Route::group(['prefix' => 'cicles'], function() {
        Route::get('/', ['as' => 'index.cicles', 'uses' => 'Cicles\CiclesController@index']);
        Route::get('/new', ['as' => 'new.cicles', 'uses' => 'Cicles\CiclesController@create']);
        Route::get('/edit', ['as' => 'edit.cicles', 'uses' => 'Cicles\CiclesController@edit']);
    });

    //Enhancement Plan Routes
    Route::group(['prefix' => 'enhacementPlan'], function() {
        Route::get('/', ['as' => 'index.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@index']);
        Route::get('/new', ['as' => 'new.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@create']);
        Route::post('/save', ['as' => 'save.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@save']);
        Route::get('/edit', ['as' => 'edit.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@edit']);
        Route::post('/update', ['as' => 'update.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@update']);
        Route::get('/tracing', ['as' => 'tracing.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@tracing']);
        Route::post('/comment', ['as' => 'comment.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@comment']);

        Route::get('/uploadAction', ['as' => 'uploadAction.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@uploadAction']);
        Route::get('/downloadAction', ['as' => 'downloadAction.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@downloadAction']);
        Route::post('/add',[ 'as' => 'addentryA', 'uses' => 'EnhacementPlan\EnhacementController@add']);
        Route::post('/addPlan',[ 'as' => 'addPentryA', 'uses' => 'EnhacementPlan\EnhacementController@addPlan']);
        Route::get('/delete', ['as' => 'delete.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@delete']);
        Route::post('/search', ['as' => 'search.enhacementPlan', 'uses' => 'EnhacementPlan\EnhacementController@search']);

        //AJAX routes

        Route::get('/getEnhacementPlan/{improvementPlanId}', ['uses' => 'EnhacementPlan\EnhacementController@getEnhacementPlan']);
        Route::get('/CiclesAndTeachers', ['uses' => 'EnhacementPlan\EnhacementController@CiclesAndTeachers']);
    });

    //DictatedCourses Plan Routes
    Route::group(['prefix' => 'dictatedCourses'], function() {
        Route::get('/', ['as' => 'index.dictatedCourses', 'uses' => 'DictatedCourses\DictatedCoursesController@index']);
        Route::get('/edit', ['as' => 'edit.dictatedCourses', 'uses' => 'DictatedCourses\DictatedCoursesController@edit']);
        Route::post('/update', ['as' => 'update.dictatedCourses', 'uses' => 'DictatedCourses\DictatedCoursesController@update']);
        Route::get('/view', ['as' => 'view.dictatedCourses', 'uses' => 'DictatedCourses\DictatedCoursesController@view']);
        Route::get('/timetable',['as'=>'timetable.dictatedCourses','uses'=>'DictatedCourses\DictatedCoursesController@timetable']);
        Route::post('/register',['as'=>'register.dictatedCourses','uses'=>'DictatedCourses\DictatedCoursesController@register']);
        Route::get('/delete', ['as' => 'delete.dictatedCourses', 'uses' => 'DictatedCourses\DictatedCoursesController@delete']);
        Route::get('/editTimeTable', ['as' => 'editTimeTable.dictatedCourses', 'uses' => 'DictatedCourses\DictatedCoursesController@editTimeTable']);
        Route::post('/updateTimeTable',['as'=>'updateTimeTable.dictatedCourses','uses'=>'DictatedCourses\DictatedCoursesController@updateTimeTable']);

        //AJAX routes

        Route::get('/getCodigo/{codeT}', ['uses' => 'DictatedCourses\DictatedCoursesController@getCodigo']);
    });

    //Measurement Source
    Route::group(['prefix' => 'measurementSource'], function() {
        Route::get('/',['as'=>'index.measurementSource','uses'=>'MeasurementSource\MeasurementSourceController@index']);
        Route::post('/save', ['as' => 'save.measurementSource', 'uses' => 'MeasurementSource\MeasurementSourceController@save']);
        Route::post('/update', ['as' => 'update.measurementSource', 'uses' => 'MeasurementSource\MeasurementSourceController@update']);
        Route::get('/delete', ['as' => 'delete.measurementSource', 'uses' => 'MeasurementSource\MeasurementSourceController@delete']);

        Route::get('/viewMesuringByCourse', ['as' => 'viewMesuringByCourse.measurementSource', 'uses' => 'MeasurementSource\MeasurementSourceController@viewMesuringByCourse']);
        Route::get('/editMesuringByCourse', ['as' => 'editMesuringByCourse.measurementSource', 'uses' => 'MeasurementSource\MeasurementSourceController@editMesuringByCourse']);
        Route::post('/measuringByCourse', ['uses' => 'MeasurementSource\MeasurementSourceController@measuringByCourse']);
        Route::post('/saveMesuringByCourse', ['as' => 'saveMesuringByCourse.measurementSource','uses' => 'MeasurementSource\MeasurementSourceController@saveMesuringByCourse']);

        //AJAX routes

        Route::get('/findMeasuringByCriterion/{idCriterioCurso}', ['uses' => 'MeasurementSource\MeasurementSourceController@findMeasuringByCriterion']);

    });

    //Type Improvement Plan Routes
    Route::group(['prefix' => 'typeImprovement'], function() {
        Route::get('/',['as'=>'index.typeImprovement','uses'=>'TypeImprovementPlan\TypeImprovementPlanController@index']);
        Route::get('/new',['as'=>'new.typeImprovement','uses'=>'TypeImprovementPlan\TypeImprovementPlanController@create']);
        Route::post('/save',['as'=>'save.typeImprovement','uses'=>'TypeImprovementPlan\TypeImprovementPlanController@save']);
        Route::get('/edit',['as'=>'edit.typeImprovement','uses'=>'TypeImprovementPlan\TypeImprovementPlanController@edit']);
        Route::post('/update',['as'=>'update.typeImprovement','uses'=>'TypeImprovementPlan\TypeImprovementPlanController@update']);
        Route::get('/delete', ['as' => 'delete.typeImprovement', 'uses' => 'TypeImprovementPlan\TypeImprovementPlanController@delete']);

        //AYAX routes
        Route::get('/getCode/{code}', ['uses' => 'TypeImprovementPlan\TypeImprovementPlanController@getCode']);
        Route::get('/getTypeImprovementPlan/{typeImprovementPlanId}', ['uses' => 'TypeImprovementPlan\TypeImprovementPlanController@getTypeImprovementPlan']);
    });

    //Study Plan Routes
    Route::group(['prefix' => 'studyPlan'], function() {
        Route::get('/', ['as' => 'index.studyPlan', 'uses' => 'StudyPlan\StudyPlanController@index']);
        Route::get('/view/{course_id}/semester/{semester_id}', ['as' => 'view.studyPlan', 'uses' => 'StudyPlan\StudyPlanController@view']);
        Route::get('/{semester_id}/courses', ['uses' => 'StudyPlan\StudyPlanController@getCourses']);
    });


    //Faculty Routes
    Route::group(['prefix' => 'faculty'], function() {

        // faculty vpopmail_add_domain(domain, dir, uid, gid)
        Route::get('/', ['as' => 'index.faculty', 'uses' => 'Faculty\FacultyController@index']);
        Route::get('/new', ['as' => 'new.faculty', 'uses' => 'Faculty\FacultyController@create']);
        Route::post('/save', ['as' => 'save.faculty', 'uses' => 'Faculty\FacultyController@save']);
        Route::get('/edit', ['as' => 'edit.faculty', 'uses' => 'Faculty\FacultyController@edit']);
        Route::get('/editCoordinator',['as'=>'editCoordinator.faculty','uses'=>'Faculty\FacultyController@editCoordinator']);
        Route::post('/updateCoordinator',['as'=>'updateCoordinator.faculty','uses'=>'Faculty\FacultyController@updateCoordinator']);
        Route::get('/delete/{id}', ['as' => 'delete.faculty', 'uses' => 'Faculty\FacultyController@delete']);
        Route::post('/update', ['as' => 'update.faculty', 'uses' => 'Faculty\FacultyController@update']);
        Route::get('/view/{id}', ['as' => 'view.faculty', 'uses' => 'Faculty\FacultyController@view']);



        // assign faculty
        Route::get('/periods', ['as' => 'viewPeriod.faculty', 'uses' => 'Faculty\FacultyController@getPeriods']);
        Route::get('/periods/create', ['as' => 'createPeriod.faculty', 'uses' => 'Faculty\FacultyController@createPeriod']);
        Route::post('/periods/create', ['as' => 'storePeriod.faculty', 'uses' => 'Faculty\FacultyController@storePeriod']);
        Route::get('/periods/{period_id}', ['as' => 'editPeriod.faculty', 'uses' => 'Faculty\FacultyController@editPeriod']);
        Route::get('/editPeriod', ['as' => 'editPeriod.faculty', 'uses' => 'Faculty\FacultyController@editPeriod']);
        Route::post('/updatePeriod', ['as' => 'updatePeriod.faculty', 'uses' => 'Faculty\FacultyController@updatePeriod']);
        Route::get('/end/period/{period_id}', ['as' => 'endPeriod.faculty', 'uses' => 'Faculty\FacultyController@endPeriod']);

        Route::get('/viewCycle', ['as' => 'viewCycle.faculty', 'uses' => 'Faculty\FacultyController@viewCycle']);
        Route::get('/editCycle', ['as' => 'editCycle.faculty', 'uses' => 'Faculty\FacultyController@editCycle']);
        Route::post('/updateCycle', ['as' => 'updateCycle.faculty', 'uses' => 'Faculty\FacultyController@updateCycle']);


        Route::get('/Assign', ['as' => 'indexAcademicCycle.faculty', 'uses' => 'Faculty\FacultyController@indexAcademicCycle']);
        Route::get('/editAssign', ['as' => 'editAcademicCycle.faculty', 'uses' => 'Faculty\FacultyController@editAcademicCycle']);
        Route::post('/updateAssign', ['as' => 'updateAcademicCycle.faculty', 'uses' => 'Faculty\FacultyController@updateAcademicCycle']);
        Route::post('/saveAssign', ['as' => 'saveAcademicCycle.faculty', 'uses' => 'Faculty\FacultyController@saveAcademicCycle']);
        Route::get('/newAssign', ['as' => 'newAcademicCycle.faculty', 'uses' => 'Faculty\FacultyController@createAcademicCycle']);
        Route::get('/viewAssign/{id}', ['as' => 'viewAcademicCycle.faculty', 'uses' => 'Faculty\FacultyController@viewAcademicCycle']);
        Route::post('/activate', ['as' => 'activate.faculty', 'uses' => 'Faculty\FacultyController@activateCycle']);
        Route::get('/desactivate', ['as' => 'desactivate.faculty', 'uses' => 'Faculty\FacultyController@desactivateCycle']);
        Route::get('/desactivatePeriod', ['as' => 'desactivatePeriod.faculty', 'uses' => 'Faculty\FacultyController@desactivatePeriod']);

        //ajax routes

        Route::get('/getCode/{codigo}', ['uses' => 'Faculty\FacultyController@getCode']);
        Route::get('/getName/{nombre}', ['uses' => 'Faculty\FacultyController@getName']);
    });

    //Our Faculty Routes
    Route::group(['prefix' => 'ourFaculty'], function() {
        Route::get('/{id}', ['as' => 'index.ourFaculty', 'uses' => 'OurFaculty\OurFacultyController@index']);
    });

    //User routes
    Route::group(['prefix' => 'users'], function() {
        Route::get('/', ['as' => 'index.users', 'uses' => 'User\UserController@index']);
        Route::get('/new', ['as' => 'new.users', 'uses' => 'User\UserController@create']);
        Route::get('/edit', ['as' => 'edit.users', 'uses' => 'User\UserController@edit']);
        Route::post('/save', ['as' => 'save.users', 'uses' => 'User\UserController@save']);
        Route::get('/delete', ['as' => 'delete.users', 'uses' => 'User\UserController@delete']);
        Route::post('/update', ['as' => 'update.users', 'uses' => 'User\UserController@update']);
        Route::get('/view', ['as' => 'view.users', 'uses' => 'User\UserController@view']);
        Route::get('/getUsername/{code}', ['uses' => 'User\UserController@getUsername']);
    });

    //Courses routes
    Route::group(['prefix' => 'courses'], function() {
        Route::get('/', ['as' => 'index.courses', 'uses' => 'Course\CourseController@index']);
        Route::get('/new', ['as' => 'new.courses', 'uses' => 'Course\CourseController@create']);
        Route::get('/edit', ['as' => 'edit.courses', 'uses' => 'Course\CourseController@edit']);
        Route::get('/evidence', ['as' => 'evidence.courses', 'uses' => 'Course\CourseController@evidence']);
        Route::post('/save', ['as' => 'save.courses', 'uses' => 'Course\CourseController@save']);
        Route::get('/delete', ['as' => 'delete.courses', 'uses' => 'Course\CourseController@delete']);
        Route::post('/update', ['as' => 'update.courses', 'uses' => 'Course\CourseController@update']);
        Route::get('/view', ['as' => 'view.courses', 'uses' => 'Course\CourseController@view']);
        Route::get('/pick', ['as' => 'pick.courses', 'uses' => 'Course\CourseController@pick']);
        Route::get('/getCode/{code}', ['uses' => 'Course\CourseController@getCode']);
    });

    Route::group(['prefix' => 'evaluatedCourses'], function() {
        Route::get('/', ['as' => 'index.evaluatedCourses', 'uses' => 'EvaluatedCourse\EvaluatedCourseController@index']);
        Route::get('/edit', ['as' => 'edit.evaluatedCourses', 'uses' => 'EvaluatedCourse\EvaluatedCourseController@edit']);
    });

    //Educational Objectives Routes
    Route::group(['prefix' => 'educationalObjectives'], function() {
        Route::get('/', ['as' => 'index.educationalObjectives', 'uses' => 'EducationalObjectives\EducationalObjectivesController@index']);
        Route::get('/view', ['as' => 'view.educationalObjectives', 'uses' => 'EducationalObjectives\EducationalObjectivesController@view']);
        Route::get('/new', ['as' => 'new.educationalObjectives', 'uses' => 'EducationalObjectives\EducationalObjectivesController@create']);
        Route::get('/edit', ['as' => 'edit.educationalObjectives', 'uses' => 'EducationalObjectives\EducationalObjectivesController@edit']);
        Route::post('/save', ['as' => 'save.educationalObjectives', 'uses' => 'EducationalObjectives\EducationalObjectivesController@save']);
        Route::post('/update', ['as' => 'update.educationalObjectives', 'uses' => 'EducationalObjectives\EducationalObjectivesController@update']);

        Route::get('/getEducationalObjectiveStudentResults/{educationalObjective_code}', ['uses' => 'EducationalObjectives\EducationalObjectivesController@getEducationalObjectiveStudentResults']);
        Route::get('/getById/{educationalObjective_code}', ['uses' => 'EducationalObjectives\EducationalObjectivesController@getById']);
        Route::get('/delete', ['uses' => 'EducationalObjectives\EducationalObjectivesController@delete']);
        Route::get('/indexByFaculty/{idFaculty}', ['uses' => 'EducationalObjectives\EducationalObjectivesController@indexByFaculty']);
        Route::get('/findFaculty/{idFaculty}', ['uses' => 'EducationalObjectives\EducationalObjectivesController@findFaculty']);

         //AYAX routes
        Route::get('/getNumber/{number}', ['uses' => 'EducationalObjectives\EducationalObjectivesController@getNumber']);
    });

    //Suggestion Routes
    Route::group(['prefix' => 'suggestions'], function() {
        Route::get('/', ['as' => 'index.suggestions', 'uses' => 'Suggestion\SuggestionController@index']);
        Route::get('/indexAll', ['as' => 'indexAll.suggestions', 'uses' => 'Suggestion\SuggestionController@indexAll']);
        Route::get('/aprrove', ['as' => 'aprrove.suggestions', 'uses' => 'Suggestion\SuggestionController@aprrove']);
        Route::get('/reject', ['as' => 'reject.suggestions', 'uses' => 'Suggestion\SuggestionController@reject']);
        Route::get('/new', ['as' => 'new.suggestions', 'uses' => 'Suggestion\SuggestionController@create']);
        Route::post('/save', ['as' => 'save.suggestions', 'uses' => 'Suggestion\SuggestionController@save']);
        Route::get('/edit', ['as' => 'edit.suggestions', 'uses' => 'Suggestion\SuggestionController@edit']);
        Route::post('/update', ['as' => 'update.suggestions', 'uses' => 'Suggestion\SuggestionController@update']);
        Route::get('/delete', ['as' => 'delete.suggestions', 'uses' => 'Suggestion\SuggestionController@delete']);
        Route::post('/search', ['as' => 'search.suggestions', 'uses' => 'Suggestion\SuggestionController@searchSuggestions']);

        //AJAX routes
        Route::get('/getSuggestion/{suggestionId}', ['uses' => 'Suggestion\SuggestionController@getSuggestion']);
        Route::post('/searchSuggestions', ['uses' => 'Suggestion\SuggestionController@searchSuggestions']);
    });

    //Teacher Management Routes
    Route::group(['prefix' => 'teachers'], function() {
        Route::get('/', ['as' => 'index.teachers', 'uses' => 'Teacher\TeacherController@index']);
        Route::get('/new', ['as' => 'new.teacher', 'uses' => 'Teacher\TeacherController@create']);
        Route::post('/save', ['as' => 'saveTeacher.teachers', 'uses' => 'Teacher\TeacherController@save']);
        Route::get('/edit', ['as' => 'edit.teacher', 'uses' => 'Teacher\TeacherController@edit']);
        Route::get('/delete', ['as' => 'delete.teacher', 'uses' => 'Teacher\TeacherController@delete']);
        Route::post('/update', ['as' => 'updateTeacher.teachers', 'uses' => 'Teacher\TeacherController@update']);
        Route::get('/view', ['as' => 'view.teacher', 'uses' => 'Teacher\TeacherController@view']);
        Route::post('/', ['as' => 'search.teachers', 'uses' => 'Teacher\TeacherController@search']);

        //AJAX routes
        Route::post('/search', ['uses' => 'Teacher\TeacherController@searchModal']);

        Route::get('/getTeacher/{teacherId}', ['uses' => 'Teacher\TeacherController@getTeacher']);
        Route::get('/delete/{teacherid}', ['uses' => 'Teacher\TeacherController@delete']);
        Route::get('/getCodigo/{codigo}', ['uses' => 'Teacher\TeacherController@getCodigo']);
        Route::get('/getEmail/{email}', ['uses' => 'Teacher\TeacherController@getEmail']);
    });

    //MyCourses routes
    Route::group(['prefix' => 'myCourses'], function() {
        Route::get('/', ['as' => 'index.myCourses', 'uses' => 'MyCourses\MyCoursesController@index']);
        Route::get('/tableView', ['as' => 'tableView.myCourses', 'uses' => 'MyCourses\MyCoursesController@tableView']);
        Route::get('/tableEdit', ['as' => 'tableEdit.myCourses', 'uses' => 'MyCourses\MyCoursesController@tableEdit']);
        Route::get('/finish', ['as' => 'finish.myCourses', 'uses' => 'MyCourses\MyCoursesController@finish']);
        Route::get('/reportView', ['as' => 'reportView.myCourses', 'uses' => 'MyCourses\MyCoursesController@reportView']);
        Route::get('/reportEdit', ['as' => 'reportEdit.myCourses', 'uses' => 'MyCourses\MyCoursesController@reportEdit']);
        Route::post('/reportSave', ['as' => 'reportSave.myCourses', 'uses' => 'MyCourses\MyCoursesController@reportSave']);
        Route::post('/saveTable', ['as' => 'saveTable.myCourses', 'uses' => 'MyCourses\MyCoursesController@saveTable']);

        //Student Management Routes
        Route::group(['prefix' => 'students'], function() {
            Route::get('/load', ['as' => 'load.students', 'uses' => 'Student\StudentController@load']);

            Route::get('/importExport', 'Student\StudentController@importExport');
            Route::get('/downloadExcel/{type}', 'Student\StudentController@downloadExcel');
            Route::post('/importExcel', [ 'as' => 'upload.students', 'uses' => 'Student\StudentController@importExcel']);
            Route::get('/delete', 'Student\StudentController@delete');
        });

        //Evidence Management Routes
        Route::group(['prefix' => 'evidences'], function() {
            Route::get('/upload', ['as' => 'upload.evidences', 'uses' => 'Evidence\EvidenceController@upload']);
            Route::get('/get/{filename}', ['as' => 'getentry.evidences', 'uses' => 'Evidence\EvidenceController@get']);
            Route::post('/add',[ 'as' => 'addentry.evidences', 'uses' => 'Evidence\EvidenceController@add']);
            Route::post('/addM',[ 'as' => 'addentryM.evidences', 'uses' => 'Evidence\EvidenceController@addM']);
            Route::get('/uploadMeasuring', ['as' => 'uploadMeasuring.evidences', 'uses' => 'Evidence\EvidenceController@uploadMeasuring']);
            Route::get('/download/{filename}', ['as' => 'getDownload.evidences' , 'uses' => 'Evidence\EvidenceController@getDownload']);
            Route::get('/delete', ['as' => 'delete.evidences' , 'uses' => 'Evidence\EvidenceController@delete']);
            Route::get('/deleteM', ['as' => 'deleteM.evidences' , 'uses' => 'Evidence\EvidenceController@deleteM']);
        });
    });

    //Timetable Management Route
    Route::group(['prefix' => 'timetables'], function() {
        Route::get('/', ['as' => 'index.timetable', 'uses' => 'Timetable\TimetableController@index']);

        Route::get('/create', ['as' => 'create.timetable', 'uses' => 'Timetable\TimetableController@create']);
        Route::post('/save', ['as' => 'save.timetable', 'uses' => 'Timetable\TimetableController@save']);

        Route::get('/edit', ['as' => 'edit.timetable', 'uses' => 'Timetable\TimetableController@edit']);
    });

    //Students Results
    Route::group(['prefix' => 'studentsResult'], function() {
        Route::get('/', ['as' => 'index.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@index']);
        Route::get('/create', ['as' => 'create.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@create']);
        Route::post('/save', ['as' => 'save.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@save']);
        Route::get('/view/{id}', ['as' => 'view.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@view']);
        Route::get('/edit/{id}', ['as' => 'edit.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@edit']);
        Route::post('/update', ['as' => 'update.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@update']);
        Route::get('/delete', ['as' => 'delete.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@delete']);

        Route::get('/indexEvaluated', ['as' => 'indexEvaluated.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@indexEvaluated']);
        Route::get('/editEvaluated', ['as' => 'editEvaluated.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@editEvaluated']);
        Route::post('/updateEvaluated', ['as' => 'updateEvaluated.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@updateEvaluated']);

        Route::get('/contributions', ['as' => 'contributions.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@contributions']);
        Route::post('/updateContributions', ['as' => 'updateContributions.studentsResult', 'uses' => 'StudentsResult\StudentsResultController@updateContributions']);

        //AYAX routes
        Route::get('/getById/{studentResultCode}', ['uses' => 'StudentsResult\StudentsResultController@getById']);
        Route::get('/getIdentifier/{identifier}', ['uses' => 'StudentsResult\StudentsResultController@getIdentifier']);
    });

    //Aspect Routes
    Route::group(['prefix' => 'aspects'], function() {
        Route::get('/', ['as' => 'index.aspects', 'uses' => 'Aspect\AspectController@index']);
        Route::get('/create', ['as' => 'create.aspects', 'uses' => 'Aspect\AspectController@create']);
        Route::post('/save', ['as' => 'save.aspects', 'uses' => 'Aspect\AspectController@save']);
        Route::get('/edit', ['as' => 'edit.aspects', 'uses' => 'Aspect\AspectController@edit']);
        Route::post('/update', ['as' => 'update.aspects', 'uses' => 'Aspect\AspectController@update']);
        Route::get('/view', ['as' => 'view.aspects', 'uses' => 'Aspect\AspectController@view']);
        Route::get('/delete', ['as' => 'delete.aspects', 'uses' => 'Aspect\AspectController@delete']);

        //AJAX Routes

        Route::get('/findRE/{idStudentsResult}', ['uses' => 'Aspect\AspectController@findRE']);
        Route::get('/findAspect', ['uses' => 'Aspect\AspectController@findAspect']);
        Route::get('/findAspectByRE/{idStudentsResult}', ['uses' => 'Aspect\AspectController@findAspectByRE']);

        Route::get('/getAspectCriteria/{aspectCode}', ['uses' => 'Aspect\AspectController@getAspectCriteria']);
        Route::get('/getAll', ['uses' => 'Aspect\AspectController@getAll']);
        Route::get('/getById/{aspectCode}', ['uses' => 'Aspect\AspectController@getById']);
        Route::get('/indexByRubric/{idRubric}', ['uses' => 'Aspect\AspectController@indexByRubric']);
    });

    //Criteria Routes
    Route::group(['prefix' => 'criteria'], function() {
        Route::get('/edit', ['as' => 'edit.criteria', 'uses' => 'Criteria\CriteriaController@edit']);
        Route::post('/save', ['as' => 'save.criteria', 'uses' => 'Criteria\CriteriaController@save']);
        Route::post('/update', ['as' => 'update.criteria', 'uses' => 'Criteria\CriteriaController@update']);
        Route::post('/updateCriterionLevel', ['as' => 'updateCriterionLevel.criteria', 'uses' => 'Criteria\CriteriaController@updateCriterionLevel']);
        Route::get('/view', ['as' => 'view.criteria', 'uses' => 'Criteria\CriteriaController@view']);
        Route::get('/delete', ['as' => 'delete.criteria', 'uses' => 'Criteria\CriteriaController@delete']);

        //AJAX routes

        Route::get('/findCriterionByAspect/{idAspect}', ['uses' => 'Criteria\CriteriaController@findCriterionByAspect']);
    });

    //Criteria Level Routes
    Route::group(['prefix' => 'criterialevel'], function() {
        Route::get('/', ['as' => 'index.criterialevel', 'uses' => 'CriteriaLevel\CriteriaLevelController@index']);
        Route::get('/new', ['as' => 'new.criterialevel', 'uses' => 'CriteriaLevel\CriteriaLevelController@create']);
        Route::get('/edit', ['as' => 'edit.criterialevel', 'uses' => 'CriteriaLevel\CriteriaLevelController@edit']);
        Route::post('/update', ['as' => 'update.criterialevel', 'uses' => 'CriteriaLevel\CriteriaLevelController@update']);
    });


    //Consolidated
    Route::group(['prefix' => 'consolidated'], function() {

        //Evaluation
        Route::group(['prefix' => 'evaluation'], function() {
            Route::get('/', ['as' => 'index.evaluation', 'uses' => 'Consolidated\EvaluationController@index']);
            Route::get('/view', ['as' => 'view.evaluation', 'uses' => 'Consolidated\EvaluationController@view']);
            Route::get('/download', ['as' => 'downloadAsPdf.evaluation', 'uses' => 'Consolidated\EvaluationController@downloadAsPdf']);
        });

        Route::get('/view-status', ['as' => 'view-status.evaluation', 'uses' => 'Consolidated\EvaluationController@view_matrix_advance']);
        Route::get('/status', ['as' => 'status.evaluation', 'uses' => 'Consolidated\EvaluationController@search_matrix_advance']);

        //AYAX Evaluation

        //Route::get('/view/{cycle}', ['uses' => 'Consolidated\EvaluationController@view']);

        //
        Route::get('/measuring/', ['as' => 'index.measuring', 'uses' => 'Consolidated\MeasuringController@index']);
        Route::post('/period/', ['as' => 'period.measuring', 'uses' => 'Consolidated\MeasuringController@period']);
        Route::post('/measuring/download', ['as'=>'downloadAsPdf.measuring', 'uses'=>'Consolidated\MeasuringController@downloadAsPdf']);

        Route::get('/results/', ['as' => 'index.results', 'uses' => 'Consolidated\ResultsController@index']);
        Route::get('/results/download', ['as' => 'downloadAsPdf.results', 'uses' => 'Consolidated\ResultsController@downloadAsPdf']);
    });

    //Profile Routes
    Route::group(['prefix' => 'profile'], function() {
        Route::get('/', ['as' => 'index.profile', 'uses' => 'Profile\ProfileController@index']);
        Route::get('/new', ['as' => 'new.profile', 'uses' => 'Profile\ProfileController@create']);
        Route::post('/save', ['as' => 'save.profile', 'uses' => 'Profile\ProfileController@save']);
        Route::get('/edit', ['as' => 'edit.profile', 'uses' => 'Profile\ProfileController@edit']);
        Route::get('/view', ['as' => 'view.profile', 'uses' => 'Profile\ProfileController@view']);
        Route::post('/update', ['as' => 'update.profile', 'uses' => 'Profile\ProfileController@update']);
        Route::get('/delete', ['as' => 'delete.profile', 'uses' => 'Profile\ProfileController@delete']);
    });

    //Academic Cycle
    Route::group(['prefix' => 'academicCycle'], function() {
        Route::get('/', ['as' => 'index.academicCycle', 'uses' => 'AcademicCycle\AcademicCycleController@index']);
        Route::get('/new', ['as' => 'form.academicCycle', 'uses' => 'AcademicCycle\AcademicCycleController@create']);
        Route::post('/save', ['as' => 'save.academicCycle', 'uses' => 'AcademicCycle\AcademicCycleController@save']);
        Route::get('/delete', ['as' => 'delete.academicCycle', 'uses' => 'AcademicCycle\AcademicCycleController@delete']);

        //AYAX routes

        Route::get('/getCycle/{cycle}', ['uses' => 'AcademicCycle\AcademicCycleController@getCycle']);
    });

    Route::group(['prefix' => 'myUser'], function() {
        Route::get('/edit', ['as' => 'edit.myUser', 'uses' => 'MyUser\MyUserController@edit']);
        Route::post('/update', ['as' => 'update.myUser', 'uses' => 'MyUser\MyUserController@update']);
    });
});

//FILES DOWNLOAD
Route::get('/myCourses/evidences/download/{filename}', ['as' => 'getDownload.evidences' , 'uses' => 'Evidence\EvidenceController@getDownload']);
Route::get('/enhacementPlan/get/{filename}', ['as' => 'getentry', 'uses' => 'EnhacementPlan\EnhacementController@get']);

// API endpoints
$api = app(Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'Intranet\Http\Controllers\API'], function ($api) {
        $api->post('authenticate', 'Auth\AuthController@authenticate');

        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->get('users/me', 'User\UserController@getUserInfo');

            $api->group(['namespace' => 'Faculty', 'prefix' => 'faculties'], function($api) {
                $api->get('/', 'FacultyController@get');
                $api->get('/{faculty_id}/educational-objectives', 'FacultyController@getEducationalObjectives');
                $api->get('/{faculty_id}/students-results', 'FacultyController@getStudentsResult');
                $api->get('/{faculty_id}/aspects', 'FacultyController@getAspects');
                $api->get('/{faculty_id}/evaluated_courses', 'FacultyController@getEvaluatedCourses');
                $api->get('/{faculty_id}/evaluated_courses/{course_id}/semesters/{semester_id}', 'FacultyController@getCourseReport');
                $api->get('/{faculty_id}/measure_report', 'FacultyController@getMeasureReport');
                $api->get('/{faculty_id}/suggestions', 'FacultyController@getSuggestions');
                $api->get('/{faculty_id}/improvement_plans', 'FacultyController@getImprovementsPlans');
            });
            $api->get('faculties/{f_id}/periods/actual/semesters', 'Period\PeriodController@getSemesters');

            $api->get('aspects/{id}/criterions', 'Aspect\AspectController@getCriterions');
            $api->get('/faculties/{id}/teachers', 'Faculty\FacultyController@getTeachers');
        });
    });
});



//NUEVAS RUTAS PARA SEGUNDA PARTE DEL PROYECTO