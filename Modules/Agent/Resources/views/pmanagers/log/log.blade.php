@extends('core::layouts.app')
@section('title', 'Consultant Log')
@section('content')

    @include('core::inc.component')
    @include('core::inc.selectable-table')



    <!-- start filter modal  -->
    @component('core::inc.filter')
        @slot('filter_route')
            {{ Form::open(['route' => ['agent.log.pmanager.filter', $module], 'method' => 'GET']) }}
        @endslot

        @slot('filter_title')
            Filter Activity Logs
        @endslot

        @slot('filter_body')
            <div class="row">

                @if (Auth::user()->flag == 23)
                    <div class="col-md-3">
                        <label class="filter-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search Name"
                            value="{{ $search }}" />
                    </div>
                @endif

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
                        </div>
                    </nav>


                    <div class="bottom_nav">
                        <div class="row">
                            <div class="col-md-12 d-flex">
                                <ul class="me-auto d-flex left">
                                    <li>{{ $logs->total() }} Items</li>
                                    <li>Shorted By ID</li>

                                    <li>Filterd By {{ $filter_by }}</li>

                                    @if (!is_null($last_updated))
                                        <li>Updated {{ $last_updated }}</li>
                                    @endif
                                </ul>

                                <div class="d-flex" style="margin-bottom: 20px;">
                                    <a href="{{ route('agent.log.consultant', $module) }}"><button type="button"
                                            class="icon_btn"><i class="fas fa-redo"></i></button></a>
                                    {{-- @auth_access('agent-mem-cons-stkholder-filter') --}}
                                    <a href="#"><button type="button" class="icon_btn" data-bs-toggle="modal"
                                            data-bs-target="#filterModal"><i class="fas fa-filter"></i></button></a>
                                    {{-- @end_auth_access --}}
                                </div>

                            </div>
                        </div>
                    </div>


                    {{ Form::open(['route' => ['agent.stackholder.export', $module], 'method' => 'POST', 'id' => 'exportForm']) }}
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

                                                    <td class="text-color table-body-index">Data has been
                                                        {{ eventStyle($log->event) }}</td>
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

                                                                    @if ($log->log_name == 'ProMngAssignProject')
                                                                        {{ proMngAssignProjectLog($col, $v, $log->event) }}
                                                                    @endif
                                                                    @if ($log->log_name == 'Project')
                                                                        {{ projectLog($col, $v, $log->event) }}
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
                                                <tr class="text-center text-danger">
                                                    <td colspan="20">No Data</td>
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
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>


@endsection

@section('bottom_script')
    <script>
        function submitSelectableExport() {
            document.getElementById("exportForm").submit();
        }
    </script>
@endsection
