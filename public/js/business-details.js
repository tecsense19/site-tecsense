$(document).ready(function(){

    $("form[name='business_detail_model_form']").validate({
        // Specify validation rules
        
        rules: {
            service_id: {
                required: true
            },
            text: {
                required: true
            },
            business_description: {
                required: true
            },
        },
        // Specify validation error messages
        messages: {
            service_id: {
                required: "Our Service is required."
            },
            text: {
                required: "Title is required."
            },
            business_description: {
                required: "Description is required."
            },
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
                        $('.quick_look_close_model').trigger('click');
                        showSuccessMessage('Success', response.message, function() {
                            // This function is called when the user clicks "OK"
                            businessdetaillist();
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

    businessdetaillist();

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

    // Remove fields group
    let removeServices = $('#remove_business_detail');
    $("body").on("click",".remove",function() { 
        if($(this).attr('data-id'))
        {
            let removeids = removeServices.val() ? removeServices.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')

            removeServices.val(removeids);

        }
        $(this).parents(".fieldGroup").remove();
    });

    // Attach a click event handler to the remove-image icon
    let removeImages = $('#remove_business_pic');
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


    let business_detail_images = $('.business-detail-images');
    let businessDetailModelForm = $("form[name='business_detail_model_form']")[0];
    $("body").on("click",".business-detail-edit, .business-detial-add",function() {

        businessDetailModelForm.reset();
        $("#business-detail-modal-xl").modal("show");

        let rowData = $(this).attr('data-business-detail') ? $(this).attr('data-business-detail') : '';

        if(rowData){
            rowData = JSON.parse(rowData);
            console.log(rowData);
            $('#business_detail_id').val(rowData.id);
            $('#text').val(rowData.text);
            $('#service_id').val(rowData.service_id);
            $('#business_description').val(rowData.business_description);

            business_detail_images.html('');
            if(rowData.business_detail_images.length > 0)
            {
                $.each(rowData.business_detail_images, function(index, item) {            

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

                    business_detail_images.append(

                        `<div class="fieldGroup d-flex w-100">

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group mb-2">

                                    <label>Title</label>

                                    <input type="text" name="image_title[]" class="form-control" placeholder="Title" value="`+item.title+`" >

                                    <input type="hidden" name="image_id[]" id="image_id" value="`+item.id+`" />

                                </div>

                            </div>

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group mb-2">

                                    <label style="display: `+hidden+`">Image</label>

                                    <input type="file" name="image[]" class="form-control" placeholder="Service Image" style="display: `+hidden+`" />

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

                business_detail_images.append(
                    `<div class="col-md-3 col-sm-12">
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
        }else{
            setBlankImages();
        }
    });

    $('body').on('click', '.business-detail-delete', function(e) {

        let serviceIds = $(this).attr('data-id');

        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this Business Deatil?', function() {

            // This function is called when the user clicks "OK"

            deletequicklookDetail(serviceIds)

        });

    });

    // service detail search
    $('#search_business_detail').on('keyup', function(e) {
        businessdetaillist();
    });
});


function setBlankImages()
{
    let businessImages = $('.business-detail-images');
    businessImages.html(
        `<div class="fieldGroup d-flex w-100 full">
            <div class="col-md-4 col-sm-12">
                <div class="form-group mb-2">
                    <label>Title</label>
                    <input type="text" name="image_title[]" class="form-control" placeholder="Title"/>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group mb-2">
                    <label>Image</label>
                    <input type="file" name="image[]" class="form-control" placeholder="Image"/>
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
function businessdetaillist(){
    var search = $('#search_business_detail').val();

    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url: 'businessdetail/list',

        data: { search_business_detail: search },

        success:function(response)
        {

            $('.quicklookdetailDataList').html(response);

        }

    });
}


// service detail delete ajax call
function deletequicklookDetail(serviceIds){
    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url:'businessdetail/delete',

        data: { business_id: serviceIds },

        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {

                    // This function is called when the user clicks "OK"

                    businessdetaillist();

                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}