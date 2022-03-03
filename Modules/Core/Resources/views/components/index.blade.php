@extends('core::layouts.app')
@section('title','Dashboard')
@section('top_script')
<style>
    .module-link{
        font-size: 14px;
        color: #212529;
        text-decoration: none;
    }
    .module-link:hover{
        text-decoration: underline;
        color: #212529;
        font-weight: bold;
    }
</style>
@endsection


@section('content')

    @include('core::inc.component')
    @include('core::inc.sweetalert')

    <div class="content-bg-section">
        <div class="content-section">

        <div class="container-fluid table-design-container">
            <!--start card -->
            <div class="card full-height">
                <div class="card-body">
                    <h2 class="module-title"><i class="fas fa-database"></i> Components</h1>

                    <div class="row">
                        @php
                            $permissions = auth()->user()->getDirectPermissions()->pluck('id')->toArray();
                        @endphp

                        @foreach($user_componets as $comp)
                            @if(in_array($comp->id,$componentIds))
                                <div class="col-2" style="margin-bottom: 10px;">
                                    <div class="card">
                                        <div class="card-body" style="padding: 7px 10px;">

                                            @foreach ($comp->componet_permissions->where('is_view_with_component',1)->where('is_view_with_component_page',1)->sortBy('order_by') as $item)
                                                @if(in_array($item->id,$permissions))
                                                <a class="module-link" href="{{ url($item->route_url)}}"><i class="far fa-arrow-alt-circle-right"></i> {{$comp->title}}</a>
                                                @endif
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>


                </div>
            </div>
        </div>
        </div>
    </div>

@endsection
