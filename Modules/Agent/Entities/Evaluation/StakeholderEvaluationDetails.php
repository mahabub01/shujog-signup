<?php

namespace Modules\Agent\Entities\Evaluation;


use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StakeholderEvaluationDetails extends Model{
    use LogsActivity;

    protected $table = "agent_stkholder_eval_details";

    protected $fillable = [
        'agent_stkholder_evalu_id','agent_ev_qus_id','answer','status','mark','user_id'
    ];

    protected static $logAttributes = [
        'agent_stkholder_evalu_id','agent_ev_qus_id','answer','status','mark','user_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->useLogName('EvalutionDetails')
        ->logOnlyDirty();
    }


    protected $hidden = [
        'created_at','updated_at'
    ];


}
