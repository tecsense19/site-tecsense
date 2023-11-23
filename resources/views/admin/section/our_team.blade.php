<form method="POST" action="{{ route('admin.our.team.store') }}" enctype="multipart/form-data" name="team_form" id="team_form">
    {!! csrf_field() !!}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Text<span class="required">*</span></label>
                    <input type="text" name="team_text" id="team_text" class="form-control" placeholder="Text">
                    <input type="hidden" name="team_id" id="team_id" class="form-control">
                    <input type="hidden" name="remove_team" id="remove_team" class="form-control">
                    <input type="hidden" name="remove_team_image" id="remove_team_image" class="form-control">
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="team_title" id="team_title" class="form-control" placeholder="Title">
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <label>Description <span class="required">*</span></label>
                    <textarea class="form-control" name="team_description" id="team_description" rows="5" placeholder="Description"></textarea>
                </div>
            </div>
        </div> -->
        <div class="row mb-4 team-images">
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>

<div class="teamFieldGroupCopy" style="display: none;">
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <label>Title</label>
            <input type="text" name="team_image_title[]" class="form-control" placeholder="Image Title"/>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group mb-2">
            <label>Image</label>
            <input type="file" name="team_image[]" class="form-control" />
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group">
            <label>Description <span class="required">*</span></label>
            <textarea class="form-control" name="team_description[]" id="team_description" rows="5" placeholder="Description"></textarea>
        </div>
    </div>
    <div class="col-md-2 col-sm-12">
        <div class="form-group mb-2">
            <a href="javascript:void(0)" class="btn btn-danger team-remove" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>
        </div>
    </div>
</div>