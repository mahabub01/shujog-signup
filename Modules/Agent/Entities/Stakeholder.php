<?php

namespace Modules\Agent\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Stakeholder extends Model{

    protected $table = "agent_stakeholders";

    protected $fillable = [
        'user_id','consultant_status','trainer_status','deployer_status','network_status','project_manager'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }


}
