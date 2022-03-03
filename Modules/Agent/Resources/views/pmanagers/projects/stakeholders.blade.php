@extends('core::layouts.app')
@section('title','Project Stakeholders')
@section('content')

@include('core::inc.component')
@include('core::inc.sweetalert')


<!-- start filter modal  -->
@component('core::inc.filter')
    @slot('filter_route')
    {{Form::open(['route'=>['pro-manager.project.stkHolderFilter',[$module,$project_id]],'method'=>'GET'])}}

    @endslot

    @slot('filter_title')
        Filter Stackholders
    @endslot

    @slot('filter_body')

    <div class="row">


     <div class="col-md-3">
            <label class="filter-label">Search</label>
            <input type="text" name="search" class="form-control" placeholder="Search Name/mobile" value=""/>
        </div>

        <div class="col-md-3">
            <label class="filter-label">Start Date</label>
            <input type="date" name="start_date"  class="form-control" value="{{ $start_date }}"/>
        </div>


        <div class="col-md-3">
            <label class="filter-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ $end_date }}"/>
        </div>


        <div class="col-md-3">
            <label class="filter-label">Division</label>
            <select class="form-control js-example-basic-multiple full-width" name="division_id" onchange="setDistrictByDivision(this.value)">
                <option value="">Choose</option>
                @foreach($divisions as $division)
                    @if($division_id == $division->id)
                         <option value="{{ $division->id }}" selected>{{ $division->name }}</option>
                    @else
                        <option value="{{ $division->id }}">{{ $division->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="row" style="margin-top:20px;">

        <div class="col-md-3">
            <label class="filter-label">District</label>
            <select class="form-control js-example-basic-multiple full-width" name="district_id" id="district_id" onchange="setUpazilaByDistrict(this.value)">
                <option value="">Choose</option>
            </select>
        </div>


        <div class="col-md-3">
            <label class="filter-label">Upazila</label>
            <select class="form-control js-example-basic-multiple full-width" name="upazila_id" id="upazila_id">
                <option value="">Choose</option>
            </select>
        </div>


        <div class="col-md-3">
            <label class="filter-label">Roles</label>
            <select class="form-control js-example-basic-multiple full-width" name="role_id">
                <option value="">Choose</option>
                @foreach($roles as $role_search)
                    @if($role_id == $role_search->id)
                         <option value="{{ $role_search->id }}" selected>{{ $role_search->name }}</option>
                    @else
                        <option value="{{ $role_search->id }}">{{ $role_search->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>


        <div class="col-md-3">
            <label class="filter-label">Status</label>
            @php $statuses = array('1'=>'Active','0'=>'Deactive') @endphp
            <select class="form-control js-example-basic-multiple full-width" name="is_active">
                <option value="">Choose</option>
                @foreach($statuses as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endslot
@endcomponent
<!--end filter modal -->




<div class="content-bg-section">
    <div class="content-section">
        <div class="container-fluid margin-top-20 table-design-container">
            {{-- <ul class="nav nav-tabs">
                @foreach ($selectRoles as $sel_roles)
                  <li class="nav-item">
                    <a class="nav-link @if($first_active_tab->id == $sel_roles->id) active @endif" href="{{ url($module.'/stakeholders/?role='.$sel_roles->role_id) }}" style="font-weight: bolder;color:#495057">{{ $sel_roles->role->name }}</a>
                  </li>
                @endforeach
            </ul> --}}

            <div class="card" style="border-radius: 0px 5px 5px 5px;border-top:none">

                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                      <a class="navbar-brand" href="#" style="padding: 7px 13px;"><i class="fas fa-address-card"></i></a>
                      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                      </button>
                      <div class="collapse navbar-collapse driction" id="navbarSupportedContent">
                        <div class="row">
                            <p style="margin-top: 5px;"><a href="{{ url('agent/load-component') }}">Agent</a></p>
                            <h5>{{ $project->name }} Stakeholders <span class="pin"><i class="fas fa-thumbtack"></i></span></h5>
                        </div>

                      </div>
                      <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                          {{-- <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-cloud-upload-alt"></i> Import</a>
                          </li> --}}
                          @auth_access('agent-admin-pmanager-stk-export-by-project')
                          <li class="nav-item">
                            <a class="nav-link" href="#" onclick="submitSelectableExport()"><i class="far fa-file-excel"></i> Export</a>
                          </li>
                          @end_auth_access
                        </ul>

                      </div>
                    </div>
                  </nav>


                  <div class="bottom_nav">
                    <div class="row">
                        <div class="col-md-12 d-flex">
                            <ul class="me-auto d-flex left">
                                <li>{{ !is_null($project) ? count($project->assign_pro):0 }} Items</li>
                                <li>Shorted By ID</li>

                                {{-- <li>Filterd By {{ $filter_by }}</li>

                                @if(!is_null($last_updated))
                                    <li>Updated {{ $last_updated }}</li>
                                @endif --}}
                            </ul>

                            <div class="d-flex" style="margin-bottom: 20px;">
                                <a href="{{ url($module.'/pmg-projects-stakeholders/'.$project_id) }}"><button type="button" class="icon_btn"><i class="fas fa-redo"></i></button></a>
                                @auth_access('agent-admin-pmanager-stk-filter-by-project')
                                    <a href="#"><button type="button" class="icon_btn" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="fas fa-filter"></i></button></a>
                                @end_auth_access
                            </div>

                        </div>
                    </div>
                </div>


                {{Form::open(['route'=>['agent.pro_manager_stk_by_pro_export',[$project->slug,$module]],'method'=>'POST','id'=>'exportForm'])}}
                <input type="hidden" name="project_id" value="{{ $project->id }}"/>


                <div class="table_section">
                    <div class="row">
                        <div class="col-md-12">

                            <div style="overflow-x: auto;margin-bottom:10px;">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th class="col-serial"></th>
                                    <th class="col-serial"><input type="checkbox" onchange="selects(this)"></th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Project Status</th>
                                    <th class="col-serial"></th>
                                  </tr>
                                </thead>
                                <tbody>

                                @foreach ($project_stakeholders as $k => $item)


                                  <tr>
                                    <td class="col-serial">{{ $k+1 }}</td>
                                    <td class="col-serial"><input type="checkbox" value="{{$item->stakeholder->id}}" name="ids[]" class="select-checkbox"></td>
                                    <td class="text-color">{{ date('d-m-Y h:i A',strtotime($item->created_at)) }}</td>
                                    <td class="text-color">
                                        @if (!is_null($item->stakeholder->spatieRole))
                                        <i class="fas fa-user-tag"></i> {{ $item->stakeholder->spatieRole->name }}
                                        @else
                                             N/A
                                        @endif
                                    </td>
                                    <td class="text-color">{{ $item->stakeholder->name }}</td>
                                    <td class="text-color"><i class="fas fa-phone-alt"></i> {{ $item->stakeholder->mobile }}</td>
                                    <td class="text-color">
                                        @if($item->is_active == 0)
                                            <span style="color:red;"><i class="far fa-times-circle"></i> In-Active</span>
                                        @else
                                            <span style="color:green;"><i class="far fa-check-circle"></i> Active</span>
                                        @endif
                                    </td>
                                    <td class="col-serial">
                                        <div class="btn-group">
                                            <button type="button" class="table_icon_btn" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                                <i class="fas fa-sort-down"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg-end table-dropdown">
                                              @auth_access('agent-admin-pmanager-stk-activation-by-project')
                                                @if($item->is_active == 0)
                                                    <li><a href="{{ url($module.'/pmg-projects-activation/active/'.$project->slug.'/'.$item->stakeholder->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Active</a></li>
                                                @else
                                                    <li><a href="{{ url($module.'/pmg-projects-activation/deactive/'.$project->slug.'/'.$item->stakeholder->id) }}" class="dropdown-item"><i class="fas fa-eye-slash"></i> In-Active</a></li>
                                                @endif
                                              @end_auth_access

                                              @auth_access('agent-admin-pmanager-stk-remove-by-project')
                                                <li><a href="{{ url($module.'/pmg-projects-re-stk/'.$project->slug.'/'.$item->stakeholder->id) }}" class="dropdown-item"><i class="fas fa-trash-alt"></i> Remove</a></li>
                                              @end_auth_access
                                            </ul>
                                          </div>
                                    </td>
                                  </tr>
                                @endforeach
                                </tbody>
                              </table>
                            </div>

                        </div>
                    </div>
                </div>
                {{Form::close()}}



                <div class="container-fluid">
                    {{-- {{ $stakeholders->appends(['role'=>$first_active_tab->role_id,'search'=>$search,'start_date'=>$start_date,'end_date'=>$end_date,'division_id'=>$division_id,'district_id'=>$district_id,'upazila_id'=>$upazila_id,'status'=>$status])->links() }} --}}
                </div>

            </div>
        </div>




    </div>
</div>


@endsection

@section('bottom_script')
<script>

    function selects(obj){
        var ele = document.getElementsByClassName('select-checkbox');
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

    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });



    function setDistrictByDivision(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var districts = '{{ $district_ids }}';

        var dataString = {division_id:id,districts:districts};
        $.ajax({
            type:"post",
            url:"{{url('api/pro-stkholder-load-district-by-division')}}",
            data:dataString,
            success:function(data){
                $("#district_id").html(data);
                console.log(data);
            }
        });
    }


    function setUpazilaByDistrict(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var upazilas = '{{ $upazila_ids }}';

        var dataString = {district_id:id,upazilas:upazilas};
        $.ajax({
            type:"post",
            url:"{{url('api/pro-stkholder-load-upazila-by-district')}}",
            data:dataString,
            success:function(data){
                $("#upazila_id").html(data);
            }
        });
    }




    function  submitSelectableExport(){
        document.getElementById("exportForm").submit();
    }


</script>
@endsection

