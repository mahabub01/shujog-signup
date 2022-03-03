<?php

namespace Modules\Core\Entities\Shujog;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SignupReference extends Model
{

    use HasTranslations;

    protected $table = "sujog_signup_reference";

    public $translatable = ['title'];


    protected $fillable = [
        'title','comments','show_input_box','is_active'
    ];


    protected $hidden = [
        'created_at', 'updated_at'
    ];

}
