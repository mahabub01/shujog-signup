<?php

namespace Modules\Agent\Http\Controllers\Stakeholder;

use App\Models\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\AgentRoleUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Modules\Core\Entities\Location\District;
use Modules\Core\Entities\Location\Division;
use Modules\Core\Entities\Location\Union;
use Modules\Core\Entities\Location\Upazila;
use Modules\Core\Entities\Location\Village;
use Modules\Core\Entities\Shujog\SignupReference;
use Modules\Agent\Entities\Stakeholder;
use Spatie\Permission\Models\Role;
use Modules\Core\Entities\Common\EducationRequirement;
use Modules\Core\Entities\Common\InvestmentRequirement;
use Modules\Core\Entities\Common\AssetAvailability;
use Modules\Core\Entities\Shujog\Hub\Hub;
use Modules\Core\Entities\Shujog\Hub\HubWmm;
use Modules\Core\Entities\Shujog\GenderEducationAssetRole;
use Modules\Core\Entities\Shujog\GenderEducationAssetRoleComponent;
use Modules\Core\Entities\Auth\SubmoduleUser;
use Modules\Core\Entities\Shujog\ComponentCategory;
use Modules\Core\Entities\Shujog\UserSelectedCategoryOrService;
use Modules\Agent\Http\Requests\UpdateSignupInfoRequest;
use DB;



class UnCompleteSignupController extends Controller
{



    public function incompleteSignup(Request $request, $module)
    {

        $divisions = Division::where(['is_active'=>1])->get(['id','name']);

        $selectRoles = AgentRoleUser::with('role')->where(['user_id'=>auth()->user()->id])->get();
        if(count($selectRoles) == 0){
            Session::flash('warning','Do Not Have Any Stackholder Permissions. Please Contact Consultant Admin.');
            return redirect()->back();
        }

        $first_active_tab = null;
        if(isset($request->role)){
            $first_active_tab = AgentRoleUser::with('role')->where(['user_id'=>auth()->user()->id,'role_id'=>$request->role])->first();
        }


        $uncomplete = User::where('is_complete_quick_signup', 0)->paginate(100);



        $filter_by = "None";
        $last_updated = last_modify_human_date(AgentRoleUser::latest()->first());

        $filter_district = null;
        $filter_upazila = null;


        return view("agent::stakeholders.uncomplete.uncomplete",[
            'uncomplete'=>$uncomplete,
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
            'filter_district'=>null,
            'filter_upazila'=>null,
        ]);
    }







    public function filter(Request $request, $module){
        $divisions = Division::where(['is_active'=>1])->get(['id','name']);
        // $module = 'agent';

        $selectRoles = AgentRoleUser::with('role')->where(['user_id'=>auth()->user()->id])->get();
        if(count($selectRoles) == 0){
            abort(404,"Do Not Have Any Stackholder Permissions.");
        }

        $first_active_tab = null;
        $filter_by = "";
        $filter_district = null;
        $filter_upazila = null;



            $first_active_tab = $selectRoles->first();
            $stakeholders_query = User::where(['is_complete_quick_signup'=>0]);





            if($request->search != ""){
                $stakeholders_query->where('name','like','%'.$request->search.'%')
                ->orWhere(['mobile'=>$request->search]);
                $filter_by .= "Name/Mobile, ";
            }

            if($request->start_date != "" && $request->end_date != ""){
                $stakeholders_query->whereBetween('created_at',[$request->start_date.'00.00.00',$request->end_date.'23.59.59']);
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







            $data =  $stakeholders_query->orderBy(Stakeholder::select('consultant_status')->whereColumn('agent_stakeholders.user_id','sujog_users.id'))->paginate(100);





        $last_updated = last_modify_human_date(AgentRoleUser::latest()->first());

        $references = SignupReference::where(['is_active'=>1])->get(['id','title']);

        return view("agent::stakeholders.uncomplete.uncomplete",[
            'uncomplete'=>$data,
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



    public function details($module,$id)
    {

        $thardPary_info = Http::withOptions(['verify' => false])->get('http://app.shujog.xyz/api/thardparyservice/get-user-info', [
            'user_id' => $id,
        ]);

        $user = User::where(['id'=>$id])
        ->first();


        return view("agent::stakeholders.uncomplete.details",[
            'user'=>$user,
            'module'=>$module,
            'thardpary_info'=>$thardPary_info->json(),
        ]);
    }




    public function edit($module,$id)
    {

        $thardPary_info = Http::withOptions(['verify' => false])->get('http://app.shujog.xyz/api/thardparyservice/get-user-info', [
            'user_id' => $id,
        ]);

        $user = User::with(['spatieRole','education', 'investment', 'asset', 'division', 'district', 'upazila'])
        ->where(['id'=>$id])
        ->first();

        $roles = Role::where(['is_view_agent_panel'=>1])->get(['id','name']);

        $educations = EducationRequirement::where(['is_active'=>1])->get(['id','title']);

        $investments = InvestmentRequirement::where(['is_active'=>1])->get(['id','title']);

        $assets = AssetAvailability::where(['is_active'=>1])->get(['id','title']);

        $divisions = Division::all(['id','name']);

        $districts = District::all(['id','name']);

        $upazilas = Upazila::all(['id','name']);

        $unions = Union::where(['is_active'=>1])->get(['id','name']);

        $villages = Village::where(['is_active'=>1])->get(['id','name']);
        
        $references = SignUpReference::where(['is_active' => 1])->get(['id','title']);


        return view("agent::stakeholders.uncomplete.edit",[
            'user'=>$user,
            'module'=>$module,
            'id'=>$id,
            'roles'=>$roles,
            'educations'=>$educations,
            'investments'=>$investments,
            'assets'=>$assets,
            'divisions'=>$divisions,
            'districts'=>$districts,
            'upazilas'=>$upazilas,
            'unions'=>$unions,
            'villages'=>$villages,
            'thardpary_info'=>$thardPary_info->json(),
            'references'=>$references
        ]);
    }

    public function update(UpdateSignupInfoRequest $request,$module,$id){
        
        
        if($request->division_id == 9 || $request->district_id == 65 || $request->upazila_id == 545){
            Session::flash("error","Please update Division, District, Upazila Information");
            return redirect()->back();
            
        }


        try{
            //dd($request->all());

            $role = Role::where(['id'=>$request->role_id])->firstOrFail();
            $user = User::findOrFail($id);
            
            //Update Role Related Information
            if($user->spatie_role_id == "" || $user->spatie_role_id == null){
                
                $gender_education_asset_role = GenderEducationAssetRole::where(['role_id'=>$request->role_id])
                ->pluck('id')
                ->toArray();
    
                $component_ids = GenderEducationAssetRoleComponent::whereIn('gender_education_asset_role_id',$gender_education_asset_role)
                    ->pluck('component_id')
                    ->toArray();
        
                $component_ids_unique = array_unique($component_ids);
        
                $data = [];
                foreach ($component_ids_unique as $com_id){
                    $data[] = [
                        'user_id'=>$user->id,
                        'submodule_id'=>$com_id,
                        'created_at'=>now(),
                        'updated_at'=>now()
                    ];
                }
                
                
                 SubmoduleUser::insert($data); //insert user component


                $categories = ComponentCategory::with('categoryApi')
                    ->whereIn('component_id', $component_ids)
                    ->get();
    
                $category_insert_data = [];
                foreach ($categories as $category){
                    $is_approve = 0;
                    $category_id = $category->categoryApi->id;
                    //return $category_id;
    
                    if($category->categoryApi->category_flag == "1"){
                        $is_approve = 1;
                    }
    
                    $category_insert_data[] = [
                        'user_id'=>$user->id,
                        'category_id'=>$category_id,
                        'is_approve'=>$is_approve,
                        'is_active'=>1,
                        'created_at'=>now(),
                        'updated_at'=>now()
                    ];
    
                }
    
                UserSelectedCategoryOrService::insert($category_insert_data); //insert User Selected Category
                
                $user->spatie_role_id = $request->role_id;
                $user->flag = $role->flag;
        
            }
            //end Role Related Information
            
            
            $user->setTranslation('name','en',$request->name);
            $user->setTranslation('name','bn',$request->name);
            $user->signup_reference_id = $request->signup_reference_id;
            

            $user->mobile = $request->mobile;
            $user->self_nid_number = $request->self_nid_number;
            $user->date_of_birth = $request->date_of_birth;
            $user->gender = $request->gender;
            $user->email = $request->email;
            $user->self_nid_present_address = $request->self_nid_present_address;
            $user->self_permenant_address = $request->self_permenant_address;


            $user->education_requirement_id = $request->education_requirement_id;
            $user->institute_name = $request->institute_name;
            $user->division_id = $request->division_id;
            $user->district_id = $request->district_id;
            $user->upazila_id = $request->upazila_id;
            
            



            $union_id = null;
            if($request->union_id == "others"){
                if(isset($request->union_name) && $request->union_name != ""){
                    $uon = new Union();
                    $uon->setTranslation('name','en',$request->union_name);
                    $uon->setTranslation('name','bn',$request->union_name);
                    $uon->upazila_id =  $request->upazila_id;
                    $uon->is_active = 1;
                    $uon->save();
                    $union_id = $uon->id;
                    $user->union = $request->union;
                }
            }else{
                $user->union_id = $request->union_id;
                $union_id = $request->union_id;
            }




            if($request->village_id == "others"){
                if(isset($request->village_name) && $request->village_name != ""){
                    $vill = new Village();
                    $vill->setTranslation('name','en',$request->village_name);
                    $vill->setTranslation('name','bn',$request->village_name);
                    $vill->union_id =  $union_id;
                    $vill->is_active = 1;
                    $vill->save();
                    $vill->mouza = $request->village_name;
                    $user->village_id = $vill->id;
                }
            }else{
                $user->village_id = $request->village_id;
            }


            //MFS
            $mfs = array(
                'bkash'=>$request->bkash,
                'nagad'=>$request->nagad,
                'rocket'=>$request->rocket,
                'sure_cash'=>$request->sure_cash,
            );


            $user->self_mfs = json_encode($mfs);
            $user->self_bank_asia_account = $request->self_bank_asia_account;
            $user->investment_requirement_id = $request->investment_requirement_id;
            $user->trade_license_number = $request->trade_license_number;
            $user->asset_availabilitiey_id = $request->asset_availabilitiey_id;

            $user->guardian_relation = $request->guardian_relation;
            $user->guardian_name = $request->guardian_name;
            $user->guardian_phone = $request->guardian_phone;
            $user->guardian_nid_number = $request->guardian_nid_number;
            $user->is_complete_quick_signup = 1;
            $user->user_status = 1;
            $user->is_assign_hub = 1;
            $user->assign_hub_type = "auto";
            $user->is_approved_by_agent = "1";
            $user->is_follow_up_by_agent = "No";
            $user->is_complete_earn_signup = 1;
            $user->is_complete_genarel_signup = 1;
            $user->is_active = 1;
            $user->role_id = 2;
            $user->password = bcrypt(1234);
            $user->update();
            
            //Assign Hub
            $hub = Hub::where(['is_active'=>1,'division_id'=>$request->division_id,'district_id'=>$request->district_id,'upazila_id'=>$request->upazila_id])->first();
            if(is_null($hub)){
                $hubUser = new HubWmm();
                $hubUser->hub_id = 29; // 29 Defalut Isocial Hub
                $hubUser->user_id = $user->id;
                $hubUser->flag = 4;
                $hubUser->save();
            }else{
                $hubUser = new HubWmm();
                $hubUser->hub_id = $hub->id;
                $hubUser->user_id = $user->id;
                $hubUser->flag = 4;
                $hubUser->save();
            }
            
            
            
            

            Session::flash('success', "User Data Updated Successfully");
            return redirect($module."/stakeholder/incomplete-signup");

        }catch(Exception $ex){
            Session::flash('error', $ex->getMessage());
            return redirect()->back();
        }
        
        
    }












}









