@extends('core::layouts.app')
@section('title','Add Project')

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

            <div class="card">
                <div class="card-header">
                    <h3>Add Your Projects</h3>
                </div>
                <div class="card-body">

                    {{ Form::open(['route'=>['agent.dp-project.store',$module],'method'=>'POST']) }}
                    <!--start row -->
                    <div class="row">
                        <div class="col-md-2" style="text-align: right">
                            <label class="default_label">Project<span style="color: red">*</span></label>
                        </div>

                        <div class="col-md-4">
                            <select class="form-control js-example-basic-single" name="project_id">
                                <option value="">Choose</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--end row -->

                    <!--start row -->
                    <div class="row marign-top-20">
                        <div class="col-md-2" style="text-align: right">
                            <label class="default_label">Stakeholders<span style="color: red">*</span></label>
                        </div>

                        <div class="col-md-4">
                            <select class="form-control js-example-basic-multiple" name="stakeholder_id[]" multiple="muitiple">
                                <option value="">Choose</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--end row -->


                    <!--start row -->
                    <div class="row  marign-top-20">
                        <div class="col-md-2">

                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="theme-button btn-block"><i class="far fa-save"></i> Submit</button>
                        </div>
                    </div>
                    <!--end row -->
                    {{ Form::close() }}


                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('bottom_script')
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        $('.js-example-basic-multiple').select2();
    });
</script>
@endsection
