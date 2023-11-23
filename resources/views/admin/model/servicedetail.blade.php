<div class="modal fade bs-example-modal-xl" id="servicedetail-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.service.servicedetail_store') }}" enctype="multipart/form-data" name="servicedetail_model_form" id="servicedetail_model_form">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Our Service Detail</h4>
                    <button type="button" class="close service_close_model" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">

                            <div class="form-group">

                                <label>Profile Pic</label>

                                <input type="file" name="servicedetail_pic" id="servicedetail_pic" class="form-control" />

                                <input type="hidden" name="service_detail_id" id="service_detail_id" class="form-control">
                                <input type="hidden" name="remove_service_detail" id="remove_service_detail" class="form-control">
                                <input type="hidden" name="remove_servicedetail_pic" id="remove_servicedetail_pic" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Our Service <span class="required">*</span></label>
                                <select class="form-control" name="service_id" id="service_id">
                                    <option value="">Select Service</option>
                                    @foreach($ourservice as $service)
                                    <option value="{{$service->id}}">{{$service->service_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group">
                                <label> &nbsp; </label>
                                <div class="profile_image_preview">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Title <span class="required">*</span></label>
                                <input type="text" name="service_detail_title" id="service_detail_title" class="form-control" />
                            </div>
                        </div>
                        
                    </div>

                    <div class="row mb-4  servicedetail-images">

                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary service_close_model" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary service-details-submit">Save changes</button>
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

<!-- <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> -->
<script type="text/javascript" src="https://cdn.ckeditor.com/4.5.11/standard/ckeditor.js"></script>
