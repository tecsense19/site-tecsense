<div class="modal fade bs-example-modal-lg" id="testimonial-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="{{ route('admin.testimonial.store') }}" enctype="multipart/form-data" name="testimonial_model_form" id="testimonial_model_form">

                {!! csrf_field() !!}

                <div class="modal-header">

                    <h4 class="modal-title" id="myLargeModalLabel">Testimonial</h4>

                    <button type="button" class="close tec_close_model" data-dismiss="modal" aria-hidden="true">×</button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group">

                                <label>Profile Pic<span class="required">*</span></label>

                                <input type="file" name="profile_pic" id="profile_pic" class="form-control" />

                                <div class="mx-2" bis_skin_checked="1" id="profile_image_preview">

                                    

                                </div>

                                <input type="hidden" name="testimonial_id" id="testimonial_id" class="form-control">

                                <input type="hidden" name="remove_profile_pic" id="remove_profile_pic" class="form-control">

                                <input type="hidden" name="remove_testimonial_image" id="remove_testimonial_image" class="form-control">

                            </div>

                        </div>

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group">

                                <label>Full Name<span class="required">*</span></label>

                                <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Full Name" />

                            </div>

                        </div>

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group">

                                <label>Country<span class="required">*</span></label>

                                <input type="text" name="country" id="country" class="form-control" placeholder="Country" />

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12 col-sm-12">

                            <div class="form-group">

                                <label>Client Description<span class="required">*</span></label>

                                <textarea class="form-control" name="client_description" id="client_description" rows="5" placeholder="Client Description"></textarea>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12 col-sm-12">

                            <div class="form-group">

                                <label>Logo's</label>

                                <input type="file" class="form-control" name="logos[]" id="logos" multiple/>

                            </div>

                        </div>

                    </div>

                    <div>

                        <div class="col-md-12 col-sm-12 d-flex flex-wrap" id="testimonial_images">

                        </div>

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