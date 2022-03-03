<?php

namespace Modules\Core\Http\Controllers\LocationApi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Entities\Location\District;
use Modules\Core\Entities\Location\Union;
use Modules\Core\Entities\Location\Upazila;
use Modules\Core\Entities\Location\Village;

class LocationController extends Controller
{


    public function getDistrictForSignUp(Request $request){
        $data = District::where(['division_id'=>$request->division_id, 'is_active' => 1])->get();
        echo '<option value="">--</option>';
        foreach ($data as $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }

    }



    public function getUpazilaForSignUp(Request $request){
        $data = Upazila::where(['district_id'=>$request->district_id, 'is_active' => 1])->get();
        echo '<option value="">--</option>';
        foreach ($data as $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }
    }



    public function getUnionForSignUp(Request $request){
        $data = Union::where(['upazila_id'=>$request->upazila_id, 'is_active' => 1])->get();
        echo '<option value="">--</option>';
        echo '<option value="others">Others</option>';
        foreach ($data as $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }
    }


    public function getVillageForSignUp(Request $request){
        $data = Village::where(['union_id'=>$request->union_id, 'is_active' => 1])->get();
        echo '<option value="">--</option>';
        echo '<option value="others">Others</option>';
        foreach ($data as $value) {
            echo '<option value="'.$value->id.'">'.$value->name.'</option>';
        }
    }


}
