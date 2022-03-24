<?php


namespace Modules\Core\Entities\Auth;


use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Permission extends Model
{
    use LogsActivity;
    protected $table ="permissions";

    /**
     * @var string[]
     */
    protected $fillable = [
        'name','guard_name','module_id','sub_module_id','module_id','action','comments','professional_name','is_view_with_component','route_url'
    ];

    protected static $logAttributes = [
        'name','guard_name','module_id','sub_module_id','module_id','action','comments','professional_name','is_view_with_component','route_url'
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->useLogName('Permission')
        ->logOnlyDirty();
    }

    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at','updated_at'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function module(){
        return $this->hasOne(Module::class,'id','module_id');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function submodule(){
        return $this->hasOne(Submodule::class,'id','sub_module_id');
    }

}
