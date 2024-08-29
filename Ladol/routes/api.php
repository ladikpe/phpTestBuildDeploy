<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/app_key', function (Request $request) {
    return env('APP_KEY');
});
Route::get('bc_int/', 'CompensationController@bc_int');
Route::post('navsion_integration/','CompensationController@nav_int');
Route::post('hcrecruit_add_user/','UserController@saveNewHcRecruit');

Route::get('hcrecruit_companies/','UserController@getCompaniesForIntegration');
Route::get('hcrecruit_departments/{company_id}','UserController@getDepartmentsForIntegration');
Route::get('hcrecruit_branches/{company_id}','UserController@getBranchesForIntegration');
Route::get('hcrecruit_grades','UserController@getGradesForIntegration');
Route::get('hcrecruit_roles','UserController@getRolesForIntegration');
Route::get('hcrecruit_job_roles/{department_id}','UserController@getJobRolesForIntegration');


//biometric api
Route::get('/data', 'BiometricController@data');
Route::get('/iclock/cdata', 'BiometricController@checkDevice');
Route::post('/iclock/cdata', 'BiometricController@receiveRecords');
Route::get('/iclock/getrequest', 'BiometricController@getRequest');
Route::post('/iclock/devicecmd', 'BiometricController@deviceCMD');


Route::get('/iclock/getrequest', 'BiometricController@getrequest');
Route::get('/iclock/devicecmd', 'BiometricController@deviceCMD');

Route::get('/soft/auth', 'AttendanceAppController@authenticateUser');
Route::get('/soft/clock-in', 'AttendanceAppController@softClockIn');
Route::get('/soft/clock-out', 'AttendanceAppController@softClockOut');

Route::post('/soft/clock', 'AttendanceAppController@softClock');
Route::get('/soft/clock', 'AttendanceAppController@softClock');

//visitor
Route::get('/visitor/users', 'VisitorApiController@users');
Route::get('/visitor/departments', 'VisitorApiController@departments');
Route::get('/visitor/roles', 'VisitorApiController@roles');

//bc integration
Route::get('/bc-users', 'UserController@bc_export');
Route::get('/bc-payroll-journal', 'CompensationController@nav_int');
//pali
Route::get('/pali-users', 'UserController@pali_sync');

// demographics

Route::get('{companyId}/employees', 'ReportController@employees');

Route::get('{companyId}/departments', 'ReportController@department_reports');

Route::get('/companies', 'ReportController@companies');

Route::get('/performanceCategories', 'ReportController@performanceCategories');

Route::get('/pfas', 'ReportController@pfas');

Route::get('{companyId}/projectSalaryCategories', 'ReportController@projectSalaryCategories');

Route::get('{countryId}/states', 'ReportController@states');

Route::get('/countries', 'ReportController@countries');

Route::get('{companyId}/grades', 'ReportController@grades');

Route::get('{companyId}/sections', 'ReportController@sections');

Route::get('{companyId}/branches', 'ReportController@branches');

Route::get('{stateId}/lgas', 'ReportController@lgas');

Route::get('{companyId}/lineManager/{lineManagerId}/', 'ReportController@lineManager');

Route::get('/roles', 'ReportController@roles');

Route::get('/banks', 'ReportController@banks');

Route::get('/jobs', 'ReportController@jobs');

Route::get('/staffCategories', 'ReportController@staffCategories');


// leave

Route::get('/leaves', 'ReportController@leaves');
Route::get('/leave-requests', 'ReportController@leaveRequests');
Route::get('/leave-request-approvals', 'ReportController@leaveRequestApprovals');
Route::get('/leave-request-dates', 'ReportController@leaveRequestDates');


// payroll

Route::get('{companyId}/payroll', 'ReportController@payroll');
Route::get('{companyId}/payroll/all', 'ReportController@payrollTable');


// payScales
Route::get('/pay-scales', 'PayrollSettingController@fetchPayscales')->middleware(['bi_report']);
Route::post('/save-payscale', 'PayrollSettingController@savePayscale');
Route::delete('/delete-payscale/{id}', 'PayrollSettingController@deletePayscale');
// pensionFundAdmins
Route::get('/pension-fund-admins', 'PayrollSettingController@fetchPensionFundAdmins')->middleware(['bi_report']);
Route::post('/save-pension-fund-admin', 'PayrollSettingController@savePensionFundAdmin');
Route::delete('/delete-pension-fund-admin/{id}', 'PayrollSettingController@deletePensionFundAdmin');
// taxAdmins
Route::get('/tax-admins', 'PayrollSettingController@fetchTaxAdmins')->middleware(['bi_report']);
Route::post('/save-tax-admin', 'PayrollSettingController@saveTaxAdmin');
Route::delete('/delete-tax-admin/{id}', 'PayrollSettingController@deleteTaxAdmin');

// hmo hospitals
Route::get('/hmo/hospitals', 'AARHMO@getHMOHospitals')->middleware(['bi_report']);
Route::get('/hmo/index', 'AARHMO@getHMOs')->middleware(['bi_report']);
Route::post('/hmo/save-hmo', 'AARHMO@saveHMO');
Route::delete('/hmo/delete-hmo/{id}', 'AARHMO@deleteHMO');
Route::post('/hmo/save-hmo-hospital', 'AARHMO@saveHMOHospital');
Route::post('/hmo/import-hmo-hospitals', 'AARHMO@importHMOHospitals');
Route::get('/hmo/download-hmo-hospitals-template', 'AARHMO@downloadHMOHospitalsTemplate');
Route::delete('/hmo/delete-hmo-hospital/{id}', 'AARHMO@deleteHMOHospital');


// authenticated user
Route::get('/user-by-id', 'UserController@getAuthUser');




// performance discussion
Route::post('/bsceval-store', 'BSCEvaluationController@store');
Route::get('/get-bsc-discussion', 'BSCEvaluationController@getSingleDiscussionAPI');


