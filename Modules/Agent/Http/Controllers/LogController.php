<?php

namespace Modules\Agent\Http\Controllers;

use Spatie\Activitylog\Models\Activity;

class LogController
{
    public function index($module){


        $logs = Activity::all();
        $first_active_tab = null;
        // if(isset($request->role)){
        //     $first_active_tab = AgentRoleUser::with('role')->where(['user_id'=>auth()->user()->id,'role_id'=>$request->role])->first();

        //     $stakeholders = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','signupReference','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','stakeholderCommnetForNmanager','assignProject'=>function($q){
        //         $q->select('stakeholder_id','project_id');
        //     },'assignProject.project'])->where(['spatie_role_id'=>$request->role])
        //     ->whereHas('stakeholder',function($query){
        //         $query->whereIn('consultant_status',[1,2,3]);
        //     })
        //     ->orderBy(Stakeholder::select('consultant_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))
        //     ->paginate(100);


        // }else{

        //     $first_active_tab = $selectRoles->first();
        //     $stakeholders = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','signupReference','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','stakeholderCommnetForNmanager','assignProject'=>function($q){
        //         $q->select('stakeholder_id','project_id');
        //     },'assignProject.project'])
        //     ->where(['spatie_role_id'=>$first_active_tab->role_id])
        //     ->whereHas('stakeholder',function($query){
        //         $query->whereIn('consultant_status',[1,2,3]);
        //     })
        //     ->orderBy(Stakeholder::select('consultant_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))
        //     ->paginate(100);


        // }


        $filter_by = "None";
        // $last_updated = last_modify_human_date(AgentRoleUser::latest()->first());

        // $filter_district = null;
        // $filter_upazila = null;

        // $references = SignupReference::where(['is_active'=>1])->get(['id','title']);

        return view("agent::stakeholders.log.log",[
            // 'stakeholders'=>$stakeholders,
            // 'selectRoles'=>$selectRoles,
            // 'last_updated'=>$last_updated,
            // 'status'=>null,
            // 'references'=>$references,
            // 'reference_id'=>null,
            // 'login_status'=> null
            'logs' => $logs,
            'module'=>$module,
            'filter_by'=>$filter_by,
            'search'=>null,
            'start_date'=>null,
            'end_date'=>null,
            'first_active_tab'=>$first_active_tab,
        ]);

        // print_r($data);
    }

}
