<?php

namespace Modules\Agent\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StakeholderComment extends Model{

    protected $table = "agent_stakeholder_comments";

    protected $fillable = [
        'user_id','comment','agent_id','status','role_id','flag'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];


    public function stackholder()
    {
       return $this->hasOne(User::class,'id','user_id');
    }

    public function user()
    {
       return $this->hasOne(User::class,'id','agent_id');
    }

}
