@extends('core::layouts.app')
@section('title','Permission Edit')
@section('content')
@include('core::inc.sweetalert')
{{Form::open(['route'=>['core.permissions.update',$data->id],'method'=>'PUT','files'=>true])}}

    <!--start content  section-->
   <div class="content-header sticky-top">
        <div class="container-fluid">
           <h2 class="me-auto mb-2 mb-lg-0">Edit Permisssion</h2>
            <div class="d-flex">
                <a href="{{route('core.permissions.index')}}"><button type="button" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</button></a>
                <button type="submit" class="btn btn-info"><i class="far fa-save"></i> Save</button>
            </div>
        </div>
    </div>


        <div class="form-section">

            <div class="container-fluid">

                <h3 class="form-subtitle">Permisssion Information</h3>

                <div class="row">
                    <div class="col-md-6 col-lg-5">
                        <div class="mb-3 row form-row">
                            <label for="inputTitle" class="col-sm-4 col-form-label text-end">Permission Name</label>
                            <div class="col-sm-8">
                            <input type="text" name="name" value="{{$data->name}}"  placeholder="Write your Permission Name" class="form-control" id="inputTitle">
                            <span style="color:silver">Example: users-display</span>
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputAction" class="col-sm-4 col-form-label text-end">Professional Name</label>
                            <div class="col-sm-8">
                            <input type="text" name="professional_name" value="{{$data->professional_name}}" placeholder="Write your Professional Name" class="form-control" id="inputAction">
                            <span style="color:silver">Example: User List,Create New</span>
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputURL" class="col-sm-4 col-form-label text-end">Is View With Component SubMenu?</label>
                            <div class="col-sm-8">
                                <input type="radio"  name="is_view_with_component" value="1" @if($data->is_view_with_component == 1) checked @endif> Yes
                                <input type="radio"  name="is_view_with_component" value="0" @if($data->is_view_with_component == 0) checked @endif> No
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputRouteUrl" class="col-sm-4 col-form-label text-end">Route Url</label>
                            <div class="col-sm-8">
                                <input type="text" name="route_url" value="{{$data->route_url}}" placeholder="Write your Route Url" class="form-control" id="inputRouteUrl">
                                <span style="color:silver">Example: agent/users</span>
                            </div>
                        </div>

                        @livewire('core::permission.edit',['data'=>$data])

                        <div class="mb-3 row form-row">
                            <label for="inputRouteUrl" class="col-sm-4 col-form-label text-end">Action</label>
                            <div class="col-sm-8">
                                <input type="text" name="action" value="{{$data->action}}" placeholder="Write your Action name" class="form-control" id="inputRouteUrl">
                                <span style="color:silver">\Modules\Product\Http\Controllers\Auth\ProductController@index</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-5 offset-lg-1">
                        <div class="mb-3 row form-row">
                            <label for="exampleFormControlTextarea1" class="col-sm-6 col-form-label">Write Details about this Permission</label>
                            <textarea name="comments" class="form-control gray-border" id="exampleFormControlTextarea1" rows="10">{{$data->comments}}</textarea>



                        </div>
                    </div>
                </div>

            </div>
        </div>


<!--end content  section-->
{{Form::close()}}


@endsection


