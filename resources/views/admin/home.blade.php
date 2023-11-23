@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Dashboard</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Section</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <div class="dropdown">
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
            </div>
        </div>
    </div>
    @include('admin.model.our_services', ['errors' => $errors])
    @include('admin.model.portfolio', ['errors' => $errors])
    @include('admin.model.dedicated_experts', ['errors' => $errors])
    @include('admin.model.why_choose', ['errors' => $errors])
    @include('admin.model.technology', ['errors' => $errors])
    @include('admin.model.testimonial', ['errors' => $errors])
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
                    Our Services
                    </button>
                </h2>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="card-body">
                    <h2 class="h4 py-4">Our Services List</h2>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <input class="form-control" name="search_service" id="search_service" placeholder="Search Service" />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive serviceDataList">
                    </div>
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
                    Look At Our Portfolio
                    </button>
                </h2>
            </div>
            <div id="collapseThree" class="collapse"
                aria-labelledby="headingThree"
                data-parent="#accordionExample">
                <div class="card-body">
                    <h2 class="h4 py-4">Images</h2>
                    <div class="portfolioDataList">
                    </div>
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
                    Hire Our Dedicated Experts
                    </button>
                </h2>
            </div>
            <div id="collapseFour" class="collapse"
                aria-labelledby="headingFout"
                data-parent="#accordionExample">
                <div class="card-body">
                    <h2 class="h4 py-4">Our Dedicated Experts List</h2>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <input class="form-control" name="search_expert" id="search_expert" placeholder="Search Expert" />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive expertDataList">
                    </div>
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
                    why Choose US
                    </button>
                </h2>
            </div>
            <div id="collapseFive" class="collapse"
                aria-labelledby="headingFive"
                data-parent="#accordionExample">
                <div class="card-body">
                    <h2 class="h4 py-4">Why Choose List</h2>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <input class="form-control" name="search_why_choose" id="search_why_choose" placeholder="Search Expert" />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive whyChooseDataList">
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-box" id="headingSix">
                <h2 class="mb-0">
                    <button type="button"
                        class="btn btn-link collapsed"
                        data-toggle="collapse"
                        data-target="#collapseSix">
                    <i class="icon-copy fa fa-bandcamp" aria-hidden="true"></i>
                    Technology
                    </button>
                </h2>
            </div>
            <div id="collapseSix" class="collapse"
                aria-labelledby="headingSix"
                data-parent="#accordionExample">
                <div class="card-body">
                    <h2 class="h4 py-4">Technology List</h2>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <input class="form-control" name="search_technology" id="search_technology" placeholder="Search Technology" />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive technologyDataList">
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-box" id="headingSeven">
                <h2 class="mb-0">
                    <button type="button"
                        class="btn btn-link collapsed"
                        data-toggle="collapse"
                        data-target="#collapseSeven">
                    <i class="icon-copy fa fa-bandcamp" aria-hidden="true"></i>
                    What Our Clients Say
                    </button>
                </h2>
            </div>
            <div id="collapseSeven" class="collapse"
                aria-labelledby="headingSeven"
                data-parent="#accordionExample">
                <div class="card-body">
                    <h2 class="h4 py-4">Testimonial List</h2>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <input class="form-control" name="search_testimonial" id="search_testimonial" placeholder="Search Testimonial" />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive testimonialDataList">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ URL::asset('public/js/service.js') }}"></script>
<script src="{{ URL::asset('public/js/portfolio.js') }}"></script>
<script src="{{ URL::asset('public/js/dedicated_experts.js') }}"></script>
<script src="{{ URL::asset('public/js/why_choose.js') }}"></script>
<script src="{{ URL::asset('public/js/technology.js') }}"></script>
<script src="{{ URL::asset('public/js/testimonial.js') }}"></script>
@include('admin.layout.footer')
@include('admin.layout.footerjs')