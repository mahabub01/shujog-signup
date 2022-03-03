<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class ComponentCategory extends Model
{
    use HasFactory;

    protected $table = "component_categories";

    protected $fillable = [
        'category_id', 'component_id', 'is_auto_assign'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];


    public function categoryApi()
    {
        return $this->hasOne(Category::class, 'id', 'category_id')->select(['id', 'category_flag']);
    }


}
