@include('admin.layout.front')
@include('admin.layout.header')
@include('admin.layout.sidebar')
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Footer</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Footer</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Menu</li>
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
            <h4 class="h4">Create/Update Menu</h4>
            <p class="mb-30"></p>
        </div>
        <div class="pull-right">
            
        </div>
    </div>
    <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data" name="footer_menu_model_form" id="footer_menu_model_form">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label>Menu Category <span class="required">*</span></label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($getCategoryList as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label>Menu Title <span class="required">*</span></label>
                    <input type="text" name="menu_title" id="menu_title" class="form-control" placeholder="Menu Title">
                    <input type="hidden" name="menu_id" id="menu_id" class="form-control">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label>Menu Link</label>
                    <input type="text" name="menu_link" id="menu_link" class="form-control" placeholder="Menu Link">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label>Menu Image</label>
                    <input type="file" name="menu_image" id="menu_image" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary menu_close">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="pd-20 card-box mb-30">
    <div class="clearfix">
        <div class="pull-left">
            <h4 class="h4">Menu List</h4>
            <p class="mb-30"></p>
        </div>
        <div class="pull-right">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <input type="text" class="form-control" name="search_menu" id="search_menu" placeholder="Search Footer Menu" />
            </div>
        </div>
    </div>
    <div class="table-responsive footerMenuDataList">
        
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ URL::asset('public/js/footer_menu.js') }}"></script>
@include('admin.layout.footer')
@include('admin.layout.footerjs')