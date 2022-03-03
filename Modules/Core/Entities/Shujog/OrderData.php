<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class OrderData extends Model
{

    use HasTranslations;

    protected $table = "sujog_orders_data";

    public $translatable = ['product_name', 'unit'];


    protected $fillable = [
        'order_id', 'product_name', 'qty', 'discount', 'price', 'unit', 'product_id', 'package_id', 'received_qty', 'hub_id', 'company_id', 'is_generate_po', 'pro_type', 'note', 'is_save', 'is_received', 'distributor_price', 'unit_quantity', 'unit_identify','received_number_of_unit_type','received_number_of_quantity_type','received_number_of_micro_quantity_type'
    ];


    protected $hidden = [
        'created_at', 'updated_at'
    ];



    public function retailsProduct()
    {
        return $this->hasOne(\Modules\Core\Entities\Shujog\RetailNetworkProduct::class, 'id', 'product_id');
    }



    public function order()
    {
        return $this->hasOne(Orders::class, 'id', 'order_id');
    }




    public function getPoRelatedData($product_id, $company_id, $hub_id)
    {
        return $this::with('retailsProduct')->where(['product_id' => $product_id, 'company_id' => $company_id, 'is_generate_po' => 0, 'is_confirm' => 1, 'hub_id' => $hub_id])->get();
    }


    public function product()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function countQty($product_id, $company_id, $hub_id)
    {
        return $this::where(['product_id' => $product_id, 'company_id' => $company_id, 'is_generate_po' => 0, 'is_confirm' => 1, 'hub_id' => $hub_id])->get()->sum('qty');
    }



    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function removeAllTranslation()
    {
        $i = 0;
        foreach ($this->translatable as $dd){
            unset($this->translatable[$i]);
            $i++;
        }
        return $this;
    }

}
