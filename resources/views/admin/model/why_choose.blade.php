<div class="modal fade bs-example-modal-lg" id="why-choose-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.why.choose.store') }}" enctype="multipart/form-data" name="why_choose_model_form" id="why_choose_model_form">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Why Choose</h4>
                    <button type="button" class="close why_choose_close_model" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Section Title <span class="required">*</span></label>
                                <input type="text" name="why_choose_title" id="why_choose_title" class="form-control" placeholder="Section Title">
                                <input type="hidden" name="why_choose_id" id="why_choose_id" class="form-control">
                                <input type="hidden" name="remove_why_choose_service" id="remove_why_choose_service" class="form-control">
                                <input type="hidden" name="remove_why_choose_image" id="remove_why_choose_image" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Section Description <span class="required">*</span></label>
                                <textarea class="form-control" name="why_choose_description" id="why_choose_description" rows="5" placeholder="Section Description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 why-choose-images">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary why_choose_close_model" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="whyChooseFieldGroupCopy" style="display: none;">
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <label>Title</label>
            <input type="text" name="why_choose_image_title[]" class="form-control" placeholder="Image Title"/>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <label>Image</label>
            <input type="file" name="why_choose_image[]" class="form-control" />
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <label>Description</label>
            <textarea name="why_choose_image_description[]" class="form-control" rows="3" placeholder="Description"></textarea>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <a href="javascript:void(0)" class="btn btn-danger why-choose-remove" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>
        </div>
    </div>
</div>