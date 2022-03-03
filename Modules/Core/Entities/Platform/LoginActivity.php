<?php


namespace Modules\Core\Entities\Platform;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    use HasFactory;
    protected $table = 'login_activity';
    protected $fillable = ['device_info','longitude','latitude','user_id'];
    protected $hidden = ['created_at','updated_at'];
}
