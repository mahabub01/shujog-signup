<?php


namespace Modules\Core\Entities\Shujog\Hub;


use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Hub extends Model
{
    use HasTranslations;

    protected $table = "sujog_hubs";

    public $translatable = ['name'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'division_id', 'district_id', 'upazila_id', 'union_id', 'is_active'
    ];


    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];


    /**
     * @param mixed $value
     * @return false|string
     */
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }



}
