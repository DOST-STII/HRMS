<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');
Route::get('home', 'HomeController@index')->name('home');
Route::get('populate', 'HomeController@populate');
Auth::routes();

//THANK YOU
Route::get('csf/thank-you', 'GuestController@thankyou');

//
Route::get('list/{page}', 'HomeController@list');

//ADMIN
Route::post('report', 'AdminController@report');
Route::get('users', 'AdminController@users');
Route::get('json/items/{tbl}', 'AdminController@json');
Route::get('service-quality-dimension', 'ServiceDimensionController@index');
Route::post('service-quality-dimension/post', 'ServiceDimensionController@post');
Route::get('report-list', 'AdminController@reportlist');
Route::get('client/report', 'CSFController@clientreport');


//IMPORT TO LMS
Route::get('lms/import', 'ImportController@index');
Route::post('lms/import-post', 'ImportController@import');

//JSON
Route::get('json/list/{page}', 'JsonController@json');

/*************** CSF ******************/
//AUDIO VISUAL
Route::get('csf/audio-visual', 'CSF\AudioVisual@index');
Route::post('csf/audio-visual-send', 'CSF\AudioVisual@create');

//EXHIBITS
Route::get('csf/exhibit', 'CSF\Exhibit@index');
Route::get('csf/virtual-exhibit/{joomlaid}', 'CSF\Exhibit@index2');
Route::post('csf/exhibit-send', 'CSF\Exhibit@create');
Route::post('csf/virtual-exhibit-send', 'CSF\Exhibit@virtual');

//NSAARRD
Route::get('csf/nsaarrd/{joomlaid}', 'CSF\NSAARRD@index');
Route::post('csf/nsaarrd-send', 'CSF\NSAARRD@create');

//EVENTS
Route::get('csf/events/{joomlaid}', 'CSF\Events@index');
Route::post('csf/events-send', 'CSF\Events@create');

//PERSONNEL RELATED
Route::get('csf/personnel-related', 'CSF\PersonnelRelated@index');
Route::post('csf/personnel-related-send', 'CSF\PersonnelRelated@create');

//VISITORS BUREAU
Route::get('csf/visitors-bureau', 'CSF\VisitorsBureau@index');
Route::post('csf/visitors-bureau-send', 'CSF\VisitorsBureau@create');

//VISITORS BUREAU
Route::get('csf/disbursement-services', 'CSF\Disbursement@index');
Route::post('csf/disbursement-services-send', 'CSF\Disbursement@create');

//WALK-IN/ONLINE PLATFORM
Route::get('csf/walkin-online-platform', 'CSF\WalkinOnline@index');
Route::post('csf/walkin-online-platform-send', 'CSF\WalkinOnline@create');

//ICT SERVICES
Route::get('csf/ict-services', 'CSF\ICT@index');
Route::post('csf/ict-services-send', 'CSF\ICT@create');

//GRANTS-IN-AID PROGRAM
Route::get('csf/grants-in-aid-program', 'CSF\GrantProgram@index');
Route::post('csf/grants-in-aid-program-send', 'CSF\GrantProgram@create');

//NAARRDN FACILITIES IMPROVEMENT PROGRAM
Route::get('csf/facilities-improvement', 'CSF\FacilitiesImprovement@index');
Route::post('csf/facilities-improvement-send', 'CSF\FacilitiesImprovement@create');

//NON-DEGREE TRAINING
Route::get('csf/non-degree-training', 'CSF\NonDegreeTraining@index');
Route::post('csf/non-degree-training-send', 'CSF\NonDegreeTraining@create');

//NON-DEGREE TRAINING SPEAKER
Route::get('csf/non-degree-training-speaker', 'CSF\TrainingLMS@speaker');
Route::post('csf/non-degree-training-speaker-send', 'CSF\TrainingLMS@speakersend');
Route::get('csf/training-lms-speaker-json/{id}', 'CSF\NonDegreeTraining@jsonspeaker');

//NON-DEGREE TRAINING
Route::get('csf/thesis-dissertation-program', 'CSF\ThesisDissertation@index');
Route::post('csf/thesis-dissertation-program-send', 'CSF\ThesisDissertation@create');

//SEMINAR/FORUM/WORKSHOP/CONFERENCE AND OTHER SIMILAR ACTIVITY
Route::get('csf/seminar-forum-workshop', 'CSF\SemiraForumWorkshop@index');
Route::post('csf/seminar-forum-workshop-send', 'CSF\SemiraForumWorkshop@create');

//MAINTENANCE AND REPAIR OF FACILITIES
Route::get('csf/maintenance-repair', 'CSF\MaintenanceRepair@index');
Route::post('csf/maintenance-repair-send', 'CSF\MaintenanceRepair@create');

//PUBLICATION
Route::get('csf/publication', 'CSF\Publication@index');
Route::post('csf/publication-send', 'CSF\Publication@create');

//CSF FOR LMS
Route::get('csf/training-lms', 'CSF\TrainingLMS@index');
Route::post('csf/training-lms-send', 'CSF\TrainingLMS@create');
Route::get('csf/training-lms-json/{id}', 'CSF\TrainingLMS@json');



// Route::get('update-user', function () {
//     $user = App\LMSUser::get();

//     foreach ($user as $users) {
//     	$usr = App\LMSUser::where('id',$users->id)
//     						->update([
//     									"division" => getPISInfo($users->division)
//     								]);
//     }

//     // foreach ($user as $users) {

//     // 	switch (true) {
//     // 		case ($users->agerange < 20):
//     // 				$age = "20 and below";
//     // 			break;

//     // 		case ($users->agerange > 20 && $users->agerange < 31):
//     // 				$age = "21 to 30";
//     // 			break;

//     // 		case ($users->agerange > 30 && $users->agerange < 41):
//     // 				$age = "31 to 40";
//     // 			break;

//     // 		case ($users->agerange > 40 && $users->agerange < 51):
//     // 				$age = "41 to 50";
//     // 			break;

//     // 		case ($users->agerange > 50 && $users->agerange < 61):
//     // 				$age = "51 to 60";
//     // 			break;

//     // 		case ($users->agerange > 60):
//     // 				$age = "Above 60";
//     // 			break;
    		
//     // 		default:
//     // 				$age = null;
//     // 			break;
//     // 	}
//     // 	$usr = App\LMSUser::where('id',$users->id)
//     // 						->update([
//     // 									"agerange" => $age
//     // 								]);
//     // }
// });
