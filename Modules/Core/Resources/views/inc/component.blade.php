<!-- start submodule list here -->


@php
$permissions = auth()->user()->getDirectPermissions()->pluck('id')->toArray();


$submodule_ids = \Modules\Core\Entities\Auth\SubmoduleUser::where(['user_id'=>auth()->user()->id])->pluck('submodule_id')->toArray();

$clickableModule = \Modules\Core\Entities\Auth\Module::with('submodules','submodules.componet_permissions')
->where(['slug'=>$module_slug])
// ->whereHas('submodules',function($query) use($submodule_ids){
//   $query->whereIn('id',$submodule_ids);
// })
->first(['id']);

$user_componets = array();
if(!is_null($clickableModule)){ //&& isset($url_slug[1])
  $user_componets = $clickableModule->submodules;
}

@endphp

@if(count($user_componets) > 0)
<nav class="navbar navbar-expand-lg navbar-light component-navbar">
    <div class="container-fluid">

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          @foreach($user_componets as $comp)
            @if(in_array($comp->id,$submodule_ids))
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle @if(isset($url_slug[1]) && $url_slug[1] == $comp->action) active @endif" href="#" id="navbarDropdown_{{ $comp->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ $comp->title }} <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown_{{ $comp->id }}">
                  @foreach ($comp->componet_permissions->where('is_view_with_component',1)->sortBy('order_by') as $item)
                      @if(in_array($item->id,$permissions))
                        <li><a class="dropdown-item" href="{{ url($item->route_url) }}">{{ $item->professional_name }}</a></li>
                      @endif
                  @endforeach
                </ul>
              </li>
              @endif
            @endforeach


        </ul>
      </div>
    </div>
  </nav>
<!-- end submodule list here -->
@endif
