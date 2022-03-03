<?php


namespace Modules\Core\Entities\Platform;


use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Platform extends Model
{
    use HasTranslations;

    public $translatable = ['company_name'];

    protected $table = 'distribution_platform';

    protected $fillable =[
        'company_name','platform_prefix','company_logo','is_active'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    protected function asJson($value){
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


}
