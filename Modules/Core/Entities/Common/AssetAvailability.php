<?php

namespace Modules\Core\Entities\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AssetAvailability extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = "sujog_asset_availability";
    protected $dates = ['deleted_at'];

    protected $translatable = ['title'];

    protected $fillable = [
        'title', 'comments','is_active'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected function asJson($value){
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
