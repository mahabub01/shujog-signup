@extends('core::layouts.app')
@section('title','Display')
@section('content')

@include('core::inc.component')

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
                        <p>System Admin</p>
                        <h5>All Modules <i class="fas fa-caret-down"></i> <span class="pin"><i class="fas fa-thumbtack"></i></span></h5>
                    </div>

                  </div>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                      <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('demo.create') }}">New</a>
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
                                <form class="d-flex" action="">
                                    <!-- <i class="fas fa-search"></i> -->
                                    <input type="text" class="form-control serch_input" id="exampleFormControlInput1" placeholder="search this list">
                                </form>
                            </li>
                            <li><button class="icon_btn"><i class="fas fa-cog"></i></button></li>
                            <li><button class="icon_btn"><i class="far fa-calendar-alt"></i></button></li>
                            <li><button class="icon_btn"><i class="fas fa-redo"></i></button></li>
                            <li><button class="icon_btn"><i class="fas fa-chart-pie"></i></button></li>
                            <li><button class="icon_btn"><i class="fas fa-pencil-alt"></i></button></li>
                            <li><button class="icon_btn"><i class="fa fa-filter"></i></button></li>


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
                                <th scope="col">Name</th>
                                <th scope="col">Acount Name</th>
                                <th scope="col">Title</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Email</th>
                                <th scope="col">Owner First Name</th>
                                <th scope="col">Owner Last Name</th>
                                <th scope="col"></th>
                              </tr>
                            </thead>
                            <tbody>

                              @for($i = 0;$i <= 200;$i++)
                                <tr>
                                    <td class="col-serial">{{ $i }}</td>
                                    <td class="col-serial"><input type="checkbox"></td>
                                    <td class="text-color">Groff Minor (Sample)</td>
                                    <td class="text-color">Global Media ( Sample )</td>
                                    <td>President</td>
                                    <td class="text-color"><i class="fas fa-phone-alt"></i> 0195288888</td>
                                    <td class="text-color">info@gmail.com</td>
                                    <td>Monjurul</td>
                                    <td>Hassan</td>
                                    <td><button class="table_icon_btn"><i class="fas fa-sort-down"></i></button></td>
                                </tr>
                              @endfor


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
