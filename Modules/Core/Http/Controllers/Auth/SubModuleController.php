<?php

namespace Modules\Core\Http\Controllers\Auth;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Core\Repositories\Auth\ModuleRepository;
use Modules\Core\Repositories\Auth\SubModuleRepository;

class SubModuleController extends Controller
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
        return view('core::auth.sub-modules.index',[
            'datas'=>$this->submodule->getAll(),
            'modules'=>$this->module->getAllActive(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('core::auth.sub-modules.create',[
            'modules'=>$this->module->getAllActive(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        if($this->submodule->store($request)){
            Session::flash('success','Store your Data successfully');
            return redirect()->route('core.components.index');
        }else{
            Session::flash('error',$this->submodule->Errors);
            return redirect()->route('core.components.index');
        }
    }


    /**
     * This method use for Filter
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function filter(Request $request){
        return view('core::auth.sub-modules.index',[
            'datas'=>$this->submodule->filter($request),
            'modules'=>$this->module->getAllActive(),
        ]);
    }




    /**
     * Change Data status
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($id){
        if($this->submodule->changeStatus($id)){
            Session::flash('success','Change your data status.');
            return redirect()->back();
        }else{
            Session::flash('error',$this->submodule->Errors);
            return redirect()->back();
        }
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
        $data = $this->submodule->findBy(['id'=>$id]);
        return view('core::auth.sub-modules.edit',[
            'data'=>$data,
            'modules'=>$this->module->getAllActive(),
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
        if($this->submodule->update($request,$id)){
            Session::flash('success','Update your Data successfully');
            return redirect()->route('core.components.index');
        }else{
            Session::flash('error',$this->module->Errors);
            return redirect()->route('core.components.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if($this->submodule->delete($id)){
            Session::flash('success','Delete your Data successfully');
            return redirect()->route('core.components.index');
        }else{
            Session::flash('error',$this->submodule->Errors);
            return redirect()->route('core.components.index');
        }
    }


}
