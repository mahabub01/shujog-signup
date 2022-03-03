<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Spatie\Translatable\HasTranslations;

class SellPayment extends Model
{
    use HasTranslations;

    protected $table;
    public $translatable = ['remarks'];

    public function __construct()
    {
        #$this->table = Cookie::get('prefix') . '_orders';

        if (!Cookie::has('prefix')) {

            $this->table = "sujog_sell_payments";

        } else {

            $this->table = Cookie::get('prefix') . '_sell_payments';
        }
    }


    protected $fillable = [
        'transaction_id', 'sell_id', 'pay_method_id', 'pay_mode_id', 'status', 'remarks', 'amount', 'verification_id', 'image', 'payment_pay_date'
    ];


    public function payment()
    {
        return $this->belongsTo("App\Models\Distribution\Sells", "sell_id", 'id');
    }


    protected $hidden = [
        'created_at', 'updated_at'
    ];


    public function order()
    {
        return $this->hasOne('App\Models\Distribution\Sells', 'id', 'order_id');
    }

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function payMethod()
    {
        return $this->hasOne('App\Models\Distribution\PayMethod', 'id', 'pay_method_id');
    }


    public function payMode()
    {
        return $this->hasOne('App\Models\Distribution\PayMode', 'id', 'pay_mode_id');
    }


    public function payBank()
    {
        return $this->hasOne('App\Models\Distribution\PayBank', 'id', 'pay_bank_id');
    }

    public function sell()
    {
        return $this->hasOne(Sells::class, 'id', 'sell_id');
    }

}
