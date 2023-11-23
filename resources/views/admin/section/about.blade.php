<form method="POST" action="{{ route('admin.about.store') }}" enctype="multipart/form-data" name="about_form" id="about_form">
    {!! csrf_field() !!}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label>Section Logo </label>
                    <input type="file" name="about_section_image" id="about_section_image" class="form-control" />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label> &nbsp; </label>
                    <div class="section-image-preview">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Text<span class="required">*</span></label>
                    <input type="text" name="about_text" id="about_text" class="form-control" placeholder="About Text">
                    <input type="hidden" name="about_id" id="about_id" class="form-control">
                    <input type="hidden" name="remove_about" id="remove_about" class="form-control">
                    <input type="hidden" name="remove_about_image" id="remove_about_image" class="form-control">
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="about_title" id="about_title" class="form-control" placeholder="About Title">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <label>About Description <span class="required">*</span></label>
                    <textarea class="form-control" name="about_description" id="about_description" rows="5" placeholder="About Description"></textarea>
                </div>
            </div>
        </div>
        <div class="row mb-4 about-images">
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>

<div class="aboutFieldGroupCopy" style="display: none;">
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <label>Title</label>
            <input type="text" name="about_image_title[]" class="form-control" placeholder="Image Title"/>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <label>Image</label>
            <input type="file" name="about_image[]" class="form-control" />
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <a href="javascript:void(0)" class="btn btn-danger about-remove" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>
        </div>
    </div>
</div>