$(document).ready(function(){


    // Maximum number of groups can be added

    var maxGroup = 10;

    

    // Add more group of input fields

    $('body').on('click', '.addMore', function(e) {

        

        if($('body').find('.fieldGroup').length < maxGroup){

            var fieldHTML = '<div class="fieldGroup d-flex w-100">'+$(".fieldGroupCopy").html()+'</div>';

            $('body').find('.fieldGroup:last').after(fieldHTML);

        }else{

            alert('Maximum '+maxGroup+' groups are allowed.');

        }

    });
    
    

    let removeServices = $('#remove_service');



    // Remove fields group

     $("body").on("click",".remove",function() { 



        if($(this).attr('data-id'))

        {

            let removeids = removeServices.val() ? removeServices.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')

            removeServices.val(removeids);

        }



        $(this).parents(".fieldGroup").remove();

    });



    $("form[name='service_model_form']").validate({

        // Specify validation rules

        rules: {

            service_title: {

                required: true

            },

            service_description: {

                required: true

            }

        },

        // Specify validation error messages

        messages: {

            service_title: {

                required: "Service title is required."

            },

            service_description: {

                required: "Service description is required."

            }

        },

        // Make sure the form is submitted to the destination defined

        // in the "action" attribute of the form when valid

        submitHandler: function(form, event) {

            // form.submit();

            event.preventDefault();

            var formData = new FormData(form);



            // var fileInputs = $('input[name="service_image[]"]');



            // Loop through each file input and append the selected files to the formData

            // fileInputs.each(function() {

            //     var files = this.files;

            //     for (var i = 0; i < files.length; i++) {

            //         formData.append('service_image[]', files[i]);

            //     }

            // });



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

                        $('.close_model').trigger('click');

                        showSuccessMessage('Success', response.message, function() {

                            // This function is called when the user clicks "OK"

                            servicesList();

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



    servicesList();

    $('body').on('click', '.pagination a', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');

        getPerPageServicesList(url);

    });


    let serviceModelForm = $("form[name='service_model_form']")[0];

    let ourServicesModalLg = $("#our-services-modal-lg");

    let serviceImages = $('.service-images');

    let serviceId = $('#service_id');



    $('body').on('click', '.service-edit, .service-add', function(e) {

        serviceModelForm.reset();

        ourServicesModalLg.modal("show");

        removeServices.val('');

        removeImages.val('');

        serviceId.val('');



        let rowData = $(this).attr('data-service') ? $(this).attr('data-service') : '';
        
        if(rowData)
        {
            rowData = JSON.parse(rowData);           

            $('#service_id').val(rowData.id);

            $('#service_title').val(rowData.service_title);

            $('#service_description').val(rowData.service_description);

            $('.display-black-logo').html('');
            $('.display-white-logo').html('');

            if(rowData.service_black_logo)
            {
                $('.display-black-logo').html('<img src="'+ appUrl + rowData.service_black_logo +'" style="width: 25px; height: 25px;" />');
            }
            else if(rowData.service_white_logo)
            {
                $('.display-white-logo').html('<img src="'+ appUrl + rowData.service_white_logo +'" style="width: 25px; height: 25px;" />');
            }

            serviceImages.html('');
            if(rowData.service_images.length > 0)
            {
                $.each(rowData.service_images, function(index, item) {            

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

                    serviceImages.append(

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

                serviceImages.append(
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


    let removeImages = $('#remove_image');
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

    $('body').on('click', '.service-delete', function(e) {



        let serviceIds = $(this).attr('data-id');

        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this service?', function() {

            // This function is called when the user clicks "OK"

            deleteService(serviceIds)

        });

    });

    

    $('#search_service').on('keyup', function(e) {

        servicesList();

    });


});

function setBlankImages()
{
    let serviceImages = $('.service-images');
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

function servicesList()
{
    var search = $('#search_service').val();

    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url: 'service/list',

        data: { search_service: search },

        success:function(response)

        {

            $('.serviceDataList').html(response);

        }

    });

}


function getPerPageServicesList(get_pagination_url) 

{

    var search = $('#search_service').val();

    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url:get_pagination_url,

        data: { search: search },

        success:function(data)

        {

            $('.serviceDataList').html(data);

        }

    });   

}



function deleteService(serviceIds)

{

    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url:'service/delete',

        data: { service_id: serviceIds },

        success:function(response)

        {

            if(response.success)

            {

                showSuccessMessage('Success', response.message, function() {

                    // This function is called when the user clicks "OK"

                    servicesList();

                });

            }

            else

            {

                showErrorMessage('Error', response.message)

            }

        }

    });

}