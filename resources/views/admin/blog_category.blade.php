@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Blog</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Category</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
        </div>
    </div>
</div>
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="h4">Create/Update Category</h4>
            <p class="mb-30"></p>
        </div>
        <div class="pull-right">
            
        </div>
    </div>
    <form method="POST" action="{{ route('admin.category.store') }}" enctype="multipart/form-data" name="blog_category_model_form" id="blog_category_model_form">
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label">Category Name <span class="required">*</span></label>
            <div class="col-sm-12 col-md-8">
                <input class="form-control" type="text" name="title" id="title"  placeholder="Category Name">
                <input type="hidden" class="form-control" name="category_id" id="category_id" />
            </div>
            <div class="col-sm-12 col-md-2">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary category_close">Close</button>
            </div>
        </div>
    </form>
</div>
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="h4">Category List</h4>
            <p class="mb-30"></p>
        </div>
        <div class="pull-right">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <input type="text" class="form-control" name="search_blog_category" id="search_blog_category" placeholder="Search Blog Category" />
            </div>
        </div>
    </div>
    <div class="table-responsive blogCategoryDataList">
        
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ URL::asset('public/js/blog_category.js') }}"></script>
@include('admin.layout.footer')
@include('admin.layout.footerjs')