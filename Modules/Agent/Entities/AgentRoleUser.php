<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class AgentRoleUser extends Model{

    protected $table = "agent_role_users";

    protected $fillable = [
        'user_id','role_id'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function role(){
        return $this->hasOne(Role::class,'id','role_id');
    }


}
