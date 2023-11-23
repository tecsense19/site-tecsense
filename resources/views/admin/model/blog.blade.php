<div class="modal fade bs-example-modal-xl" id="blog-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" name="blog_model_form" id="blog_model_form">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Blog</h4>
                    <button type="button" class="close blog_close_model" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Title <span class="required">*</span></label>
                                <input type="text" name="blog_title" id="blog_title" class="form-control" />
                                <input type="hidden" name="blog_id" id="blog_id" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Slug <span class="required">*</span></label>
                                <input type="text" name="slug" id="slug" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Content</label>
                                <textarea class="form-control" name="blog_content" id="editor" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Logo</label>
                                <input type="file" name="blog_image" id="blog_image" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" name="blog_category" id="blog_category">
                                    <option value="">Select Category</option>
                                    @foreach($blogCategoryList as $cat)
                                        <option value="{{ $cat->title }}">{{ $cat->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Tag</label>
                                <!-- <select class="custom-select2 form-control" name="blog_tag" id="blog_tag" multiple="multiple" style="width: 100%;"> -->
                                <select class="form-control" name="blog_tag" id="blog_tag">
                                    <option value="">Select Tag</option>
                                    @foreach($blogTagList as $tag)
                                        <option value="{{ $tag->title }}">{{ $tag->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>SEO Meta Title</label>
                                <input type="text" name="seo_meta_title" id="seo_meta_title" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>SEO Meta Description</label>
                                <input type="text" name="seo_meta_description" id="seo_meta_description" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>SEO Meta Keyword</label>
                                <input type="text" name="seo_meta_keyword" id="seo_meta_keyword" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary blog_close_model" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> -->
<script type="text/javascript" src="https://cdn.ckeditor.com/4.5.11/standard/ckeditor.js"></script>
