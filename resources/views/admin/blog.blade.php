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
                    <li class="breadcrumb-item active" aria-current="page">Section</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <div class="dropdown">
                <a class="btn btn-secondary blog-add" href="#" role="button">Create Blog</a>
            </div>
        </div>
    </div>
</div>
@include('admin.model.blog', ['errors' => $errors, 'blogCategoryList' => $blogCategoryList, 'blogTagList' => $blogTagList])
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="h4">Blog List</h4>
            <p class="mb-30"></p>
        </div>
        <div class="pull-right">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <input class="form-control" name="search_blog" id="search_blog" placeholder="Search Blog" />
            </div>
        </div>
    </div>
    <div class="table-responsive blogDataList">
        
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ URL::asset('public/js/blog.js') }}"></script>
@include('admin.layout.footer')
@include('admin.layout.footerjs')