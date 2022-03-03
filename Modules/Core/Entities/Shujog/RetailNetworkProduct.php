<?php

namespace Modules\Core\Entities\Shujog;

use App\Http\Requests\RetailCategory;
use App\Models\Fmcg\Brand;
use App\Models\Fmcg\Fmcg;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class RetailNetworkProduct extends Model
{
    use HasTranslations;
    protected $table = "sujog_retailproducts";



    public $translatable = ['quantity_type', 'unit_type','micro_quantity_type'];



    protected $fillable = ['product_id', 'category_id', 'brand_id', 'moq', 'product_unit_id', 'distributor_purchase_price', 'user_purchase_price', 'user_selling_price', 'delivery_time', 'discount', 'discount_way', 'is_active',
        'company_id', 'product_unit_measurement_id', 'is_hidden','bouns_point','qty_for_bonus_point','impact_area_id ','problem_area_id','target_market_id ','education_requirement_id','skills_certification_id','investment_requirment_id',
        'management_role_id','sku_number','warranty_type','warranty','return_policy','imei_nubmer','emi_payment','product_expiry_date','quantity_type','unit_type','number_of_unit_quantity','micro_quantity_type','number_of_micro_quantity',
        'quanity_identifi','sales_point','client_sales_point','subcategory_id','is_order_active','moq_for_unit'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];



    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }


    public function forgetMyselfTranslation(string $key): self
    {
        $translations = $this->getTranslations($key);
        unset($this->translatable[2]);
        return $this;
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
