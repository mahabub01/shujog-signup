<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Models\Auth\Submodule;

class GenderEducationAssetRoleComponent extends Model
{
    use HasFactory;

    protected $table = "gender_education_asset_role_component";

    protected $fillable = [
        'gender_education_asset_role_id', 'component_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];


    public function genderEducationAssetRole()
    {
        return $this->hasOne(GenderEducationAssetRole::class, 'id', 'gender_education_asset_role_id');
    }

    public function component()
    {
        return $this->hasOne(Submodule::class, 'id', 'component_id');
    }


    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


}
