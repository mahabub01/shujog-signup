<?php

namespace Modules\Core\Entities\Shujog;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Spatie\Translatable\HasTranslations;

class Sells extends Model
{
    use HasTranslations;

    protected $table = "sujog_sells";

    public $translatable = ['remarks'];

    protected $fillable = [
        'sells_unique_id', 'remarks', 'status', 'user_id', 'member_id', 'invoice', 'type', 'pos', 'sales_point', 'sales_client_points', 'discount_way', 'total_sales_amount', 'total_discount', 'discount_point_amount', 'total_purchase_amount'
    ];

    protected $columns = [
        'created_at', 'updated_at'
    ];

    public function payment()
    {
        return $this->hasMany(SellPayment::class, "sell_id", 'id');
    }

    public function salesData()
    {
        return $this->hasMany(SellsData::class, "sell_id", 'id');
    }

    public function onePayment()
    {
        return $this->hasOne(SellPayment::class, "sell_id", 'id');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

}
