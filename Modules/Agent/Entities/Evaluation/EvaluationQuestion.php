<?php

namespace Modules\Agent\Entities\Evaluation;

use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model{

    protected $table = "agent_evalu_questions";

    protected $fillable = [
        'question','ans_a','ans_b','ans_c','ans_d','ans_e','question_type','mark','correct_answer'
    ];

    

    protected $hidden = [
        'created_at','updated_at'
    ];



}
