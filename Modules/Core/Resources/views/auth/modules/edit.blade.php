@extends('core::layouts.app')
@section('title','Module Edit')

@section('top_script')
    <style>
        #changeImageDesktop{
            display: block;
        }
    </style>
@endsection

@section('content')
@include('core::inc.sweetalert')
{{Form::open(['route'=>['core.modules.update',$data->id],'method'=>'PUT','files'=>true])}}

    <!--start content  section-->
   <div class="content-header sticky-top">
        <div class="container-fluid">
           <h2 class="me-auto mb-2 mb-lg-0">Edit Modules</h2>
            <div class="d-flex">
                <a href="{{route('core.modules.index')}}"><button type="button" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</button></a>
                <button type="submit" class="btn btn-info"><i class="far fa-save"></i> Save</button>
            </div>
        </div>
    </div>


        <div class="form-section">

            <div class="container-fluid">

                <h3 class="form-subtitle">Module Information</h3>

                <div class="row">
                    <div class="col-md-6 col-lg-5">
                        <div class="mb-3 row form-row">
                            <label for="inputTitle" class="col-sm-4 col-form-label text-end">Module Name</label>
                            <div class="col-sm-8">
                            <input type="text" name="title" value="{{$data->title}}" placeholder="Write your module name" class="form-control" id="inputTitle">
                            </div>
                        </div>










                        <div class="mb-3 row form-row">
                            <label for="inputAction" class="col-sm-4 col-form-label text-end">Action Name</label>
                            <div class="col-sm-8">
                            <input type="text" name="action" value="{{$data->action}}" placeholder="Write your action name" class="form-control" id="inputAction">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputURL" class="col-sm-4 col-form-label text-end">Action URL</label>
                            <div class="col-sm-8">
                                @php
                                    $arr = array('url'=>'URL','route'=>"Route");
                                @endphp
                                <select name="action_type" class="form-control gray-border form-select" aria-label="Default select example" id="inputURL">
                                    @foreach($arr as $k => $act)
                                        @if($data->action_type == $k)
                                            <option value="{{$k}}" selected>{{$act}}</option>
                                        @else
                                            <option value="{{$k}}">{{$act}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputPassword" class="col-sm-4 col-form-label text-end">Module Icon</label>
                            <div class="col-sm-8">
                            <input type="text" name="icons" value="{{$data->icons}}" class="form-control" id="inputPassword" placeholder="Font Awesome Icon">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="icon" class="col-sm-4 col-form-label text-end">Upload Custome Icon</label>
                            <div class="col-sm-8">
                            <input type="file" name="images" id="icon" class="form-control mb-1" id="inputPassword">

                            @if($data->upload_icon != "")
                                <img id="changeImageDesktop" src="{{asset('uploads/module-icon/'.$data->upload_icon)}}" height="50px" width="50px;"/>
                            @else
                                <img id="changeImageDesktop" src="{{asset('assets/backend/images/design/no_images.png')}}" height="50px" width="50px;"/>
                            @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-5 offset-lg-1">
                        <div class="mb-3 row form-row">
                            <label for="exampleFormControlTextarea1" class="col-sm-6 col-form-label">Write Details about this Module</label>
                            <textarea name="comments" class="form-control gray-border" id="exampleFormControlTextarea1" rows="10">{{$data->comments}}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>


<!--end content  section-->
{{Form::close()}}


@endsection

@section('bottom_script')
<script>
    $('#icon').change(function () {
        $("#changeImageDesktop").attr('src',window.URL.createObjectURL(this.files[0]))
    });
</script>
@endsection
