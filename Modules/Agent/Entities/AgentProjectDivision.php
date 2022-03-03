<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;


class AgentProjectDivision extends Model{

    protected $table = "agent_project_divisions";

    protected $fillable = [
        'agent_project_id','division_id'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

}
