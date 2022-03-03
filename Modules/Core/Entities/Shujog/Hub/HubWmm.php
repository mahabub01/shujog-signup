<?php


namespace Modules\Core\Entities\Shujog\Hub;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Modules\Core\Models\User\RetailUser;

class HubWmm extends Model
{

    protected $table = "sujog_hub_user";

    /**
     * @var string[]
     */
    protected $fillable = [
        'hub_id', 'user_id', 'flag'
    ];


    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];


}
