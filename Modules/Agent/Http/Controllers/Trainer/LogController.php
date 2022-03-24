<?php

namespace Modules\Agent\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index($module)
    {

        $id = Auth::id();
        $flag = Auth::user()->flag;

        if ($flag == 20) {
            $user_id = User::whereIn('flag', [20, 25])->pluck('id')->toArray();
            $logs = Activity::whereIn('causer_id', $user_id)->whereDate('created_at', Carbon::today())->latest()->paginate(20);
        } else {
            $logs = Activity::where('causer_id', $id)->latest()->paginate(20);
        }

        $filter_by = "None";
        $last_updated = last_modify_human_date(Activity::latest()->first());


        return view("agent::trainers.log.log", [
            'last_updated' => $last_updated,
            'logs' => $logs,
            'module' => $module,
            'filter_by' => $filter_by,
            'search' => null,
            'start_date' => null,
            'end_date' => null,
        ]);
    }

    public function filter(Request $request, $module)
    {

        $id = Auth::id();
        $flag = Auth::user()->flag;
        $search = $request->search;
        $filter_by = "";


        if ($flag == 20) {

            // search by name
            if($request->search != ""){
                $user_id = User::whereIn('flag', [20, 25])->where('name','like','%'.$request->search.'%')->pluck('id')->toArray();
                $logs = Activity::whereIn('causer_id', $user_id);
                $filter_by .= "Name, ";
            }else{
                $user_id = User::whereIn('flag', [20, 25])->pluck('id')->toArray();
                $logs = Activity::whereIn('causer_id', $user_id);
            }

            // search by date
            if($request->start_date != "" && $request->end_date != ""){
                $logs->whereBetween('created_at',[$request->start_date.' 00.00.00',$request->end_date.' 23.59.59']);
                $filter_by .= "Date, ";
            }else{
                $logs->whereDate('created_at', Carbon::today());
            }

        } else {

            // member condition
            $logs = Activity::where('causer_id', $id);

            // search by date
            if($request->start_date != "" && $request->end_date != ""){
                $logs->whereBetween('created_at',[$request->start_date.' 00.00.00',$request->end_date.' 23.59.59']);
                $filter_by .= "Date, ";
            }else{
                $logs->whereDate('created_at', Carbon::today());
            }

        }

        $last_updated = last_modify_human_date(Activity::latest()->first());


        $data = $logs->latest()->paginate(10);

        return view("agent::trainers.log.log", [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'last_updated' => $last_updated,
            'logs' => $data,
            'module' => $module,
            'filter_by' => $filter_by,
        ]);
    }
}
