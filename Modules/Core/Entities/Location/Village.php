<?php

namespace Modules\Core\Entities\Location;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Village extends Model
{
    use HasTranslations;
	public $translatable = ['name'];

    protected $table ="villages";
    protected $fillable = ['name','union_id','is_active'];
    protected $hidden = ['created_at','updated_at'];

    public function union(){
    	return $this->hasOne('Modules\Core\Entities\Location\Union','id','union_id');
    }

    protected function asJson($value){
    	return json_encode($value, JSON_UNESCAPED_UNICODE);
	}
}
