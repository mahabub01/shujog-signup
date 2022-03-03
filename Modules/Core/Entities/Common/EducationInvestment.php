<?php

namespace Modules\Core\Entities\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Common\InvestmentRequirement;
use Modules\Core\Entities\Common\EducationRequirement;

class EducationInvestment extends Model
{
    use HasFactory;

    protected $table = "sujog_education_investment";

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'education_id', 'investment_id'
    ];


    public function investment(){
        return $this->hasOne(InvestmentRequirement::class,'id','investment_id');
    }

    public function education(){
        return $this->hasOne(EducationRequirement::class,'id','education_id');
    }

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
