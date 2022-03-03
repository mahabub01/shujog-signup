<?php


namespace Modules\Core\Entities\Shujog;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Products\CategoryType;

class UserSelectedCategoryOrService extends Model
{
    use HasFactory;

    protected $table = "sujog_user_selected_category_or_service";

    /**
     * @var string[]
     */
    protected $fillable = [
        'category_id', 'user_id', 'category_type_id','category_type_flag','is_approve','is_active','channel_id'
    ];


    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];


    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function categoryType()
    {
        return $this->hasOne(CategoryType::class, 'id', 'category_type_id');
    }


    /**
     * @param mixed $value
     * @return false|string
     */
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
