$(document).ready(function() {
    // Maximum number of groups can be added
    var maxGroup = 10;
        
    // Add more group of input fields
    $('body').on('click', '.about-addMore', function(e) {
        
        if($('body').find('.aboutFieldGroup').length < maxGroup){
            var fieldHTML = '<div class="aboutFieldGroup d-flex w-100">'+$(".aboutFieldGroupCopy").html()+'</div>';
            $('body').find('.aboutFieldGroup:last').after(fieldHTML);
        }else{
            alert('Maximum '+maxGroup+' groups are allowed.');
        }
    });

    let removeAbout = $('#remove_about');

    // Remove fields group
    $("body").on("click",".about-remove",function() { 

        if($(this).attr('data-id'))
        {
            let removeids = removeAbout.val() ? removeAbout.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeAbout.val(removeids);
        }

        $(this).parents(".aboutFieldGroup").remove();
    });

    let removeAboutImages = $('#remove_about_image');

    // Attach a click event handler to the about-remove-image icon
    $("body").on("click",".about-remove-image",function(e) {

        var imagePreview = $(this).closest(".about-image-preview");
        var label = $(this).closest(".form-group").find("label");
        var fileInput = $(this).closest(".form-group").find("input[type='file']");

        if($(this).attr('data-id'))
        {
            let removeids = removeAboutImages.val() ? removeAboutImages.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeAboutImages.val(removeids);
        }
        // Remove the image preview and display the file input
        imagePreview.remove();
        label.show();
        fileInput.show();
    });

    $("form[name='about_form']").validate({
        // Specify validation rules
        rules: {
            about_text: {
                required: true
            },
            about_title: {
                required: true
            },
            about_description: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            about_text: {
                required: "Text is required."
            },
            about_title: {
                required: "Title is required."
            },
            about_description: {
                required: "Description is required."
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
                            getAboutDetails();
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

    getAboutDetails();
});

function getAboutDetails()
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'about/details',
        data: { },
        success:function(response)
        {
            let aboutImages = $('.about-images');

            if(response.success)
            {
                let rowData = response.data;
                if(rowData)
                {
                    $('#about_id').val(rowData.id);
                    $('#about_text').val(rowData.text);
                    $('#about_title').val(rowData.title);
                    $('#about_description').val(rowData.description);
                    $('#remove_about').val('');
                    $('#remove_about_image').val('');

                    $('.section-image-preview').html('');
                    if(rowData.section_image)
                    {
                        $('.section-image-preview').html('<img src="'+ appUrl + rowData.section_image+'" style="width: 100px; height: 100px;">')
                    }

                    aboutImages.html('');
                    $.each(rowData.about_images, function(index, item) {            
                        // Access properties of the current item

                        let imageTag = '';
                        let hidden = '';
                        if(item.image_path)
                        {
                            imageTag = '<img src="'+ appUrl + item.image_path+'" style="width: 25px; height: 25px; margin-top: 40px;"><i class="ml-2 icon-copy fa fa-times-circle-o about-remove-image" aria-hidden="true" data-id="'+item.id+'" style="margin-top: 43px; cursor: pointer; color: #dc3545;"></i>';
                            hidden = 'none';
                        }

                        let buttons = '';
                        if(index == 0)
                        {
                            buttons = '';
                        }
                        else
                        {
                            buttons = '<a href="javascript:void(0)" class="btn btn-danger about-remove" data-id="'+item.id+'" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>';
                        }

                        aboutImages.append(
                            `<div class="aboutFieldGroup d-flex w-100">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label>Title</label>
                                        <input type="text" name="about_image_title[]" class="form-control" placeholder="Image Title" value="`+item.title+`" />
                                        <input type="hidden" name="about_image_id[]" id="image_id" value="`+item.id+`" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label style="display: `+hidden+`">Image</label>
                                        <input type="file" name="about_image[]" class="form-control" style="display: `+hidden+`" />
                                        <div class="about-image-preview">
                                        `+imageTag+`
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group mb-2">
                                        `+buttons+`
                                    </div>
                                </div>
                            </div>`
                        );
                    });
                    aboutImages.append(
                        `<div class="col-md-3 col-sm-12">
                            <div class="form-group mb-2">
                                <a href="javascript:void(0);" class="btn btn-success about-addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
                            </div>
                        </div>`
                    )
                }
                else
                {
                    aboutImageAdd();
                }
            }
            else
            {
                aboutImageAdd();
            }
        }
    });
}

function aboutImageAdd() 
{
    let aboutImages = $('.about-images');
    aboutImages.html(
        `<div class="aboutFieldGroup d-flex w-100 full">
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Title</label>
                    <input type="text" name="about_image_title[]" class="form-control" placeholder="Image Title"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Image</label>
                    <input type="file" name="about_image[]" class="form-control" />
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group mb-2">
                <a href="javascript:void(0);" class="btn btn-success about-addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
            </div>
        </div>`
    )    
}