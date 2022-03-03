<?php

namespace Modules\Core\Entities\Location;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Division extends Model
{
	use HasTranslations;

    protected $table ="divisions";
    public $translatable = ['name'];

    protected $fillable = ['name','is_active'];
    protected $hidden = ['created_at','updated_at'];

    public function users()
    {
        return $this->hasMany(User::class, 'division_id', 'id');
    }

    protected function asJson($value){
    	return json_encode($value, JSON_UNESCAPED_UNICODE);
	}


	public static function getName($id){
		return Division::where(['id'=>$id])->firstOrFail();
	}
}
