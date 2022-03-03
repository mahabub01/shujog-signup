<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class OrderFoDelivery extends Model
{
    protected $table = "sujog_order_fo_delivery";

    protected $fillable = [
        'order_id', 'demand_note', 'user_id', 'fo_id', 'status', 'wmm_is_received'
    ];

    protected $columns = ['created_at', 'updated_at'];


    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

}
