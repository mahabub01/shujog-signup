@extends('core::layouts.app')
@section('title','Role List')

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

@section('content')

@include('core::inc.component')
@include('core::inc.sweetalert')

<!-- start filter modal  -->
@component('core::inc.filter')
    @slot('filter_route')
    {{Form::open(['route'=>['core.roles.filter'],'method'=>'GET'])}}
    @endslot

    @slot('filter_title')
        Filter Roles
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
                        <p>Role</p>
                        <h5>All Role <i class="fas fa-caret-down"></i> <span class="pin"><i class="fas fa-thumbtack"></i></span></h5>
                    </div>

                  </div>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                      <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{route('core.roles.create')}}">New</a>
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
                                {{Form::open(['route'=>['core.roles.filter'],'method'=>'GET'])}}
                                {{-- <i class="fas fa-search"></i> --}}
                                    <input type="text" class="form-control serch_input" id="exampleFormControlInput1" placeholder="search this list">
                                {{Form::close()}}
                            </li>

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
                                <th scope="col">Create Date</th>
                                <th scope="col">Role Name</th>
                                <th scope="col">Comments</th>
                                <th scope="col"></th>
                              </tr>
                            </thead>
                            <tbody>


                                @if(count($datas) > 0)

                                @foreach($datas as $k => $data)

                                <tr>
                                    <td class="col-serial">{{$datas->firstItem() + $k}}</td>
                                    <td class="col-serial"><input type="checkbox"></td>

                                    <td>{{date('jS F, Y', strtotime($data->created_at))}}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->comments}}</td>
                                    <td>
                                        <a class="table_icon_btn" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-sort-down"></i></a>

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">



                                            <li><a class="dropdown-item" href="{{url('admins/settings/roles/'.$data->id.'/edit')}}"><i class="fa fa-edit"></i> Edit</a></li>



                                            <li><a class="text-decoration-none"><button type="submit" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#bt5DeleteModal"
                                                data-url="{{url('admins/settings/roles/'.$data->id)}}"
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
                          {{ $datas->links() }}
                    </div>
                </div>
            </div>
        </div>
        <!--end card -->
    </div>
    </div>

</div>


@endsection
