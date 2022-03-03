<?php
use Illuminate\Support\Facades\Route;
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

/***************************************************
 * Developer Controller
 * ****************************/

Route::get('add-user-for-agent',[\Modules\Agent\Http\Controllers\DeveloperController::class,'addUsersForAgent']);


/***************************************************
 * Developer Controller
 * ****************************/











Route::post('loads-roles',[\Modules\Agent\Http\Controllers\UserController::class,'loadRoles'])->name('agent.loadroles');

//Load Project for project Manager
Route::post('loads-projects',[\Modules\Agent\Http\Controllers\Pmanager\ProjectController::class,'loadProjects'])->name('agent.loadproject');


Route::post('load-upazila-by-district',[\Modules\Agent\Http\Controllers\UserController::class,'loadUpazilaByDistrict']);
Route::post('load-district-by-division',[\Modules\Agent\Http\Controllers\UserController::class,'loadDistrictByDivision']);













//prevent-back-history middleware('core_permission')->
Route::group(['middleware' => ['prevent-back-history','core_permission']],function () {
    // Route::resource('{modules}/consult-projects','\Modules\Agent\Http\Controllers\ProjectController')->only('index');
    // Route::resource('{modules}/logs','\Modules\Agent\Http\Controllers\LogController');
    //Users Route


    Route::resource('{modules}/users','\Modules\Agent\Http\Controllers\UserController',['as'=>'agent']);
    Route::get('{modules}/users-filter',[\Modules\Agent\Http\Controllers\UserController::class,'filter'])->name('agent.users.filter');
    Route::post('{modules}/assign-role',[\Modules\Agent\Http\Controllers\UserController::class,'assignRole'])->name('agent.users.assignRole');
    Route::get('{modules}/ag-edit-permission/{user_id}',[\Modules\Agent\Http\Controllers\UserController::class,'editPermission']);
    Route::post('{modules}/ag-edit-permission/{user_id}',[\Modules\Agent\Http\Controllers\UserController::class,'editPermissionSubmit'])->name('agent.user.editpremission');
    Route::get('{modules}/users-activation/{status}/{user_id}',[\Modules\Agent\Http\Controllers\UserController::class,'activation']);

    //Stack Holders
    Route::resource('{modules}/stakeholders','\Modules\Agent\Http\Controllers\StakeholderController',['as'=>'agent'])->only('index','show','edit','update');
    Route::get('{modules}/stakeholders-filter',[\Modules\Agent\Http\Controllers\StakeholderController::class,'filter'])->name('agent.stakeholders.filter');
    Route::get('{modules}/stakeholders-all-export',[\Modules\Agent\Http\Controllers\StakeholderController::class,'allExport']);
    Route::post('{module}/stakeholders-single-export',[\Modules\Agent\Http\Controllers\StakeholderController::class,'selectable'])->name('agent.stackholder.export');

    Route::get('{modules}/stakeholders/consultant-comments/{id}',[\Modules\Agent\Http\Controllers\StakeholderController::class,'commentView']);
    Route::post('{modules}/stakeholders/consultant-comments/{id}',[\Modules\Agent\Http\Controllers\StakeholderController::class,'consultantCommentSubmit'])->name('agent.stackholders.consul_comment');

    Route::get('{modules}/stakeholders/evaluation/{id}',[\Modules\Agent\Http\Controllers\Stakeholder\EvaluationController::class,'evaluationFrom']);
    Route::post('{modules}/stakeholders/evaluation/{id}',[\Modules\Agent\Http\Controllers\Stakeholder\EvaluationController::class,'evaluationFromSubmit'])->name('agent.stakeholder.evaluationsubmit');


    //Stackholer Reject List
    Route::resource('{modules}/consult-reject-stakeholder','\Modules\Agent\Http\Controllers\Stakeholder\RejectStakeholderController',['as'=>'agent']);
    Route::get('{modules}/consult-reject-stkholder-filter',[\Modules\Agent\Http\Controllers\Stakeholder\RejectStakeholderController::class,'filter'])->name('agent.con-reject-stkholder.filter');
    Route::get('{modules}/consult-reject-all-export',[\Modules\Agent\Http\Controllers\Stakeholder\RejectStakeholderController::class,'allExport']);
    Route::post('{module}/consult-reject-single-export',[\Modules\Agent\Http\Controllers\Stakeholder\RejectStakeholderController::class,'selectable'])->name('agent.consult-sing-stkhol.export');

    Route::get('{modules}/consult-reject-comments/{id}',[\Modules\Agent\Http\Controllers\Stakeholder\RejectStakeholderController::class,'commentView']);
    Route::post('{modules}/consult-reject-comments/{id}',[\Modules\Agent\Http\Controllers\Stakeholder\RejectStakeholderController::class,'consultantCommentSubmit'])->name('agent.reject-consul-comment');





    /*****************************************
     *  Trainer Route
     * *****************************************/
    //Users
    Route::resource('{modules}/trainer-users','\Modules\Agent\Http\Controllers\Trainer\UserController',['as'=>'agent']);
    Route::get('{modules}/trainer-users-filter',[\Modules\Agent\Http\Controllers\Trainer\UserController::class,'filter'])->name('agent.trainer-users.filter');
    Route::post('{modules}/trainer-assign-role',[\Modules\Agent\Http\Controllers\Trainer\UserController::class,'assignRole'])->name('agent.trainer-users.assignRole');
    Route::get('{modules}/trainer-ag-edit-permission/{user_id}',[\Modules\Agent\Http\Controllers\Trainer\UserController::class,'editPermission']);
    Route::post('{modules}/trainer-ag-edit-permission/{user_id}',[\Modules\Agent\Http\Controllers\Trainer\UserController::class,'editPermissionSubmit'])->name('agent.trainer-user.editpremission');
    Route::get('{modules}/trainer-users-activation/{status}/{user_id}',[\Modules\Agent\Http\Controllers\Trainer\UserController::class,'activation']);


    //Stack Holders
    Route::resource('{modules}/trainer-stakeholders','\Modules\Agent\Http\Controllers\Trainer\StakeholderController',['as'=>'agent'])->only('index','show','edit','update');
    Route::get('{modules}/trainer-stakeholders-filter',[\Modules\Agent\Http\Controllers\Trainer\StakeholderController::class,'filter'])->name('agent.trainer-stakeholders.filter');
    Route::get('{modules}/trainer-stakeholders-all-export',[\Modules\Agent\Http\Controllers\Trainer\StakeholderController::class,'allExport']);
    Route::post('{module}/trainer-stakeholders-single-export',[\Modules\Agent\Http\Controllers\Trainer\StakeholderController::class,'selectable'])->name('agent.trainer-stackholder.export');

    Route::get('{modules}/stakeholders/trainer-comments/{id}',[\Modules\Agent\Http\Controllers\Trainer\StakeholderController::class,'commentView']);
    Route::post('{modules}/stakeholders/trainer-comments/{id}',[\Modules\Agent\Http\Controllers\Trainer\StakeholderController::class,'consultantCommentSubmit'])->name('agent.stackholders.trainer_comment');


    //Stackholer Reject List
    Route::resource('{modules}/trainer-reject-stakeholder','\Modules\Agent\Http\Controllers\Trainer\RejectStakeholderController',['as'=>'agent']);
    Route::get('{modules}/trainer-reject-stkholder-filter',[\Modules\Agent\Http\Controllers\Trainer\RejectStakeholderController::class,'filter'])->name('agent.triner-reject-stkholder.filter');
    Route::get('{modules}/trainer-reject-all-export',[\Modules\Agent\Http\Controllers\Trainer\RejectStakeholderController::class,'allExport']);
    Route::post('{module}/trainer-reject-single-export',[\Modules\Agent\Http\Controllers\Trainer\RejectStakeholderController::class,'selectable'])->name('agent.trainer-rej-sing-stkhol.export');

    Route::get('{modules}/trainer-reject-comments/{id}',[\Modules\Agent\Http\Controllers\Trainer\RejectStakeholderController::class,'commentView']);
    Route::post('{modules}/trainer-reject-comments/{id}',[\Modules\Agent\Http\Controllers\Trainer\RejectStakeholderController::class,'consultantCommentSubmit'])->name('agent.trainer-reject-stk-comment');
    /*****************************************
     *  Trainer Route
     * *****************************************/





    /*****************************************
     *  Deployer Route
     * *****************************************/
    Route::resource('{modules}/deployer-users','\Modules\Agent\Http\Controllers\Deployer\UserController',['as'=>'agent']);
    Route::get('{modules}/deployer-users-filter',[\Modules\Agent\Http\Controllers\Deployer\UserController::class,'filter'])->name('agent.deployer-users.filter');
    Route::post('{modules}/deployer-assign-role',[\Modules\Agent\Http\Controllers\Deployer\UserController::class,'assignRole'])->name('agent.deployer-users.assignRole');
    Route::get('{modules}/deployer-ag-edit-permission/{user_id}',[\Modules\Agent\Http\Controllers\Deployer\UserController::class,'editPermission']);
    Route::post('{modules}/deployer-ag-edit-permission/{user_id}',[\Modules\Agent\Http\Controllers\Deployer\UserController::class,'editPermissionSubmit'])->name('agent.deployer-user.editpremission');
    Route::get('{modules}/deployer-users-activation/{status}/{user_id}',[\Modules\Agent\Http\Controllers\Deployer\UserController::class,'activation']);


    //Stack Holders
    Route::resource('{modules}/dp-stkholders','\Modules\Agent\Http\Controllers\Deployer\StakeholderController',['as'=>'agent'])->only('index','show','edit','update');

    Route::get('{modules}/dp-stkholders-filter',[\Modules\Agent\Http\Controllers\Deployer\StakeholderController::class,'filter'])->name('agent.dp-stkholder.filter');

    Route::get('{modules}/dp-stkholders-all-export',[\Modules\Agent\Http\Controllers\Deployer\StakeholderController::class,'allExport']);

    Route::post('{module}/dp-stkholders-single-export',[\Modules\Agent\Http\Controllers\Deployer\StakeholderController::class,'selectable'])->name('agent.dp-stkholder.export');

    Route::get('{modules}/dp-stkholders/comments/{id}',[\Modules\Agent\Http\Controllers\Deployer\StakeholderController::class,'commentView']);

    Route::post('{modules}/dp-stkholders/comments/{id}',[\Modules\Agent\Http\Controllers\Deployer\StakeholderController::class,'consultantCommentSubmit'])->name('agent.dp-stkholder.comment');





    //Stackholer Reject List dp means deployer
    Route::resource('{modules}/dp-reject-stakeholder','\Modules\Agent\Http\Controllers\Deployer\RejectStakeholderController',['as'=>'agent']);
    Route::get('{modules}/dp-reject-stkholder-filter',[\Modules\Agent\Http\Controllers\Deployer\RejectStakeholderController::class,'filter'])->name('agent.dp-reject-stkholder.filter');
    Route::get('{modules}/dp-reject-all-export',[\Modules\Agent\Http\Controllers\Deployer\RejectStakeholderController::class,'allExport']);
    Route::post('{module}/dp-reject-single-export',[\Modules\Agent\Http\Controllers\Deployer\RejectStakeholderController::class,'selectable'])->name('agent.dp-sing-stkhol.export');

    Route::get('{modules}/dp-reject-comments/{id}',[\Modules\Agent\Http\Controllers\Deployer\RejectStakeholderController::class,'commentView']);
    Route::post('{modules}/dp-reject-comments/{id}',[\Modules\Agent\Http\Controllers\Deployer\RejectStakeholderController::class,'consultantCommentSubmit'])->name('agent.reject-dp-comment');

     /*****************************************
     *  Deployer Route
     * *****************************************/






    /*****************************************
     *  Network Manager Route
     * *****************************************/
    //Users
    Route::resource('{modules}/nmanager-users','\Modules\Agent\Http\Controllers\Nmanager\UserController',['as'=>'agent']);

    Route::get('{modules}/nmanager-users-filter',[\Modules\Agent\Http\Controllers\Nmanager\UserController::class,'filter'])->name('agent.nmanager-users.filter');

    Route::post('{modules}/nmanager-assign-role',[\Modules\Agent\Http\Controllers\Nmanager\UserController::class,'assignRole'])->name('agent.nmanager-users.assignRole');

    Route::get('{modules}/nmanager-edit-permission/{user_id}',[\Modules\Agent\Http\Controllers\Nmanager\UserController::class,'editPermission']);

    Route::post('{modules}/nmanager-edit-permission/{user_id}',[\Modules\Agent\Http\Controllers\Nmanager\UserController::class,'editPermissionSubmit'])->name('agent.nmanager.editpremission');

    Route::get('{modules}/nmanager-users-activation/{status}/{user_id}',[\Modules\Agent\Http\Controllers\Nmanager\UserController::class,'activation']);


    //Stakeholders
    Route::resource('{modules}/nmg-stkholders','\Modules\Agent\Http\Controllers\Nmanager\StkholderController',['as'=>'agent'])->only('index','show','edit','update');

    Route::get('{modules}/nmg-stkholders-filter',[\Modules\Agent\Http\Controllers\Nmanager\StkholderController::class,'filter'])->name('agent.nmg-stkholder.filter');

    Route::get('{modules}/nmg-stkholders-all-export',[\Modules\Agent\Http\Controllers\Nmanager\StkholderController::class,'allExport']);

    Route::post('{module}/nmg-stkholders-single-export',[\Modules\Agent\Http\Controllers\Nmanager\StkholderController::class,'selectable'])->name('agent.nmg-stkholder.export');

    Route::get('{modules}/nmg-stkholders/comments/{id}',[\Modules\Agent\Http\Controllers\Nmanager\StkholderController::class,'commentView']);

    Route::post('{modules}/nmg-stkholders/comments/{id}',[\Modules\Agent\Http\Controllers\Nmanager\StkholderController::class,'consultantCommentSubmit'])->name('agent.nmg-stkholder.comment');


    //Reject Stakeholder
    Route::resource('{modules}/nmg-reject-stkholders','\Modules\Agent\Http\Controllers\Nmanager\RejectStkholderController',['as'=>'agent'])->only('index','show','edit','update');

    Route::get('{modules}/nmg-reject-stkholders-filter',[\Modules\Agent\Http\Controllers\Nmanager\RejectStkholderController::class,'filter'])->name('agent.nmg-rej-stkholder.filter');

    Route::get('{modules}/nmg-reject-stkholders-all-export',[\Modules\Agent\Http\Controllers\Nmanager\RejectStkholderController::class,'allExport']);

    Route::post('{module}/nmg-reject-stkholders-single-export',[\Modules\Agent\Http\Controllers\Nmanager\RejectStkholderController::class,'selectable'])->name('agent.nmg-rej-stkholder.export');

    Route::get('{modules}/nmg-reject-stkholders/comments/{id}',[\Modules\Agent\Http\Controllers\Nmanager\RejectStkholderController::class,'commentView']);

    Route::post('{modules}/nmg-reject-stkholders/comments/{id}',[\Modules\Agent\Http\Controllers\Nmanager\RejectStkholderController::class,'consultantCommentSubmit'])->name('agent.nmg-rej-stkholder.comment');

    /*****************************************
     *   Network Manager Route
     * *****************************************/




    /*****************************************
     *  Project Manager Route
     * *****************************************/
    Route::resource('{modules}/pmanager-users','\Modules\Agent\Http\Controllers\Pmanager\UserController',['as'=>'agent']);
    Route::get('{modules}/pmanager-users-filter',[\Modules\Agent\Http\Controllers\Pmanager\UserController::class,'filter'])->name('agent.pmanager-users.filter');
    Route::post('{modules}/pmanager-assign-role',[\Modules\Agent\Http\Controllers\Pmanager\UserController::class,'assignProject'])->name('agent.pmanager-users.assignProject');
    Route::get('{modules}/pmanager-ag-edit-permission/{user_id}',[\Modules\Agent\Http\Controllers\Pmanager\UserController::class,'editPermission']);
    Route::post('{modules}/pmanager-ag-edit-permission/{user_id}',[\Modules\Agent\Http\Controllers\Pmanager\UserController::class,'editPermissionSubmit'])->name('agent.pmanager-user.editpremission');
    Route::get('{modules}/pmanager-users-activation/{status}/{user_id}',[\Modules\Agent\Http\Controllers\Pmanager\UserController::class,'activation']);




    Route::resource('{modules}/pmg-projects','\Modules\Agent\Http\Controllers\Pmanager\ProjectController',['as'=>'agent']);
    Route::get('{modules}/pmg-projects-filter',[\Modules\Agent\Http\Controllers\Pmanager\ProjectController::class,'filter'])->name('agent.pmg-projects.filter');
    Route::get('{modules}/pmg-projects-activation/{status}/{user_id}',[\Modules\Agent\Http\Controllers\Pmanager\ProjectController::class,'activation']);

    Route::get('{modules}/pmg-projects-stakeholders/{project_id}',[\Modules\Agent\Http\Controllers\Pmanager\ProjectController::class,'projectStakeholders']);

    Route::get('{modules}/pmg-projects-dashboard/{project_id}',[\Modules\Agent\Http\Controllers\Pmanager\ProjectController::class,'projectDashboard']);


    Route::get('{modules}/pmg-projects-filter-dashboard/{project_id}',[\Modules\Agent\Http\Controllers\Pmanager\ProjectController::class,'projectDashboardFilter'])->name('agent.pro-deshboard-filter');


    Route::get('{modules}/pmg-projects-activation/{status}/{project_slug}/{user_id}',[\Modules\Agent\Http\Controllers\Pmanager\ProjectController::class,'stkHolderActivation']);

    Route::get('{modules}/pmg-projects-re-stk/{project_slug}/{user_id}',[\Modules\Agent\Http\Controllers\Pmanager\ProjectController::class,'stkHolderRemove']);


    Route::get('{modules}/pmg-projects-stk-filter/{project_slug}',[\Modules\Agent\Http\Controllers\Pmanager\ProjectController::class,'stkHolderFilter'])->name('pro-manager.project.stkHolderFilter');


    Route::post('{modules}/pmg-projects-stk-export/{project_slug}',[\Modules\Agent\Http\Controllers\Pmanager\ProjectController::class,'stkHolderExport'])->name('agent.pro_manager_stk_by_pro_export');


    Route::resource('{modules}/dp-project','\Modules\Agent\Http\Controllers\Deployer\ProjectController',['as'=>'agent']);


     /*****************************************
     *  Project Manager Route
     * *****************************************/


});
