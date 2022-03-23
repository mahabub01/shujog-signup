<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AgentRoleUser extends Model{
    use LogsActivity;


    protected $table = "agent_role_users";

    protected $fillable = [
        'user_id','role_id'
    ];

    protected static $logAttributes = [
        'user_id','role_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->useLogName('Role')
        ->logOnlyDirty();
    }

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function role(){
        return $this->hasOne(Role::class,'id','role_id');
    }

}
