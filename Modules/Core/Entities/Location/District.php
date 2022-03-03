<?php

namespace Modules\Core\Entities\Location;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class District extends Model
{

	use HasTranslations;
	public $translatable = ['name'];
    protected $table ="districts";
    protected $fillable = ['name','division_id','is_active'];
    protected $hidden = ['created_at','updated_at'];

    public function division(){
    	return $this->hasOne('Modules\Core\Entities\Location\Division','id','division_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'district_id', 'id');
    }


    protected function asJson($value){
    	return json_encode($value, JSON_UNESCAPED_UNICODE);
	}

    public static function getName($id){
        return District::where(['id'=>$id])->firstOrFail();
    }
}
