@extends('core::layouts.app')
@section('title','Edit Stakeholders Information')

@section('top_script')
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 31px;
        }

        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 36px;
            user-select: none;
            -webkit-user-select: none;
        }
    </style>
@endsection

@section('content')
@include('core::inc.component')
@include('core::inc.sweetalert')

<div class="content-bg-section">
    <div class="content-section">
        <div class="container-fluid margin-top-20">

            {{ Form::open(['route'=>['agent.stakeholders.update',$module,$id],'method'=>'PUT','files'=>true]) }}

            <div class="card">
                <div class="card-header">
                    <h3>Personal Information</h3>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-9">
                            <table class="table table-bordered">
                            <tr>
                                <th width="30%">Your Name<span style="color:red">*</span></th>
                                <td>
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control"/>
                                </td>
                            </tr>

                            <tr>
                                <th>Role<span style="color:red">*</span></th>
                                <td>
                                    <select name="role_id" class="form-control js-example-basic-single">
                                        @foreach ($roles as $role)
                                            @if($user->spatie_role_id == $role->id)
                                                <option value="{{ $role->id }}" selected>{{ $role->name }}</option>
                                            @else
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th>Mobile<span style="color:red">*</span></th>
                                <td>
                                    <input type="text" name="mobile" value="{{ $user->mobile }}" class="form-control"/>
                                </td>
                            </tr>

                            <tr>
                                <th>NID</th>
                                <td>
                                    <input type="text" name="self_nid_number" value="{{ $user->self_nid_number }}" class="form-control"/>
                                </td>
                            </tr>

                            <tr>
                                <th>Date of Birth</th>
                                <td>
                                    <input type="text" name="date_of_birth" value="{{ $user->date_of_birth }}" class="form-control"/>
                                </td>
                            </tr>

                            <tr>
                                <th>Gender<span style="color:red">*</span></th>
                                <td>
                                    <input type="text" name="gender" value="{{ $user->gender }}" class="form-control"/>
                                </td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>
                                    <input type="text" name="email" value="{{ $user->email }}" class="form-control"/>
                                </td>
                            </tr>

                            <tr>
                                <th>Present Address</th>
                                <td>
                                    <textarea class="form-control" name="self_nid_present_address">{{ $user->self_nid_present_address }}</textarea>
                                </td>
                            </tr>

                            <tr>
                                <th>Permanent Address</th>
                                <td>
                                    <textarea class="form-control" name="self_permenant_address">{{ $user->self_permenant_address }}</textarea>
                                </td>
                            </tr>

                            </table>
                        </div>
                        <div class="col-md-3">

                            {{-- thardpary_info --}}

                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
                                            <input type="file" name="profile_picture" class="form-control"><br/>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="card" style="margin-top: 10px;">
                                @if($thardpary_info['self_nid_card_front_image'] != "")
                                <div class="card-header">
                                    <p>NID Front End <a href="{{ $thardpary_info['self_nid_card_front_image'] }}" target="_blank" style="float: right;"><i class="fas fa-images"></i> View</a></p>
                                </div>


                                <img src="{{ $thardpary_info['self_nid_card_front_image'] }}"
                                    alt="{{$user->name}}"
                                    width="100%" height="150px">
                                @else
                                <div class="card-header">
                                    <p>NID Front End</p>
                                </div>
                                    <img src="{{asset('uploads/No-image-found.jpg')}}"
                                        alt="User Image"
                                        height="150px">
                                @endif
                            </div>



                            <div class="card" style="margin-top: 10px;">

                                @if($thardpary_info['self_nid_card_back_image'] != "")

                                <div class="card-header">
                                    <p>NID Front End <a href="{{ $thardpary_info['self_nid_card_back_image'] }}" target="_blank" style="float: right"><i class="fas fa-images"></i> View</a></p>
                                </div>

                                <img src="{{ $thardpary_info['self_nid_card_back_image'] }}"
                                    alt="{{$user->name}}"
                                    width="100%" height="150px">
                                @else
                                <div class="card-header">
                                    <p>NID Back End</p>
                                </div>

                                    <img src="{{asset('uploads/No-image-found.jpg')}}"
                                        alt="User Image"
                                        height="150px">
                                @endif

                            </div>


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
                            <select name="education_requirement_id" class="form-control js-example-basic-single">
                                @foreach ($educations as $education)
                                    @if($user->education_requirement_id == $education->id)
                                        <option value="{{ $education->id }}" selected>{{ $education->title }}</option>
                                    @else
                                        <option value="{{ $education->id }}">{{ $education->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>Institution Name</th>
                        <td>
                            <input type="text" name="institute_name" value="{{ $user->institute_name }}" class="form-control"/>

                        </td>
                    </tr>

                    <tr>
                        <th>Business Area</th>
                        <td>

                            <label style="font-weight: 600;padding-bottom:5px;">Division<span style="color:red">*</span></label>
                            <select name="division_id" class="form-control js-example-basic-single" onchange="setDistrictByDivision(this.value)">
                                @foreach ($divisions as $division)
                                    @if($user->division_id == $division->id)
                                        <option value="{{ $division->id }}" selected>{{ $division->name }}</option>
                                    @else
                                        <option value="{{ $division->id }}">{{ $division->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <br>


                            <label style="font-weight: 600;padding-bottom:5px;margin-top:10px;">District<span style="color:red">*</span></label>
                            <select name="district_id" class="form-control js-example-basic-single" id="district_id" onchange="setUpazilaByDistrict(this.value)">
                                @foreach ($districts as $district)
                                    @if($user->district_id == $district->id)
                                        <option value="{{ $district->id }}" selected>{{ $district->name }}</option>
                                    @else
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <br>



                            <label style="font-weight: 600;padding-bottom:5px;margin-top:10px;">Upazila<span style="color:red">*</span></label>
                            <select name="upazila_id" class="form-control js-example-basic-single" id="upazila_id" onchange="setUnionByUpazila(this.value)">
                                @foreach ($upazilas as $upazila)
                                    @if($user->upazila_id == $upazila->id)
                                        <option value="{{ $upazila->id }}" selected>{{ $upazila->name }}</option>
                                    @else
                                        <option value="{{ $upazila->id }}">{{ $upazila->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <br>



                            <label style="font-weight: 600;padding-bottom:5px;margin-top:10px;">Union</label>
                            <select name="union_id" class="form-control js-example-basic-single" id="union_id" onchange="setVillageByUnion(this.value)">
                                <option value="" >Choose</option>
                                <option value="others" >Others</option>
                                @foreach ($unions as $union)
                                    @if($user->union_id == $union->id)
                                        <option value="{{ $union->id }}" selected>{{ $union->name }}</option>
                                    @else
                                        <option value="{{ $union->id }}">{{ $union->name }}</option>
                                    @endif
                                @endforeach
                            </select>


                            <input id="union_name" type="text" name="union_name" class="form-control display-hidden" style="margin-top: 20px;" placeholder="Enter Your Union Name"/>



                            <label style="font-weight: 600;padding-bottom:5px;margin-top:10px;">Villages</label>
                            <select name="village_id" class="form-control js-example-basic-single" id="village_id" onchange="checkOthersOption(this.value)">
                                <option value="">Choose</option>
                                <option value="others">Others</option>
                                @foreach ($villages as $village)
                                    @if($user->village_id == $village->id)
                                        <option value="{{ $village->id }}" selected>{{ $village->name }}</option>
                                    @else
                                        <option value="{{ $village->id }}">{{ $village->name }}</option>
                                    @endif
                                @endforeach
                            </select>



                            <input id="village_name" type="text" name="village_name" class="form-control display-hidden" style="margin-top: 20px;" placeholder="Enter Your Union Name"/>


                        </td>
                    </tr>

                    <tr>
                        <th>Investment</th>
                        <td>
                            <select name="investment_requirement_id" class="form-control js-example-basic-single">
                                @foreach ($investments as $investment)
                                    @if($user->investment_requirement_id == $investment->id)
                                        <option value="{{ $investment->id }}" selected>{{ $investment->title }}</option>
                                    @else
                                        <option value="{{ $investment->id }}">{{ $investment->title }}</option>
                                    @endif
                                @endforeach
                            </select>

                        </td>
                    </tr>

                    <tr>
                        <th>Trade License Number</th>
                        <td>
                            <input type="text" name="trade_license_number" value="{{ $user->trade_license_number }}" class="form-control"/>
                        </td>
                    </tr>

                    <tr>
                        <th>Equipment</th>
                        <td>
                            <select name="asset_availabilitiey_id" class="form-control js-example-basic-single">
                                @foreach ($assets as $asset)
                                    @if($user->asset_availabilitiey_id == $asset->id)
                                        <option value="{{ $asset->id }}" selected>{{ $asset->title }}</option>
                                    @else
                                        <option value="{{ $asset->id }}">{{ $asset->title }}</option>
                                    @endif
                                @endforeach
                            </select>
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
                            <input type="text" name="self_bank_asia_account" value="{{ $user->self_bank_asia_account }}" class="form-control"/>
                        </td>
                    </tr>

                    @foreach($mfs as $mk => $mf)

                        <tr>
                            <th>{{ucwords(str_replace('_',' ',$mk))}}</th>
                            <td>
                                <input type="text" name="{{$mk}}" value="{{$mf}}" class="form-control"/>
                            </td>
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
                            <input type="text" name="guardian_relation" value="{{ $user->guardian_relation }}" class="form-control"/>
                        </td>
                    </tr>

                    <tr>
                        <th>Guardian Name</th>
                        <td>
                            <input type="text" name="guardian_name" value="{{ $user->guardian_name }}" class="form-control"/>
                        </td>
                    </tr>

                    <tr>
                        <th>Mobile</th>
                        <td>
                            <input type="text" name="guardian_phone" value="{{ $user->guardian_phone }}" class="form-control"/>
                        </td>
                    </tr>

                    <tr>
                        <th>NID</th>
                        <td>
                            <input type="text" name="guardian_nid_number" value="{{ $user->guardian_nid_number }}" class="form-control"/>
                        </td>
                    </tr>

                </table>


            <div class="row">
                <div class="col-md-12">
                    <button class="theme-button btn-block"><i class="fas fa-user-edit"></i> Update information</button>
                </div>
            </div>

        </div>

        </div>


        {{ Form::close() }}



    </div>
</div>

@endsection


@section('bottom_script')
<script>

    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });

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
                console.log(data);
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



    function setUnionByUpazila(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var dataString = {upazila_id:id};
        $.ajax({
            type:"post",
            url:"{{url('api/load-union-by-upazila')}}",
            data:dataString,
            success:function(data){
                $("#union_id").html(data);
              //  $("#union_id").append('<option value="others"></option>');
            }
        });
    }


    function setVillageByUnion(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        if(id == "others"){
            $("#union_name").removeClass('display-hidden');
        }else{
            $("#union_name").addClass('display-hidden');
        }

        var dataString = {union_id:id};
        $.ajax({
            type:"post",
            url:"{{url('api/load-village-by-union')}}",
            data:dataString,
            success:function(data){
                $("#village_id").html(data);
              //  $("#union_id").append('<option value="others"></option>');
            }
        });
    }


    function checkOthersOption(id){
        if(id == "others"){
            $("#village_name").removeClass('display-hidden');
        }else{
            $("#village_name").addClass('display-hidden');
        }
    }

</script>
@endsection
