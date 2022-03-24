<?php


namespace Modules\Core\Entities\Auth;


use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\User\RetailUser;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SubmoduleUser extends Model
{
    use LogsActivity;
    protected $table ="submodule_user";

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id','submodule_id'
    ];

    protected static $logAttributes = [
        'user_id','submodule_id'
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
        ->useLogName('SubmoduleUser')
        ->logOnlyDirty();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function submodules(){
        return $this->hasMany(Submodule::class,'id','submodule_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function users(){
        return $this->hasMany(RetailUser::class,'id','user_id');
    }

}
