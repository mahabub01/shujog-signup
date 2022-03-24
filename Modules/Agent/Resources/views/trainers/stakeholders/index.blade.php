@extends('core::layouts.app')
@section('title','Stakeholders')
@section('content')

@include('core::inc.component')
@include('core::inc.sweetalert')
@include('core::inc.selectable-table')


<!-- start filter modal  -->
@component('core::inc.filter')
    @slot('filter_route')
    {{Form::open(['route'=>['agent.trainer-stakeholders.filter',$module],'method'=>'GET'])}}

    @endslot

    @slot('filter_title')
        Filter Stackholders
    @endslot

    @slot('filter_body')

    <div class="row">

        <input type="hidden" name="role" class="form-control" value="{{ $first_active_tab->role_id }}"/>

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


        <div class="col-md-3">
            <label class="filter-label">Status</label>
            <select class="form-control js-example-basic-multiple full-width" name="status">
                <option value="">Choose</option>
                @foreach(getConsultantStatus(true,['4']) as $k => $sts)
                    @if($status == $k)
                        <option value="{{ $k }}" selected>{{ $sts }}</option>
                    @else
                        <option value="{{ $k }}">{{ $sts }}</option>
                    @endif
                @endforeach
            </select>
        </div>


        <div class="col-md-3">
            <label class="filter-label">Signup Reference</label>
            <select class="form-control js-example-basic-multiple full-width" name="reference_id">
                <option value="">Choose</option>
                @foreach($references as $reference)
                    @if($reference_id == $reference->id)
                        <option value="{{ $reference->id }}" selected>{{ $reference->title }}</option>
                    @else
                        <option value="{{ $reference->id }}">{{ $reference->title  }}</option>
                    @endif
                @endforeach
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
                    <a class="nav-link @if($first_active_tab->id == $sel_roles->id) active @endif" href="{{ url($module.'/trainer-stakeholders/?role='.$sel_roles->role_id) }}" style="font-weight: bolder;color:#495057">{{ $sel_roles->role->name }}</a>
                  </li>
                @endforeach
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
                            <h5>All Stakeholders <span class="pin"><i class="fas fa-thumbtack"></i></span></h5>
                        </div>

                      </div>
                      <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                          {{-- <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-cloud-upload-alt"></i> Import</a>
                          </li> --}}
                          @auth_access('agent-mem-trainer-stkholder-single-export')
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
                                <li>{{ $stakeholders->total() }} Items</li>
                                <li>Shorted By ID</li>

                                <li>Filterd By {{ $filter_by }}</li>

                                @if(!is_null($last_updated))
                                    <li>Updated {{ $last_updated }}</li>
                                @endif
                            </ul>

                            <div class="d-flex" style="margin-bottom: 20px;">

                                <a href="{{ route('agent.log.trainer', $module) }}"><button type="button" class="icon_btn">log</button></a>

                                <div class="btn-group">
                                    <button type="button" class="icon_btn" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-lg-end">
                                      @auth_access('agent-mem-trainer-stkholder-all-export')
                                        <li><a href="{{ url($module.'/trainer-stakeholders-all-export?role='.$first_active_tab->role_id.'&search='.$search.'&start_date='.$start_date.'&end_date='.$end_date.'&division_id='.$division_id.'&district_id='.$district_id.'&upazila_id='.$upazila_id.'&status='.$status.'&reference_id='.$reference_id) }}" class="dropdown-item"><i class="far fa-file-excel"></i> All Export</a></li>
                                      @end_auth_access

                                      <li><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#selectableModal" class="dropdown-item"><i class="fas fa-columns"></i> Show/Hide Column</a></li>

                                    </ul>
                                  </div>


                                <a href="{{ url($module.'/trainer-stakeholders/?role='.$first_active_tab->role_id) }}"><button type="button" class="icon_btn"><i class="fas fa-redo"></i></button></a>
                                @auth_access('agent-mem-trainer-stkholder-filter')
                                    <a href="#"><button type="button" class="icon_btn" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="fas fa-filter"></i></button></a>
                                @end_auth_access
                            </div>

                        </div>
                    </div>
                </div>


                {{Form::open(['route'=>['agent.trainer-stackholder.export',$module],'method'=>'POST','id'=>'exportForm'])}}
                <input type="hidden" value="{{$first_active_tab->role_id}}" name="role_id"/>
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
                                    <th class="table-header-index width-150">Ongoing Project</th>
                                    <th class="table-header-index width-150">Consultant</th>
                                    <th class="table-header-index width-150">Trainer</th>
                                    <th class="table-header-index width-150">Deployers</th>
                                    <th class="table-header-index width-150">Network Manager</th>
                                    <th class="table-header-index width-150">Project Manager</th>
                                    <th class="table-header-index">Mood</th>
                                    <th class="table-header-index width-150">Scheduled Time</th>
                                    <th class="table-header-index width-150">User Info Status</th>
                                    <th class="table-header-index width-150">Evaluation Status</th>
                                    <th class="table-header-index width-150">Sign Up Reference</th>
                                  </tr>
                                </thead>
                                <tbody>

                                @foreach ($stakeholders as $k => $user)
                                  <tr class="table-row-index">
                                    <td class="col-serial table-body-index">{{ $k+1 }}</td>
                                    <td class="col-serial table-body-index">
                                        <div class="btn-group">
                                            <button type="button" class="table_icon_btn" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                                <i class="fas fa-sort-down"></i>
                                            </button>
                                            <ul class="dropdown-menu table-dropdown">
                                              @auth_access('agent-mem-trainer-stkholder-details')
                                              <li><a href="{{ url($module.'/trainer-stakeholders/'.$user->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Details</a></li>
                                              @end_auth_access

                                              @auth_access('agent-mem-trainer-stkholder-cmnt-view')
                                              <li><a href="{{ url($module.'/stakeholders/trainer-comments/'.$user->id) }}" class="dropdown-item"><i class="far fa-comments"></i> Comment</a></li>
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
                                    <td class="text-color table-body-index">{{ $user->name }}</td>
                                    <td class="text-color table-body-index"><i class="fas fa-phone-alt"></i> {{ $user->mobile }}</td>
                                    <td class="text-color table-body-index width-150">
                                    @if(!is_null($user->assignProject))
                                        <ol type="1" class="no-margin no-padding" style="list-style-type: decimal">
                                            @foreach($user->assignProject as $pro)
                                                <li>{{ $pro->project->name }}</li>
                                            @endforeach
                                        </ol>
                                    @else
                                        N/A
                                    @endif
                                    </td>
                                    <td class="text-color table-body-index width-150">
                                        @if(!is_null($user->stakeholderCommnet))
                                           <p>{{ $user->stakeholderCommnet->user->name }}</p>
                                           <p>({!! getStatusFullForm($user->stakeholderCommnet->status) !!})</p>
                                        @else
                                            <p>Default</p>
                                            <p style="color: silver">(Pending)</p>
                                        @endif
                                    </td>
                                    <td class="text-color table-body-index width-150">
                                        @if(!is_null($user->stakeholderCommnetForTrainer))
                                        <p>{{ $user->stakeholderCommnetForTrainer->user->name }}</p>
                                        <p>({!! getStatusFullForm($user->stakeholderCommnetForTrainer->status) !!})</p>
                                     @else
                                         <p>Default</p>
                                         <p style="color: silver">(Pending)</p>
                                     @endif
                                    </td>
                                    <td class="text-color table-body-index width-150">
                                        @if(!is_null($user->stakeholderCommnetForDeployer))
                                            <p>{{ $user->stakeholderCommnetForDeployer->user->name }}</p>
                                            <p>({!! getStatusFullForm($user->stakeholderCommnetForDeployer->status) !!})</p>
                                        @else
                                            <p>Default</p>
                                            <p style="color: silver">(Pending)</p>
                                        @endif
                                    </td>
                                    <td class="text-color table-body-index width-150">
                                        @if(!is_null($user->stakeholderCommnetForNmanager))
                                           <p>{{ $user->stakeholderCommnetForNmanager->user->name }}</p>
                                           <p>({!! getStatusFullForm($user->stakeholderCommnetForNmanager->status) !!})</p>
                                        @else
                                            <p>Default</p>
                                            <p style="color: green">(Active)</p>
                                        @endif
                                    </td>
                                    <td class="text-color table-body-index width-150"></td>
                                    <td class="text-color table-body-index"></td>
                                    <td class="text-color table-body-index width-150" ></td>

                                    <td class="text-color table-body-index width-150">
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ getUserCollectCal($user) }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{ getUserCollectCal($user) }} %</div>
                                        </div>
                                    </td>

                                    <td class="text-color table-body-index width-150">
                                        @if(!is_null($user->stakeholderEvaluation))
                                            @if($user->stakeholderEvaluation->status == 1)
                                            <span class="success-text"><i class="fas fa-check-circle"></i> Passed ({{ $user->stakeholderEvaluation->mark }} mark)</span>
                                            @else
                                            <span class="fail-text"><i class="far fa-times-circle"></i> Failed ({{ $user->stakeholderEvaluation->mark }} mark)</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>


                                    <td class="text-color table-body-index width-150">
                                        @if(!is_null($user->signupReference))
                                            {{ $user->signupReference->title }}
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
                    {{ $stakeholders->appends(['role'=>$first_active_tab->role_id,'search'=>$search,'start_date'=>$start_date,'end_date'=>$end_date,'division_id'=>$division_id,'district_id'=>$district_id,'upazila_id'=>$upazila_id,'status'=>$status,'reference_id'=>$reference_id])->links() }}
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
