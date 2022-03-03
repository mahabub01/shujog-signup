@extends('core::layouts.app')
@section('title','Project Edit')

@section('top_script')
    <style>
        #changeImageDesktop{
            display: block;
        }
    </style>
@endsection


@section('content')

@include('core::inc.sweetalert')

{{Form::open(['route'=>['agent.pmg-projects.update',[$module,$project->id]],'method'=>'PUT','files'=>true])}}

    <!--start content  section-->
   <div class="content-header sticky-top">
        <div class="container-fluid">
           <h2 class="me-auto mb-2 mb-lg-0">Update Project</h2>
            <div class="d-flex">
                <a href="{{route('agent.pmg-projects.index',$module)}}"><button type="button" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</button></a>
                <button type="submit" class="btn btn-info"><i class="far fa-save"></i> Save</button>
            </div>
        </div>
    </div>


        <div class="form-section">

            <div class="container-fluid">

                <h3 class="form-subtitle">Project Information</h3>

                <div class="row">
                    <div class="col-md-6 col-lg-5">

                        <div class="mb-3 row form-row">
                            <label for="inputTitle" class="col-sm-4 col-form-label text-end">Project Name<span class="mandatory">*</span></label>
                            <div class="col-sm-8">
                            <input type="text" name="name" placeholder="Write your name" class="form-control" value="{{ $project->name }}" id="inputTitle">
                            </div>
                        </div>


                        <div class="mb-3 row form-row">
                            <label for="start_time" class="col-sm-4 col-form-label text-end">Start Time<span class="mandatory">*</span></label>
                            <div class="col-sm-8">
                            <input type="date" name="start_time"  class="form-control" id="start_time" value="{{ date('Y-m-d',strtotime($project->start_date)) }}">
                            </div>
                        </div>



                        <div class="mb-3 row form-row">
                            <label for="extention_time" class="col-sm-4 col-form-label text-end">Extension Time</label>
                            <div class="col-sm-8">
                            <input type="date" name="extention_time"  class="form-control" id="extention_time" value="{{ date('Y-m-d',strtotime($project->extention_time)) }}">
                            </div>
                        </div>



                        <div class="mb-3 row form-row">
                            <label for="customer_served" class="col-sm-4 col-form-label text-end">Customer Served</label>
                            <div class="col-sm-8">
                            <input type="number" name="customer_served"  class="form-control" id="customer_served" value="{{ $project->customer_served }}">
                            </div>
                        </div>


                        <div class="mb-3 row form-row">
                            <label for="division_level" class="col-sm-4 col-form-label text-end">Division<span class="mandatory">*</span></label>
                            <div class="col-sm-8">

                            <select id="division_level" class="form-control js-example-basic-multiple setDistrictByUpazilaArray" name="division_id[]" multiple="multiple">
                                <option value="">Choose</option>
                                @foreach($divisions as $divison)
                                    @if(in_array($divison->id,$selected_division))
                                        <option value="{{ $divison->id }}" selected>{{ $divison->name }}</option>
                                    @else
                                        <option value="{{ $divison->id }}">{{ $divison->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            </div>
                        </div>


                        <div class="mb-3 row form-row">
                            <label for="description_level" class="col-sm-4 col-form-label text-end">Description</label>
                            <div class="col-sm-8">
                                <textarea name="description"  class="form-control" id="description_level">{{ $project->description }}</textarea>
                            </div>
                        </div>


                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 row form-row">
                            <label for="sur_name" class="col-sm-4 col-form-label text-end">Sur Name<span class="mandatory">*</span></label>
                            <div class="col-sm-8">
                            <input type="text" name="sur_name" placeholder="Write Sur Name" class="form-control" id="sur_name" value="{{ $project->sur_name }}">
                            </div>
                        </div>


                        <div class="mb-3 row form-row">
                            <label for="end_time" class="col-sm-4 col-form-label text-end">End Time<span class="mandatory">*</span></label>
                            <div class="col-sm-8">
                            <input type="date" name="end_time"  class="form-control" id="end_time" value="{{ date('Y-m-d',strtotime($project->end_date)) }}">
                            </div>
                        </div>



                        <div class="mb-3 row form-row">
                            <label for="wmm_target" class="col-sm-4 col-form-label text-end">Number Of Kollany/Shukormi</label>
                            <div class="col-sm-8">
                            <input type="number" name="wmm_target"  class="form-control" id="wmm_target" value="{{ $project->wmm_target }}">
                            </div>
                        </div>



                        <div class="mb-3 row form-row">
                            <label for="sales_target" class="col-sm-4 col-form-label text-end">Sales Target</label>
                            <div class="col-sm-8">
                            <input type="number" name="sales_target"  class="form-control" id="sales_target" value="{{ $project->sales_target }}">
                            </div>
                        </div>


                        <div class="mb-3 row form-row">
                            <label for="district_level" class="col-sm-4 col-form-label text-end">District<span class="mandatory">*</span></label>
                            <div class="col-sm-8">
                            <select id="district_level" class="form-control js-example-basic-multiple setUpazilaByDistrictArray" name="district_id[]" multiple="multiple">
                                <option value="">Choose</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id.':'.$district->division_id }}" selected>{{ $district->name }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>


                        <div class="mb-3 row form-row">
                            <label for="upazila_level" class="col-sm-4 col-form-label text-end">Upazila<span class="mandatory">*</span></label>
                            <div class="col-sm-8">
                            <select id="upazila_level" class="form-control js-example-basic-multiple" name="upazila_id[]" multiple="multiple">
                                <option value="">Choose</option>
                                @foreach($upazilas as $upazila)
                                    <option value="{{ $upazila->id.':'.$upazila->district_id }}" selected>{{ $upazila->name }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>


                    </div>

                </div>


            </div>
        </div>


<!--end content  section-->
{{Form::close()}}


@endsection

@section('bottom_script')
<script>

    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });

    $('.setUpazilaByDistrictArray').change(function (e) {

    e.preventDefault();

    let district_array = $(this).val();

    $.ajax({
        headers: {
            'X-CSRF-Token': "{{ csrf_token() }}"
        },
        type: "post",
        url: "{{ url('load-upazila-by-district') }}",
        data: {
            district_id: district_array
        },
        success: function (data) {
            console.log(data);
            $("#upazila_level").html(data);
        }
    });

});





$('.setDistrictByUpazilaArray').change(function (e) {

e.preventDefault();

let district_array = $(this).val();


$.ajax({
    headers: {
        'X-CSRF-Token': "{{ csrf_token() }}"
    },
    type: "post",
    url: "{{ url('load-district-by-division') }}",
    data: {
        division_id: district_array
    },
    success: function (data) {
        console.log(data);
        $("#district_level").html(data);
    }
});

});

</script>
@endsection
