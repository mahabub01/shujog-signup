<?php

namespace Modules\Core\Entities\Location;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Village extends Model
{
    use LogsActivity;
    use HasTranslations;
	public $translatable = ['name'];

    protected $table ="villages";
    protected $fillable = ['name','union_id','is_active'];
    protected static $logAttributes = ['name','union_id','is_active'];
    protected $hidden = ['created_at','updated_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll()
        ->useLogName('Village')
        ->logOnlyDirty();
    }

    public function union(){
    	return $this->hasOne('Modules\Core\Entities\Location\Union','id','union_id');
    }

    protected function asJson($value){
    	return json_encode($value, JSON_UNESCAPED_UNICODE);
	}
}
