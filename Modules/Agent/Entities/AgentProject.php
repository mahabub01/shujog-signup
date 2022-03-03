<?php

namespace Modules\Agent\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class AgentProject extends Model{

    protected $table = "agent_projects";

    protected $fillable = [
        'name','sur_name','start_date','end_date','extention_time','user_id','description','customer_served','sales_target','wmm_target','is_active','user_assing_status','created_by','slug'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function divisions(){
        return $this->hasMany(AgentProjectDivision::class,'agent_project_id','id');
    }


    public function districts(){
        return $this->hasMany(AgentProjectDistrict::class,'agent_project_id','id');
    }


    public function upazilas(){
        return $this->hasMany(AgentProjectUpazila::class,'agent_project_id','id');
    }


    public function assign_pro(){
        return $this->hasMany(AgentAssignProjectStakeholder::class,'project_id','id')
        ->select('project_id');
    }

}
