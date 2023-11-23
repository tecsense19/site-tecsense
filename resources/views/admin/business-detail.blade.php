@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Business Detail</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Business</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Section</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <div class="dropdown">
                <a class="btn btn-secondary business-detial-add" href="#" role="button">Create Business Detail</a>
            </div>
        </div>
    </div>
</div>
@include('admin.model.businessdetail', ['errors' => $errors])
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="h4">Business Detail List</h4>
            <p class="mb-30"></p>
        </div>
        <div class="pull-right">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <input class="form-control" name="search_business_detail" id="search_business_detail" placeholder="Search Business Detail" />
            </div>
        </div>
    </div>
    <div class="table-responsive quicklookdetailDataList">
        
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ URL::asset('public/js/business-details.js') }}"></script>
@include('admin.layout.footer')
@include('admin.layout.footerjs')