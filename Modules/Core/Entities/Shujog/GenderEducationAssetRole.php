<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Models\Products\AssetAvailability;
use Modules\Core\Models\Products\EducationRequirement;
use Spatie\Permission\Models\Role;
use Modules\Core\Models\Auth\Submodule;

class GenderEducationAssetRole extends Model
{
    use HasFactory;

    protected $table = "gender_education_asset_role";

    protected $fillable = [
        'role_id', 'gender_id', 'education_id', 'asset_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

 
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }




}
