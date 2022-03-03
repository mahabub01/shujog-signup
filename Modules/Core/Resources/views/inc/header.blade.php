
<!--start nav section-->
<nav class="navbar navbar-expand-lg nav-bg main-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            {{ ucwords(getPanelHeaderName(auth()->user()->flag)) }} Panel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                @if(auth()->check())
                   @php
                       $modules = \Modules\Core\Entities\Auth\ModuleUser::with('module')->where(['user_id'=>auth()->user()->id])->get();
//dd($modules);
                       //$submoduleIds = \Modules\Core\Entities\Auth\SubmoduleUser::where(['user_id'=>auth()->user()->id])->pluck('submodule_id')->toArray();
                   @endphp

                   @foreach($modules as $mod)
                         @php
                            //$submodules = $mod->module->submodules->whereIn('id',$submoduleIds);
                         @endphp

                        <li class="nav-item">
                            @if($mod->module->action_type == "url")
                                <a class="nav-link" href="{{url($mod->module->slug.'/load-component')}}" @if($mod->module->slug == $module_slug) style="color:yellow !important" @endif>
                                    @if($mod->module->icons != "") <span>{!! $mod->module->icons !!}</span> @else <span><i class="fas fa-table"></i></span> @endif
                                    {{$mod->module->title}}
                                </a>
                            @else
                                <a class="nav-link" href="#" @if($mod->module->slug == $module_slug) style="color:red" @endif>
                                    @if($mod->icons != "") <span>{!! $mod->icons !!}</span> @else <span><i class="fas fa-table"></i></span> @endif
                                    {{$mod->module->title}}
                                </a>
                            @endif
                        </li>
                   @endforeach

               @endif


                {{-- <li class="nav-item dropdown nav-more-btn">
                    <a class="nav-link dropdown-toggle nav-more-btn-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span style="opacity: 0;"><i class="fas fa-home"></i></span>
                        <i class="fas fa-ellipsis-h" style="font-size: 20px;"></i>
                    </a>
                    <ul class="dropdown-menu nav-more-btn-submenu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li> --}}

            </ul>

            <ul class="navbar-nav d-flex icon-menu-bar">

                <!--start setting -->
                {{-- <li class="nav-item dropdown nav-more-btn">
                    <a style="padding-left: 10px !important;padding-right: 10px !important;padding-top: 18px !important;" href="#" class="nav-link dropdown-toggle nav-more-btn-toggle icon-padding-top-5" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                        <span><i class="fas fa-cog font-size-22"></i></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        <li><button class="dropdown-item" type="button">Settings</button></li>
                        <li><button class="dropdown-item" type="button">Another action</button></li>
                        <li><button class="dropdown-item" type="button">Something else here</button></li>
                    </ul>
                </li>

                <li class="nav-item dropdown nav-more-btn">
                    <a style="padding-left: 10px !important;padding-right: 10px !important;padding-top: 15px !important;" href="#" class="nav-link">
                        <span style="color: gray;font-size: 18px;">   | </span>
                    </a>
                </li> --}}

                <!--end setting -->

                <li class="nav-item dropdown nav-more-btn">
                    <a style="padding-left: 10px !important;padding-right: 10px !important;" href="#" class="nav-link dropdown-toggle nav-more-btn-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                        <span class="profile-icon-design"> <img src="{{ "https://www.gravatar.com/avatar/" . md5( strtolower( trim( auth()->user()->email ) ) )}}" width="30" class="user-img rounded-circle mr-3"> </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end" style="width:300px">
                        <li>
                            <div class="profile-icons">
                                <img src="{{ "https://www.gravatar.com/avatar/" . md5( strtolower( trim( auth()->user()->email ) ) )}}" width="48">
                            </div>
                            <div class="profile-info">
                                <h3>{{ auth()->user()->name }}</h3>
                                <p>Role: {{ getRoleUsingFlag(auth()->user()->flag) }}</p>
                            </div>
                        </li>
                        {{-- <li><button class="dropdown-item" type="button">Another action</button></li>
                        <li><button class="dropdown-item" type="button">Something else here</button></li> --}}
                    </ul>
                </li>

                <li class="nav-item dropdown nav-more-btn">
                    <a style="padding-left: 10px !important;padding-right: 10px !important;padding-top: 18px !important;" href="#" class="nav-link dropdown-toggle nav-more-btn-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                        <span><i class="fas fa-grip-horizontal font-size-22"></i></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                      @if(auth()->user()->is_system_admin == 1)
                        <li><a class="dropdown-item" href="{{url('admins/settings/modules')}}"><i class="fas fa-arrow-circle-right"></i> Modules</a></li>
                        <li><a class="dropdown-item" href="{{url('admins/settings/components')}}"><i class="fas fa-arrow-circle-right"></i> Components</a></li>
                        <li><a class="dropdown-item" href="{{url('admins/settings/permissions')}}"><i class="fas fa-arrow-circle-right"></i> Permissions</a></li>
                        <li><a class="dropdown-item" href="{{url('admins/settings/roles')}}"><i class="fas fa-arrow-circle-right"></i> Roles</a></li>
                        <li><a class="dropdown-item" href="{{url('admins/settings/users')}}"><i class="fas fa-arrow-circle-right"></i> Users</a></li>
                      @endif
                        <li><a class="dropdown-item" href="{{url('admins/logout')}}"><i class="fas fa-power-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>
<!--end nav section-->
























