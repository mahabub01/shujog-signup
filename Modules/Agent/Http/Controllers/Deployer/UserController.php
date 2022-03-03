<?php

namespace Modules\Agent\Http\Controllers\Deployer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\DataInsertedEvent;
use App\Events\ErrorEvent;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Agent\Entities\AgentRoleUser;
use Modules\Core\Entities\Auth\Module;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{

    public function index($module){

        $users = User::with('spatieRole','agentAssignRole','agentAssignRole.role')
        ->whereIn('flag',getAgentDeployerUserFlag())
        ->orderBy('id','desc')
        ->paginate(100);

        $search = null;
        $role_id = null;
        $filter_by = "None";

        $last_updated = last_modify_human_date(AgentRoleUser::latest()->first());

        $roles = Role::whereIn('flag',[21,26])->get();

        return view('agent::deployers.users.index',compact('users','roles','module','search','role_id','filter_by','last_updated'));
    }



    public function create($module){
        $roles = Role::where(['flag'=>26])->get(['id','name']);

        return view('agent::deployers.users.create',[
             'module'=>$module,
             'roles'=>$roles
        ]);
    }



    public function store(Request $request,$module){
        $this->validate($request,[
            'name'=>'required|max:255',
            'email'=>'required|email|max:255|unique:sujog_users',
            'mobile'=>'required|max:15|unique:sujog_users',
            'role_id'=>'required',
            'password'=>'required|min:4|max:20'
        ]);



        $role_has_permission = DB::table('role_has_permissions')->where(['role_id'=>$request->role_id])->pluck('permission_id')->toArray();
        $permissions = Permission::whereIn('id',$role_has_permission)->get();

        if(count($permissions) == 0){
            $this->Errors = "Do not have Any Module or Sub-Module in this Role.";
            return false;
        }

        $rols = DB::table('roles')->where(['id'=>$request->role_id])->first();

        try{

             DB::beginTransaction();
             $obj = new User();
             $obj->setTranslation('name', 'en', $request->name);
             $obj->setTranslation('name', 'bn', $request->name);
             $obj->role_id = 24; //others role from sujog_roles
             $obj->spatie_role_id = $request->role_id;
             $obj->email = $request->email;
             $obj->flag = $rols->flag;
             $obj->mobile  = $request->mobile;
             $obj->is_active = 1;
             $obj->password = bcrypt($request->password);
             $obj->save();

             /***********************************
              * Role Management
              **************************************/
             $role = Role::findById($request->role_id);
             $user = User::find($obj->id);
             $user->assignRole($role->name);


             $modules = [];
             $submodules = [];
             //module assign and submodule
             foreach ($permissions as $pp){
                 $modules[] = $pp->module_id;
                 $submodules[] = $pp->sub_module_id;
             }

             //Direct Assign Permission
             $user->syncPermissions($permissions->pluck('name')->toArray());


             $unique_modules = array_unique($modules);
             $unique_sub_modules = array_unique($submodules);

             foreach ($unique_modules as $module){
                 DB::table('module_user')->insert([
                     'user_id'=>$obj->id,
                     'module_id'=>$module,
                     'created_at'=>now(),
                     'updated_at'=>now(),
                 ]);
             }

             foreach ($unique_sub_modules as $submodule){
                 DB::table('submodule_user')->insert([
                     'user_id'=>$obj->id,
                     'submodule_id'=>$submodule,
                     'created_at'=>now(),
                     'updated_at'=>now(),
                 ]);
             }

             /***********************************
              * Role Management
              **************************************/
             DB::commit();
             Session::flash('success', "User Created Successfully");
             return redirect()->route('agent.deployer-users.index',$module);

         }catch (\Exception $exception){
             DB::rollback();
             Session::flash('error', $exception->getMessage());
             return redirect()->route('agent.deployer-users.index',$module);
         }
    }




    public function editPermission($mod,$user_id)
    {
        $fnd_user = User::with('roles')->find($user_id);
        $user = $fnd_user->getDirectPermissions();

        $role_name = null;
        if(!is_null( $fnd_user->roles->first())){
            $role_name = $fnd_user->roles->first()->name;
        }

        $permissionIds = $user->pluck('id')->toArray();
        $moduleids = $user->pluck('module_id')->toArray();
        $submodulesIds = $user->pluck('sub_module_id')->toArray();

        $modules = Module::with('permissions')
        ->whereIn('id', $moduleids)
        ->get();

        return view('agent::deployers.users.edit-permission',[
            'modules'=> $modules,
            'allPermissions'=>$user,
            'moduleIds'=>$moduleids,
            'submodulesIds'=>$submodulesIds,
            'permissionIds'=>$permissionIds,
            'user_id'=>$user_id,
            'role_name'=>$role_name,
            'mod'=>$mod
        ]);

    }


    public function editPermissionSubmit(Request $request,$mod,$user_id){
        try{
            DB::beginTransaction();

            $user = User::find($user_id);
            $all_old_permissions = $user->getAllPermissions()->pluck('id')->toArray();

            $permissionName = Permission::whereIn('id',$request->permission_id)->pluck('name')->toArray();


            $user->syncPermissions($permissionName);
            //update user permission

            $permissions = Permission::whereIn('id',$request->permission_id)->get();
            $modules = [];
            $submodules = [];
            foreach ($permissions as $permission){
                $modules[] = $permission->module_id;
                $submodules[] = $permission->sub_module_id;
            }

            $unique_modules = array_unique($modules);
            $unique_sub_modules = array_unique($submodules);

            //module insert
            DB::table('module_user')->where(['user_id'=>$user_id])->delete();
            foreach ($unique_modules as $module){
                DB::table('module_user')->insert([
                    'user_id'=>$user_id,
                    'module_id'=>$module,
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ]);
            }

            // submodules
            DB::table('submodule_user')->where(['user_id'=>$user_id])->delete();
            foreach ($unique_sub_modules as $submodule){
                DB::table('submodule_user')->insert([
                    'user_id'=>$user_id,
                    'submodule_id'=>$submodule,
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ]);
            }

            DB::commit();
            Session::flash('success','Update Permission successfully');
            return redirect()->route('agent.deployers-users.index',$mod);
        }catch (\Exception $exception){
            DB::rollback();
            Session::flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }




    public function assignRole($module,Request $request)
    {
        $this->validate($request,[
            'roles'=>'required'
        ]);

        try{
            //Remove old Data
            AgentRoleUser::where(['user_id'=>$request->user_id])->delete();

            foreach($request->roles as $role){
                AgentRoleUser::create([
                    'user_id'=>$request->user_id,
                    'role_id'=>$role
                ]);
            }

            event(new DataInsertedEvent("Data Saved Successfully"));
            return redirect()->route('agent.deployer-users.index',$module);

        }catch(Exception $ex){
            event(new ErrorEvent($ex->getMessage()));
            return redirect()->route('agent.deployer-users.index',$module);
        }

    }




    public function filter($module,Request $request){

       $filter_by = "";

       $query = User::with('spatieRole')
       ->whereIn('flag',getAgentDeployerUserFlag());

       if($request->search != ""){
        $query->where('name','like','%'.$request->search.'%')
        ->orWhere('email','like','%'.$request->search.'%')
        ->orWhere('mobile','like','%'.$request->search.'%');
        $filter_by .= "Name, Email, Mobile, ";
       }

       if($request->role_id != ""){
            $query->where(['spatie_role_id' => $request->role_id]);
            $filter_by .= "Role";
       }

       $users = $query->orderBy('id','desc')->paginate(100);

       $roles = Role::whereIn('flag',getAgentDeployerUserFlag())->where(['is_active'=>1])->get();
       $search = $request->search;
       $role_id = $request->role_id;

       $last_updated = last_modify_human_date(AgentRoleUser::latest()->first());



       return view('agent::deployers.users.index',compact('users','roles','module','search','role_id','filter_by','last_updated'));

    }




    public function activation($module,$status,$user_id)
    {

        if($status == "active"){
            try{
                User::where(['id'=>$user_id])->update([
                    'is_active'=>1
                ]);
                Session::flash('success',"Change Status successfully");
                return redirect()->route('agent.deployer-users.index',$module);

            }catch(Exception $ex){
                Session::flash('error',$ex->getMessage());
                return redirect()->back();
            }

        }else{

            try{
                User::where(['id'=>$user_id])->update([
                    'is_active'=>0
                ]);
                Session::flash('success',"Change Status successfully");
                return redirect()->route('agent.deployer-users.index',$module);

            }catch(Exception $ex){
                Session::flash('error',$ex->getMessage());
                return redirect()->back();
            }

        }
    }




    public function edit($module,$user_id)
    {
        $user = User::findOrFail($user_id);
        $roles = Role::where(['flag'=>26])->get(['id','name']);
        return view('agent::deployers.users.edit',[
             'module'=>$module,
             'roles'=>$roles,
             'user'=>$user
        ]);
    }




    public function update(Request $request,$module,$user_id)
    {

        $this->validate($request,[
            'name'=>'required|max:255',
            'email'=>'required|email|max:255|unique:sujog_users,email,'.$user_id,
            'mobile'=>'required|max:15|unique:sujog_users,mobile,'.$user_id,
            'role_id'=>'required',
            'password'=>'required|min:4|max:20'
        ]);


        try{

             $obj = User::findOrFail($user_id);
             $obj->setTranslation('name', 'en', $request->name);
             $obj->setTranslation('name', 'bn', $request->name);
             $obj->spatie_role_id = $request->role_id;
             $obj->email = $request->email;
             $obj->mobile  = $request->mobile;
             $obj->password = bcrypt($request->password);
             $obj->update();

             Session::flash('success', "Update user Successfully");
             return redirect()->route('agent.deployer-users.index',$module);

         }catch (\Exception $exception){
             DB::rollback();
             Session::flash('error', $exception->getMessage());
             return redirect()->route('agent.deployer-users.index',$module);
         }
    }



    public function destroy(Request $request,$module,$user_id)
    {
        try{
            $obj = User::findOrFail($user_id);
            $obj->delete();
            Session::flash('success', "Remove User Successfully");
            return redirect()->route('agent.deployer-users.index',$module);

        }catch (\Exception $exception){
            DB::rollback();
            Session::flash('error', $exception->getMessage());
            return redirect()->route('agent.deployer-users.index',$module);
        }
    }

}
