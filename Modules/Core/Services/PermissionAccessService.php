<?php


namespace Modules\Core\Services;


class PermissionAccessService
{
    public static function canAccess($action, $user)
    {
        $permissions = $user->getDirectPermissions()->pluck('action')->toArray();
       // dd($permissions);

        //\Modules\Agent\Http\Controllers\Deployer\StakeholderController@index

        //"\Modules\Agent\Http\Controllers\Deployer\StakeholderController@index"

        if(!in_array($action,$permissions)){
            return false;
        }
        return true;
    }
}
