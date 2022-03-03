@extends('core::layouts.app')
@section('title','Create')
@section('content')

    <!--start content  section-->
   <div class="content-header sticky-top">
        <div class="container-fluid">
           <h2 class="me-auto mb-2 mb-lg-0">Create Account</h2>
            <div class="d-flex">
                <a href="{{ route('demo.index') }}"><button class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</button></a>
                <button class="btn btn-info"><i class="far fa-save"></i> Save</button>
            </div>
        </div>
    </div>


        <div class="form-section">

            <div class="container-fluid">

                <h3 class="form-subtitle">Account Information</h3>

                <div class="row">
                    <div class="col-md-6 col-lg-5">
                        <div class="mb-3 row form-row">
                            <label for="inputPassword" class="col-sm-4 col-form-label text-end">Account Owner</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputPassword">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputPassword" class="col-sm-4 col-form-label text-end">Account Owner</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputPassword">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputPassword" class="col-sm-4 col-form-label text-end">Account Owner</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputPassword">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputPassword" class="col-sm-4 col-form-label text-end">Account Owner</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputPassword">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputPassword" class="col-sm-4 col-form-label text-end">Account Owner</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputPassword">
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 col-lg-5 offset-lg-1">
                        <div class="mb-3 row form-row">
                            <label for="inputPassword" class="col-sm-4 col-form-label text-end">Account Owner</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputPassword">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputPassword" class="col-sm-4 col-form-label text-end">Account Owner</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputPassword">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputPassword" class="col-sm-4 col-form-label text-end">Account Owner</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputPassword">
                            </div>
                        </div>

                        <div class="mb-3 row form-row">
                            <label for="inputPassword" class="col-sm-4 col-form-label text-end">Account Owner</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputPassword">
                            </div>
                        </div>

                    </div>
                </div>



            </div>
        </div>


<!--end content  section-->






@endsection
