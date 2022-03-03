<?php

namespace Modules\Agent\Http\Controllers\Pmanager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Agent\Entities\AgentAssignProjectStakeholder;
use Modules\Agent\Entities\AgentProject;
use Modules\Agent\Entities\AgentProjectDistrict;
use Modules\Agent\Entities\AgentProjectDivision;
use Modules\Agent\Entities\AgentProjectUpazila;
use Modules\Agent\Entities\AgentProMngAssignProject;
use Modules\Agent\Exports\ProManager\ManagerProjectStkExport;
use Modules\Agent\Http\Requests\CreateAgentProjectRequest;
use Modules\Core\Entities\Location\District;
use Modules\Core\Entities\Location\Division;
use Modules\Core\Entities\Location\Upazila;
use Modules\Core\Entities\Shujog\Member;
use Modules\Core\Entities\Shujog\OrderData;
use Modules\Core\Entities\Shujog\Orders;
use Modules\Core\Entities\Shujog\Sells;
use Modules\Core\Entities\Shujog\SellsData;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class ProjectController extends Controller
{

    public function loadProjects(Request $request)
    {
        $userProjects = AgentProMngAssignProject::where(['user_id'=>$request->user_id])
        ->pluck('project_id')
        ->toArray();


        $projects = AgentProject::where(['is_active'=>1])->get(['id','name','sur_name']);

        return view("agent::ajax-load.selected-project",[
            'user_id'=>$request->user_id,
            'user_projects'=>$userProjects,
            'projects'=>$projects,
        ]);

    }






    public function index($module){

        if(auth()->user()->flag == 27){
            //For Member project Manager
            $project_ids = AgentProMngAssignProject::where(['user_id'=>auth()->user()->id])
            ->pluck('project_id')
            ->toArray();

            $projects = AgentProject::with('divisions','districts','upazilas','assign_pro')
            ->whereIn('id',$project_ids)
            ->orderBy('id','desc')
            ->paginate(100);

        }else{
            //For Admin project Manager
            $projects = AgentProject::with('divisions','districts','upazilas','assign_pro')
            ->orderBy('id','desc')
            ->paginate(100);
        }



        $last_updated = last_modify_human_date(AgentProject::latest()->first());
        $search = null;
        $filter_by = "None";

        $start_time = null;
        $end_time = null;

        return view('agent::pmanagers.projects.index',[
            'projects'=>$projects,
            'search'=>$search,
            'filter_by'=>$filter_by,
            'module'=>$module,
            'last_updated'=>$last_updated,
            'start_time'=>$start_time,
            'end_time'=>$end_time,
        ]);
    }



        public function create($module){

            $districts = District::where(['is_active'=>1])->get(['id','name']);
            $divisions = Division::where(['is_active'=>1])->get(['id','name']);

            return view('agent::pmanagers.projects.create',[
                 'module'=>$module,
                 'districts'=>$districts,
                 'divisions'=>$divisions,
            ]);
        }


        public function store(CreateAgentProjectRequest $request,$module){

            try{
                DB::beginTransaction();

                $project = AgentProject::create([
                    'name'=>$request->name,
                    'sur_name'=>$request->sur_name,
                    'slug'=>strtolower(str_replace(' ','-',$request->sur_name)),
                    'start_date'=>$request->start_time,
                    'end_date'=>$request->end_time,
                    'extention_time'=>$request->extention_time,
                    'customer_served'=>$request->customer_served,
                    'description'=>$request->description,
                    'wmm_target'=>$request->wmm_target,
                    'sales_target'=>$request->sales_target,
                    'created_by'=>auth()->user()->id,
                ]);

                $project_id =  $project->id;

                //Add Divisions
                if(!is_null($request->division_id)){
                    $division_query_data = array();
                    foreach($request->division_id as $div){
                        $division_query_data[] = [
                            'agent_project_id'=>$project_id,
                            'division_id'=>$div,
                            'created_at'=>now(),
                            'updated_at'=>now(),
                        ];
                    }
                    AgentProjectDivision::insert($division_query_data);
                }


                //Add Districts
                if(!is_null($request->district_id)){
                    $district_query_data = array();
                    foreach($request->district_id as $dis){
                        $ex = explode(':',$dis);
                        $district_query_data[] = [
                            'agent_project_id'=>$project_id,
                            'district_id'=>$ex[0],
                            'division_id'=>$ex[1],
                            'created_at'=>now(),
                            'updated_at'=>now(),
                        ];
                    }
                    AgentProjectDistrict::insert($district_query_data);
                }

                //Add Upazila
                if(!is_null($request->upazila_id)){
                    $upazila_query_data = array();
                    foreach($request->upazila_id as $upazila){
                        $ex = explode(':',$upazila);
                        $upazila_query_data[] = [
                            'agent_project_id'=>$project_id,
                            'district_id'=>$ex[1],
                            'upazila_id'=>$ex[0],
                            'created_at'=>now(),
                            'updated_at'=>now(),
                        ];
                    }
                    AgentProjectUpazila::insert($upazila_query_data);
                }

                DB::commit();
                Session::flash('success',"Create project Successfully");
                return redirect()->route('agent.pmg-projects.index',$module);

            }catch(Exception $ex){
                DB::rollBack();
                Session::flash('error',$ex->getMessage());
                return redirect()->back();
            }

        }





        public function filter($module,Request $request){

            //send success/fail message
           // event(new DataInsertedEvent());
           $filter_by = "";

           $query = AgentProject::with('divisions','districts','upazilas','assign_pro');

           if($request->search != ""){
                $filter_by .= "Name/Sur Name, ";
                $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('sales_target', 'LIKE', '%' . $request->search . '%')
                ->orWhere('wmm_target', 'LIKE', '%' . $request->search . '%')
                ->orWhere('customer_served', 'LIKE', '%' . $request->search . '%');
           }


           if ($request->start_date != "") {
                $query->whereDate('start_date', '>=', date('Y-m-d', strtotime($request->start_date)));
            }

            if ($request->end_date != "") {
                $query->whereDate('end_date', '<=', date('Y-m-d', strtotime($request->end_date)));
            }

            if(auth()->user()->flag == 27){
                //For Member project Manager
                $project_ids = AgentProMngAssignProject::where(['user_id'=>auth()->user()->id])
                ->pluck('project_id')
                ->toArray();

                $query->whereIn('id',$project_ids);
            }

           $projects = $query->orderBy('id','desc')->paginate(100);

            $last_updated = last_modify_human_date(AgentProject::latest()->first());
            $search = null;
            $filter_by = "None";

            $start_time = $request->start_time;
            $end_time = $request->end_time;

            return view('agent::pmanagers.projects.index',[
                'projects'=>$projects,
                'search'=>$search,
                'filter_by'=>$filter_by,
                'module'=>$module,
                'last_updated'=>$last_updated,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
            ]);
        }




        public function activation($module,$status,$id)
        {

            if($status == "active"){
                try{
                    AgentProject::where(['id'=>$id])->update([
                        'is_active'=>1
                    ]);
                    Session::flash('success',"Change Status successfully");
                    return redirect()->route('agent.pmg-projects.index',$module);

                }catch(Exception $ex){
                    Session::flash('error',$ex->getMessage());
                    return redirect()->back();
                }

            }else{

                try{
                    AgentProject::where(['id'=>$id])->update([
                        'is_active'=>0
                    ]);
                    Session::flash('success',"Change Status successfully");
                    return redirect()->route('agent.pmg-projects.index',$module);

                }catch(Exception $ex){
                    Session::flash('error',$ex->getMessage());
                    return redirect()->back();
                }

            }
        }




        public function edit($module,$id)
        {

            $project = AgentProject::findOrFail($id);


            $divisions = Division::where(['is_active'=>1])->get(['id','name']);

            $selected_district = AgentProjectDistrict::where(['agent_project_id'=>$id])->pluck('district_id')->toArray();
            $selected_division = AgentProjectDivision::where(['agent_project_id'=>$id])->pluck('division_id')->toArray();
            $selected_upazila = AgentProjectUpazila::where(['agent_project_id'=>$id])->pluck('upazila_id')->toArray();

            $districts = District::where(['is_active'=>1])
            ->whereIn('id',$selected_district)
            ->get(['id','name','division_id']);

            $upazilas = Upazila::where(['is_active'=>1])
            ->whereIn('id',$selected_upazila)
            ->get(['id','name','district_id']);


            return view('agent::pmanagers.projects.edit',[
                 'module'=>$module,
                 'districts'=>$districts,
                 'divisions'=>$divisions,
                 'project'=>$project,
                 'selected_district'=>$selected_district,
                 'selected_division'=>$selected_division,
                 'selected_upazila'=>$selected_upazila,
                 'upazilas'=>$upazilas
            ]);
        }



        public function update(Request $request,$module,$id)
        {
            try{
                DB::beginTransaction();

                $project = AgentProject::where(['id'=>$id])->update([
                    'name'=>$request->name,
                    'sur_name'=>$request->sur_name,
                    'slug'=>strtolower(str_replace(' ','-',$request->sur_name)),
                    'start_date'=>$request->start_time,
                    'end_date'=>$request->end_time,
                    'extention_time'=>$request->extention_time,
                    'customer_served'=>$request->customer_served,
                    'description'=>$request->description,
                    'wmm_target'=>$request->wmm_target,
                    'sales_target'=>$request->sales_target,
                    'created_by'=>auth()->user()->id,
                ]);



                //Add Divisions
                if(!is_null($request->division_id)){
                    $division_query_data = array();

                    foreach($request->division_id as $div){
                        $division_query_data[] = [
                            'agent_project_id'=>$id,
                            'division_id'=>$div,
                            'created_at'=>now(),
                            'updated_at'=>now(),
                        ];
                    }
                    AgentProjectDivision::where(['agent_project_id'=>$id])->delete();
                    AgentProjectDivision::insert($division_query_data);
                }


                //Add Districts
                if(!is_null($request->district_id)){
                    $district_query_data = array();
                    foreach($request->district_id as $dis){
                        $ex = explode(':',$dis);
                        $district_query_data[] = [
                            'agent_project_id'=>$id,
                            'district_id'=>$ex[0],
                            'division_id'=>$ex[1],
                            'created_at'=>now(),
                            'updated_at'=>now(),
                        ];
                    }
                    AgentProjectDistrict::where(['agent_project_id'=>$id])->delete();
                    AgentProjectDistrict::insert($district_query_data);
                }

                //Add Upazila
                if(!is_null($request->upazila_id)){
                    $upazila_query_data = array();
                    foreach($request->upazila_id as $upazila){
                        $ex = explode(':',$upazila);
                        $upazila_query_data[] = [
                            'agent_project_id'=>$id,
                            'district_id'=>$ex[1],
                            'upazila_id'=>$ex[0],
                            'created_at'=>now(),
                            'updated_at'=>now(),
                        ];
                    }
                    AgentProjectUpazila::where(['agent_project_id'=>$id])->delete();
                    AgentProjectUpazila::insert($upazila_query_data);
                }

                DB::commit();
                Session::flash('success',"Update project Successfully");
                return redirect()->route('agent.pmg-projects.index',$module);

            }catch(Exception $ex){
                DB::rollBack();
                Session::flash('error',$ex->getMessage());
                return redirect()->back();
            }
        }



        public function destroy(Request $request,$module,$id)
        {

            try{
                $obj = AgentProject::findOrFail($id);
                $obj->delete();
                Session::flash('success', "Remove User Successfully");
                return redirect()->route('agent.pmg-projects.index',$module);

            }catch (\Exception $exception){
                DB::rollback();
                Session::flash('error', $exception->getMessage());
                return redirect()->route('agent.pmg-projects.index',$module);
            }
        }


        public function projectStakeholders($module,$id)
        {

            $project = AgentProject::with('assign_pro')->findOrFail($id);

            $project_stakeholders = AgentAssignProjectStakeholder::with('stakeholder')
            ->where(['project_id'=>$id])
            ->get();


            $all_stakeholder_ids = AgentAssignProjectStakeholder::where(['project_id'=>$id])
            ->pluck('stakeholder_id')
            ->toArray();


            $all_stakeholders = User::whereIn('id',$all_stakeholder_ids)
            ->get(['id','division_id','district_id','upazila_id']);


            $divisions = Division::whereIn('id',$all_stakeholders->pluck('division_id')->toArray())->get(['id','name']);

            $districts_ids = implode(",",$all_stakeholders->pluck('district_id')->toArray());
            $upazila_ids = implode(",",$all_stakeholders->pluck('upazila_id')->toArray());

            $roles = Role::where(['is_view_agent_panel'=>1])->get(['id','name']);


            return view("agent::pmanagers.projects.stakeholders",[
                'project_stakeholders'=>$project_stakeholders,
                'module'=>$module,
                'project'=>$project,
                'start_date'=>null,
                'end_date'=>null,
                'search'=>null,
                'project_id'=>$id,
                'division_id'=>null,
                'district_id'=>null,
                'upazila_id'=>null,
                'role_id'=>null,
                'divisions'=>$divisions,
                'roles'=>$roles,
                'district_ids'=>$districts_ids,
                'upazila_ids'=>$upazila_ids,
                'status'=>null
            ]);
        }



        public function stkHolderFilter(Request $request, $module,$id)
        {

            $project = AgentProject::with('assign_pro')->findOrFail($id);

            $project_query = AgentAssignProjectStakeholder::with('stakeholder');

            // ->where(['project_id'=>$id])
            //->get();


            if($request->search != ""){
                $project_query->whereHas('stakeholder',function($sub) use($request){
                    $sub->where('name','like','%'.$request->search.'%')
                    ->orWhere('mobile','like','%'.$request->search.'%');
                });
            }


            if($request->division_id != ""){
                $project_query->whereHas('stakeholder',function($sub) use($request){
                    $sub->where('division_id',$request->division_id);
                });
            }


            if($request->district_id != ""){
                $project_query->whereHas('stakeholder',function($sub) use($request){
                    $sub->where('district_id',$request->district_id);
                });
            }

            if($request->upazila_id != ""){
                $project_query->whereHas('stakeholder',function($sub) use($request){
                    $sub->where('upazila_id',$request->upazila_id);
                });
            }

            if($request->role_id != ""){
                $project_query->whereHas('stakeholder',function($sub) use($request){
                    $sub->where('spatie_role_id',$request->role_id);
                });
            }




            if($request->start_date != "" || $request->end_date != ""){
                $project_query->whereBetween('created_at',array($request->start_date.' 00:00:00',$request->end_date. ' 23:59:59'));

            }


            if($request->is_active != ""){
                $project_query->where(['is_active'=>$request->is_active]);
            }

            $project_stakeholders = $project_query->where(['project_id'=>$id])->get();


            $all_stakeholder_ids = AgentAssignProjectStakeholder::with('stakeholder')
            ->where(['project_id'=>$id])
            ->pluck('stakeholder_id')
            ->toArray();


            $all_stakeholders = User::whereIn('id',$all_stakeholder_ids)
            ->get(['id','division_id','district_id','upazila_id']);


            $divisions = Division::whereIn('id',$all_stakeholders->pluck('division_id')->toArray())->get(['id','name']);

            $districts_ids = implode(",",$all_stakeholders->pluck('district_id')->toArray());
            $upazila_ids = implode(",",$all_stakeholders->pluck('upazila_id')->toArray());

            $roles = Role::where(['is_view_agent_panel'=>1])->get(['id','name']);

            return view("agent::pmanagers.projects.stakeholders",[
                'project_stakeholders'=>$project_stakeholders,
                'module'=>$module,
                'project'=>$project,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'search'=>$request->search,
                'project_id'=>$id,
                'division_id'=>$request->division_id,
                'district_id'=>$request->district_id,
                'upazila_id'=>$request->upazila_id,
                'role_id'=>$request->role_id,
                'divisions'=>$divisions,
                'upazila_ids'=>$upazila_ids,
                'district_ids'=>$districts_ids,
                'roles'=>$roles,
                'status'=>$request->status
            ]);
        }



        public function stkHolderExport(Request $request,$slug,$module)
        {
            if(!isset($request->ids)){
                Session::flash('error','Please Select at-least One Stakeholder.');
                return redirect()->back();
            }

            return Excel::download(new ManagerProjectStkExport($request), time().'_stakeholders.xlsx');
        }




        public function projectDashboard($module,$id)
        {
            $project = AgentProject::with('assign_pro','districts', 'upazilas')->findOrFail($id);

            $project_district_array = [];

            if (count($project->districts) > 0) {

                foreach ($project->districts as $district) {
                    $project_district_array[] = $district->district_id;
                }

            }


            $project_upazila_array = [];

            if (count($project->upazilas) > 0) {
                foreach ($project->upazilas as $upazila) {
                    $project_upazila_array[] = $upazila->upazila_id;
                }
            }



            $user_id_array = AgentAssignProjectStakeholder::where([
                'project_id' => $id,
                'is_active' => 1,
            ])
            ->orderBy('stakeholder_id', 'asc')
            ->pluck('stakeholder_id')
            ->toArray();

            $order_id_array = Orders::with('orderData', 'orderData.retailsProduct')
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($project->start_date)), date('Y-m-d 23:59:59', strtotime($project->end_date))])
            ->whereIn('user_id', $user_id_array)
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



        /*** Start Sells calculation ***/

        $sells = Sells::with('salesData')
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($project->start_date)), date('Y-m-d 23:59:59', strtotime($project->end_date))])
            ->whereIn('user_id', $user_id_array)
            ->get();


        $sell_id_array = Sells::with('salesData')
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($project->start_date)), date('Y-m-d 23:59:59', strtotime($project->end_date))])
            ->whereIn('user_id', $user_id_array)
            ->pluck('id')
            ->toArray();

        #dd($sell_id_array);

        $sells_data = SellsData::with(['sell', 'retailsProduct', 'service'])
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

            } elseif ($sells_data_item->pro_type == 'service') {

                $total_sell_amount_array[] = ($sells_data_item->qty * $sells_data_item->price);

            } else {

            }

        }

        /*** End Sells calculation ***/

        $projectDivisionIds = AgentProjectDivision::where(['agent_project_id'=>$project->id])
        ->pluck('division_id')
        ->toArray();

        $divisions = Division::where(['is_active'=>1])
        ->whereIn('id',$projectDivisionIds)
        ->get(['id','name']);


        $roles = Role::where(['is_active'=>1])->get(['id','name']);


        $clients = Member::whereIn('user_id', $user_id_array)
        ->where('is_active', '1')
        ->count();

        $all_users = User::whereIn('id',$user_id_array)->get(['id','name','flag','spatie_role_id']);
        $all_flags = array_unique($all_users->pluck('flag')->toArray());

        //Make array for role wise filter
        $flagsWithData = array();
        foreach($all_flags as $flag){
            $flagsWithData[strtolower(getRoleName($flag))] = $flag;
        }


        //dd(array_unique($arr));

        //dd($flagsWithData);

            return view("agent::pmanagers.projects.dashboard",[
                'module'=>$module,
                'project_id'=>$id,
                'project'=>$project,
                'divisions'=>$divisions,
                'roles'=>$roles,
                'total_order_amount' => array_sum($totalOrderAmountArray),
                'total_sell_amount' => array_sum($total_sell_amount_array),
                'total_purchase_amount' => array_sum($total_purchase_amount_array),
                'total_discount_amount_array' => array_sum($total_discount_amount_array),
                'total_clients' => $clients,
                'start_date' => date('d-m-Y', strtotime(now())),
                'end_date' => date('d-m-Y', strtotime(now())),
                'search' => '',
                'division_id' => '',
                'role_id'=>'',
                'status'=>'',
                'all_users'=>$all_users,
                'all_flags'=>$all_flags,
                'flagsWithData'=>$flagsWithData
            ]);
        }


        public function projectDashboardFilter(Request $request,$module,$id){

            $project = AgentProject::with('assign_pro','districts', 'upazilas')->findOrFail($id);

            $project_district_array = [];

            if (count($project->districts) > 0) {

                foreach ($project->districts as $district) {
                    $project_district_array[] = $district->district_id;
                }

            }


            $project_upazila_array = [];

            if (count($project->upazilas) > 0) {
                foreach ($project->upazilas as $upazila) {
                    $project_upazila_array[] = $upazila->upazila_id;
                }
            }


            $assign_user_query = AgentAssignProjectStakeholder::with('stakeholder')->where([
                'project_id' => $id,
                'is_active' => 1,
            ]);

            if($request->division_id != ""){
                $assign_user_query->whereHas('stakeholder',function($sub) use($request){
                    $sub->where('division_id',$request->division_id);
                });
            }

            if($request->district_id != ""){
                $assign_user_query->whereHas('stakeholder',function($sub) use($request){
                    $sub->where('district_id',$request->district_id);
                });
            }

            if($request->upazila_id != ""){
                $assign_user_query->whereHas('stakeholder',function($sub) use($request){
                    $sub->where('upazila_id',$request->upazila_id);
                });
            }

            if($request->user_id != ""){
                $assign_user_query->whereHas('stakeholder',function($sub) use($request){
                    $sub->where('user_id',$request->user_id);
                });
            }


            $user_id_array = $assign_user_query->orderBy('stakeholder_id', 'asc')
            ->pluck('stakeholder_id')
            ->toArray();


            $order_id_array_query = Orders::with('orderData', 'orderData.retailsProduct');
            if($request->start_date != "" && $request->end_date != ""){
                $order_id_array_query->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($request->start_date)), date('Y-m-d 23:59:59', strtotime($request->end_date))]);
            }else{
                $order_id_array_query->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($project->start_date)), date('Y-m-d 23:59:59', strtotime($project->end_date))]);
            }

            $order_id_array = $order_id_array_query->whereIn('user_id', $user_id_array)
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



        /*** Start Sells calculation ***/

        $sells_query = Sells::with('salesData');
            if($request->start_date != "" && $request->end_date != ""){
                $sells_query->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($request->start_date)), date('Y-m-d 23:59:59', strtotime($request->end_date))]);
            }else{
                $sells_query->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($project->start_date)), date('Y-m-d 23:59:59', strtotime($project->end_date))]);
            }
            $sells = $sells_query->whereIn('user_id', $user_id_array)
            ->get();


        $sell_id_array_query = Sells::with('salesData');
            // ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($project->start_date)), date('Y-m-d 23:59:59', strtotime($project->end_date))])

            if($request->start_date != "" && $request->end_date != ""){
                $sell_id_array_query->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($request->start_date)), date('Y-m-d 23:59:59', strtotime($request->end_date))]);
            }else{
                $sell_id_array_query->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($project->start_date)), date('Y-m-d 23:59:59', strtotime($project->end_date))]);
            }

            $sell_id_array = $sell_id_array_query->whereIn('user_id', $user_id_array)
            ->pluck('id')
            ->toArray();

        #dd($sell_id_array);

        $sells_data = SellsData::with(['sell', 'retailsProduct', 'service'])
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

            } elseif ($sells_data_item->pro_type == 'service') {

                $total_sell_amount_array[] = ($sells_data_item->qty * $sells_data_item->price);

            } else {

            }

        }

        /*** End Sells calculation ***/

        $projectDivisionIds = AgentProjectDivision::where(['agent_project_id'=>$project->id])
        ->pluck('division_id')
        ->toArray();

        $divisions = Division::where(['is_active'=>1])
        ->whereIn('id',$projectDivisionIds)
        ->get(['id','name']);


        $roles = Role::where(['is_active'=>1])->get(['id','name']);


        $clients = Member::whereIn('user_id', $user_id_array)
        ->where('is_active', '1')
        ->count();

        $all_users = User::whereIn('id',$user_id_array)->get(['id','name','flag','spatie_role_id']);
        $all_flags = array_unique($all_users->pluck('flag')->toArray());

        //Make array for role wise filter
        $flagsWithData = array();
        foreach($all_flags as $flag){
            $flagsWithData[strtolower(getRoleName($flag))] = $flag;
        }


        return view("agent::pmanagers.projects.dashboard",[
                'module'=>$module,
                'project_id'=>$id,
                'project'=>$project,
                'divisions'=>$divisions,
                'roles'=>$roles,
                'total_order_amount' => array_sum($totalOrderAmountArray),
                'total_sell_amount' => array_sum($total_sell_amount_array),
                'total_purchase_amount' => array_sum($total_purchase_amount_array),
                'total_discount_amount_array' => array_sum($total_discount_amount_array),
                'total_clients' => $clients,
                'start_date' => date('d-m-Y', strtotime(now())),
                'end_date' => date('d-m-Y', strtotime(now())),
                'search' => '',
                'division_id' => '',
                'role_id'=>'',
                'status'=>'',
                'all_users'=>$all_users,
                'all_flags'=>$all_flags,
                'flagsWithData'=>$flagsWithData
            ]);

        }


        //Active or Deactive Stakeholder From Agent
        public function stkHolderActivation($module,$status,$project_slug,$stk_id){

            $project = AgentProject::where(['slug'=>$project_slug])->first();
            if(is_null($project)){
                abort(404);
            }

                try{
                    if($status == "active"){
                        AgentAssignProjectStakeholder::where(['project_id'=>$project->id,'stakeholder_id'=>$stk_id])
                        ->update([
                            'is_active'=>1
                        ]);
                        Session::flash('success',"Change Status successfully");
                        return redirect()->back();

                    }else{
                        AgentAssignProjectStakeholder::where(['project_id'=>$project->id,'stakeholder_id'=>$stk_id])
                        ->update([
                            'is_active'=>0
                        ]);
                        Session::flash('success',"Change Status successfully");
                        return redirect()->back();
                    }

                }catch(Exception $ex){
                    Session::flash('error',$ex->getMessage());
                    return redirect()->back();
                }


        }





        public function stkHolderRemove($module,$project_slug,$stk_id){

            $project = AgentProject::where(['slug'=>$project_slug])->first();
            if(is_null($project)){
                abort(404);
            }

            try{
                AgentAssignProjectStakeholder::where(['project_id'=>$project->id,'stakeholder_id'=>$stk_id])
                ->delete();
                Session::flash('success',"Remove stakeholder successfully from ".$project->name." Project");
                return redirect()->back();

            }catch(Exception $ex){
                Session::flash('error',$ex->getMessage());
                return redirect()->back();
            }
        }



}


