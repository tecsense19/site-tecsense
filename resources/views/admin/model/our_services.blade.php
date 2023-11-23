<div class="modal fade bs-example-modal-lg" id="our-services-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="{{ route('admin.service.store') }}" enctype="multipart/form-data" name="service_model_form" id="service_model_form">

                {!! csrf_field() !!}

                <div class="modal-header">

                    <h4 class="modal-title" id="myLargeModalLabel">Our Services</h4>

                    <button type="button" class="close close_model" data-dismiss="modal" aria-hidden="true">×</button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group">

                                <label>Service Black Logo </label>

                                <input type="file" name="service_black_logo" id="service_black_logo" class="form-control" />

                            </div>

                        </div>
                        
                        <div class="col-md-4 col-sm-12">

                            <div class="form-group">

                                <label>Service White Logo </label>

                                <input type="file" name="service_white_logo" id="service_white_logo" class="form-control" />

                            </div>

                        </div>

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group">

                                <label> &nbsp; </label>

                                <div class="display-black-logo">

                                </div>
                                <div class="display-white-logo">

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12 col-sm-12">

                            <div class="form-group">

                                <label>Service Title <span class="required">*</span></label>

                                <input type="text" name="service_title" id="service_title" class="form-control" placeholder="Service Title">

                                <input type="hidden" name="service_id" id="service_id" class="form-control">

                                <input type="hidden" name="remove_service" id="remove_service" class="form-control">

                                <input type="hidden" name="remove_image" id="remove_image" class="form-control">

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12 col-sm-12">

                            <div class="form-group">

                                <label>Service Description <span class="required">*</span></label>

                                <textarea class="form-control" name="service_description" id="service_description" rows="5" placeholder="Service Description"></textarea>

                            </div>

                        </div>

                    </div>

                    <div class="row mb-4 service-images">

                        

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary close_model" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary">Save changes</button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="fieldGroupCopy" style="display: none;">

    <div class="col-md-4 col-sm-12">

        <div class="form-group mb-2">

            <label>Title</label>

            <input type="text" name="image_title[]" class="form-control" placeholder="Image Title"/>

        </div>

    </div>

    <div class="col-md-4 col-sm-12">

        <div class="form-group mb-2">

            <label>Image</label>

            <input type="file" name="service_image[]" class="form-control" placeholder="Service Image"/>

        </div>

    </div>

    <div class="col-md-4 col-sm-12">

        <div class="form-group mb-2">

            <a href="javascript:void(0)" class="btn btn-danger remove" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>

        </div>

    </div>

</div>