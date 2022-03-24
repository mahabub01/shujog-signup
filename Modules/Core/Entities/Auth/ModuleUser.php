<?php


namespace Modules\Core\Entities\Auth;


use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\User\RetailUser;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ModuleUser extends Model
{
    use LogsActivity;
    protected $table ="module_user";

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id','module_id'
    ];

    protected static $logAttributes = [
        'user_id','module_id'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at','updated_at'
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->useLogName('ModuleUser')
        ->logOnlyDirty();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function modules(){
        return $this->hasMany(Module::class,'id','module_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function users(){
        return $this->hasMany(RetailUser::class,'id','user_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function module(){
        return $this->hasOne(Module::class,'id','module_id');
    }

}
