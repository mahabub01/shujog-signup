<?php
namespace Modules\Agent\Entities;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AgentProMngAssignProject extends Model{

    use LogsActivity;

    protected $table = "agent_project_manager_assign_projects";

    protected $fillable = [
        'user_id','project_id','is_active'
    ];
    protected static $logAttributes = [
        'user_id','project_id','is_active'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->useLogName('ProMngAssignProject')
        ->logOnlyDirty();
    }

    protected $hidden = [
        'created_at','updated_at'
    ];


    public function project(){
        return $this->hasOne(AgentProject::class,'id','project_id');
    }




}

