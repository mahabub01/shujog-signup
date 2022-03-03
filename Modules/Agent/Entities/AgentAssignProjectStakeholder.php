<?php

namespace Modules\Agent\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class AgentAssignProjectStakeholder extends Model{

    protected $table = "agent_stk_assign_projects";

    protected $fillable = [
        'user_id','project_id','stakeholder_id','is_active'
    ];


    protected $hidden = [
        'created_at','updated_at'
    ];

    // public function user(){
    //     return $this->hasOne(User::class,'id','user_id');
    // }

    public function project(){
        return $this->hasOne(AgentProject::class,'id','project_id');
    }

    public function stakeholder(){
        return $this->hasOne(User::class,'id','stakeholder_id');
    }

    public function stakeholders(){
        return $this->hasMany(User::class,'id','stakeholder_id');
    }



}
