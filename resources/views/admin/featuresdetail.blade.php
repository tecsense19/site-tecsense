@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Features Detail</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Features Detail</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Section</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <div class="dropdown">
                <a class="btn btn-secondary featuresdetail-add" href="#" role="button">Features Detail</a>
            </div>
        </div>
    </div>
</div>
@include('admin.model.featuresdetail', ['errors' => $errors])
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="h4">Features Detail List</h4>
            <p class="mb-30"></p>
        </div>
        <div class="pull-right">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <input class="form-control" name="search_features_detail" id="search_features_detail" placeholder="Search Features Detail" />
            </div>
        </div>
    </div>
    <div class="table-responsive featuresdetailList">
        
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ URL::asset('public/js/featuresdetails.js') }}"></script>
@include('admin.layout.footer')
@include('admin.layout.footerjs')