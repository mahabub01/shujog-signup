<?php

namespace Modules\Agent\Entities\Evaluation;


use Illuminate\Database\Eloquent\Model;

class StakeholderEvaluationDetails extends Model{

    protected $table = "agent_stkholder_eval_details";

    protected $fillable = [
        'agent_stkholder_evalu_id','agent_ev_qus_id','answer','status','mark','user_id'
    ];
    

    protected $hidden = [
        'created_at','updated_at'
    ];


}
