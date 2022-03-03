@extends('core::layouts.app')
@section('title','Agent Users')
@section('content')

@include('core::inc.component')
@include('core::inc.sweetalert')

<!-- start filter modal  -->
@component('core::inc.filter')
    @slot('filter_route')
    {{ Form::open(['route'=>['agent.deployer-users.filter',$module],'method'=>'GET']) }}
    @endslot

    @slot('filter_title')
        Filter Users
    @endslot

    @slot('filter_body')

    <div class="row">
        <div class="col-md-4">
            <label class="filter-label">Search</label>
            <input type="text" class="form-control" name="search" value="{{ $search }}"/>
        </div>

        <div class="col-md-4">
            <label class="filter-label">Roles</label>
            <select class="form-control" name="role_id" style="width: 100%">
                <option value="">Select</option>
                @foreach ($roles as $role)
                    @if($role == $role_id)
                        <option value="{{ $role->id }}" selected>{{ $role->name }}</option>
                    @else
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
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
                        <h5>All Users <span class="pin"><i class="fas fa-thumbtack"></i></span></h5>
                    </div>

                  </div>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                      @auth_access('agent-admin-deployer-user-create')
                      <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('agent.deployer-users.create',$module) }}"><i class="fas fa-plus"></i> New</a>
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
                            <li>{{ $users->total() }} Items</li>
                            <li>Shorted By ID</li>

                            <li>Filterd By {{ $filter_by }}</li>

                            @if(!is_null($last_updated))
                                <li>Updated {{ $last_updated }}</li>
                            @endif
                        </ul>
                        <ul class="ms-auto d-flex">

                            {{-- <li><button class="icon_btn"><i class="fas fa-cog"></i></button></li> --}}

                            <li><a href="{{ route('agent.deployer-users.index',$module) }}"><button type="button" class="icon_btn"><i class="fas fa-redo"></i></button></a></li>
                            {{-- <li><button class="icon_btn"><i class="fas fa-chart-pie"></i></button></li> --}}
                            @auth_access('agent-admin-deployer-user-filter')
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
                                <th scope="col">Name</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Assign Role</th>
                                <th class="col-serial"></th>
                              </tr>
                            </thead>
                            <tbody>

                            @foreach ($users as $k => $user)
                              <tr>
                                <td class="col-serial">{{ $k+1 }}</td>
                                <td class="col-serial"><input type="checkbox" class="select-checkbox"></td>
                                <td class="text-color">{{ $user->name }}</td>
                                <td class="text-color"><i class="fas fa-phone-alt"></i> {{ $user->mobile }}</td>
                                <td class="text-color"><i class="fas fa-envelope"></i> {{ $user->email }}</td>
                                <td class="text-color">
                                    @if (!is_null($user->spatieRole))
                                    <i class="fas fa-user-tag"></i> {{ $user->spatieRole->name }}
                                    @else
                                         N/A
                                    @endif
                                </td>

                                <td class="text-color">
                                    @if(!is_null($user->agentAssignRole))
                                        @foreach($user->agentAssignRole as $assignRole)
                                            <span class="role-bg-color">{{ $assignRole->role->name }}, </span>
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td class="col-serial">
                                    @if($user->flag != 21 && $user->flag == 26)
                                    <div class="btn-group">
                                        <button type="button" class="table_icon_btn" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                            <i class="fas fa-sort-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-lg-end table-dropdown">

                                          @auth_access('agent-admin-deployer-user-assign-stkholder-role')
                                          <li><a href="javascript:void(0)" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="{{ $user->id }}"><i class="fas fa-user-tag"></i> Assign Stackholder Roles</a></li>
                                          @end_auth_access

                                          @auth_access('agent-admin-deployer-user-permission-edit')
                                          <li><a href="{{ url($module.'/deployer-ag-edit-permission/'.$user->id) }}" class="dropdown-item"><i class="fas fa-users-cog"></i> Edit Permission</a></li>
                                          @end_auth_access


                                          @auth_access('agent-admin-deployer-user-edit')
                                          <li><a href="{{ url($module.'/deployer-users/'.$user->id.'/edit') }}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a></li>
                                          @end_auth_access


                                          @auth_access('agent-admin-deployer-user-activation')
                                          @if($user->is_active == 0)
                                            <li><a href="{{ url($module.'/deployer-users-activation/active/'.$user->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Active</a></li>
                                          @else
                                            <li><a href="{{ url($module.'/deployer-users-activation/deactive/'.$user->id) }}" class="dropdown-item"><i class="fas fa-eye-slash"></i> De-Active</a></li>
                                          @endif
                                          @end_auth_access


                                          @auth_access('agent-admin-deployer-user-delete')
                                        <li class="padding-left-6">
                                            {{ Form::open(['route'=>['agent.deployer-users.destroy',[$module,$user->id]],'method'=>'DELETE','style'=>'display:inline']) }}
                                                <button onclick="return confirm('Are your sure Remove it?')" class="btn btn-link table-delete-button" type="submit"><i class="fas fa-trash-alt"></i> Remove</button>
                                            {{ Form::close() }}
                                        </li>
                                        @end_auth_access

                                        </ul>
                                    </div>
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
        <!--end card -->
    </div>
    </div>

</div>




<!--start modal for Assign role -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

    {{ Form::open(['route'=>['agent.deployer-users.assignRole',$module],'method'=>'POST']) }}
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Assign Stackholder Roles</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="load_roles">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    {{ Form::close() }}

      </div>
    </div>
  </div>
<!--end modal for Assign role -->

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
