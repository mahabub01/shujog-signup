<?php

namespace Modules\Core\Entities\Shujog;


use Illuminate\Database\Eloquent\Model;

class Member extends Model
{

    protected $table = "sujog_clients";

    protected $fillable = [
        'name', 'institution', 'sex', 'father_name', 'mother_name', 'age', 'mobile', 'address', 'user_id','points'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];


    public function sells()
    {
        return $this->hasMany(Sells::class, 'member_id', 'id');
    }




}
