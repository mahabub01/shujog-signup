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

            <div class="card">
                <div class="card-header">
                    <h3>Add Your Comments</h3>
                </div>
                <div class="card-body">

                    {{ Form::open(['route'=>['agent.nmg-stkholder.comment',$module,$user_id],'method'=>'POST']) }}
                    <!--start row -->
                    <div class="row">
                        <div class="col-md-2" style="text-align: right">
                            <label class="default_label">Status<span style="color: red">*</span></label>
                        </div>

                        <div class="col-md-4">
                            <select class="form-control js-example-basic-single" name="status">
                                <option value="">Choose</option>
                                @foreach (getNetworkMngStatus() as $k => $status)
                                    <option value="{{ $k }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--end row -->

                    <!--start row -->
                    <div class="row marign-top-20">
                        <div class="col-md-2" style="text-align: right">
                            <label class="default_label">Comments<span style="color: red">*</span></label>
                        </div>

                        <div class="col-md-4">
                            <textarea class="form-control" name="comment"></textarea>
                        </div>
                    </div>
                    <!--end row -->

                    <!--start row -->
                    <div class="row  marign-top-20">
                        <div class="col-md-2">

                        </div>

                        <div class="col-md-2">
                            @if(is_null($is_complete))
                                <button type="submit" class="theme-button btn-block"><i class="far fa-save"></i> Submit</button>
                            @else
                                <button type="submit" class="theme-button btn-block" disabled><i class="far fa-save"></i> Submit</button>
                            @endif
                        </div>
                    </div>
                    <!--end row -->
                    {{ Form::close() }}


                </div>
            </div>

            <!--start comments List -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3>Comments</h3>
                </div>
                <div class="card-body">

                    @foreach ($comments as $item)
                    <div class="card" style="margin-bottom: 20px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="user d-flex flex-row align-items-center">
                                        <img src="{{ "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $item->user->email ) ) )}}" width="50" class="user-img rounded-circle mr-2">
                                        <span style="margin-left: 13px;">
                                        <small class="font-weight-bold text-primary comments-name">{{ $item->user->name }}</small><br>
                                        <small class="font-weight-bold comments-status">Status: {!! getStatusFullForm($item->status) !!}</small><br>
                                        <small class="font-weight-bold comments-status">Role: {{$item->user->spatieRole->name}}</small><br>
                                    </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div style="display: table; height: 100px; overflow: hidden;">
                                        <div style="display: table-cell; vertical-align: middle;">{{ $item->comment }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p style="text-align: right;font-size: 13px;">{{ $item->created_at->diffForhumans()}}</p>
                                    <p style="text-align: right;font-size: 13px" class="font-weight-bold comments-date"><i class="far fa-clock"></i> {{ date('jS F Y, h:i A',strtotime($item->created_at)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                </div>
            </div>
            <!--end comments List -->



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
