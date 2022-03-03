@extends('core::layouts.app')
@section('title','Project Stakeholders')
@section('content')

@include('core::inc.component')
@include('core::inc.sweetalert')


<!-- start filter modal  -->
@component('core::inc.filter')
    @slot('filter_route')
    {{Form::open(['route'=>['agent.pro-deshboard-filter',[$module,$project_id]],'method'=>'GET'])}}

    @endslot

    @slot('filter_title')
        Filter Stackholders
    @endslot

    @slot('filter_body')

    <div class="row">
        <div class="col-md-3">
            <label class="filter-label">Start Date</label>
            <input type="date" name="start_date"  class="form-control"/>
        </div>


        <div class="col-md-3">
            <label class="filter-label">End Date</label>
            <input type="date" name="end_date" class="form-control"/>
        </div>

        <div class="col-md-3">
            <label class="filter-label">Division</label>
            <select class="form-control js-example-basic-multiple full-width" name="division_id" onchange="setDistrictByDivision(this.value,{{ $project->id }})">
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



        <div class="col-md-3">
            <label class="filter-label">District</label>
            <select class="form-control js-example-basic-multiple full-width" name="district_id" id="district_id" onchange="setUpazilaByDistrict(this.value,{{ $project->id }})">
                <option value="">Choose</option>
                {{-- @if(!is_null($filter_district))
                    <option value="{{ $filter_district->id }}">{{ $filter_district->name }}</option>
                @endif --}}
            </select>
        </div>
    </div>

    <div class="row" style="margin-top: 20px;">
        <div class="col-md-3">
            <label class="filter-label">Upazila</label>
            <select class="form-control js-example-basic-multiple full-width" name="upazila_id" id="upazila_id">
                <option value="">Choose</option>
                {{-- @if(!is_null($filter_upazila))
                    <option value="{{ $filter_upazila->id }}">{{ $filter_upazila->name }}</option>
                @endif --}}
            </select>
        </div>

        @foreach($flagsWithData as $k => $search_filter)
        <div class="col-md-3">
            <label class="filter-label">{{ ucwords($k) }}</label>
            <select class="form-control js-example-basic-multiple full-width" name="user_id">
                <option value="">Choose</option>
                @foreach($all_users->where('flag',$search_filter) as $s_user)
                    <option value="{{ $s_user->id }}">{{ $s_user->name }}</option>
                @endforeach
            </select>
        </div>
        @endforeach

    </div>


    @endslot
@endcomponent
<!--end filter modal -->




<div class="content-bg-section">
    <div class="content-section">
        <div class="container-fluid margin-top-20 table-design-container" style="padding-bottom: 30px;">
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

                          <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="fas fa-filter"></i> Filter</a>
                          </li>

                        </ul>

                      </div>
                    </div>
                  </nav>





                <div class="row dashboard-padding-20" style="margin-top: 20px;">

                    <!--start here -->
                    <div class="col-md-3 col-sm-12 col-mergin-bottom">

                        <div class="project_dashboard_box" style="background: #D5F4F6;">

                            <h3 class="project_dashboard_box_title"> {{ $project->sur_name }} </h3>

                            <h4 class="project_dashboard_box_amount">
                                {{ $project->wmm_target }}
                            </h4>

                            <p class="project_dashboard_box_see_more">
                                <a href="#"
                                    class="btn btn-default" style="border-radius: 20%;">
                                    See More Details
                                </a>
                            </p>

                        </div>

                    </div>
                    <!--end here -->


                    <!--start here -->
                    <div class="col-md-3 col-sm-12 col-mergin-bottom">

                        <div class="project_dashboard_box" style="background: #FDD4CD;">

                            <h3 class="project_dashboard_box_title"> Total Order Amount </h3>

                            <h4 class="project_dashboard_box_amount"> {{ number_format($total_order_amount,2)  }} </h4>


                            <p class="project_dashboard_box_see_more">
                                <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                    See More Details
                                </a>
                            </p>

                        </div>

                    </div>

                    <!--end here -->


                    <!--start here -->
                    <div class="col-md-3 col-sm-12 col-mergin-bottom">

                        <div class="project_dashboard_box" style="background: #FFCF86;">

                            <h3 class="project_dashboard_box_title"> Total Sales Amount </h3>

                            <h4 class="project_dashboard_box_amount"> {{ number_format($total_sell_amount,2)  }} </h4>

                            <p class="project_dashboard_box_see_more">
                                <a href="#"
                                   class="btn btn-default" style="border-radius: 20%;">
                                    See More Details
                                </a>
                            </p>

                        </div>

                    </div>
                    <!--end here -->


                    <!--start here -->
                    <div class="col-md-3 col-sm-12 col-mergin-bottom">

                        <div class="project_dashboard_box" style="background: #BED8FB;">

                            <h3 class="project_dashboard_box_title"> Sales Target </h3>

                            <h4 class="project_dashboard_box_amount"> {{ number_format($project->sales_target,2) }} </>

                            <p class="project_dashboard_box_see_more">
                                <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                    See More Details
                                </a>
                            </p>

                        </div>

                    </div>
                    <!--end here -->




                </div>


                <div class="row dashboard-padding-20">

                <!--start here -->
                    <div class="col-md-3 col-sm-12 col-mergin-bottom">

                        <div class="project_dashboard_box" style="background: #E3EEFF;">

                            <h3 class="project_dashboard_box_title"> Total Registered Customers </h3>

                            <h4 class="project_dashboard_box_amount">
                                {{ $total_clients }}
                            </h4>

                            <p class="project_dashboard_box_see_more">
                                <a href="#"
                                   class="btn btn-default" style="border-radius: 20%;">
                                    See More Details
                                </a>
                            </p>

                        </div>

                    </div>
                <!--end here -->


                <!--start here -->
                <div class="col-md-3 col-sm-12 col-mergin-bottom">

                    <div class="project_dashboard_box" style="background: #E0E4FF;">

                        <h3 class="project_dashboard_box_title"> Income </h3>

                        <h4 class="project_dashboard_box_amount">
                            @php

                                $income = (float)$total_sell_amount - (float)$total_purchase_amount;

                                echo number_format($income,2);

                            @endphp
                        </h4>


                        <p class="project_dashboard_box_see_more">
                            <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                 See More Details
                            </a>
                        </p>

                    </div>

                </div>
                <!--end here -->

                <!--start here -->
                <div class="col-md-3 col-sm-12 col-mergin-bottom">

                    <div class="project_dashboard_box" style="background: #9981FF;">

                        <h3 class="project_dashboard_box_title"> Targer vs Sales </h3>

                        <h4 class="project_dashboard_box_amount">

                            @php

                                $sales_difference = (float)$project->sales_target - (float)$total_sell_amount;

                                echo number_format($sales_difference,2);

                            @endphp

                        </h4>

                        <p class="project_dashboard_box_see_more">
                            <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                See More Details
                            </a>
                        </p>

                    </div>

                </div>
                <!--end here -->


                <!--start here -->
                <div class="col-md-3 col-sm-12 col-mergin-bottom">

                    <div class="project_dashboard_box" style="background: #D5F4F6;">

                        <h3 class="project_dashboard_box_title"> Total Discount Amount </h3>

                        <h4 class="project_dashboard_box_amount">

                            {{ number_format($total_discount_amount_array,2) }}

                        </h4>

                        <p class="project_dashboard_box_see_more">
                            <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                See More Details
                            </a>
                        </p>

                    </div>

                </div>
                <!--end here -->

                </div>
            <!--start row -->
            <div class="row dashboard-padding-20">
                <div class="col-md-3 col-sm-12 col-mergin-bottom">

                    <div class="project_dashboard_box" style="background: #FFCF86;">

                        <h3 class="project_dashboard_box_title"> Net Sales Amount </h3>

                        <h4 class="project_dashboard_box_amount">
                            {{ number_format($total_sell_amount - $total_discount_amount_array,2) }}
                            {{--                    {{ count($project->users) }}--}}
                        </h4>

                        <p class="project_dashboard_box_see_more">
                            <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                See More Details
                            </a>
                        </p>

                    </div>

                </div>

            </div>
            <!--end row -->


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



    function setDistrictByDivision(id,project_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        var dataString = {division_id:id,project_id:project_id};
        $.ajax({
            type:"post",
            url:"{{url('api/dashboard-load-district-by-division')}}",
            data:dataString,
            success:function(data){
                $("#district_id").html(data);
                console.log(data);
            }
        });
    }


    function setUpazilaByDistrict(id,project_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var dataString = {district_id:id,project_id:project_id};
        $.ajax({
            type:"post",
            url:"{{url('api/dashboard-load-upazila-by-district')}}",
            data:dataString,
            success:function(data){
                $("#upazila_id").html(data);
                console.log(data);
            }
        });
    }




    function  submitSelectableExport(){
        document.getElementById("exportForm").submit();
    }


</script>
@endsection


