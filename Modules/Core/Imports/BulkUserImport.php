<?php

namespace Modules\Core\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Modules\Agent\Entities\Stakeholder;
use Modules\Core\Entities\Auth\SubmoduleUser;
use Modules\Core\Entities\Location\District;
use Modules\Core\Entities\Location\Division;
use Modules\Core\Entities\Location\Union;
use Modules\Core\Entities\Location\Upazila;
use Modules\Core\Entities\Location\Village;
use Modules\Core\Entities\Shujog\UserSelectedCategoryOrService;
use Modules\Core\Entities\Shujog\ComponentCategory;
use Modules\Core\Entities\Shujog\GenderEducationAssetRole;
use Modules\Core\Entities\Shujog\GenderEducationAssetRoleComponet;
use Modules\Core\Entities\Shujog\Hub\Hub;
use Modules\Core\Entities\Shujog\Hub\HubWmm;
use Spatie\Permission\Models\Role;

class BulkUserImport implements ToModel{

    public function model(array $row)
    {


        if(isset($row[1]) && $row[1] != "Name" && ($row[1] != "" || $row[1] != null )){

            $gender_id = null;
            if(strtolower($row[18]) == "male"){
                $gender_id = 1;
            }else if(strtolower($row[18]) == "female"){
                $gender_id = 2;
            }

            //Get Role Id
            $role_id = getRoleId($row[0]);
            if(!is_null($role_id)){

                //Data insert Here
                $role = Role::where(['id'=>$role_id])->first();

                $division = Division::where('name','like','%'.$row[5].'%')
                ->first();
                if(!is_null($division)){
                    $division_id = $division->id;
                }else{
                    $division_id = null;
                }


                $distict = District::where('name','like','%'.$row[6].'%')
                ->first();
                if(!is_null($distict)){
                    $distict_id = $distict->id;
                }else{
                    $distict_id = null;
                }


                $upazila = Upazila::where('name','like','%'.$row[7].'%')
                ->first();
                if(!is_null($upazila)){
                    $upazila_id = $upazila->id;
                }else{
                    $upazila_id = null;
                }


                if($row[8] != ""){
                    $union = Union::where('name','like','%'.$row[8].'%')
                    ->first();
                    if(!is_null($union)){
                        $union_id = $union->id;
                    }else{
                        $union_id = null;
                    }
                }else{
                    $union_id = null;
                }

                if($row[9] != ""){
                    $village = Village::where('name','like','%'.$row[9].'%')
                    ->first();
                    if(!is_null($village)){
                        $village_id = $village->id;
                    }else{
                        $village_id = null;
                    }
                }else{
                    $village_id = null;
                }


                //start operation here
                try{
                    DB::beginTransaction();
                     $obj = new User();
                     $obj->setTranslation('name', 'en', $row[1]);
                     $obj->setTranslation('name', 'bn', $row[1]);
                     $obj->email = is_null($row[3])? '0'.$row[2]."@shujog.xyz":strtolower($row[3]);
                     $obj->mobile = '0'.$row[2];
                     $obj->division_id = is_null($division_id)? 1:$division_id;
                     $obj->district_id = is_null($distict_id)? 1:$distict_id;
                     $obj->upazila_id = is_null($upazila_id)? 1:$upazila_id;
                     $obj->union_id = $union_id;
                     $obj->village_id = $village_id;
                     $obj->union = $row[10];
                     $obj->mouza = $row[11];
                     $obj->password = Hash::make(1234);
                     $obj->date_of_birth = $row[17];
                     $obj->self_nid_number = $row[16];
                     $obj->is_nid_card = is_null($row[16])? 0: 1;
                     $obj->username = uniqid();
                     $obj->gender = is_null($row[18])? "male":strtolower($row[18]);
                     $obj->gender_id = is_null($gender_id)? 1:$gender_id;
                     $obj->institute_name = $row[19];
                     $obj->signup_media = '2';

                    $bkash = $row[12] != "" ? '0'.$row[12]:null;
                    $nagad = $row[13] != "" ? '0'.$row[13]:null;
                    $rocket = $row[14] != "" ? '0'.$row[14]:null;

                    $mfs = array(
                        'bkash'=>$bkash,
                        'nagad'=>$nagad,
                        'rocket'=>$rocket,
                        'sure_cash'=>null,
                    );

                     $obj->self_mfs = json_encode($mfs);
                     $obj->self_bank_asia_account = $row[15];

                     if(strtolower($row[0]) == "kallyani"){
                        $obj->role_id = 2;
                     }else if(strtolower($row[0]) == "sukormi"){
                        $obj->role_id = 2;
                     }else{
                        $obj->role_id = 24;
                     }

                     $obj->spatie_role_id = $role->id;
                     $obj->flag = $role->flag;
                     $obj->is_active = 1;
                     $obj->save();


                     //Create Data for Agent Panel
                     Stakeholder::create([
                         'user_id'=>$obj->id
                     ]);


                    //Insert Permission and category for Mobile App GenderEducationAssetRoleComponent
                    if(strtolower($row[0]) == "kallyani" || strtolower($row[0]) == "sukormi" || strtolower($row[0]) == "shujog-shohojogi"){

                        $gender_education_asset_role = GenderEducationAssetRole::where(['role_id'=>$role->id])
                            ->pluck('id')
                            ->toArray();

                        $component_ids = GenderEducationAssetRoleComponet::whereIn('gender_education_asset_role_id',$gender_education_asset_role)
                            ->pluck('component_id')
                            ->toArray();

                        $component_ids_unique = array_unique($component_ids);

                        $data = [];
                        foreach ($component_ids_unique as $com_id){
                            $data[] = [
                                'user_id'=>$obj->id,
                                'submodule_id'=>$com_id,
                                'created_at'=>now(),
                                'updated_at'=>now()
                            ];
                        }

                        //SubmoduleUser
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
                                'user_id'=>$obj->id,
                                'category_id'=>$category_id,
                                'is_approve'=>$is_approve,
                                'is_active'=>1,
                                'created_at'=>now(),
                                'updated_at'=>now()
                            ];

                        }

                        //UserSelectedCategoryOrService

                        UserSelectedCategoryOrService::insert($category_insert_data); //insert User Selected Category


                        User::where(['id' => $obj->id])->update([
                            'user_status' => 1,
                            'is_assign_hub' => 1,
                            'assign_hub_type' => 'auto',
                            'is_approved_by_agent' => "1",
                            'is_follow_up_by_agent' => "No",
                            'is_complete_earn_signup'=>1,
                            'is_complete_genarel_signup'=>1,
                            'is_active'=>1
                        ]);

                    }
                    // End for mobile App


                    //Assign Hub

                    $hub = Hub::where(['is_active'=>1,'division_id'=>$division_id,'district_id'=>$distict_id,'upazila_id'=>$upazila_id])->first();
                    if(is_null($hub)){
                        $hubUser = new HubWmm();
                        $hubUser->hub_id = 29; // 29 Defalut Isocial Hub
                        $hubUser->user_id = $obj->id;
                        $hubUser->flag = $role->flag;
                        $hubUser->save();
                    }else{
                        $hubUser = new HubWmm();
                        $hubUser->hub_id = $hub->id;
                        $hubUser->user_id = $obj->id;
                        $hubUser->flag = $role->flag;
                        $hubUser->save();
                    }


                     DB::commit();
                 }catch (\Exception $ex){
                     DB::rollBack();
                     dd($ex->getMessage());
                     Session::flash('error','Your Data file is Not Right Formate. and '.$ex->getMessage());
                 }

                //end operation here





            }


        }


    }

}
