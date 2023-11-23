$(document).ready(function() {
    // Maximum number of groups can be added
    var maxGroup = 10;
        
    // Add more group of input fields
    $('body').on('click', '.vision-addMore', function(e) {
        
        if($('body').find('.visionFieldGroup').length < maxGroup){
            var fieldHTML = '<div class="visionFieldGroup d-flex w-100">'+$(".visionFieldGroupCopy").html()+'</div>';
            $('body').find('.visionFieldGroup:last').after(fieldHTML);
        }else{
            alert('Maximum '+maxGroup+' groups are allowed.');
        }
    });

    let removeVision = $('#remove_vision');

    // Remove fields group
    $("body").on("click",".vision-remove",function() { 

        if($(this).attr('data-id'))
        {
            let removeids = removeVision.val() ? removeVision.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeVision.val(removeids);
        }

        $(this).parents(".visionFieldGroup").remove();
    });

    let removeVisionImages = $('#remove_vision_image');

    // Attach a click event handler to the about-remove-image icon
    $("body").on("click",".vision-remove-image",function(e) {

        var imagePreview = $(this).closest(".vision-image-preview");
        var label = $(this).closest(".form-group").find("label");
        var fileInput = $(this).closest(".form-group").find("input[type='file']");

        if($(this).attr('data-id'))
        {
            let removeids = removeVisionImages.val() ? removeVisionImages.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeVisionImages.val(removeids);
        }
        // Remove the image preview and display the file input
        imagePreview.remove();
        label.show();
        fileInput.show();
    });

    $("form[name='vision_form']").validate({
        // Specify validation rules
        rules: {
            vision_text: {
                required: true
            },
            vision_title: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            vision_text: {
                required: "Text is required."
            },
            vision_title: {
                required: "Title is required."
            }
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form, event) {
            // form.submit();
            event.preventDefault();
            var formData = new FormData(form);

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
                        showSuccessMessage('Success', response.message, function() {
                            // This function is called when the user clicks "OK"
                            getVisionDetails();
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

    getVisionDetails();
});

function getVisionDetails()
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'our/vision/details',
        data: { },
        success:function(response)
        {
            let visionImages = $('.vision-images');

            if(response.success)
            {
                let rowData = response.data;
                if(rowData)
                {
                    $('#vision_id').val(rowData.id);
                    $('#vision_text').val(rowData.text);
                    $('#vision_title').val(rowData.title);
                    $('#remove_vision').val('');
                    $('#remove_vision_image').val('');

                    $('.vision-image-preview').html('');
                    if(rowData.section_image)
                    {
                        $('.vision-image-preview').html('<img src="'+ appUrl + rowData.section_image+'" style="width: 100px; height: 100px;">')
                    }

                    visionImages.html('');
                    $.each(rowData.vision_images, function(index, item) {            
                        // Access properties of the current item

                        let imageTag = '';
                        let hidden = '';
                        if(item.image_path)
                        {
                            imageTag = '<img src="'+ appUrl + item.image_path+'" style="width: 25px; height: 25px; margin-top: 40px;"><i class="ml-2 icon-copy fa fa-times-circle-o vision-remove-image" aria-hidden="true" data-id="'+item.id+'" style="margin-top: 43px; cursor: pointer; color: #dc3545;"></i>';
                            hidden = 'none';
                        }

                        let buttons = '';
                        if(index == 0)
                        {
                            buttons = '';
                        }
                        else
                        {
                            buttons = '<a href="javascript:void(0)" class="btn btn-danger vision-remove" data-id="'+item.id+'" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>';
                        }

                        visionImages.append(
                            `<div class="visionFieldGroup d-flex w-100">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label>Title</label>
                                        <input type="text" name="vision_image_title[]" class="form-control" placeholder="Image Title" value="`+item.title+`" />
                                        <input type="hidden" name="vision_image_id[]" id="image_id" value="`+item.id+`" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label style="display: `+hidden+`">Image</label>
                                        <input type="file" name="vision_image[]" class="form-control" style="display: `+hidden+`" />
                                        <div class="vision-image-preview">
                                        `+imageTag+`
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label>Description</label>
                                        <textarea class="form-control" name="vision_description[]" id="vision_description" rows="3" placeholder="Description">`+ item.description +`</textarea>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group mb-2">
                                        `+buttons+`
                                    </div>
                                </div>
                            </div>`
                        );
                    });
                    visionImages.append(
                        `<div class="col-md-3 col-sm-12">
                            <div class="form-group mb-2">
                                <a href="javascript:void(0);" class="btn btn-success vision-addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
                            </div>
                        </div>`
                    )
                }
                else
                {
                    visionImageAdd();
                }
            }
            else
            {
                visionImageAdd();
            }
        }
    });
}

function visionImageAdd() 
{
    let visionImages = $('.vision-images');
    visionImages.html(
        `<div class="visionFieldGroup d-flex w-100 full">
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Title</label>
                    <input type="text" name="vision_image_title[]" class="form-control" placeholder="Image Title"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Image</label>
                    <input type="file" name="vision_image[]" class="form-control" />
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group mb-2">
                    <label>Description</label>
                    <textarea class="form-control" name="vision_description[]" id="vision_description" rows="3" placeholder="Description"></textarea>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-12">
            <div class="form-group mb-2">
                <a href="javascript:void(0);" class="btn btn-success vision-addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
            </div>
        </div>`
    );
}