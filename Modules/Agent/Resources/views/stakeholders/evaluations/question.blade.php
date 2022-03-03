@extends('core::layouts.app')
@section('title','Stakeholders Comments')

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


            <div class="row">
                <div class="col-md-6">
                    <!--start -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Evaluation Form</h3>
                        </div>
                        <div class="card-body">

                            {{ Form::open(['route'=>['agent.stakeholder.evaluationsubmit',$module,$user_id],'method'=>'POST']) }}


                            @foreach ($questions as $k => $qus)
                            <!--start row -->
                            <div class="row" style="margin-bottom: 30px;">
                                <div class="col-md-10 offset-md-1">
                                    <h4>{{ $k+1 }}. {{ $qus->question }}</h4>
                                    <div style="margin-left:20px;">
                                        @if($qus->question_type == '1')
                                            <div class="row">
                                                {{ getPossibleAnsSingle($qus) }}
                                            </div>
                                        @elseif($qus->question_type == '2')
                                            <div class="row">
                                                {{ getPossibleAnsMulti($qus) }}
                                            </div>
                                        @elseif($qus->question_type == '3')
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <textarea class="form-control" name="{{ $qus->id }}" rows="5"></textarea>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <!--end row -->

                            @endforeach


                            <!--start row -->
                            @if(is_null($user_evaluation))
                            <div class="row  marign-top-20 padding-bottom-50">
                                <div class="col-md-4 offset-md-1">
                                    <button type="submit" class="theme-button btn-block" style="margin-left: 20px;"><i class="far fa-save"></i> Submit</button>
                                </div>
                            </div>
                            @else
                            <div class="row  marign-top-20 padding-bottom-50">
                                <div class="col-md-4 offset-md-1">
                                    <button type="submit" class="theme-button btn-block" style="margin-left: 20px;" disabled><i class="far fa-save"></i> Submit</button>
                                </div>
                            </div>
                            @endif
                            <!--end row -->
                            {{ Form::close() }}


                        </div>
                    </div>
                    <!--end-->
                </div>
                <div class="col-md-6">
                    <!--start -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Evaluation Result</h3>
                        </div>

                        <div class="card-body evaluation-result">

                            @if(!is_null($user_evaluation))

                            <div class="row">
                                @if($user_evaluation->status == 1)
                                    <div class="col-md-4">
                                        <div class="card" style="border-color:green">
                                            <h2 class="color-green"><i class="fas fa-check-circle"></i></h2>
                                            <h3 class="color-green">Passed</h3>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-4">
                                        <div class="card" style="border-color:brown">
                                            <h2 class="color-brown"><i class="far fa-times-circle"></i></h2>
                                            <h3 class="color-brown">Failed</h3>
                                        </div>
                                    </div>
                                @endif

                                {{-- <i class="far fa-times-circle"></i> --}}

                                @if($user_evaluation->status == 1)
                                    <div class="col-md-4">
                                        <div class="card" style="border-color:green">
                                            <h2 class="color-green"><i class="fas fa-marker"></i></h2>
                                            <h3 class="color-green">{{ $user_evaluation->mark }} Mark</h3>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-4">
                                        <div class="card" style="border-color:brown">
                                            <h2 class="color-brown"><i class="fas fa-marker"></i></h2>
                                            <h3 class="color-brown">{{ $user_evaluation->mark }} Mark</h3>
                                        </div>
                                    </div>
                                @endif



                                <div class="col-md-4">
                                    <div class="card">
                                        <h2 class="color-green" style="color: dimgray"><i class="fas fa-question-circle"></i></h2>
                                            @if(!is_null($user_evaluation->evaluationDetails))
                                                <h3 style="color: dimgray">{{ count($user_evaluation->evaluationDetails) }} Question</h3>
                                            @else
                                                <h3  style="color: dimgray"> 0 Questions</h3>
                                            @endif
                                    </div>
                                </div>

                            </div>





                            @else

                                <h3 style="color:silver">Evaluation data not submitted</h3>

                            @endif


                        </div>

                    </div>
                    <!--end -->
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
    });
</script>
@endsection
