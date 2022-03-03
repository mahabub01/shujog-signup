<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;


class AgentProjectUpazila extends Model{

    protected $table = "agent_project_upazilas";

    protected $fillable = [
        'agent_project_id','district_id','upazila_id'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

}
