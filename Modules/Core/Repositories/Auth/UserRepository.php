<?php


namespace Modules\Core\Repositories\Auth;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Modules\Agent\Entities\AgentRoleUser;
use Modules\Core\Repositories\Contracts\Auth\UserInterface;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRepository implements UserInterface
{
    public $Errors;

    /**
     * Return All Shop data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(){
        return User::with('spatieRole')->orderBy('id','desc')->paginate(100);
    }



    /**
     * Return All Shop data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllActive(){
        return User::with('spatieRole')
            ->where(['is_active'=>1])
            ->orderBy('id','desc')
            ->paginate(100);
    }


    /**
     * when send col name and value
     * @param $key
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|Shop
     */
    //$arry = ['id'=>2];
    public function findBy($key){
        $shops = User::with('spatieRole')
            ->where(['is_active'=>1]);
        foreach ($key as $col => $v){
            $shops->where($col,$v);
        }
        return $shops->firstOrFail();
    }


    /**
     * Insert Shop Table Data
     * @param $request
     * @return bool
     */
    public function store($request){

        $role_has_permission = DB::table('role_has_permissions')->where(['role_id'=>$request->role_id])->pluck('permission_id')->toArray();
        $permissions = Permission::whereIn('id',$role_has_permission)->get();

        if(count($permissions) == 0){
            $this->Errors = "Do not have Any Module or Sub-Module in this Role.";
            return false;
        }

        $rols = DB::table('roles')->where(['id'=>$request->role_id])->first();

        try{

           // dd($rols);

            DB::beginTransaction();
            $obj = new User();
            $obj->setTranslation('name', 'en', $request->name);
            $obj->setTranslation('name', 'bn', $request->name);
            $obj->role_id = 24; //others role from sujog_roles
            $obj->spatie_role_id = $request->role_id;
            $obj->email = $request->email;
            $obj->flag = $rols->flag;
            //$obj->user_type  = $request->user_type;
            $obj->mobile  = $request->mobile;
            $obj->is_active = 1;
            $obj->password = bcrypt($request->password);
            $obj->save();


            //jodi Admin Consultant hoi tahole all Stakeholder ar tab ar jonno user Id and role Id assign hobe
            //19 is Admin consultant flag
            if($rols->flag == 19 || $rols->flag == 20 || $rols->flag == 21 || $rols->flag == 23 || $rols->flag == 22){
                $agent_roles = DB::table('roles')->where(['is_view_agent_panel'=>1])->get(['id']);
                foreach($agent_roles as $agent_role){
                    AgentRoleUser::create([
                        'user_id'=>$obj->id,
                        'role_id'=>$agent_role->id
                    ]);
                }
            }




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
            return true;
        }catch (\Exception $exception){
            DB::rollback();
            $this->Errors = $exception->getMessage();
            return false;
        }

    }



    /**
     * Return All Shop data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filter($request){
        $data = User::with('spatieRole');
        if($request->search != ""){
            $data->where('name','like','%'.$request->search.'%');
        }

        if($request->role_id !=  ""){
            $data->where(['spatie_role_id'=>$request->role_id]);
        }
        return $data->paginate(100);

    }



    /**
     * Update Shop Table Data
     * @param $request
     * @param $id
     * @return bool
     */
    public function update($request,$id){

        try{

            $rols = DB::table('roles')->where(['id'=>$request->role_id])->first();

            $obj = User::find($id);
            $selected_role = $obj->role_id;
            $obj->name = $request->name;
            $obj->role_id = 24; //others role from sujog_roles
            $obj->spatie_role_id = $request->role_id;
            $obj->flag = $rols->flag;
            $obj->email = $request->email;
            $obj->mobile = $request->mobile;
            $obj->password = bcrypt($request->password);

            if($selected_role != $request->role_id){

                $role_has_permission = DB::table('role_has_permissions')->where(['role_id'=>$request->role_id])->pluck('permission_id')->toArray();
                $permissions = Permission::whereIn('id',$role_has_permission)->get();

                //Remove Role
                $obj->removeRole($selected_role);

                $obj->update();

                //Syn Role
                $obj->assignRole($request->role_id);

                //Direct Assign Permission
                $obj->syncPermissions($permissions->pluck('name')->toArray());

            }else{
                $obj->update();
            }


            return true;
        }catch (\Exception $exception){
            $this->Errors = $exception->getMessage();
            return false;
        }
    }


    /**
     * Change Status Active or Deactive
     * @param $id
     * @return bool
     */
    public function changeStatus($id){
        try{
            $data = User::find($id);
            if($data->is_active == 1){
                $data->is_active = 0;
            }else{
                $data->is_active = 1;
            }
            $data->update();
            return true;
        }catch (\Exception $exception){
            $this->Errors = $exception->getMessage();
            return false;
        }
    }


    /**
     * Hard Delete
     * @param $id
     * @return bool
     */
    public function delete($id){
        try{
            $shop = User::find($id);
            $shop->delete();
            return true;
        }catch (\Exception $exception){
            $this->Errors = $exception->getMessage();
            return false;
        }
    }

}
