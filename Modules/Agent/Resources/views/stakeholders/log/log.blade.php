@extends('core::layouts.app')
@section('title', 'Stakeholders')
@section('content')

    @include('core::inc.component')
    @include('core::inc.sweetalert')
    @include('core::inc.selectable-table')






    <!-- start filter modal  -->
    @component('core::inc.filter')
        @slot('filter_route')
            {{ Form::open(['route' => ['agent.stakeholders.filter', $module], 'method' => 'GET']) }}
        @endslot

        @slot('filter_title')
            Filter Stackholders
        @endslot

        @slot('filter_body')
            <div class="row">

                {{-- <input type="hidden" name="role" class="form-control" value="{{ $first_active_tab->role_id }}"/> --}}

                <div class="col-md-3">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search Name/mobile"
                        value="{{ $search }}" />
                </div>

                <div class="col-md-3">
                    <label class="filter-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $start_date }}" />
                </div>


                <div class="col-md-3">
                    <label class="filter-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $end_date }}" />
                </div>

            </div>
        @endslot
    @endcomponent
    <!--end filter modal -->




    <div class="content-bg-section">
        <div class="content-section">
            <div class="container-fluid table-design-container">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="" style="font-weight: bolder;color:#495057;font-size:14px">Activity
                            Log</a>
                    </li>
                </ul>

                <div class="card" style="border-radius: 0px 5px 5px 5px;border-top:none">

                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="#" style="padding: 7px 13px;"><i
                                    class="fas fa-address-card"></i></a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse driction" id="navbarSupportedContent">
                                <div class="row">
                                    <p style="margin-top: 5px;"><a class="rev-underline-subtitle"
                                            href="{{ url('agent/load-component') }}">{{ getPanelPageTitle(auth()->user()->flag) }}</a>
                                    </p>
                                    <h5>All Activity Logs <span class="pin"><i
                                                class="fas fa-thumbtack"></i></span></h5>
                                </div>

                            </div>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                                    {{-- <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-cloud-upload-alt"></i> Import</a>
                          </li> --}}

                                </ul>

                            </div>
                        </div>
                    </nav>


                    <div class="bottom_nav">
                        <div class="row">
                            <div class="col-md-12 d-flex">
                                <ul class="me-auto d-flex left">
                                    {{-- <li>{{ $stakeholders->total() }} Items</li> --}}
                                    <li>Shorted By ID</li>

                                    <li>Filterd By {{ $filter_by }}</li>

                                    {{-- @if (!is_null($last_updated))
                                    <li>Updated {{ $last_updated }}</li>
                                @endif --}}
                                </ul>

                                <div class="d-flex" style="margin-bottom: 20px;">
                                    <a href="{{ route('agent.log.consultant', $module) }}"><button type="button"
                                            class="icon_btn"><i class="fas fa-redo"></i></button></a>
                                    @auth_access('agent-mem-cons-stkholder-filter')
                                    <a href="#"><button type="button" class="icon_btn" data-bs-toggle="modal"
                                            data-bs-target="#filterModal"><i class="fas fa-filter"></i></button></a>
                                    @end_auth_access
                                </div>

                            </div>
                        </div>
                    </div>


                    {{ Form::open(['route' => ['agent.stackholder.export', $module], 'method' => 'POST', 'id' => 'exportForm']) }}
                    {{-- <input type="hidden" value="{{$first_active_tab->role_id}}" name="role_id"/> --}}
                    <div class="table_section">
                        <div class="row">
                            <div class="col-md-12">

                                <div style="overflow-x: auto;margin-bottom:10px;">
                                    <table class="table" id="selectable-table">
                                        <thead>
                                            <tr>

                                                <th class="col-serial table-header-index">No</th>
                                                <th class="table-header-index">Date</th>
                                                <th class="table-header-index">Event</th>
                                                <th class="table-header-index">Who</th>
                                                <th class="table-header-index">New</th>
                                                <th class="table-header-index">Old</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($logs as $k => $log)
                                                <tr class="table-row-index">


                                                    <td class="col-serial table-body-index">{{ $k + 1 }} {{ $log->log_name }}</td>
                                                    <td class="text-color table-body-index">
                                                        {{ date('d-m-Y h:i A', strtotime($log->created_at)) }}</td>

                                                    <td class="text-color table-body-index">Data has been {{ $log->event }}</td>
                                                    <td class="text-color table-body-index">
                                                        @if (issetUser($log->causer_id))
                                                            <ul class="no-margin no-padding">
                                                                <li><span class="fw-bold">Name :</span>
                                                                    {{ $log->user['name'] }}</li>
                                                                <li><span class="fw-bold">Email :</span>
                                                                    {{ $log->user['email'] }}</li>
                                                            </ul>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if (isset($log->properties['attributes']))
                                                            <ul class="no-margin no-padding">
                                                                @foreach ($log->properties['attributes'] as $col => $v)

                                                                    @if ($log->log_name == 'Users')
                                                                        {{ userLogNew($col, $v, $log->event) }}
                                                                    @endif

                                                                    @if ($log->log_name == 'Role')
                                                                        {{ roleLog($col, $v, $log->event) }}
                                                                    @endif

                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (isset($log->properties['old']))
                                                        <ul class="no-margin no-padding">

                                                            @foreach ($log->properties['old'] as $col => $v)
                                                                @if ($log->log_name == 'Users')
                                                                    {{ userLogOld($col, $v, $log->event) }}
                                                                @endif


                                                            @endforeach
                                                        </ul>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td>No Data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}



                    <div class="container-fluid">
                        {{-- {{ $logs->appends(['search'=>$search,'start_date'=>$start_date,'end_date'=>$end_date])->links() }} --}}
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

            let selectable_field = [{
                    index: 3,
                    name: "Date"
                },
                {
                    index: 4,
                    name: "Status"
                },
                {
                    index: 5,
                    name: "Name"
                },
                {
                    index: 6,
                    name: "Phone"
                },
                {
                    index: 7,
                    name: "Ongoing_project"
                },
                {
                    index: 8,
                    name: "Consultant"
                },
                {
                    index: 9,
                    name: "Trainer"
                },
                {
                    index: 10,
                    name: "Deployers"
                },
                {
                    index: 11,
                    name: "Network_manager"
                },
                {
                    index: 12,
                    name: "Project_manager"
                },
                {
                    index: 13,
                    name: "Mood"
                },
                {
                    index: 14,
                    name: "Schedule_time"
                },
                {
                    index: 15,
                    name: "User_info_status"
                },
                {
                    index: 16,
                    name: "Evaluation_status"
                },
                {
                    index: 17,
                    name: "Sign_up_reference"
                },
            ];
            getSelectableField(selectable_field)
        });



        function selects(obj) {
            var ele = document.getElementsByClassName('select-checkbox');
            if (obj.checked) {
                for (var i = 0; i < ele.length; i++) {
                    if (ele[i].type == 'checkbox') {
                        ele[i].checked = true;
                    }
                }
            } else {
                for (var i = 0; i < ele.length; i++) {
                    if (ele[i].type == 'checkbox') {
                        ele[i].checked = false;
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


            var dataString = {
                division_id: id
            };
            $.ajax({
                type: "post",
                url: "{{ url('api/load-district-by-division') }}",
                data: dataString,
                success: function(data) {
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

            var dataString = {
                district_id: id
            };
            $.ajax({
                type: "post",
                url: "{{ url('api/load-upazila-by-district') }}",
                data: dataString,
                success: function(data) {
                    $("#upazila_id").html(data);
                }
            });
        }




        function submitSelectableExport() {
            document.getElementById("exportForm").submit();
        }
    </script>
@endsection
