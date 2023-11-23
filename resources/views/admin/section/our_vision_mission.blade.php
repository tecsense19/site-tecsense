<form method="POST" action="{{ route('admin.our.vision.store') }}" enctype="multipart/form-data" name="vision_form" id="vision_form">
    {!! csrf_field() !!}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label>Section Logo </label>
                    <input type="file" name="vision_section_image" id="vision_section_image" class="form-control" />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label> &nbsp; </label>
                    <div class="vision-image-preview">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Text<span class="required">*</span></label>
                    <input type="text" name="vision_text" id="vision_text" class="form-control" placeholder="Text">
                    <input type="hidden" name="vision_id" id="vision_id" class="form-control">
                    <input type="hidden" name="remove_vision" id="remove_vision" class="form-control">
                    <input type="hidden" name="remove_vision_image" id="remove_vision_image" class="form-control">
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="vision_title" id="vision_title" class="form-control" placeholder="Title">
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <label>Description <span class="required">*</span></label>
                    <textarea class="form-control" name="vision_description" id="vision_description" rows="5" placeholder="Description"></textarea>
                </div>
            </div>
        </div> -->
        <div class="row mb-4 vision-images">
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>

<div class="visionFieldGroupCopy" style="display: none;">
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <label>Title</label>
            <input type="text" name="vision_image_title[]" class="form-control" placeholder="Image Title"/>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <label>Image</label>
            <input type="file" name="vision_image[]" class="form-control" />
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group">
            <label>Description <span class="required">*</span></label>
            <textarea class="form-control" name="vision_description[]" id="vision_description" rows="5" placeholder="Description"></textarea>
        </div>
    </div>
    <div class="col-md-2 col-sm-12">
        <div class="form-group mb-2">
            <a href="javascript:void(0)" class="btn btn-danger vision-remove" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>
        </div>
    </div>
</div>