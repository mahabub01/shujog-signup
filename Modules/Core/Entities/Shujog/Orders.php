<?php


namespace Modules\Core\Entities\Shujog;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Spatie\Translatable\HasTranslations;

class Orders extends Model
{
    use HasTranslations;

    protected $table = "sujog_orders";

    public $translatable = ['remarks'];

    protected $fillable = [
        'order_table_unique_id', 'remarks', 'status', 'user_id', 'approve_by', 'payment_status', 'approve', 'is_create_po', 'is_created_po_status', 'is_received_product', 'deliver_product_note', 'fo_is_delivered', 'wmm_is_received', 'fo_users_id','bonus_point'
    ];

    protected $columns = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function foInfo()
    {
        return $this->hasOne(User::class, 'id', 'fo_users_id');
    }

    public function payment()
    {
        return $this->hasOne(OrderPayment::class, 'order_id', 'id');
    }


    public function ordersData($order_id)
    {
        return OrderData::with('company')->where(['order_id' => $order_id])->get();
    }

    public function ordersDataAnother($order_id)
    {
        return OrderData::with('company1')->where(['order_id' => $order_id])->get();
    }


    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function orderData()
    {
        return $this->hasMany(OrderData::class, 'order_id', 'id');
    }

    public function delivery()
    {
        return $this->hasMany(OrderFoDelivery::class, 'order_id', 'id');

    }

}
