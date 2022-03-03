<?php

namespace Modules\Agent\Http\Controllers\Deployer;

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
use Modules\Agent\Entities\AgentProject;
use Modules\Agent\Exports\Deployer\DeployerAllStakeholderExport;
use Modules\Agent\Exports\Deployer\DeployerSelectableExport;
use Modules\Agent\Exports\Trainer\TrainerAllStakeholderExport;
use Modules\Agent\Exports\Trainer\TrainerSelectableExport;
use Modules\Core\Entities\Shujog\Member;
use Modules\Core\Entities\Shujog\OrderData;
use Modules\Core\Entities\Shujog\Orders;
use Modules\Core\Entities\Shujog\Sells;
use Modules\Core\Entities\Shujog\SellsData;
use Modules\Core\Entities\Shujog\SignupReference;

class StakeholderController extends Controller
{


    public function index($module,Request $request)
    {

        $divisions = Division::where(['is_active'=>1])->get(['id','name']);

        $selectRoles = AgentRoleUser::with('role')->where(['user_id'=>auth()->user()->id])->get();

        if(count($selectRoles) == 0){
            //abort(403,"Do Not Have Any Stackholder Permissions.");
            Session::flash('warning','Do Not Have Any Stackholder Permissions');
            return redirect($module.'/load-component');
        }

        $first_active_tab = null;
        if(isset($request->role)){
            $first_active_tab = AgentRoleUser::with('role')->where(['user_id'=>auth()->user()->id,'role_id'=>$request->role])->first();
            $stakeholders = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','signupReference',
            'assignProject'=>function($q){
                $q->select('stakeholder_id','project_id');
            },'assignProject.project'])->where(['spatie_role_id'=>$request->role])
            ->whereHas('stakeholder',function($query){
                $query->where('consultant_status','3')
                ->where('trainer_status','3')
                ->whereIn('deployer_status',['1','2','3']);
            })
            ->orderBy(Stakeholder::select('deployer_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))
            ->paginate(100);

        }else{

            $first_active_tab = $selectRoles->first();
            $stakeholders = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','signupReference',
            'assignProject'=>function($q){
                $q->select('stakeholder_id','project_id');
            },'assignProject.project'])
            ->where(['spatie_role_id'=>$first_active_tab->role_id])
            ->whereHas('stakeholder',function($query){
                $query->where('consultant_status','3')
                ->where('trainer_status','3')
                ->whereIn('deployer_status',['1','2','3']);
            })
            ->orderBy(Stakeholder::select('deployer_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))
            ->paginate(100);

        }


        $filter_by = "None";
        $last_updated = last_modify_human_date(AgentRoleUser::latest()->first());

        $filter_district = null;
        $filter_upazila = null;

        $references = SignupReference::where(['is_active'=>1])->get(['id','title']);

        return view("agent::deployers.stakeholders.index",[
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
            'reference_id'=>null,
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

            $stakeholders_query = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','signupReference',
            'assignProject'=>function($q){
                $q->select('stakeholder_id','project_id');
            },'assignProject.project'])
            ->where(['spatie_role_id'=>$request->role]);


            if($request->status != ""){
                $stakeholders_query->whereHas('stakeholder',function($query) use($request){
                    $query->where('consultant_status','3')
                    ->where('trainer_status','3')
                    ->where('deployer_status',$request->status);
                });
                $filter_by .= "Status, ";
            }else{
                $stakeholders_query->whereHas('stakeholder',function($query){
                    $query->whereIn('deployer_status',['1','2','3'])
                    ->where('consultant_status','3')
                    ->where('trainer_status','3');
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




            $data =  $stakeholders_query->orderBy(Stakeholder::select('deployer_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))
            ->paginate(100);



        }else{

            $first_active_tab = $selectRoles->first();
            $stakeholders_query = User::with(['stakeholder','stakeholderEvaluation','stakeholderCommnet','stakeholderCommnetForTrainer','stakeholderCommnetForDeployer','signupReference',
            'assignProject'=>function($q){
                $q->select('stakeholder_id','project_id');
            },'assignProject.project'])
            ->where(['spatie_role_id'=>$first_active_tab->role_id]);
            

            if($request->status != ""){
                $stakeholders_query->whereHas('stakeholder',function($query) use($request){
                    $query->where('consultant_status','3')
                    ->where('trainer_status','3')
                    ->where('deployer_status',$request->status);
                });
                $filter_by .= "Status, ";
            }else{
                $stakeholders_query->whereHas('stakeholder',function($query){
                    $query->whereIn('deployer_status',['1','2','3'])
                    ->where('consultant_status','3')
                    ->where('trainer_status','3');

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




            $data =  $stakeholders_query->orderBy(Stakeholder::select('deployer_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))->paginate(100);

        }



        $last_updated = last_modify_human_date(AgentRoleUser::latest()->first());

        $references = SignupReference::where(['is_active'=>1])->get(['id','title']);

        return view("agent::deployers.stakeholders.index",[
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
            'reference_id'=>$request->reference_id,
        ]);
    }



    public function allExport(Request $request,$module){
        return Excel::download(new DeployerAllStakeholderExport($request), time().'deployer-all-stackholders.xlsx');
    }



    public function selectable(Request $request,$module){

        if(!isset($request->ids)){
            Session::flash('error','Please Select at-least One Stakeholder.');
            return redirect()->back();
        }


        if(isset($request->assign_project)){
            $ids_implode = implode(",",$request->ids);
            return redirect($module.'/dp-project/create/?ids='.base64_encode($ids_implode));
        }else{
            return Excel::download(new DeployerSelectableExport($request), time().'_deployer_stakeholders.xlsx');
        }
    }




    public function show($module,$id)
    {
        $thardPary_info = Http::withOptions(['verify' => false])->get('http://app.shujog.xyz/api/thardparyservice/get-user-info', [
            'user_id' => $id,
        ]);

        $user = User::with(['spatieRole','education', 'investment', 'asset', 'division', 'district', 'upazila'])
        ->where(['id'=>$id])
        ->first();


        $order_id_array = Orders::with('orderData', 'orderData.retailsProduct')->where('user_id', $id)
        ->where(['status' => 'Confirm'])
        ->pluck('id')
        ->toArray();



        $orderData = OrderData::with(['retailsProduct'])
        ->whereIn('order_id', $order_id_array)
        ->get();

        $totalOrderAmountArray = [];

        if (count($orderData) > 0) {

            foreach ($orderData as $item) {

                $totalOrderAmountArray[] = ($item->qty * $item->price) - $item->discount;

            }

        }



        //last 30 day orders Data
        $startDate_30 = now()->subDays(30)->toDateString();
        $endDate = now()->toDateString();

        $orderData = OrderData::with('retailsProduct')
        ->whereIn('order_id', $order_id_array)
        ->whereBetween('created_at',[$startDate_30.' 00:00:00',$endDate.' 23:59:59'])
        ->get();



        $total_30days_OrderAmountArray = [];

        if (count($orderData) > 0) {

            foreach ($orderData as $item) {

                $total_30days_OrderAmountArray[] = ($item->qty * $item->price) - $item->discount;

            }

        }





        //Sells Data
        $sell_id_array = Sells::with('salesData')->where('user_id', $id)
        ->pluck('id')
        ->toArray();

        $sells = Sells::with('salesData')->where('user_id', $id)
        ->get();


        $sells_data = SellsData::with(['sell', 'retailsProduct'])
        ->whereIn('sell_id', $sell_id_array)
        ->get();


        $total_discount_amount_array = [];

        foreach ($sells as $sell) {

            if ($sell->total_discount > 0) {

                if ($sell->discount_way == 'amount') {

                    $total_discount_amount_array[] = $sell->total_discount;

                } else {

                    $total_discount_amount_array[] = (($sell->total_sales_amount * $sell->total_discount) / 100);

                }

            } else {

                $total_discount_amount_array[] = 0;

            }

        }


        $total_sell_amount_array = [];
        $total_purchase_amount_array = [];

        foreach ($sells_data as $sells_data_item) {


            if ($sells_data_item->pro_type == 'product') {

                $total_sell_amount_array[] = ($sells_data_item->qty * $sells_data_item->price);

                $user_purchase_price = 0;

                if (!is_null($sells_data_item->retailsProduct)) {

                    $user_purchase_price = json_decode($sells_data_item->retailsProduct->user_purchase_price);

                    $total_purchase_amount_array[] = ($sells_data_item->qty * $user_purchase_price->wmm);

                } else {

                    $total_purchase_amount_array[] = 0;
                }

            }
        }



    /***************************
     * Start Sales Data last 30 days
    *********************************/

        $sell_id_array = Sells::with('salesData')->where('user_id', $id)
        ->whereBetween('created_at',[$startDate_30.' 00:00:00',$endDate.' 23:59:59'])
        ->pluck('id')
        ->toArray();

        $sells = Sells::with('salesData')->where('user_id', $id)
        ->whereBetween('created_at',[$startDate_30.' 00:00:00',$endDate.' 23:59:59'])
        ->get();


        $sells_data = SellsData::with(['sell', 'retailsProduct'])
        ->whereIn('sell_id', $sell_id_array)
        ->whereBetween('created_at',[$startDate_30.' 00:00:00',$endDate.' 23:59:59'])
        ->get();


        $total_discount_30_amount_array = [];

        foreach ($sells as $sell) {

            if ($sell->total_discount > 0) {

                if ($sell->discount_way == 'amount') {

                    $total_discount_30_amount_array[] = $sell->total_discount;

                } else {

                    $total_discount_30_amount_array[] = (($sell->total_sales_amount * $sell->total_discount) / 100);

                }

            } else {

                $total_discount_30_amount_array[] = 0;

            }

        }


        $total_sell_30_amount_array = [];
        $total_purchase_30_amount_array = [];

        foreach ($sells_data as $sells_data_item) {


            if ($sells_data_item->pro_type == 'product') {

                $total_sell_30_amount_array[] = ($sells_data_item->qty * $sells_data_item->price);

                $user_purchase_price = 0;

                if (!is_null($sells_data_item->retailsProduct)) {

                    $user_purchase_price = json_decode($sells_data_item->retailsProduct->user_purchase_price);

                    $total_purchase_30_amount_array[] = ($sells_data_item->qty * $user_purchase_price->wmm);

                } else {

                    $total_purchase_30_amount_array[] = 0;
                }

            }
        }
    /***************************
     * end Sales Data last 30 days
    *********************************/



        $clients = Member::where('user_id', $id)
        ->where('is_active', '1')
        ->count();




        $startDate_45 = now()->subDays(45)->toDateString();
        $endDate = now()->toDateString();

        $clients = Member::where('user_id', $id)
        ->where('is_active', '1')
        ->count();

        $clients_45_days = Member::where('user_id', $id)
        ->where('is_active', '1')
        ->whereBetween('created_at',[$startDate_45.' 00:00:00',$endDate.' 23:59:59'])
        ->count();


        return view("agent::deployers.stakeholders.details",[
            'user'=>$user,
            'module'=>$module,
            'thardpary_info'=>$thardPary_info->json(),
            'totalOrderAmountArray'=>$totalOrderAmountArray,
            'clients'=>$clients,
            'clients_45_days'=>$clients_45_days,
            'total_30days_OrderAmountArray'=>$total_30days_OrderAmountArray,
            'total_sell_amount' => array_sum($total_sell_amount_array),
            'total_purchase_amount' => array_sum($total_purchase_amount_array),
            'total_discount_amount_array' => array_sum($total_discount_amount_array),
            'total_sell_30_amount_array' => array_sum($total_sell_30_amount_array),
        ]);
    }








    public function commentView($module,$user_id)
    {

        $commentsQuery = StakeholderComment::with('user','stackholder')
        ->where(['user_id'=>$user_id])
        ->orderBy('id','desc')
        ->get();
        

        $is_complete = StakeholderComment::where(['user_id'=>$user_id,'status'=>'3'])->whereIn('flag',[21,26])->first();

        return view("agent::deployers.stakeholders.comments.comment",[
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

        $is_complete = StakeholderComment::where(['user_id'=>$user_id,'status'=>'3'])->whereIn('flag',[21,26])->first();
        
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
                'deployer_status'=>$request->status,
            ]);


            DB::commit();
            Session::flash('success','Your Comment submitted successfully');
            return redirect($module.'/dp-stkholders/comments/'.$user_id);

        }catch(Exception $ex){
            DB::rollback();
            Session::flash('error',$ex->getMessage());
            return redirect()->back();
        }

    }


}
