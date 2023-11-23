<form method="POST" action="{{ route('admin.working.process.store') }}" enctype="multipart/form-data" name="working_process_form" id="working_process_form">
    {!! csrf_field() !!}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Text<span class="required">*</span></label>
                    <input type="text" name="working_process_text" id="working_process_text" class="form-control" placeholder="Text">
                    <input type="hidden" name="working_process_id" id="working_process_id" class="form-control">
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="working_process_title" id="working_process_title" class="form-control" placeholder="Title">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <label>Logo's</label>
                    <input type="file" class="form-control" name="working_process_images[]" id="working_process_images" multiple/>
                </div>
            </div>
        </div>
        <div class="row mb-4 clearfix working-process-images">
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>