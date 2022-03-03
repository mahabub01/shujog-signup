<?php

namespace Modules\Core\Entities\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  Modules\Core\Entities\Common\EducationRequirement;
use  Modules\Core\Entities\Common\EducationInvestment;
use Spatie\Translatable\HasTranslations;

class InvestmentRequirement extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = "sujog_investment_requirements";
    protected $dates = ['deleted_at'];
    public $translatable = ['title'];

    protected $fillable = [
        'title', 'comments', 'is_active', 'education_requirement_id'
    ];


    public function educationRequirement()
    {
        return $this->hasOne(EducationRequirement::class, 'id', 'education_requirement_id');
    }


    public function educationInvestment()
    {
        return $this->hasMany(EducationInvestment::class, 'investment_id', 'id');
    }


    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
