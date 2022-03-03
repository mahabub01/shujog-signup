<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Spatie\Translatable\HasTranslations;

class SellPaymentData extends Model
{
    use HasTranslations;
    protected  $table;
    public $translatable = ['remarks'];

    public function __construct()
    {
    	$this->table = Cookie::get('prefix').'_sell_payment_data';
    }

    protected $fillable = [
        'transaction_id', 'sell_payment_id', 'payment_way_id','payment_option_id','status','remarks','verification_id','image','payment_pay_date' ,'amount'
    ];

    protected $hidden =[
    	'created_at','updated_at'
    ];

    protected function asJson($value){
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
