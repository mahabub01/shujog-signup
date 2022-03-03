<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class GenderEducationAssetRoleComponet extends Model
{
    use HasFactory;

    protected $table = "gender_education_asset_role_component";

    protected $fillable = [
        'gender_education_asset_role_id', 'component_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];


}
