<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;


class AgentProjectDistrict extends Model{

    protected $table = "agent_project_districts";

    protected $fillable = [
        'agent_project_id','district_id','division_id'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

}
