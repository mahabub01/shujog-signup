@extends('core::layouts.app')
@section('title','Dashboard')
@section('content')

    @include('core::inc.component')

    <div class="content-bg-section">
        <div class="content-section">

        <div class="container-fluid table-design-container">
            <!--start card -->
            <div class="card full-height">
                <h3 style="font-size: 18px;padding: 9px;">Agent Dashboard</h3>
                <hr style="margin: 0px;padding:0px;background-color:silver"/>

                <div class="row dashboard-padding-20" style="margin-top: 20px;">

                        <!--start here -->
                        <div class="col-md-3 col-sm-12 col-mergin-bottom">
                            <div class="project_dashboard_box" style="background: #D5F4F6;">
                                <h3 class="project_dashboard_box_title"> Total Signup User </h3>
                                <h4 class="project_dashboard_box_amount">
                                    {{ $total_signup_users }}
                                </h4>

                                <p class="project_dashboard_box_see_more">
                                    <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                        See More Details
                                    </a>
                                </p>
                            </div>
                        </div>
                        <!--end here -->


                        <!--start here -->
                        <div class="col-md-3 col-sm-12 col-mergin-bottom">
                            <div class="project_dashboard_box" style="background: #FDD4CD;">
                                <h3 class="project_dashboard_box_title"> Reject Signup User </h3>
                                <h4 class="project_dashboard_box_amount">
                                    {{ $total_signup_rejected_users }}
                                </h4>

                                <p class="project_dashboard_box_see_more">
                                    <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                        See More Details
                                    </a>
                                </p>
                            </div>
                        </div>
                        <!--end here -->



                        <!--start here -->
                        <div class="col-md-3 col-sm-12 col-mergin-bottom">
                            <div class="project_dashboard_box" style="background: #FFCF86;">
                                <h3 class="project_dashboard_box_title"> Training Complete </h3>
                                <h4 class="project_dashboard_box_amount">
                                    {{ $total_training_complete }}
                                </h4>

                                <p class="project_dashboard_box_see_more">
                                    <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                        See More Details
                                    </a>
                                </p>
                            </div>
                        </div>
                        <!--end here -->


                         <!--start here -->
                         <div class="col-md-3 col-sm-12 col-mergin-bottom">
                            <div class="project_dashboard_box" style="background: #BED8FB;">
                                <h3 class="project_dashboard_box_title"> Training Pending </h3>
                                <h4 class="project_dashboard_box_amount">
                                    {{ $total_training_pending }}
                                </h4>

                                <p class="project_dashboard_box_see_more">
                                    <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                        See More Details
                                    </a>
                                </p>
                            </div>
                        </div>
                        <!--end here -->

                </div>



                <div class="row dashboard-padding-20" style="margin-top: 20px;">

                    <!--start here -->
                    <div class="col-md-3 col-sm-12 col-mergin-bottom">
                        <div class="project_dashboard_box" style="background: #E3EEFF;">
                            <h3 class="project_dashboard_box_title"> Deployed Complete </h3>
                            <h4 class="project_dashboard_box_amount">
                                {{ $total_deployed_complete }}
                            </h4>

                            <p class="project_dashboard_box_see_more">
                                <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                    See More Details
                                </a>
                            </p>
                        </div>
                    </div>
                    <!--end here -->


                    <!--start here -->
                    <div class="col-md-3 col-sm-12 col-mergin-bottom">
                        <div class="project_dashboard_box" style="background: #E0E4FF;">
                            <h3 class="project_dashboard_box_title"> Deployed Pending </h3>
                            <h4 class="project_dashboard_box_amount">
                                {{ $total_deployed_pending }}
                            </h4>

                            <p class="project_dashboard_box_see_more">
                                <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                    See More Details
                                </a>
                            </p>
                        </div>
                    </div>
                    <!--end here -->



                    <!--start here -->
                    <div class="col-md-3 col-sm-12 col-mergin-bottom">
                        <div class="project_dashboard_box" style="background: #9981FF;">
                            <h3 class="project_dashboard_box_title">  Active  User </h3>
                            <h4 class="project_dashboard_box_amount">
                                {{ $total_network_active }}
                            </h4>

                            <p class="project_dashboard_box_see_more">
                                <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                    See More Details
                                </a>
                            </p>
                        </div>
                    </div>
                    <!--end here -->


                     <!--start here -->
                     <div class="col-md-3 col-sm-12 col-mergin-bottom">
                        <div class="project_dashboard_box" style="background: #D5F4F6;">
                            <h3 class="project_dashboard_box_title"> Dropout User
                            </h3>
                            <h4 class="project_dashboard_box_amount">
                                {{ $total_network_in_drop_out }}
                            </h4>

                            <p class="project_dashboard_box_see_more">
                                <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                    See More Details
                                </a>
                            </p>
                        </div>
                    </div>
                    <!--end here -->


                    <!--start here -->
                     <div class="col-md-3 col-sm-12 col-mergin-bottom">
                        <div class="project_dashboard_box" style="background: #FFCF86;">
                            <h3 class="project_dashboard_box_title"> In Active User
                            </h3>
                            <h4 class="project_dashboard_box_amount">
                                {{ $total_network_in_active }}
                            </h4>

                            <p class="project_dashboard_box_see_more">
                                <a href="#" class="btn btn-default" style="border-radius: 20%;">
                                    See More Details
                                </a>
                            </p>
                        </div>
                    </div>
                    <!--end here -->

            </div>








            </div>
        </div>
        </div>
    </div>

@endsection
