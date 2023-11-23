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
                    <li class="breadcrumb-item active" aria-current="page">Tag</li>
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
            <h4 class="h4">Create/Update Tag</h4>
            <p class="mb-30"></p>
        </div>
        <div class="pull-right">
            
        </div>
    </div>
    <form method="POST" action="{{ route('admin.tag.store') }}" enctype="multipart/form-data" name="blog_tag_model_form" id="blog_tag_model_form">
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label">Tag Name <span class="required">*</span></label>
            <div class="col-sm-12 col-md-8">
                <input class="form-control" type="text" name="title" id="title"  placeholder="Tag Name">
                <input type="hidden" class="form-control" name="tag_id" id="tag_id" />
            </div>
            <div class="col-sm-12 col-md-2">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary tag_close">Close</button>
            </div>
        </div>
    </form>
</div>
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="h4">Tag List</h4>
            <p class="mb-30"></p>
        </div>
        <div class="pull-right">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <input type="text" class="form-control" name="search_tag" id="search_tag" placeholder="Search Tag" />
            </div>
        </div>
    </div>
    <div class="table-responsive blogTagDataList">
        
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ URL::asset('public/js/blog_tag.js') }}"></script>
@include('admin.layout.footer')
@include('admin.layout.footerjs')