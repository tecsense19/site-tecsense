<div class="modal fade bs-example-modal-xl" id="business-detail-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.businessdetail.business_detail_store') }}" enctype="multipart/form-data" name="business_detail_model_form" id="business_detail_model_form">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Business Detail</h4>
                    <button type="button" class="close service_close_model" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Text <span class="required">*</span></label>
                                <input type="text" name="text" id="text" class="form-control" placeholder="Title" />

                                <input type="hidden" name="business_detail_id" id="business_detail_id" class="form-control">
                                <input type="hidden" name="remove_business_detail" id="remove_business_detail" class="form-control">
                                <input type="hidden" name="remove_business_pic" id="remove_business_pic" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
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
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Service Description <span class="required">*</span></label>
                                <textarea class="form-control" name="business_description" id="business_description" rows="5" placeholder="Why Choose Description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4  business-detail-images">
                            
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary quick_look_close_model" data-dismiss="modal">Close</button>
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
            <input type="text" name="image_title[]" class="form-control" placeholder="Title"/>
        </div>

    </div>
    
    <!-- step_title -->
    <div class="col-md-4 col-sm-12">

        <div class="form-group mb-2">

            <label>Image</label>

            <input type="file" name="image[]" class="form-control" placeholder="Image"/>

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
