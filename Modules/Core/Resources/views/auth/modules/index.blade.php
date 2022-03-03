@extends('core::layouts.app')
@section('title','Module List')
@section('content')

@include('core::inc.component')
@include('core::inc.sweetalert')

<!-- start filter modal  -->
@component('core::inc.filter')
    @slot('filter_route')
    {{Form::open(['route'=>['core.modules.filter'],'method'=>'GET'])}}

    @endslot

    @slot('filter_title')
        Filter Modules
    @endslot

    @slot('filter_body')
    <label class="filter-label">Search</label>
    <input type="text" class="form-control" name="search" />

    @endslot
@endcomponent
<!--end filter modal -->

<div class="content-bg-section">
    <div class="content-section">

    <div class="container-fluid margin-top-20 table-design-container">
        <!--start card -->
        <div class="card">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                  <a class="navbar-brand" href="#"><i class="fas fa-address-card"></i></a>
                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse driction" id="navbarSupportedContent">
                    <div class="row">
                        <p>Module</p>
                        <h5>All Module <i class="fas fa-caret-down"></i> <span class="pin"><i class="fas fa-thumbtack"></i></span></h5>
                    </div>

                  </div>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                      <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{route('core.modules.create')}}">New</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Import</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Export</a>
                      </li>


                    </ul>

                  </div>
                </div>
              </nav>

            <div class="bottom_nav">
                <div class="row">
                    <div class="col-md-12 d-flex">
                        <ul class="me-auto d-flex left">
                            <li>7 Items</li>
                            <li>Shorted By Name</li>
                            <li>Filterd By All Contact</li>
                            <li>Updated a minite ago</li>
                        </ul>
                        <ul class="ms-auto d-flex">
                            <li>
                                {{Form::open(['route'=>['core.modules.filter'],'method'=>'GET'])}}
                                {{-- <i class="fas fa-search"></i> --}}
                                    <input type="text" class="form-control serch_input" id="exampleFormControlInput1" placeholder="search this list">
                                {{Form::close()}}
                            <li><button class="icon_btn"><i class="fas fa-redo"></i></button></li>
                            <li><button class="icon_btn" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="fa fa-filter"></i></button></li>


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
                                <th class="col-serial"><input type="checkbox"></th>
                                <th scope="col">Icons</th>
                                <th scope="col">Create Date</th>
                                <th scope="col">Module Name</th>
                                <th scope="col">Action</th>
                                <th scope="col">Action URL</th>
                                <th scope="col">Fontawesome Icon</th>
                                <th scope="col"></th>
                              </tr>
                            </thead>
                            <tbody>


                                @if(count($datas) > 0)

                                @foreach($datas as $k => $data)

                                <tr>
                                    <td class="col-serial">{{$datas->firstItem() + $k}}</td>
                                    <td class="col-serial"><input type="checkbox"></td>
                                    <td class="text-color">
                                        @if($data->upload_icon != "")
                                        <img src="{{asset('uploads/module-icon/'.$data->upload_icon)}}" alt="Module Icon" height="30px;">
                                    @else
                                        <img src="{{asset('assets/backend/images/design/no_images.png')}}" alt="Module Icon" height="30px;">
                                    @endif
                                    </td>
                                    <td class="text-color">{{date('jS F, Y', strtotime($data->created_at))}}</td>
                                    <td>{{$data->title}}</td>
                                    <td class="text-color">{{$data->action}}</td>
                                    <td class="text-color">{{$data->action_type}}</td>
                                    <td>{{$data->icons}}</td>
                                    <td>
                                        <a class="table_icon_btn" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-sort-down"></i></a>

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li>
                                                @if($data->is_active == 1)
                                                    <a href="{{url('admins/settings/modules/status/'.$data->id)}}" class="text-decoration-none"><button type="button" class="btn-view dropdown-item"><i class="fa fa-eye"></i> Active</button></a>
                                                @else
                                                    <a href="{{url('admins/settings/modules/status/'.$data->id)}}" class="text-decoration-none"><button type="button" class="btn-view dropdown-item"><i class="fas fa-eye-slash"></i> In-active</button></a>
                                                @endif
                                            </li>


                                            <li><a class="dropdown-item" href="{{url('admins/settings/modules/'.$data->id.'/edit')}}"><i class="fa fa-edit"></i> Edit</a></li>



                                            <li><a class="text-decoration-none"><button type="submit" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#bt5DeleteModal"
                                                data-url="{{url('admins/settings/modules/'.$data->id)}}"
                                                data-altxt="Module"><i class="fa fa-trash"></i> Delete</button></a>
                                            </li>
                                          </ul>
                                    </td>


                                </tr>

                                @endforeach

                                @else
                                    <tr>
                                        <td colspan="6" align="center">Data Not Found</td>
                                    </tr>
                                @endif


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


@endsection
