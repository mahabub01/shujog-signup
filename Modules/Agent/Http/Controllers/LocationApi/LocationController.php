<?php

namespace Modules\Agent\Http\Controllers\LocationApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Agent\Entities\AgentProjectDistrict;
use Modules\Agent\Entities\AgentProjectUpazila;
use Modules\Core\Entities\Location\District;
use Modules\Core\Entities\Location\Upazila;

class LocationController extends Controller
{

    public function dashboardLoadDistrictByDivision(Request $request){

        $division_ids = array();
        if(!is_null($request->division_id)){
            $division_ids = $request->division_id;
        }

        $district_ids = AgentProjectDistrict::where(['agent_project_id'=>$request->project_id,'division_id'=>$request->division_id])
        ->pluck('district_id')
        ->toArray();

        $districts = District::where(['is_active'=>1])
        ->where('division_id',$division_ids)
        ->whereIn('id',$district_ids)
        ->get(['id','name','division_id']);

        echo '<option value="">Choose</option>';
        foreach($districts as $district){
            echo '<option value="'.$district->id.'">'.$district->name.'</option>';
        }
    }



    public function dashboardLoadUpazilaByDistrict(Request $request){

        $district_ids = array();
        if(!is_null($request->district_id)){

            $upazila_ids = AgentProjectUpazila::where(['agent_project_id'=>$request->project_id,'district_id'=>$request->district_id])
            ->pluck('upazila_id')
            ->toArray();

            $upazilas = Upazila::where(['is_active'=>1])
            ->where('district_id',$request->district_id)
            ->whereIn('id',$upazila_ids)
            ->get(['id','name']);

            echo '<option value="">Choose</option>';
            foreach($upazilas as $upazila){
                echo '<option value="'.$upazila->id.'">'.$upazila->name.'</option>';
            }

        }else{
            echo '<option value="">Not Found</option>';
        }


    }



    public function stkLoadDistrictByDivision(Request $request)
    {
        $ex = explode(",",$request->districts);
        $districts = District::where(['is_active'=>1])
        ->where('division_id',$request->division_id)
        ->whereIn('id',$ex)
        ->get(['id','name']);

        echo '<option value="">Choose</option>';
        foreach($districts as $district){
            echo '<option value="'.$district->id.'">'.$district->name.'</option>';
        }
    }


    public function stkLoadUpazilaByDistrict(Request $request)
    {
        $ex = explode(",",$request->upazilas);
        $upazilas = Upazila::where(['is_active'=>1])
        ->where('district_id',$request->district_id)
        ->whereIn('id',$ex)
        ->get(['id','name']);

        echo '<option value="">Choose</option>';
        foreach($upazilas as $upazila){
            echo '<option value="'.$upazila->id.'">'.$upazila->name.'</option>';
        }
    }





}

