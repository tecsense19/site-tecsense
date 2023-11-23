$(document).ready(function(){
       // service detail store in ajax

       // Remove fields group
       let removeServices = $('#remove_service_detail');
        $("body").on("click",".remove",function() { 
           if($(this).attr('data-id'))
           {
               let removeids = removeServices.val() ? removeServices.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
   
               removeServices.val(removeids);
   
           }
           $(this).parents(".fieldGroup").remove();
       });

       $("form[name='servicedetail_model_form']").validate({
        // Specify validation rules
        
            rules: {
                service_id: {
                    required: true
                },
                service_detail_title: {
                    required: true
                }
            },
            // Specify validation error messages
            messages: {
                service_id: {
                    required: "Our Service is required."
                },
                service_detail_title: {
                    required: "Title is required."
                }
            },

            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function(form, event) {
                // form.submit();
                event.preventDefault();
                var formData = new FormData(form);
                console.log(formData);
                $.ajax({
                    type:'post',
                    headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
                    url: form.action,
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (response) {                  
                        if(response.success)
                        {
                            form.reset();
                            $('.service_close_model').trigger('click');
                            showSuccessMessage('Success', response.message, function() {
                                // This function is called when the user clicks "OK"
                                servicedetaillist();
                            });
                        }
                        else
                        {
                            showErrorMessage('Error', response.message)
                        }
                    },
                    error: function (xhr, status, error) {                       
                        showErrorMessage('Error', error)
                    }
                });
            }
       });


    servicedetaillist();

     // Add more group of input fields
    var maxGroup = 10;
    $('body').on('click', '.addMore', function(e) {

        if($('body').find('.fieldGroup').length < maxGroup){

            var fieldHTML = '<div class="fieldGroup d-flex w-100">'+$(".fieldGroupCopy").html()+'</div>';

            $('body').find('.fieldGroup:last').after(fieldHTML);

        }else{

            alert('Maximum '+maxGroup+' groups are allowed.');

        }

    });

    
     // service detail model open
    let servicedetail_images = $('.servicedetail-images');
    let serviceDetailModelForm = $("form[name='servicedetail_model_form']")[0];

    $("body").on("click",".service-detail-edit, .servicesdetial-add",function() {
        
        serviceDetailModelForm.reset();
        $("#servicedetail-modal-xl").modal("show");


        let rowData = $(this).attr('data-service-detail') ? $(this).attr('data-service-detail') : '';
        // setBlankImages();
        if(rowData)
        {
            rowData = JSON.parse(rowData);         

            $('#service_detail_id').val(rowData.id);
            $('#service_id').val(rowData.service_id);

            $('#service_detail_title').val(rowData.service_detail_title);

            $('#service_description').val(rowData.service_description);

            $('.profile_image_preview').html('');

            if(rowData.servicedetail_pic)
            {
                $('.profile_image_preview').html('<img src="'+ appUrl + rowData.servicedetail_pic +'" style="width: 25px; height: 25px;" />');
            }

            servicedetail_images.html('');
            if(rowData.service_detail_images.length > 0)
            {
                $.each(rowData.service_detail_images, function(index, item) {            

                    // Access properties of the current item
                    let imageTag = '';

                    let hidden = '';

                    if(item.image_path)
                    {
                        imageTag = '<img src="'+ appUrl + item.image_path +'" style="width: 25px; height: 25px; margin-top: 40px;"><i class="ml-2 icon-copy fa fa-times-circle-o remove-image" aria-hidden="true" data-id="'+item.id+'" style="margin-top: 43px; cursor: pointer; color: #dc3545;"></i>';
                        hidden = 'none';
                    }

                    let buttons = '';
                    if(index == 0)
                    {
                        buttons = '';
                    }
                    else
                    {
                        buttons = '<a href="javascript:void(0)" class="btn btn-danger remove" data-id="'+item.id+'" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>';
                    }

                    servicedetail_images.append(

                        `<div class="fieldGroup d-flex w-100">

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group mb-2">

                                    <label>Title</label>

                                    <input type="text" name="image_title[]" class="form-control" placeholder="Image Title" value="`+item.title+`" >

                                    <input type="hidden" name="image_id[]" id="image_id" value="`+item.id+`" />

                                </div>

                            </div>

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group mb-2">

                                    <label style="display: `+hidden+`">Image</label>

                                    <input type="file" name="service_image[]" class="form-control" placeholder="Service Image" style="display: `+hidden+`" />

                                    <div class="image-preview">

                                    `+imageTag+`

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group mb-2">

                                    `+buttons+`

                                </div>

                            </div>

                        </div>`

                    );
                });

                servicedetail_images.append(
                    `<div class="col-md-4 col-sm-12">
                        <div class="form-group mb-2">
                            <a href="javascript:void(0);" class="btn btn-success addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
                        </div>
                    </div>`
                )
            }
            else
            {
                setBlankImages()
            }
        }
        else
        {
            setBlankImages()
        }
    });

    let removeImages = $('#remove_servicedetail_pic');
    // Attach a click event handler to the remove-image icon
    $("body").on("click",".remove-image",function(e) {
        var imagePreview = $(this).closest(".image-preview");

        var label = $(this).closest(".form-group").find("label");

        var fileInput = $(this).closest(".form-group").find("input[type='file']");

        if($(this).attr('data-id'))
        {
            let removeids = removeImages.val() ? removeImages.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeImages.val(removeids);
        }

        // Remove the image preview and display the file input

        imagePreview.remove();

        label.show();

        fileInput.show();
    });


    // service detail search
    $('#search_service_detail').on('keyup', function(e) {
        servicedetaillist();
    });

    // service detail delete
    $('body').on('click', '.service-detail-delete', function(e) {

        let serviceIds = $(this).attr('data-id');

        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this serviceDetail?', function() {

            // This function is called when the user clicks "OK"

            deleteServiceDetail(serviceIds)

        });

    });

});


function setBlankImages()
{
    let serviceImages = $('.servicedetail-images');
    serviceImages.html(
        `<div class="fieldGroup d-flex w-100 full">
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
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-group mb-2">
                <a href="javascript:void(0);" class="btn btn-success addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
            </div>
        </div>`
    )
}


// service detail get
function servicedetaillist(){
    var search = $('#search_service_detail').val();

    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url: 'servicedetail/list',

        data: { search_service_detail: search },

        success:function(response)
        {

            $('.servicedetailDataList').html(response);

        }

    });
}


// service detail delete ajax call
function deleteServiceDetail(serviceIds){
    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url:'servicedetail/delete',

        data: { service_id: serviceIds },

        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {

                    // This function is called when the user clicks "OK"

                    servicedetaillist();

                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}