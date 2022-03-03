<?php


namespace Modules\Core\Entities\Auth;


use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table ="permissions";

    /**
     * @var string[]
     */
    protected $fillable = [
        'name','guard_name','module_id','sub_module_id','module_id','action','comments','professional_name','is_view_with_component','route_url'
    ];



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
