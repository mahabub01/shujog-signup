<?php

namespace Modules\Agent\Http\Controllers\Deployer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Agent\Entities\AgentAssignProjectStakeholder;
use Modules\Agent\Entities\AgentProject;

class ProjectController extends Controller
{
    public function index($module,Request $request)
    {


    }


    public function create($module, Request $request)
    {
        $ids_string = base64_decode($request->ids);
        $ids = explode(",",$ids_string);
        $users = User::whereIn('id',$ids)->get(['id','name']);
        $projects = AgentProject::all(['id','name']);
        return view("agent::deployers.stakeholders.projects.create",[
            'module'=>$module,
            'users'=>$users,
            'projects'=>$projects
        ]);
    }



    public function store(Request $request,$module)
    {
        $this->validate($request,[
            'project_id'=>'required',
            'stakeholder_id'=>'required'
        ]);

        try{

            if(isset($request->stakeholder_id)){
                $query = array();

                $assign_agent = AgentAssignProjectStakeholder::where(['project_id'=>$request->project_id])->pluck('stakeholder_id')->toArray();

                $total_stkholder = count($request->stakeholder_id);
                $total_already_added = 0;

                foreach($request->stakeholder_id as $stakeholder_id){

                    if(!in_array($stakeholder_id,$assign_agent)){
                        $query[] = [
                            'user_id'=>auth()->user()->id,
                            'project_id'=>$request->project_id,
                            'stakeholder_id'=>$stakeholder_id,
                            'created_at'=>now(),
                            'updated_at'=>now()
                        ];

                    }else{
                        $total_already_added++;
                    }

                }

                AgentAssignProjectStakeholder::insert($query);

                if($total_already_added != 0){
                    Session::flash('success',($total_stkholder - $total_already_added)." Stakeholder added successfully ".$total_already_added." Users skipped because they are already added before");
                }else{
                    Session::flash('success',$total_stkholder." Stakeholder added successfully");
                }
                return redirect()->route('agent.dp-stkholders.index',$module);
            }

        }catch(Exception $ex){
            Session::flash('error',$ex->getMessage());
            return redirect()->back();
        }

   
    }


}
