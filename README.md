# This code use For Module and Submodule Permission
Please copy this code and paste the same location
vendor/spatie/laravel-permission/src/Models/Permission.php

````
    /**
     * Get a new query builder that only includes soft deletes.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
	
	public function module(){
        return $this->hasOne(Module::class,'id','module_id');
    }


    /**
     * Get a new query builder that only includes soft deletes.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function submodule(){
        return $this->hasOne(Submodule::class,'id','sub_module_id');
    }
````
# If you want to set Route Permission Please use this code

````
    Route::middleware('core_permission')->prefix('accounts')->group(function () {
        //Write here your code
    
    });
````


# If you want to show/hide your action button depend on Permission

````
    //demo permission name (create country)
    @auth_access(create country)
    
    @end_auth_access
````

	
