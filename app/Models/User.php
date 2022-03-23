<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Agent\Entities\AgentAssignProjectStakeholder;
use Modules\Agent\Entities\AgentProMngAssignProject;
use Modules\Agent\Entities\AgentRoleUser;
use Modules\Agent\Entities\Evaluation\StakeholderEvaluation;
use Modules\Agent\Entities\Evaluation\StakeholderEvaluationDetails;
use Modules\Agent\Entities\Stakeholder;
use Modules\Agent\Entities\StakeholderComment;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;
use Modules\Core\Entities\Common\EducationRequirement;
use Modules\Core\Entities\Common\InvestmentRequirement;
use Modules\Core\Entities\Common\AssetAvailability;
use Modules\Core\Entities\Location\District;
use Modules\Core\Entities\Location\Division;
use Modules\Core\Entities\Location\Union;
use Modules\Core\Entities\Location\Upazila;
use Modules\Core\Entities\Location\Village;
use Modules\Core\Entities\Shujog\SignupReference;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use HasTranslations;


    protected $table = "sujog_users";


    public $translatable = ['name','guardian_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'spatie_role_id','role_id', 'flag', 'points', 'name', 'email', 'mobile', 'division_id', 'district_id', 'upazila_id', 'union_id', 'village_id', 'mouza', 'is_active', 'password', 'username', 'gender', 'date_of_birth', 'age', 'is_nid_card', 'self_nid_number', 'self_nid_fathers_name',
        'self_nid_mothers_name', 'self_nid_present_address', 'self_permenant_address', 'self_picture', 'self_mfs', 'self_bank_asia_account', 'guardian_nid_number', 'guardian_name', 'guardian_phone', 'guardian_gender', 'guardian_nid_present_address',
        'guardian_nid_permenant_address', 'guardian_picture', 'guardian_relation', 'guardian_mfs', 'is_complete_genarel_signup', 'education_requirement_id', 'investment_requirement_id', 'channel', 'category_type_ids', 'category_ids', 'suggest_course_ids',
        'pin', 'designation', 'registration_type', 'is_assign_hub', 'assign_hub_type', 'is_apologized', 'is_interested', 'otp', 'otp_status', 'user_status', 'asset_availabilitiey_id', 'is_complete_earn_signup', 'category_with_channel','signup_reference_id','referral_number',
        'device_info','longitude','latitude','is_system_admin','gender_id','institute_name','self_nid_card_front_image','self_nid_card_back_image','union','signup_media','is_complete_quick_signup'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function spatieRole(){
        return $this->hasOne(\Spatie\Permission\Models\Role::class,'id','spatie_role_id');
    }

    public function userSelectedRole(){
       // return $this->hasOne(\Spatie\Permission\Models\Role::class,'id','spatie_role_id');
       return $this->hasOneThrough(Role::class,AgentRoleUser::class);
    }


    public function education()
    {
        return $this->hasOne(EducationRequirement::class, 'id', 'education_requirement_id');
    }

    public function investment()
    {
        return $this->hasOne(InvestmentRequirement::class, 'id', 'investment_requirement_id');
    }

    public function asset()
    {
        return $this->hasOne(AssetAvailability::class, 'id', 'asset_availabilitiey_id');
    }


    public function division()
    {
        return $this->hasOne(Division::class, 'id', 'division_id');
    }

    public function district()
    {
        return $this->hasOne(District::class, 'id', 'district_id');
    }

    public function upazila()
    {
        return $this->hasOne(Upazila::class, 'id', 'upazila_id');
    }

    public function union()
    {
        return $this->hasOne(Union::class, 'id', 'union_id');
    }

    public function un()
    {
        return $this->hasOne(Union::class, 'id', 'union_id');
    }

    public function village()
    {
        return $this->hasOne(Village::class, 'id', 'village_id');
    }


    public function stakeholder(){
        return $this->hasOne(Stakeholder::class,'user_id','id');
        //->orderBy('consultant_status','asc');
    }


    public function stakeholderEvaluation(){
        return $this->hasOne(StakeholderEvaluation::class,'user_id','id');
    }


    //Need Stakeholder Consultant last one comments
    public function stakeholderCommnet(){
        return $this->hasOne(StakeholderComment::class,'user_id','id')->whereIn('flag',[19,24])->latest();
    }


    public function stakeholderCommnetForTrainer(){
        return $this->hasOne(StakeholderComment::class,'user_id','id')->whereIn('flag',[20,25])->latest();
    }


    public function stakeholderCommnetForDeployer(){
        return $this->hasOne(StakeholderComment::class,'user_id','id')->whereIn('flag',[21,26])->latest();
    }


    public function stakeholderCommnetForNmanager(){
        return $this->hasOne(StakeholderComment::class,'user_id','id')->whereIn('flag',[22,28])->latest();
    }


    public function stakeholderEvalDetails(){
        return $this->hasMany(StakeholderEvaluationDetails::class,'user_id','id');
    }


    public function agentAssignRole(){
        return $this->hasMany(AgentRoleUser::class,'user_id','id');
    }

    public function assignProject(){
        return $this->hasMany(AgentAssignProjectStakeholder::class,'stakeholder_id','id');
    }

    public function assignProMngProject(){
        return $this->hasMany(AgentProMngAssignProject::class,'user_id','id');
    }


    public function signupReference(){
        return $this->hasOne(SignupReference::class,'id','signup_reference_id')->select('id','title');
    }


}
