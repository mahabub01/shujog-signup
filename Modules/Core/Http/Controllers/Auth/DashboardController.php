<?php

namespace Modules\Core\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Stakeholder;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
   public function dashBoard(){
       return view('core::dashboard.dashboard');
   }


   public function agentDashboard(){

    $users_query = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','stakeholderCommnetForNmanager','signupReference',
    'assignProject'=>function($q){
        $q->select('stakeholder_id','project_id');
    },'assignProject.project']);


    //Total Signup Users
    $total_signup_users = $users_query->count();

    //Total Rejected Users
    $total_signup_rejected_users = $users_query->whereHas('stakeholder',function($query){
        $query->where('consultant_status','4');
    })->count();


    //Total Training Complete
    $total_training_complete = $users_query->whereHas('stakeholder',function($query){
        $query->where('trainer_status','3');
    })->count();


    //Total Training Pending
    $total_training_pending = $users_query->whereHas('stakeholder',function($query){
        $query->where('trainer_status','1');
    })->count();


    //Total Deployed Complete
    $total_deployed_complete = $users_query->whereHas('stakeholder',function($query){
        $query->where('deployer_status','3');
    })->count();


    //Total Deployed Pending
    $total_deployed_pending = $users_query->whereHas('stakeholder',function($query){
        $query->where('deployer_status','1');
    })->count();


    //Total Active Users
    $total_network_active = $users_query->whereHas('stakeholder',function($query){
        $query->where('network_status','5');
    })->count();


    //Total In Active Users
    $total_network_in_active = $users_query->whereHas('stakeholder',function($query){
        $query->where('network_status','6');
    })->count();


    //Total In DropOut Users
    $total_network_in_drop_out = $users_query->whereHas('stakeholder',function($query){
        $query->where('network_status','7');
    })->count();


    return view('core::dashboard.dashboard',[
        'total_signup_users'=>$total_signup_users,
        'total_signup_rejected_users'=>$total_signup_rejected_users,
        'total_training_complete'=>$total_training_complete,
        'total_training_pending'=>$total_training_pending,
        'total_deployed_complete'=>$total_deployed_complete,
        'total_deployed_pending'=>$total_deployed_pending,
        'total_network_active'=>$total_network_active,
        'total_network_in_active'=>$total_network_in_active,
        'total_network_in_drop_out'=>$total_network_in_drop_out,
    ]);
   }







}
