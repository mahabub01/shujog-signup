<?php

namespace Modules\Core\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;
use Modules\Core\Entities\Platform\LoginActivity;

class LoginController extends Controller
{

    /**
     * Loading Login View
     * @param $prefix
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     *
     */
   public function loginView(){
        return view("core::auth.login");
   }

    public function loginViewSubmit(Request $request){



        $credentials = array('email'=>$request->email,'password'=>$request->password);
        $remember = $request->remember;

        $user = User::where(['email'=>$request->email,'is_active'=>1])->get();
        if(count($user) == 0){
            Session::flash('error','Your account is not Active. Please contact system administrator');
            return redirect()->back()->withInput($request->only('email'));
        }


        if(Auth::attempt($credentials,$remember)){
            if(auth()->user()->flag == 18){
                return redirect()->route('core.agent.dashboard');
            }else{

                $agent = new Agent();
                $device_info = 'Platform: ' . $agent->platform() . ', Browser:' . $agent->browser();
				$activity = new LoginActivity();
                $activity->user_id = auth()->user()->id;
                $activity->device_info = $device_info;
                $activity->save();

                return redirect()->intended('agent/load-component');
            }

        }else{
            Session::flash('error',"Your username and password is not valid.");
            return redirect()->back()->withInput($request->only('email'));
        }

    }


    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('core.login.loadview');
    }


}
