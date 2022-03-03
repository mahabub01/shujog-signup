<?php


namespace Modules\Core\Entities\Shujog;


use Illuminate\Database\Eloquent\Model;

class PayMethod extends Model
{
    protected $table = "pay_method";

    protected $fillable = [
        'name', 'description'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

}
