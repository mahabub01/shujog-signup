<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Spatie\Translatable\HasTranslations;

class SellsData extends Model
{
    protected $table;

    use HasTranslations;

    public $translatable = ['product_name', 'unit'];

    public function __construct()
    {
        #$this->table = Cookie::get('prefix').'_sells_data';

        if (!Cookie::has('prefix')) {
            $this->table = "sujog_sells_data";
        } else {
            $this->table = Cookie::get('prefix') . '_sells_data';
        }
    }

    protected $fillable = [
        'sell_id', 'product_name', 'qty', 'price', 'unit', 'unit_quantity', 'unit_identify', 'product_id', 'package_id', 'service_id', 'pro_type'
    ];

    public function retailsProduct()
    {
        return $this->hasOne('\Modules\Core\Entities\Shujog\RetailNetworkProduct', 'id', 'product_id');
    }


    public function retailProduct()
    {
        return $this->hasOne('\Modules\Core\Entities\Shujog\RetailNetworkProduct', 'id', 'product_id');
    }


    public function service()
    {
        return $this->hasOne('App\Models\RetailNetwork\RetailNetworkService', 'id', 'service_id');
    }

    public function sell()
    {
        return $this->hasOne(Sells::class, 'id', 'sell_id');
    }

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
