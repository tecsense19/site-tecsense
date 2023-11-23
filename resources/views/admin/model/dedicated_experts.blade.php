<div class="modal fade bs-example-modal-lg" id="dedicated-experts-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.expert.store') }}" enctype="multipart/form-data" name="dedicated_experts_model_form" id="dedicated_experts_model_form">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Dedicated Experts</h4>
                    <button type="button" class="close expert_close_model" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Section Title <span class="required">*</span></label>
                                <input type="text" name="expert_title" id="expert_title" class="form-control" placeholder="Section Title">
                                <input type="hidden" name="expert_id" id="expert_id" class="form-control">
                                <input type="hidden" name="remove_expert_service" id="remove_expert_service" class="form-control">
                                <input type="hidden" name="remove_expert_image" id="remove_expert_image" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Section Description <span class="required">*</span></label>
                                <textarea class="form-control" name="expert_description" id="expert_description" rows="5" placeholder="Section Description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 expert-images">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary expert_close_model" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="expertFieldGroupCopy" style="display: none;">
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-2">
            <label>Title</label>
            <input type="text" name="expert_image_title[]" class="form-control" placeholder="Image Title"/>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-2">
            <label>Image</label>
            <input type="file" name="expert_image[]" class="form-control" />
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-2">
            <a href="javascript:void(0)" class="btn btn-danger expert-remove" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>
        </div>
    </div>
</div>