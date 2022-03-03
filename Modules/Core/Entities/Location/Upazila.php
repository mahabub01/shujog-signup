<?php

namespace Modules\Core\Entities\Location;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Upazila extends Model
{
    use HasTranslations;
	public $translatable = ['name'];

    protected $table ="upazilas";
    protected $fillable = ['name','district_id','is_active'];
    protected $hidden = ['created_at','updated_at'];

    public function district(){
    	return $this->hasOne('Modules\Core\Entities\Location\District','id','district_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'upazila_id', 'id');
    }

    protected function asJson($value){
    	return json_encode($value, JSON_UNESCAPED_UNICODE);
	}

}
