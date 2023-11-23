<div class="modal fade bs-example-modal-lg" id="technology-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="{{ route('admin.technology.store') }}" enctype="multipart/form-data" name="technology_model_form" id="technology_model_form">

                {!! csrf_field() !!}

                <div class="modal-header">

                    <h4 class="modal-title" id="myLargeModalLabel">Technology</h4>

                    <button type="button" class="close tec_close_model" data-dismiss="modal" aria-hidden="true">×</button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 col-sm-12">

                            <div class="form-group">

                                <label>Technology Name<span class="required">*</span></label>

                                <input type="text" name="technology_name" id="technology_name" class="form-control" placeholder="Technology Name">

                                <input type="hidden" name="technology_id" id="technology_id" class="form-control">

                                <input type="hidden" name="remove_technology" id="remove_technology" class="form-control">

                                <input type="hidden" name="remove_tec_image" id="remove_tec_image" class="form-control">

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12 col-sm-12">

                            <div class="form-group">

                                <label>Technology Description<span class="required">*</span></label>

                                <textarea class="form-control" name="technology_description" id="technology_description" rows="5" placeholder="Technology Description"></textarea>

                            </div>

                        </div>

                    </div>

                    <div class="row mb-4 technology-images">

                        

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary tec_close_model" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary">Save changes</button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="tecFieldGroupCopy" style="display: none;">

    <div class="col-md-4 col-sm-12">

        <div class="form-group mb-2">

            <label>Title</label>

            <input type="text" name="tec_image_title[]" class="form-control" placeholder="Image Title"/>

        </div>

    </div>

    <div class="col-md-4 col-sm-12">

        <div class="form-group mb-2">

            <label>Image</label>

            <input type="file" name="tec_image[]" class="form-control" />

        </div>

    </div>

    <div class="col-md-4 col-sm-12">

        <div class="form-group mb-2">

            <a href="javascript:void(0)" class="btn btn-danger tec-remove" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>

        </div>

    </div>

</div>