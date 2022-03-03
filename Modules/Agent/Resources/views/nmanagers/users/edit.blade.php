@extends('core::layouts.app')
@section('title','Users Create')

@section('top_script')
    <style>
        #changeImageDesktop{
            display: block;
        }
    </style>
@endsection


@section('content')

@include('core::inc.sweetalert')

{{Form::open(['route'=>['agent.nmanager-users.update',[$module,$user->id]],'method'=>'PUT','files'=>true])}}

    <!--start content  section-->
   <div class="content-header sticky-top">
        <div class="container-fluid">
           <h2 class="me-auto mb-2 mb-lg-0">Update Users</h2>
            <div class="d-flex">
                <a href="{{route('agent.nmanager-users.index',$module)}}"><button type="button" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</button></a>
                <button type="submit" class="btn btn-info"><i class="far fa-save"></i> Save</button>
            </div>
        </div>
    </div>


        <div class="form-section">

            <div class="container-fluid">

                <h3 class="form-subtitle">Users Information</h3>

                <div class="row">
                    <div class="col-md-6 col-lg-5">

                        <div class="mb-3 row form-row">
                            <label for="inputTitle" class="col-sm-4 col-form-label text-end">Name</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" placeholder="Write your name" class="form-control" value="{{ $user->name }}" id="inputTitle">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputTitle" class="col-sm-4 col-form-label text-end">Mobile</label>
                            <div class="col-sm-8">
                            <input type="text" name="mobile" placeholder="Write your mobile" class="form-control" value="{{ $user->mobile }}" id="inputTitle">
                            </div>
                        </div>


                        <div class="mb-3 row form-row">
                            <label for="role_id" class="col-sm-4 col-form-label text-end">Roles</label>
                            <div class="col-sm-8">

                                <select name="role_id" class="form-control gray-border form-select i-f-d" aria-label="Default select example" id="modules">
                                    <option value="">Choose</option>
                                    @foreach($roles as $role)
                                        @if($user->spatie_role_id == $role->id)
                                            <option value="{{$role->id}}" selected>{{$role->name}}</option>
                                        @else
                                             <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputTitle" class="col-sm-4 col-form-label text-end">Email</label>
                            <div class="col-sm-8">
                            <input type="text" name="email" placeholder="Write your email" value="{{ $user->email }}" class="form-control" id="inputTitle">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputTitle" class="col-sm-4 col-form-label text-end">Password</label>
                            <div class="col-sm-8">
                            <input type="password" name="password" placeholder="Write your password" class="form-control" id="inputTitle">
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
            $('.js-example-basic-single').select2();
        });
</script>
@endsection
