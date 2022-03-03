<?php

namespace Modules\Core\Http\Controllers\Auth;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Core\Http\Requests\Auth\PermissionRequest;
use Modules\Core\Repositories\Auth\ModuleRepository;
use Modules\Core\Repositories\Auth\SubModuleRepository;
use Modules\Core\Repositories\Contracts\Auth\ModuleInterface;
use Modules\Core\Repositories\Contracts\Auth\SubModuleInterface;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Module;

class PermissionController extends Controller
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
        return view('core::auth.permissions.index',[
            'submodules'=>$this->submodule->getAllActiveWithoutPaginate(),
            'modules'=>$this->module->getAllActiveWithoutPaginate(),
            'datas'=>Permission::with('module','submodule')->orderBy('id','desc')->paginate(100)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('core::auth.permissions.create',[
            'modules'=>$this->module->getAllActive(),
            'submodules'=>$this->submodule->getAllActive(),
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

            Permission::create([
                'name' => $request->name,
                'module_id' => $request->module_id,
                'sub_module_id' => $request->sub_module_id,
                'comments' => $request->comments,
                'action' => $request->action,
                'professional_name' => $request->professional_name,
                'is_view_with_component' => $request->is_view_with_component,
                'route_url' => $request->route_url,
            ]);

            Session::flash('success','Store your Data successfully');
            return redirect()->route('core.permissions.index');

        }catch (\Exception $exception){
            Session::flash('error',$exception->getMessage());
            return redirect()->route('core.permissions.index');
        }
    }


    /**
     * This method use for Filter
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function filter(Request $request){

        $data = Permission::with('module','submodule');

        if($request->search != ""){
            $data->where('name','like','%'.$request->search.'%');
        }

        if($request->module_id != ""){
            $data->where(['module_id'=>$request->module_id]);
        }

        if($request->sub_module_id != ""){
            $data->where(['sub_module_id'=>$request->sub_module_id]);
        }

        return view('core::auth.permissions.index',[
            'datas'=>$data->paginate(100),
            'modules'=>$this->module->getAllActive(),
            'submodules'=>$this->submodule->getAllActive(),
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
        //$data = Permission::findById($id);
        $data = \Modules\Core\Entities\Auth\Permission::findOrFail($id);
        return view('core::auth.permissions.edit',[
            'data'=>$data,
            'modules'=>$this->module->getAllActive(),
            'submodules'=>$this->submodule->getAllActive(),
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
            $permission = \Modules\Core\Entities\Auth\Permission::find($id);
            $permission->name = $request->name;
            $permission->module_id = $request->module_id;
            $permission->sub_module_id = $request->submodule_id;
            $permission->comments = $request->comments;
            $permission->action = $request->action;
            $permission->professional_name = $request->professional_name;
            $permission->is_view_with_component = $request->is_view_with_component;
            $permission->route_url = $request->route_url;
            $permission->update();
            Session::flash('success','Update your Data successfully');
            return redirect()->route('core.permissions.index');

        }catch (\Exception $exception){
            Session::flash('error',$exception->getMessage());
            return redirect()->route('core.permissions.index');
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
            $permission = Permission::find($id);
            $permission->delete();
            Session::flash('success','Delete your Data successfully');
            return redirect()->route('core.permissions.index');

        }catch (\Exception $exception){
            Session::flash('error',$exception->getMessage());
            return redirect()->route('core.permissions.index');
        }
    }
}
