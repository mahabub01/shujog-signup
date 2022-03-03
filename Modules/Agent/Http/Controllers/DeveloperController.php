<?php

namespace Modules\Agent\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Agent\Entities\Stakeholder;

class DeveloperController extends Controller
{

    public function addUsersForAgent(){


        $users = User::whereIn('spatie_role_id',[17,18,19,20])->get();
        foreach($users as $user){
            Stakeholder::create([
                'user_id'=>$user->id,
                'consultant_status'=>'1',
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        }


        dd("done...");



    }


}
