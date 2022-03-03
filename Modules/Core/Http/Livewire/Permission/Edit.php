<?php

namespace Modules\Core\Http\Livewire\Permission;

use Livewire\Component;
use Modules\Core\Repositories\Auth\ModuleRepository;
use Modules\Core\Repositories\Auth\SubModuleRepository;

class Edit extends Component
{

    public $Moduleid;
    public $module_id = null;
    public $submodule_id = null;

    public function mount($data){
        $this->module_id = $data->module_id;
        $this->Moduleid = $data->module_id;
        $this->submodule_id = $data->submodule_id;
    }

    public function render(SubModuleRepository $subModule, ModuleRepository $module)
    {
        $modules = $module->getAllActiveWithoutPaginate();
        if($this->Moduleid != ""){
            $submodules = $subModule->getAllActiveWithoutPaginate()->where('module_id','',$this->Moduleid);
        }else{
            $submodules = $subModule->getAllActiveWithoutPaginate();
        }

        return view('core::livewire.permission.edit',[
            'modules'=>$modules,
            'submodules'=>$submodules,
        ]);
    }
}
