<?php

namespace Modules\Core\Http\Livewire\Permission;

use Livewire\Component;
use Modules\Core\Repositories\Auth\ModuleRepository;
use Modules\Core\Repositories\Auth\SubModuleRepository;

class Create extends Component
{

    public $Moduleid;

    public function render(SubModuleRepository $subModule, ModuleRepository $module)
    {
        $modules = $module->getAllActiveWithoutPaginate();
        if($this->Moduleid != ""){
            $submodules = $subModule->getAllActiveWithoutPaginate()->where('module_id','',$this->Moduleid);
        }else{
            $submodules = $subModule->getAllActiveWithoutPaginate();
        }

        return view('core::livewire.permission.create',[
            'modules'=>$modules,
            'submodules'=>$submodules,
        ]);
    }

}
