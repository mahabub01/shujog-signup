<?php

namespace Modules\Agent\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StakeholderComment extends Model{

    use LogsActivity;
    protected $table = "agent_stakeholder_comments";

    protected $fillable = [
        'user_id','comment','agent_id','status','role_id','flag'
    ];

    protected static $logAttributes = [
        'user_id','comment','agent_id','status','role_id','flag'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->useLogName('Comment')
        ->logOnlyDirty();
    }

    public function stackholder()
    {
       return $this->hasOne(User::class,'id','user_id');
    }

    public function user()
    {
       return $this->hasOne(User::class,'id','agent_id');
    }

}
