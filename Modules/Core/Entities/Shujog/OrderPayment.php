<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Spatie\Translatable\HasTranslations;

class OrderPayment extends Model
{
    use HasTranslations;

    protected $table = "sujog_order_payments";
    public $translatable = ['remarks'];



    protected $fillable = [
        'order_id', 'transactionid', 'pay_method_id', 'pay_mode_id', 'status', 'remarks', 'amount', 'verification_id', 'image', 'fo_id', 'payment_pay_date'
    ];


    protected $hidden = [
        'created_at', 'updated_at'
    ];


    public function requsition()
    {
        return $this->hasOne(Orders::class, 'id', 'order_id');
    }


    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


}
