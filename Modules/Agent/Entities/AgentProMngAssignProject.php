<?php
namespace Modules\Agent\Entities;
use Illuminate\Database\Eloquent\Model;

class AgentProMngAssignProject extends Model{

    protected $table = "agent_project_manager_assign_projects";

    protected $fillable = [
        'user_id','project_id','is_active'
    ];


    protected $hidden = [
        'created_at','updated_at'
    ];


    public function project(){
        return $this->hasOne(AgentProject::class,'id','project_id');
    }




}

