@extends('core::layouts.app')
@section('title','Stakeholders Details')
@section('content')

@include('core::inc.component')
@include('core::inc.sweetalert')

<div class="content-bg-section">
    <div class="content-section">
        <div class="container-fluid margin-top-20">

            <div class="card">
                <div class="card-header">
                    <h3>Personal Information</h3>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-9">
                            <table class="table table-bordered">
                            <tr>
                                <th width="30%">Your Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>

                            <tr>
                                <th>Role</th>
                                <td>{{ $user->spatieRole->name }}</td>
                            </tr>

                            <tr>
                                <th>Mobile</th>
                                <td>{{ $user->mobile }}</td>
                            </tr>

                            <tr>
                                <th>NID</th>
                                <td>{{ $user->self_nid_number }}</td>
                            </tr>

                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $user->date_of_birth }}</td>
                            </tr>

                            <tr>
                                <th>Gender</th>
                                <td>{{ $user->gender }}</td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>

                            <tr>
                                <th>Present Address</th>
                                <td>{{ $user->self_nid_present_address }}</td>
                            </tr>

                            <tr>
                                <th>Permanent Address</th>
                                <td>{{ $user->self_permenant_address }}</td>
                            </tr>

                            </table>
                        </div>
                        <div class="col-md-3">
                            @if($thardpary_info['self_picture'] != "")
                            <img src="{{ $thardpary_info['self_picture'] }}"
                                alt="{{$user->name}}"
                                height="150px">
                            @else
                                <img src="{{asset('uploads/user_no_image.png')}}"
                                    alt="User Image"
                                    height="150px">
                            @endif

                        </div>

                </div>
            </div>

        </div>

        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h3>Business Information</h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Education Level</th>
                        <td>
                            @if(!is_null($user->education))
                                {{isJsonData($user->education->title)}}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Institution Name</th>
                        <td>
                            @if(!is_null($user->institute_name))
                                {{ $user->institute_name }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Business Area</th>
                        <td>
                            @if(!is_null($user->division))

                            Division
                            : {{ (!is_null($user->division)) ? $user->division->name : ''}}
                            <br>

                            District
                            :{{ (!is_null($user->district)) ? $user->district->name : '' }}
                            <br>

                            Upazila
                            :{{ (!is_null($user->upazila)) ? $user->upazila->name : '' }}
                            <br>

                            Union
                            :{{ (!is_null($user->upazila)) ? $user->upazila->name : '' }}
                            <br>

                            Mouza
                            :{{ (!is_null($user->upazila)) ? $user->mouza : '' }}
                            <br>
                        @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Investment</th>
                        <td>
                            @if(!is_null($user->investment))
                                {{$user->investment->title}}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Trade License Number</th>
                        <td>
                            @if($user->trade_license_number)
                                {{$user->trade_license_number}}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Equipment</th>
                        <td>
                            @if(!is_null($user->asset))
                                {{ $user->asset->title }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                </table>
            </div>

        </div>



        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h3>MFS Information</h3>
            </div>
        @php
            $mfs = json_decode($user->self_mfs, true);
            if (!is_null($mfs)) {

                if (array_key_exists('Type', $mfs)) {
                    unset($mfs['Type']);
                }

            }
        @endphp

            <div class="card-body">
                <table class="table table-bordered">

                @if(!is_null($mfs))

                    <tr>
                        <th width="30%">Bank Asia</th>
                        <td>
                            @if(!is_null($user->self_bank_asia_account))
                                {{$user->self_bank_asia_account}}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                    @foreach($mfs as $mk => $mf)

                        <tr>
                            <th>{{ucwords(str_replace('_',' ',$mk))}}</th>
                            <td> @if($mf != "") {{$mf}} @else N/A @endif</td>
                        </tr>

                    @endforeach

                @else

                    <tr>
                        <th width="30%">Bank Asia</th>
                        <td>N/A</td>
                    </tr>

                    <tr>
                        <th>Bkash</th>
                        <td>N/A</td>
                    </tr>

                    <tr>
                        <th>Nagad</th>
                        <td>N/A</td>
                    </tr>

                @endif

                </table>
            </div>

        </div>

        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h3>Guardian Information</h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Guardian Relation</th>
                        <td>
                            @if(!is_null($user->guardian_relation))
                                {{ $user->guardian_relation }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Guardian Name</th>
                        <td>
                            @if(!is_null($user->guardian_name))
                                {{ $user->guardian_name }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Mobile</th>
                        <td>
                            @if(!is_null($user->guardian_phone))
                                {{ $user->guardian_phone }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>NID</th>
                        <td>
                            @if(!is_null($user->guardian_nid_number))
                                {{ $user->guardian_nid_number }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>

                </table>
            </div>

        </div>


        <!--start Sales Information -->
        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h3>Sales Information</h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Total Sales Amount</th>
                        <td>
                            <span style="font-weight: bold">{{ number_format($total_sell_amount,2)  }} Taka</span>
                        </td>
                    </tr>

                    <tr>
                        <th>Last Sales (30 days ago)</th>
                        <td>
                            <span style="font-weight: bold">{{ number_format($total_sell_30_amount_array,2)  }} Taka</span>
                        </td>
                    </tr>

                    <tr>
                        <th>Total Order Amount</th>
                        <td>
                            <span style="font-weight: bold">{{ number_format(array_sum($totalOrderAmountArray),2) }} Taka</span>
                        </td>
                    </tr>

                    <tr>
                        <th>Last Order (30 days ago)</th>
                        <td>
                            <span style="font-weight: bold">{{ number_format(array_sum($total_30days_OrderAmountArray),2) }} Taka</span>
                        </td>
                    </tr>

                    <tr>
                        <th>Total Client</th>
                        <td>
                            <span style="font-weight: bold">{{  $clients }}</span>
                        </td>
                    </tr>

                    <tr>
                        <th>Last Client Add (45 days ago)</th>
                        <td>
                            <span style="font-weight: bold">{{  $clients_45_days }}</span>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
        <!--end Sales Information -->

    </div>
</div>

@endsection
