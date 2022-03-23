<?php

namespace Modules\Agent\Entities\Evaluation;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StakeholderEvaluation extends Model{

    protected $table = "agent_stakeholder_evalu";

    protected $fillable = [
        'user_id','agent_id','mark','status'
    ];




    protected $hidden = [
        'created_at','updated_at'
    ];


    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function agent(){
        return $this->hasOne(User::class,'id','agent_id');
    }


    public function evaluationDetails(){
        return $this->hasMany(StakeholderEvaluationDetails::class,'agent_stkholder_evalu_id','id');
    }


}
