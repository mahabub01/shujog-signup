<?php

namespace Modules\Core\Entities\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class EducationRequirement extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = "sujog_education_requirement";
    protected $dates = ['deleted_at'];
    public $translatable = ['title'];

    protected $fillable = [
        'title', 'comments', 'is_active'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

}
