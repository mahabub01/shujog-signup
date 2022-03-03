<?php

namespace Modules\Core\Http\Controllers\Auth;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Core\Entities\Auth\Submodule;
use Modules\Core\Http\Requests\RoleCreateRequest;
use Modules\Core\Repositories\Auth\ModuleRepository;
use Modules\Core\Repositories\Auth\SubModuleRepository;
use Modules\Core\Repositories\Contracts\Auth\ModuleInterface;
use Modules\Core\Repositories\Contracts\Auth\SubModuleInterface;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    private $submodule;
    private $module;

    public function __construct(SubModuleRepository $_submodule,ModuleRepository $_module)
    {
        $this->submodule = $_submodule;
        $this->module = $_module;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('core::auth.roles.index',[
            'submodules'=>$this->submodule->getAllActiveWithoutPaginate(),
            'modules'=>$this->module->getAllActiveWithoutPaginate(),
            'datas'=>Role::paginate(100)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('core::auth.roles.create',[
            'modules'=>$this->module->getAllActiveWithoutPaginate(),
            'submodules'=>$this->submodule->getAllActiveWithoutPaginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        try{

            if(isset($request->permission_id)){
                $role = Role::create([
                    'name' => strtolower($request->name),
                    'comments'=>$request->comments,
                    'flag'=>$request->flag_id
                ]);

                //$premissions = Permission::whereIn('id',$request->permission_id)->get();
                $role->syncPermissions($request->permission_id);
                Session::flash('success','Store your Data successfully');
                return redirect()->route('core.roles.index');

            }else{
                Session::flash('warning','Please Select Your Role permission.');
                return redirect()->back();
            }


        }catch (\Exception $exception){
            Session::flash('error',$exception->getMessage());
            return redirect()->route('core.roles.index');
        }
    }


    /**
     * This method use for Filter
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function filter(Request $request){

        $data = Role::paginate(100);

        if($request->search != ""){
            $data = Role::where('name','like','%'.$request->search.'%')->paginate(100);
        }

        return view('core::auth.roles.index',[
            'datas'=>$data,
            'modules'=>$this->module->getAllActiveWithoutPaginate(),
            'submodules'=>$this->submodule->getAllActiveWithoutPaginate(),
        ]);
    }




    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('core::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Role::with('permissions')->find($id);
        $permissionIds = null;
        $moduleids = null;
        $submodulesIds = null;
        if(!is_null($data->permissions)){
            $permissionIds = $data->permissions->pluck('id')->toArray();
            $moduleids = $data->permissions->pluck('module_id')->toArray();
            $submodulesIds = $data->permissions->pluck('submodule_id')->toArray();
        }

        return view('core::auth.roles.edit',[
            'data'=>$data,
            'modules'=>$this->module->getAllActiveWithoutPaginate(),
            'submodules'=>$this->submodule->getAllActiveWithoutPaginate(),
            'moduleIds'=>$moduleids,
            'submodulesIds'=>$submodulesIds,
            'permissionIds'=>$permissionIds,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request,$id)
    {

        try{

            if(isset($request->permission_id)){
               $role = Role::find($id);
               $role->name = strtolower($request->name);
               $role->comments = $request->comments;
               $role->flag = $request->flag_id;
               $role->update();

                $role->syncPermissions($request->permission_id);
                Session::flash('success','Update your Data successfully');
                return redirect()->route('core.roles.index');

            }else{
                Session::flash('warning','Please Select Your Role permission.');
                return redirect()->back();
            }

        }catch (\Exception $exception){
            Session::flash('error',$exception->getMessage());
            return redirect()->route('core.roles.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $permission = Role::findById($id);
            $permission->delete();
            Session::flash('success','Delete your Data successfully');
            return redirect()->route('core.roles.index');

        }catch (\Exception $exception){
            Session::flash('error',$exception->getMessage());
            return redirect()->route('core.roles.index');
        }
    }
}
