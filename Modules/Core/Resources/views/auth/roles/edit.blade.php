@extends('core::layouts.app')
@section('title','Role Edit')
@section('top_script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            font-size: 16px !important;
            color: #666666 !important;
        }
    </style>
@endsection

@include('core::inc.sweetalert')

@section('content')
{{Form::open(['route'=>['core.roles.update',$data->id],'method'=>'PUT'])}}

    <div class="content-header sticky-top">
        <div class="container-fluid">
           <h2 class="me-auto mb-2 mb-lg-0">Edit Roles</h2>
            <div class="d-flex">
                <a href="{{route('core.roles.index')}}"><button type="button" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</button></a>
                <button type="submit" class="btn btn-info"><i class="far fa-save"></i> Save</button>
            </div>
        </div>
    </div>
    <div class="form-section">

        <div class="container-fluid">

            <h3 class="form-subtitle">Roles Information</h3>

    <div class="container-xxl" style="margin-top: 20px;">
        <section class="container-xxl isocial-body-section section-table-border-custom" style="border-top: none; border-bottom: none; padding-right: 0px; padding-left: 0px;">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-4" style="margin-top: 40px;">
                       <div class="mb-5">
                           <label>Role Name</label>
                            <input type="text" name="name" class="form-control i-f-d" placeholder="Role Name" value="{{$data->name}}">
                        </div>
                    </div>

                    <div class="col-md-4" style="margin-top: 40px;">
                        <div class="mb-5">
                            <label>Flag</label>
                            <select class="form-control" name="flag_id">
                                <option value="">Select</option>
                                @foreach (DB::table("flag")->get() as $item)
                                    @if($item->id == $data->flag)
                                        <option value="{{ $item->id }}" selected>{{ $item->flag_title }}</option>
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->flag_title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4" style="margin-top: 40px;">
                        <div class="mb-5">
                            <label>Description</label>
                            <input type="text" name="comments" class="form-control i-f-d" placeholder="Description" value="{{$data->comments}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col" style="margin-top: -20px; margin-left: 10px; margin-right: 10px; margin-bottom: 40px;">

                    <div class="col-md-12">

                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            @foreach($modules as $module)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-heading{{$module->id}}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{$module->id}}" aria-expanded="false" aria-controls="flush-collapse{{$module->id}}" style="background-color: #ECF0F1; border: 1px solid #E9E6EE; margin-top: 10px;">
                                            @if($module->upload_icon != "") <img src="{{asset('public/uploads/module-icon/'.$module->upload_icon)}}" alt="module Icon" height="20px;">  @elseif($module->icons != "") {!! $module->icons !!} @else <i class="fas fa-table"></i> @endif &nbsp; {{$module->title}}

                                                @if(in_array($module->id,$moduleIds))
                                                    <input type="checkbox" name="select_module_id[]" class="form-check-input mt-0" onchange="selectModule(this,value)" style="margin-left: 20px" value="{{$module->id}}" checked/>
                                                @else
                                                    <input type="checkbox" name="select_module_id[]" class="form-check-input mt-0" onchange="selectModule(this,value)" style="margin-left: 20px" value="{{$module->id}}"/>
                                                @endif

                                        </button>
                                    </h2>
                                    <div id="flush-collapse{{$module->id}}" class="accordion-collapse collapse" aria-labelledby="flush-heading{{$module->id}}" data-bs-parent="#accordionFlushExample">

                                        <div class="accordion-body" style="border: 1px solid #c5c9ca; border-top: none;">

                                            <div class="container-">

                                                @foreach($module->submodules as $submodule)
                                                    <div style="margin-bottom: 30px;">

                                                        @if(in_array($submodule->id,$submodulesIds))
                                                            <label style="margin-bottom: 0px;border-bottom: 0.5px dotted #8E73BE;padding-bottom: 10px;" class="text-d-none"><input type="checkbox" class="form-check-input mt-0 module_selected_{{$module->id}}" name="select_submodule_id[]" value="{{$submodule->id}}" onchange="selectComponent(this,{{$submodule->id}})" checked> {{$submodule->title}} </label>
                                                        @else
                                                            <label style="margin-bottom: 0px;border-bottom: 0.5px dotted #8E73BE;padding-bottom: 10px;" class="text-d-none"><input type="checkbox" class="form-check-input mt-0 module_selected_{{$module->id}}" name="select_submodule_id[]" value="{{$submodule->id}}" onchange="selectComponent(this,{{$submodule->id}})"> {{$submodule->title}} </label>
                                                        @endif


                                                        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-4">
                                                            @foreach($module->getPermission($submodule->id) as $permission)
                                                                <div class="col accordion-app">
                                                                    <div class="position-relative">
                                                                        <div class="">
                                                                            <div class="user-circle">
                                                                                <img src="{{asset('Modules/Core/Public/assets/images/img_permission.png')}}" alt="" width="35" height="35" class="b-r-7">
                                                                                <span style="font-size: 14px;font-weight: normal;">{{$permission->name}}</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="position-absolute top-0 end-0 accordion-radio">
                                                                            @if(in_array($permission->id,$permissionIds))
                                                                                <input class="form-check-input mt-0 module_selected_{{$module->id}} submodule_selected_{{$submodule->id}}" type="checkbox" name="permission_id[]" value="{{$permission->id}}" checked>
                                                                            @else
                                                                                <input class="form-check-input mt-0 module_selected_{{$module->id}} submodule_selected_{{$submodule->id}}" type="checkbox" name="permission_id[]" value="{{$permission->id}}">
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach


                                            </div>

                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>






    {{Form::close()}}


@endsection

@section('bottom_script')
<script>

    function selectModule(obj,module_id){
        var ele = document.getElementsByClassName('module_selected_'+module_id);
        if(obj.checked){
            for(var i=0; i<ele.length; i++){
                if(ele[i].type == 'checkbox'){
                    ele[i].checked=true;
                }
            }
        }else{
            for(var i=0; i<ele.length; i++){
                if(ele[i].type == 'checkbox'){
                    ele[i].checked=false;
                }
            }
        }
    }



    function selectComponent(obj,submodule_id){
        var ele = document.getElementsByClassName('submodule_selected_'+submodule_id);
        if(obj.checked){
            for(var i=0; i<ele.length; i++){
                if(ele[i].type == 'checkbox'){
                    ele[i].checked=true;
                }
            }
        }else{
            for(var i=0; i<ele.length; i++){
                if(ele[i].type == 'checkbox'){
                    ele[i].checked=false;
                }
            }
        }
    }







    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });

    $('#icon').change(function () {
        $("#changeImageDesktop").attr('src',window.URL.createObjectURL(this.files[0]))
    });
</script>
@endsection

