<?php


namespace Modules\Core\Entities\Shujog;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{

    use HasTranslations;

    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $table = "sujog_categories";

    public $translatable = ['name'];

    protected $fillable = [
        'name', 'is_active', 'category_type_id', 'category_flag','ownership_of_service_id','comments','priority','images'
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
