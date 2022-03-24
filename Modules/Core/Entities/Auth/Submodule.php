<?php


namespace Modules\Core\Entities\Auth;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Submodule extends Model
{
    use LogsActivity;

    protected $table ="submodules";

    /**
     * @var string[]
     */
    protected $fillable = [
        'title','action','action_type','icons','module_id','is_active','upload_icon','comments'
    ];

    protected static $logAttributes = [
        'title','action','action_type','icons','module_id','is_active','upload_icon','comments'
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
        ->useLogName('Submoule')
        ->logOnlyDirty();
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function module(){
        return $this->hasOne(Module::class,'id','module_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function permissions(){
        return $this->hasMany(Permission::class,'id','sub_module_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function componet_permissions(){
        return $this->hasMany(Permission::class,'sub_module_id','id');
    }


}
