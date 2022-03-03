<?php

namespace Modules\Core\Entities\Location;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Union extends Model
{
	use HasTranslations;
	public $translatable = ['name'];

    protected $table ="unions";
    protected $fillable = ['name','upazila_id','is_active','post_office'];
    protected $hidden = ['created_at','updated_at'];

    public function upazila(){
    	return $this->hasOne('Modules\Core\Entities\Location\Upazila','id','upazila_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'upazila_id', 'id');
    }

    public static function getUnionName($id){
        return Union::where(['id'=>$id])->firstOrFail();
    }

    protected function asJson($value){
    	return json_encode($value, JSON_UNESCAPED_UNICODE);
	}
}
