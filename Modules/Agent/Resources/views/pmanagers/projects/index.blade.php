@extends('core::layouts.app')
@section('title','Agent Projects')
@section('content')

@include('core::inc.component')
@include('core::inc.sweetalert')

<!-- start filter modal  -->
@component('core::inc.filter')
    @slot('filter_route')
    {{ Form::open(['route'=>['agent.pmg-projects.filter',$module],'method'=>'GET']) }}
    @endslot

    @slot('filter_title')
        Filter Projects
    @endslot

    @slot('filter_body')

    <div class="row">

        <div class="col-md-4">
            <label class="filter-label">Search</label>
            <input type="text" class="form-control" name="search" value="{{ $search }}"/>
        </div>

        <div class="col-md-4">
            <label class="filter-label">Start Time</label>
            <input type="date" class="form-control" name="start_time" value="{{ $start_time }}"/>
        </div>

        <div class="col-md-4">
            <label class="filter-label">End Time</label>
            <input type="date" class="form-control" name="end_time" value="{{ $end_time }}"/>
        </div>

    </div>
    @endslot
@endcomponent
<!--end filter modal -->




<div class="content-bg-section">
    <div class="content-section">

    <div class="container-fluid  table-design-container">
        <!--start card -->
        <div class="card">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                  <a class="navbar-brand" href="#" style="padding: 7px 13px;"><i class="fas fa-address-card"></i></a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse driction" id="navbarSupportedContent">
                    <div class="row">
                        <p style="margin-top: 5px;"><a href="{{ url('agent/load-component') }}">{{ getPanelPageTitle(auth()->user()->flag) }}</a></p>
                        <h5>All Projects <span class="pin"><i class="fas fa-thumbtack"></i></span></h5>
                    </div>

                  </div>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                      @auth_access('agent-admin-pmanager-project-create')
                      <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('agent.pmg-projects.create',$module) }}"><i class="fas fa-plus"></i> New</a>
                      </li>
                      @end_auth_access

                      {{-- <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-cloud-upload-alt"></i> Import</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#"><i class="far fa-file-excel"></i> Export</a>
                      </li> --}}

                    </ul>

                  </div>
                </div>
              </nav>

            <div class="bottom_nav">
                <div class="row">
                    <div class="col-md-12 d-flex">
                        <ul class="me-auto d-flex left">
                            <li>{{ $projects->total() }} Items</li>
                            <li>Shorted By ID</li>

                            <li>Filterd By {{ $filter_by }}</li>

                            @if(!is_null($last_updated))
                                <li>Updated {{ $last_updated }}</li>
                            @endif
                        </ul>
                        <ul class="ms-auto d-flex">

                            {{-- <li><button class="icon_btn"><i class="fas fa-cog"></i></button></li> --}}

                            <li><a href="{{ route('agent.pmg-projects.index',$module) }}"><button type="button" class="icon_btn"><i class="fas fa-redo"></i></button></a></li>
                            {{-- <li><button class="icon_btn"><i class="fas fa-chart-pie"></i></button></li> --}}
                            @auth_access('agent-admin-pmanager-project-filter')
                            <li><button type="button" class="icon_btn" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="fas fa-filter"></i></button></li>
                            @end_auth_access

                        </ul>
                    </div>
                </div>
            </div>


            <div class="table_section">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                              <tr>
                                <th class="col-serial"></th>
                                <th class="col-serial"><input type="checkbox" onchange="selects(this)"></th>
                                <th scope="col">Project Name</th>
                                <th scope="col">Start Time</th>
                                <th scope="col">End Time</th>
                                <th scope="col">Extension Time</th>
                                <th scope="col">Target</th>
                                <th scope="col">Assign</th>
                                <th scope="col">Unassign</th>
                                <th scope="col">Customer Served</th>
                                <th scope="col">Sales Target</th>
                                <th scope="col">Description</th>
                                <th class="col-serial"></th>
                              </tr>
                            </thead>
                            <tbody>

                            @foreach ($projects as $k => $project)
                              <tr>
                                <td class="col-serial">{{ $k+1 }}</td>
                                <td class="col-serial"><input type="checkbox" class="select-checkbox"></td>
                                <td class="text-color">{{ $project->name }}</td>
                                <td class="text-color">{{ date('d-m-Y',strtotime($project->start_time)) }}</td>
                                <td class="text-color">{{ date('d-m-Y',strtotime($project->end_time)) }}</td>
                                <td class="text-color">{{ date('d-m-Y',strtotime($project->extention_time)) }}</td>
                                <td class="text-color">{{ $project->wmm_target }}</td>
                                <td class="text-color">
                                    {{ count($project->assign_pro) }}
                                </td>
                                <td class="text-color">
                                    @if($project->wmm_target != "" | $project->wmm_target != 0)
                                        {{ $project->wmm_target - count($project->assign_pro) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="text-color">{{ $project->customer_served }}</td>
                                <td class="text-color">{{ $project->sales_target }}</td>
                                <td class="text-color">{{ $project->description }}</td>

                                <td class="col-serial">

                                    <div class="btn-group">
                                        <button type="button" class="table_icon_btn" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                            <i class="fas fa-sort-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-lg-end table-dropdown">


                                          @auth_access('agent-admin-pmanager-user-edit')
                                          <li><a href="{{ url($module.'/pmg-projects/'.$project->id.'/edit') }}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a></li>
                                          @end_auth_access


                                          @auth_access('agent-admin-pmanager-project-activation')
                                          @if($project->is_active == 0)
                                            <li><a href="{{ url($module.'/pmg-projects-activation/active/'.$project->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Active</a></li>
                                          @else
                                            <li><a href="{{ url($module.'/pmg-projects-activation/deactive/'.$project->id) }}" class="dropdown-item"><i class="fas fa-eye-slash"></i> De-Active</a></li>
                                          @endif
                                          @end_auth_access

                                        <li><a href="{{ url($module.'/pmg-projects-stakeholders/'.$project->id) }}" class="dropdown-item"><i class="fas fa-users"></i> Stakeholders</a></li>


                                        <li><a href="{{ url($module.'/pmg-projects-dashboard/'.$project->id) }}" class="dropdown-item"><i class="fas fa-chart-line"></i> Dashboard</a></li>


                                        @auth_access('agent-admin-pmanager-project-delete')
                                        <li class="padding-left-6">
                                            {{ Form::open(['route'=>['agent.pmg-projects.destroy',[$module,$project->id]],'method'=>'DELETE','style'=>'display:inline']) }}
                                                <button onclick="return confirm('Are your sure Remove it?')" class="btn btn-link table-delete-button" type="submit"><i class="fas fa-trash-alt"></i> Remove</button>
                                            {{ Form::close() }}
                                        </li>
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
        <!--end card -->
    </div>
    </div>

</div>



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
        $('.js-example-basic-single').select2();
    });

    var exampleModal = document.getElementById('exampleModal')
    exampleModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var button = event.relatedTarget
    // Extract info from data-bs-* attributes
    var user_id = button.getAttribute('data-id')
    //alert(user_id);
    $("#user_id").attr('value',user_id);

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
     });

     var dataString = {user_id:user_id};
        $.ajax({
            type:"post",
            url:"{{route('agent.loadroles')}}",
            data:dataString,
            beforeSend:function(){
                $("#load_roles").html('<div class="loader"></div>');
            },
            success:function(data){
                $("#load_roles").html(data);
            }
        });

    })
</script>

@endsection

@endsection
