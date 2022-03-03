<?php

namespace Modules\Agent\Http\Controllers\Nmanager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Modules\Agent\Entities\AgentRoleUser;
use Modules\Agent\Entities\Stakeholder;
use Modules\Agent\Entities\StakeholderComment;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Location\District;
use Modules\Core\Entities\Location\Division;
use Modules\Core\Entities\Location\Upazila;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Agent\Exports\Nmanager\NmanagerAllStakeholderExport;
use Modules\Agent\Exports\Nmanager\NmanagerRejAllStakeholderExport;
use Modules\Agent\Exports\Nmanager\NmanagerRejSelectableExport;
use Modules\Agent\Exports\Nmanager\NmanagerSelectableExport;
use Modules\Core\Entities\Shujog\SignupReference;

class RejectStkholderController extends Controller
{

    public function index($module,Request $request)
    {

        $divisions = Division::where(['is_active'=>1])->get(['id','name']);

        $selectRoles = AgentRoleUser::with('role')->where(['user_id'=>auth()->user()->id])->get();

        if(count($selectRoles) == 0){
            abort(403,"Do Not Have Any Stackholder Permissions.");
        }

        $first_active_tab = null;
        if(isset($request->role)){
            $first_active_tab = AgentRoleUser::with('role')->where(['user_id'=>auth()->user()->id,'role_id'=>$request->role])->first();
            $stakeholders = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','stakeholderCommnetForNmanager','signupReference',
            'assignProject'=>function($q){
                $q->select('stakeholder_id','project_id');
            },'assignProject.project'])->where(['spatie_role_id'=>$request->role])
            ->whereHas('stakeholder',function($query){
                $query->where('consultant_status','3')
                ->where('trainer_status','3')
                ->where('deployer_status','3')
                ->whereIn('network_status',['7']);
            })
            ->orderBy(Stakeholder::select('network_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))
            ->paginate(100);

        }else{

            $first_active_tab = $selectRoles->first();
            $stakeholders = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','stakeholderCommnetForNmanager','signupReference',
            'assignProject'=>function($q){
                $q->select('stakeholder_id','project_id');
            },'assignProject.project'])
            ->where(['spatie_role_id'=>$first_active_tab->role_id])
            ->whereHas('stakeholder',function($query){
                $query->where('consultant_status','3')
                ->where('trainer_status','3')
                ->where('deployer_status','3')
                ->whereIn('network_status',['7']);
            })
            ->orderBy(Stakeholder::select('network_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))
            ->paginate(100);

        }


        $filter_by = "None";
        $last_updated = last_modify_human_date(AgentRoleUser::latest()->first());

        $filter_district = null;
        $filter_upazila = null;

        $references = SignupReference::where(['is_active'=>1])->get(['id','title']);
        return view("agent::nmanagers.stakeholders.reject.index",[
            'stakeholders'=>$stakeholders,
            'selectRoles'=>$selectRoles,
            'first_active_tab'=>$first_active_tab,
            'filter_by'=>$filter_by,
            'last_updated'=>$last_updated,
            'module'=>$module,
            'divisions'=>$divisions,
            'search'=>null,
            'start_date'=>null,
            'end_date'=>null,
            'division_id'=>null,
            'district_id'=>null,
            'upazila_id'=>null,
            'status'=>null,
            'filter_district'=>null,
            'filter_upazila'=>null,
            'references'=>$references,
            'reference_id'=>null
        ]);
    }




    public function filter(Request $request,$module)
    {

        $divisions = Division::where(['is_active'=>1])->get(['id','name']);

        $selectRoles = AgentRoleUser::with('role')->where(['user_id'=>auth()->user()->id])->get();
        if(count($selectRoles) == 0){
            abort(404,"Do Not Have Any Stackholder Permissions.");
        }

        $first_active_tab = null;
        $filter_by = "";
        $filter_district = null;
        $filter_upazila = null;

        if(isset($request->role)){

            $first_active_tab = AgentRoleUser::with('role')->where(['user_id'=>auth()->user()->id,'role_id'=>$request->role])->first();

            $stakeholders_query = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','stakeholderCommnetForNmanager','signupReference',
            'assignProject'=>function($q){
                $q->select('stakeholder_id','project_id');
            },'assignProject.project'])
            ->where(['spatie_role_id'=>$request->role]);


            if($request->status != ""){
                $stakeholders_query->whereHas('stakeholder',function($query) use($request){
                    $query->where('consultant_status','3')
                    ->where('trainer_status','3')
                    ->where('deployer_status','3')
                    ->where('network_status',$request->status);
                });
                $filter_by .= "Status, ";
            }else{
                $stakeholders_query->whereHas('stakeholder',function($query){
                    $query->whereIn('network_status',['5','6'])
                    ->where('consultant_status','3')
                    ->where('trainer_status','3')
                    ->where('deployer_status','3');
                });
            }

            
            if($request->search != ""){
                $stakeholders_query->where('name','like','%'.$request->search.'%')
                ->orWhere(['mobile'=>$request->search]);
                $filter_by .= "Name/Mobile, ";
            }

            if($request->start_date != "" && $request->end_date != ""){
                $stakeholders_query->whereBetween('created_at',[$request->start_date.' 00.00.00',$request->end_date.' 23.59.59']);
                $filter_by .= "Date, ";
            }


            if($request->division_id != ""){
                $stakeholders_query->where(['division_id'=>$request->division_id]);
                $filter_by .= "Division, ";
            }

            if($request->district_id != ""){
                $stakeholders_query->where(['district_id'=>$request->district_id]);
                $filter_by .= "District, ";
                $filter_district = District::where(['id'=>$request->district_id])->first(['id','name']);
            }

            if($request->upazila_id != ""){
                $stakeholders_query->where(['upazila_id'=>$request->upazila_id]);
                $filter_by .= "Upazila, ";
                $filter_upazila = Upazila::where(['id'=>$request->upazila_id])->first(['id','name']);
            }

            if($request->reference_id != ""){
                $stakeholders_query->where(['signup_reference_id'=>$request->reference_id]);
                $filter_by .= "Reference, ";
            }



            $data =  $stakeholders_query->orderBy(Stakeholder::select('network_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))
            ->paginate(100);



        }else{

            $first_active_tab = $selectRoles->first();
            $stakeholders_query = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','stakeholderCommnetForNmanager','signupReference',
            'assignProject'=>function($q){
                $q->select('stakeholder_id','project_id');
            },'assignProject.project'])
            ->where(['spatie_role_id'=>$first_active_tab->role_id]);



            if($request->status != ""){
                $stakeholders_query->whereHas('stakeholder',function($query) use($request){
                    $query->where('consultant_status','3')
                    ->where('trainer_status','3')
                    ->where('deployer_status','3')
                    ->where('network_status',$request->status);
                });
                $filter_by .= "Status, ";
            }else{
                $stakeholders_query->whereHas('stakeholder',function($query){
                    $query->whereIn('network_status',['5','6'])
                    ->where('consultant_status','3')
                    ->where('trainer_status','3')
                    ->where('deployer_status','3');
                });
            }



            if($request->search != ""){
                $stakeholders_query->where('name','like','%'.$request->search.'%')
                ->orWhere(['mobile'=>$request->search]);
                $filter_by .= "Name/Mobile, ";
            }

            if($request->start_date != "" && $request->end_date != ""){
                $stakeholders_query->whereBetween('created_at',[$request->start_date.' 00.00.00',$request->end_date.' 23.59.59']);
                $filter_by .= "Date, ";
            }


            if($request->division_id != ""){
                $stakeholders_query->where(['division_id'=>$request->division_id]);
                $filter_by .= "Division, ";
            }

            if($request->district_id != ""){
                $stakeholders_query->where(['district_id'=>$request->district_id]);
                $filter_by .= "District, ";
                $filter_district = District::where(['id'=>$request->district_id])->first(['id','name']);
            }

            if($request->upazila_id != ""){
                $stakeholders_query->where(['upazila_id'=>$request->upazila_id]);
                $filter_by .= "Upazila, ";
                $filter_upazila = Upazila::where(['id'=>$request->upazila_id])->first(['id','name']);
            }

            if($request->reference_id != ""){
                $stakeholders_query->where(['signup_reference_id'=>$request->reference_id]);
                $filter_by .= "Reference, ";
            }



            $data =  $stakeholders_query->orderBy(Stakeholder::select('network_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))->paginate(100);

        }



        $last_updated = last_modify_human_date(AgentRoleUser::latest()->first());
        $references = SignupReference::where(['is_active'=>1])->get(['id','title']);
        return view("agent::nmanagers.stakeholders.reject.index",[
            'stakeholders'=>$data,
            'selectRoles'=>$selectRoles,
            'first_active_tab'=>$first_active_tab,
            'filter_by'=>$filter_by,
            'last_updated'=>$last_updated,
            'module'=>$module,
            'divisions'=>$divisions,
            'search'=>$request->search,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'division_id'=>$request->division_id,
            'district_id'=>$request->district_id,
            'upazila_id'=>$request->upazila_id,
            'status'=>$request->status,
            'filter_district'=>$filter_district,
            'filter_upazila'=>$filter_upazila,
            'references'=>$references,
            'reference_id'=>$request->reference_id
        ]);
    }



    public function allExport(Request $request,$module){
        return Excel::download(new NmanagerRejAllStakeholderExport($request), time().'all-stackholders.xlsx');
    }



    public function selectable(Request $request,$module){

        if(!isset($request->ids)){
            Session::flash('error','Please Select at-least One Stakeholder.');
            return redirect()->back();
        }

        return Excel::download(new NmanagerRejSelectableExport($request), time().'_reject_stakeholders.xlsx');
    }




    public function show($module,$id)
    {
        $thardPary_info = Http::withOptions(['verify' => false])->get('http://app.shujog.xyz/api/thardparyservice/get-user-info', [
            'user_id' => $id,
        ]);

        $user = User::with(['spatieRole','education', 'investment', 'asset', 'division', 'district', 'upazila'])
        ->where(['id'=>$id])
        ->first();

        return view("agent::nmanagers.stakeholders.reject.details",[
            'user'=>$user,
            'module'=>$module,
            'thardpary_info'=>$thardPary_info->json()
        ]);
    }






    public function commentView($module,$user_id)
    {
        $commentsQuery = StakeholderComment::with('user','stackholder')
        ->where(['user_id'=>$user_id])
        ->orderBy('id','desc')
        ->get();

        $is_complete = StakeholderComment::where(['user_id'=>$user_id,'status'=>'3'])
        ->whereIn('flag',[22,28])
        ->first();

        return view("agent::nmanagers.stakeholders.comments.reject-comment",[
            'module'=>$module,
            'user_id'=>$user_id,
            'comments'=>$commentsQuery,
            'is_complete'=>$is_complete
        ]);
    }


    public function consultantCommentSubmit(Request $request,$module,$user_id)
    {

        $this->validate($request,[
            'status'=>'required',
            'comment'=>'required|min:5|max:1000'
        ]);


        $is_complete = StakeholderComment::where(['user_id'=>$user_id,'status'=>'3'])
        ->whereIn('flag',[22,28])
        ->first();

        if(!is_null($is_complete)){
            Session::flash('error',"This Stakeholder Status already Complete. So you do not change this Status. Please Contant your system admin.");
            return redirect()->back();
        }        


        try{

            DB::beginTransaction();
            StakeholderComment::create([
                'role_id'=>auth()->user()->spatie_role_id,
                'flag'=>auth()->user()->flag,
                'comment'=>$request->comment,
                'status'=>$request->status,
                'user_id'=>$user_id,
                'agent_id'=>auth()->user()->id,
            ]);

            // 'user_id','comment','agent_id','status','status_type','role_id'
            Stakeholder::where(['user_id'=>$user_id])->update([
                'network_status'=>$request->status,
            ]);

            if($request->status == "6" || $request->status == "7"){
                User::where(['id'=>$user_id])->update([
                    'is_active'=> 0
                ]);
            }else{
                User::where(['id'=>$user_id])->update([
                    'is_active'=>1
                ]);
            }


            DB::commit();
            Session::flash('success','Your Comment submitted successfully');
            return redirect($module.'/nmg-reject-stkholders/comments/'.$user_id);

        }catch(Exception $ex){
            DB::rollback();
            Session::flash('error',$ex->getMessage());
            return redirect()->back();
        }

    }


}
