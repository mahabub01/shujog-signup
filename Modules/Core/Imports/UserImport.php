<?php

namespace Modules\Core\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Modules\Agent\Entities\Stakeholder;
use Modules\Core\Entities\Location\District;
use Modules\Core\Entities\Location\Division;
use Modules\Core\Entities\Location\Upazila;

class UserImport implements ToModel{

    public function model(array $row)
    {

        /***
         *   0 => "Name"
            1 => "Email_Address"
            2 => "Contact_Number"
            3 => "Date_of_Birth"
            4 => "NID_Number"
            5 => "MFS_Account_Number"
            6 => "Division  "
            7 => "District "
            8 => "Upazilla"
            9 => "Union"
            10 => "Village"
            11 => "Gender (1= Male/ 2= Female)"
            12 => "Instutition Name "
            13 => "Sign-up Ref"
         *
         * * */


        if($row[0] != "Name" && ($row[0] != "" || $row[0] != null )){
            $gender = null;
            if(strtolower($row[11]) == 1){
                $gender = "male";
            }else if(strtolower($row[11]) == 2){
                $gender = "female";
            }

            $division = Division::where('name','like','%'.$row[6].'%')
            ->first();
            if(!is_null($division)){
                $division_id = $division->id;
            }else{
                $division_id = 1;
            }


            $distict = District::where('name','like','%'.$row[7].'%')
            ->first();
            if(!is_null($distict)){
                $distict_id = $distict->id;
            }else{
                $distict_id = 1;
            }


            $upazila = Upazila::where('name','like','%'.$row[8].'%')
            ->first();
            if(!is_null($upazila)){
                $upazila_id = $upazila->id;
            }else{
                $upazila_id = 1;
            }


            try{
               DB::beginTransaction();

                $obj = new User();
                $obj->setTranslation('name', 'en', $row[0]);
                $obj->setTranslation('name', 'bn', $row[0]);
                $obj->email = $row[1];
                $obj->mobile = '0'.$row[2];
                $obj->date_of_birth = $row[3];
                $obj->self_nid_number = $row[4];
                $obj->is_nid_card = 1;
                $obj->password = Hash::make(1234);
                $obj->username = uniqid();
                $obj->gender = $gender;
                $obj->gender_id = $row[11];
                $obj->signup_reference_id = 1;
                $obj->institute_name = $row[12];

                $mfs = array(
                    'bkash'=>'0'.$row[5],
                    'nagad'=>null,
                    'rocket'=>null,
                    'sure_cash'=>null,
                );

                $obj->self_mfs = json_encode($mfs);
                $obj->division_id = $division_id;
                $obj->district_id = $distict_id;
                $obj->upazila_id = $upazila_id;
                $obj->union = $row[9];
                $obj->mouza = $row[10];

                $obj->role_id = 24;
                $obj->spatie_role_id = 20;
                $obj->flag = 29;
                $obj->is_active = 1;
                $obj->save();

                Stakeholder::create([
                    'user_id'=>$obj->id
                ]);
                DB::commit();
            }catch (\Exception $ex){
                DB::rollBack();
                dd($ex->getMessage());
                Session::flash('error','Your Data file is Not Right Formate. and '.$ex->getMessage());
            }



        }


    }

}
