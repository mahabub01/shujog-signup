@extends('core::layouts.app')
@section('title','Stakeholders')
@section('content')

@include('core::inc.component')
@include('core::inc.sweetalert')
@include('core::inc.selectable-table')






<!-- start filter modal  -->
@component('core::inc.filter')
    @slot('filter_route')
    {{Form::open(['route'=>['agent.incomplete-signup.filter', $module],  'method'=>'GET'])}}

    @endslot

    @slot('filter_title')
        Filter Stackholders
    @endslot

    @slot('filter_body')

    <div class="row">

        {{-- <input type="hidden" name="role" class="form-control" value="{{ $first_active_tab->role_id }}"/> --}}

        <div class="col-md-3">
            <label class="filter-label">Search</label>
            <input type="text" name="search" class="form-control" placeholder="Search Name/mobile" value="{{ $search }}"/>
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
                @if(!is_null($filter_district))
                    <option value="{{ $filter_district->id }}">{{ $filter_district->name }}</option>
                @endif
            </select>
        </div>


        <div class="col-md-3">
            <label class="filter-label">Upazila</label>
            <select class="form-control js-example-basic-multiple full-width" name="upazila_id" id="upazila_id">
                <option value="">Choose</option>
                @if(!is_null($filter_upazila))
                    <option value="{{ $filter_upazila->id }}">{{ $filter_upazila->name }}</option>
                @endif
            </select>
        </div>



    </div>




    @endslot
@endcomponent
<!--end filter modal -->




<div class="content-bg-section">
    <div class="content-section">
        <div class="container-fluid table-design-container">
            <ul class="nav nav-tabs">
                @foreach ($selectRoles as $sel_roles)
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url($module.'/stakeholders/?role='.$sel_roles->role_id) }}" style="font-weight: bolder;color:#495057;font-size:14px">{{ $sel_roles->role->name }}</a>
                  </li>
                @endforeach
                <li class="nav-item">
                    <a class="nav-link  active" href="{{ route('agent.incomplete-signup', $module)}}" style="font-weight: bolder;color:#495057;font-size:14px">Incomplete Signup</a>
                  </li>
            </ul>

            <div class="card" style="border-radius: 0px 5px 5px 5px;border-top:none">

                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                      <a class="navbar-brand" href="#" style="padding: 7px 13px;"><i class="fas fa-address-card"></i></a>
                      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                      </button>
                      <div class="collapse navbar-collapse driction" id="navbarSupportedContent">
                        <div class="row">
                            <p style="margin-top: 5px;"><a class="rev-underline-subtitle" href="{{ url('agent/load-component') }}">{{ getPanelPageTitle(auth()->user()->flag) }}</a></p>
                            <h5>All Incomplete Users <span class="pin"><i class="fas fa-thumbtack"></i></span></h5>
                        </div>

                      </div>
                      <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="ms-auto mb-2 mb-lg-0 d-flex">


                            <li>
                                <a href="{{ route('agent.incomplete-signup.filter', $module) }}"><button type="button" class="icon_btn"><i class="fas fa-redo"></i></button></a>
                            </li>
                            @auth_access('agent-mem-cons-stkholder-filter')
                            <li>
                                <a href="#"><button type="button" class="icon_btn" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="fas fa-filter"></i></button></a>
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
                                <li>{{ $uncomplete->total() }} Items</li>
                                <li>Shorted By ID</li>

                                <li>Filterd By {{ $filter_by }}</li>

                                @if(!is_null($last_updated))
                                    <li>Updated {{ $last_updated }}</li>
                                @endif
                            </ul>



                        </div>
                    </div>
                </div>


                {{Form::open(['route'=>['agent.stackholder.export',$module],'method'=>'POST','id'=>'exportForm'])}}
                {{-- <input type="hidden" value="{{$first_active_tab->role_id}}" name="role_id"/> --}}
                <div class="table_section">
                    <div class="row">
                        <div class="col-md-12">

                            <div style="overflow-x: auto;margin-bottom:10px;">
                            <table class="table" id="selectable-table">
                                <thead>
                                  <tr>
                                    <th class="col-serial table-header-index"></th>
                                    <th class="col-serial table-header-index"></th>
                                    <th class="col-serial table-header-index">
                                        <input type="checkbox" onchange="selects(this)"></th>
                                    <th class="table-header-index">Date</th>
                                    <th class="table-header-index">Role</th>
                                    <th class="table-header-index">Name</th>
                                    <th class="table-header-index">Phone</th>
                                    <th class="table-header-index">Division</th>
                                    <th class="table-header-index">District</th>
                                    <th class="table-header-index">Upzila</th>

                                  </tr>
                                </thead>
                                <tbody>

                                @foreach ($uncomplete as $k => $user)
                                  <tr class="table-row-index">
                                    <td class="col-serial table-body-index">{{ $k+1 }}</td>
                                    <td class="col-serial table-body-index">
                                        <div class="btn-group">
                                            <button type="button" class="table_icon_btn" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                                <i class="fas fa-sort-down"></i>
                                            </button>
                                            <ul class="dropdown-menu table-dropdown">
                                              @auth_access('agent-mem-cons-stkholder-details')
                                              <li><a href="{{ url($module.'/stakeholder/incomplete-signup/details/'.$user->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Details</a></li>
                                              <li><a href="{{ url($module.'/stakeholder/incomplete-signup/edit/'.$user->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Update Profile</a></li>
                                              @end_auth_access

                                            </ul>
                                          </div>
                                    </td>
                                    <td class="col-serial table-body-index"><input type="checkbox" value="{{$user->id}}" name="ids[]" class="select-checkbox"></td>
                                    <td class="text-color table-body-index">{{ date('d-m-Y h:i A',strtotime($user->created_at)) }}</td>
                                    <td class="text-color table-body-index">
                                        @if (!is_null($user->spatieRole))
                                        <i class="fas fa-user-tag"></i> {{ $user->spatieRole->name }}
                                        @else
                                             N/A
                                        @endif
                                    </td>

                                    <td class="text-color table-body-index">
                                        @if (!is_null($user->name))
                                            {{ $user->name }}
                                        @else
                                             N/A
                                        @endif
                                    </td>

                                    <td class="text-color table-body-index">
                                        @if (!is_null($user->mobile))
                                            <i class="fas fa-phone-alt"></i> {{ $user->mobile }}
                                        @else
                                             N/A
                                        @endif
                                    </td>



                                    <td class="text-color table-body-index">
                                        @if (!is_null($user->division))
                                            {{ $user->division->name }}
                                        @else
                                             N/A
                                        @endif
                                    </td>

                                    <td class="text-color table-body-index">
                                        @if (!is_null($user->district))
                                            {{ $user->district->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>

                                    <td class="text-color table-body-index">
                                        @if (!is_null($user->upazila))
                                            {{ $user->upazila->name }}
                                        @else
                                            N/A
                                        @endif
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
                    {{ $uncomplete->links() }}
                </div>

            </div>
        </div>




    </div>
</div>


@endsection

@section('bottom_script')
<script>

    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();

        let selectable_field = [
            {index:3,name:"Date"},
            {index:4,name:"Role"},
            {index:5,name:"Name"},
            {index:6,name:"Phone"},
            {index:7,name:"Ongoing_project"},
            {index:8,name:"Consultant"},
            {index:9,name:"Trainer"},
            {index:10,name:"Deployers"},
            {index:11,name:"Network_manager"},
            {index:12,name:"Project_manager"},
            {index:13,name:"Mood"},
            {index:14,name:"Schedule_time"},
            {index:15,name:"User_info_status"},
            {index:16,name:"Evaluation_status"},
            {index:17,name:"Sign_up_reference"},
        ];
        getSelectableField(selectable_field)
    });



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





    function setDistrictByDivision(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        var dataString = {division_id:id};
        $.ajax({
            type:"post",
            url:"{{url('api/load-district-by-division')}}",
            data:dataString,
            success:function(data){
                $("#district_id").html(data);
            }
        });
    }


    function setUpazilaByDistrict(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var dataString = {district_id:id};
        $.ajax({
            type:"post",
            url:"{{url('api/load-upazila-by-district')}}",
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
