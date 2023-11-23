@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>About</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">About</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Section</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <!-- <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                Create
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item service-add" href="#">Our Services</a>
                    <a class="dropdown-item portfolio-add" href="#">Portfolio</a>
                    <a class="dropdown-item expert-add" href="#">Dedicated Experts</a>
                    <a class="dropdown-item why-choose-add" href="#">Why Choose</a>
                    <a class="dropdown-item technology-add" href="#">Technology</a>
                    <a class="dropdown-item testimonial-add" href="#">Testimonial</a>
                </div>
            </div> -->
        </div>
    </div>
</div>
<div class="accordion pd-20 card-box mb-30">
    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header card-box" id="headingOne">
                <h2 class="mb-0">
                    <button type="button" class="btn btn-link"
                        data-toggle="collapse"
                        data-target="#collapseOne">
                    <i class="icon-copy fa fa-bandcamp" aria-hidden="true"></i>
                    Banner
                    </button>
                </h2>
            </div>
            <div id="collapseOne" class="collapse"
                aria-labelledby="headingOne"
                data-parent="#accordionExample">
                <div class="card-body">
                    <p>
                        GeeksforGeeks is a computer
                        science portal. It is the best
                        platform to lean programming.
                    </p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-box" id="headingTwo">
                <h2 class="mb-0">
                    <button type="button"
                        class="btn btn-link collapsed"
                        data-toggle="collapse"
                        data-target="#collapseTwo">
                    <i class="icon-copy fa fa-bandcamp" aria-hidden="true"></i>
                    About Us
                    </button>
                </h2>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="card-body">
                    @include('admin.section.about', ['errors' => $errors])
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-box" id="headingThree">
                <h2 class="mb-0">
                    <button type="button"
                        class="btn btn-link collapsed"
                        data-toggle="collapse"
                        data-target="#collapseThree">
                    <i class="icon-copy fa fa-bandcamp" aria-hidden="true"></i>
                    Our Working Process
                    </button>
                </h2>
            </div>
            <div id="collapseThree" class="collapse"
                aria-labelledby="headingThree"
                data-parent="#accordionExample">
                <div class="card-body">
                    @include('admin.section.working_process', ['errors' => $errors])
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-box" id="headingFout">
                <h2 class="mb-0">
                    <button type="button"
                        class="btn btn-link collapsed"
                        data-toggle="collapse"
                        data-target="#collapseFour">
                    <i class="icon-copy fa fa-bandcamp" aria-hidden="true"></i>
                        Our Vision & Mission
                    </button>
                </h2>
            </div>
            <div id="collapseFour" class="collapse"
                aria-labelledby="headingFout"
                data-parent="#accordionExample">
                <div class="card-body">
                    @include('admin.section.our_vision_mission', ['errors' => $errors])
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-box" id="headingFive">
                <h2 class="mb-0">
                    <button type="button"
                        class="btn btn-link collapsed"
                        data-toggle="collapse"
                        data-target="#collapseFive">
                    <i class="icon-copy fa fa-bandcamp" aria-hidden="true"></i>
                        Our Team
                    </button>
                </h2>
            </div>
            <div id="collapseFive" class="collapse"
                aria-labelledby="headingFive"
                data-parent="#accordionExample">
                <div class="card-body">
                    @include('admin.section.our_team', ['errors' => $errors])
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ URL::asset('public/js/about.js') }}"></script>
<script src="{{ URL::asset('public/js/working_process.js') }}"></script>
<script src="{{ URL::asset('public/js/our_vision_mission.js') }}"></script>
<script src="{{ URL::asset('public/js/our_team.js') }}"></script>
@include('admin.layout.footer')
@include('admin.layout.footerjs')