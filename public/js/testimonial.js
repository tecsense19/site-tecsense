$(document).ready(function(){

    $("form[name='testimonial_model_form']").validate({
        // Specify validation rules
        rules: {
            full_name: {
                required: true
            },
            country: {
                required: true
            },
            client_description: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            full_name: {
                required: "Full name is required."
            },
            country: {
                required: "Country is required."
            },
            client_description: {
                required: "Client description is required."
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
                        $('.tec_close_model').trigger('click');
                        showSuccessMessage('Success', response.message, function() {
                            // This function is called when the user clicks "OK"
                            testimonialList();
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

    testimonialList();
    $('body').on('click', '.pagination a', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');
        getPerPageTestimonialList(url);
    });

    let testimonialModelForm = $("form[name='testimonial_model_form']")[0];
    let testimonialModalLg = $("#testimonial-modal-lg");
    let testimonialImages = $('#testimonial_images');
    let testimonialId = $('#testimonial_id');
    let profilePic = $('#profile_pic');
    let removeProfilePic = $('#remove_profile_pic');
    let profileImagePreview = $('#profile_image_preview')

    $('body').on('click', '.testimonial-edit, .testimonial-add', function(e) {

        testimonialModelForm.reset();
        testimonialModalLg.modal("show");
        testimonialId.val('');
        removeProfilePic.val('');
        
        let rowData = $(this).attr('data-testimonial') ? $(this).attr('data-testimonial') : '';

        if(rowData)
        {
            rowData = JSON.parse(rowData);
            
            testimonialId.val(rowData.id);
            $('#full_name').val(rowData.full_name);
            $('#country').val(rowData.country);
            $('#client_description').val(rowData.client_description);

            if(rowData.profile_pic != '')
            {
                profilePic.css('display', 'none');
                profileImagePreview.html('<img src="'+ appUrl + rowData.profile_pic +'" style="width: 50px; height: 50px;"><i class="ml-2 icon-copy fa fa-times-circle-o profile-remove-image" aria-hidden="true" data-id="'+rowData.id+'" style="cursor: pointer; color: #dc3545;"></i>');
            }
            else
            {
                profilePic.css('display', 'block');
            }           

            testimonialImages.html('');
            if(rowData.testimonial_images)
            {
                $.each(rowData.testimonial_images, function(index, item) {            
                    // Access properties of the current item

                    let imageTag = '';
                    if(item.image_path)
                    {
                        imageTag = '<img src="'+ appUrl + item.image_path +'" style="width: 100%; height: 25px;"><i class="ml-2 icon-copy fa fa-times-circle-o testimonial-remove-image" aria-hidden="true" data-id="'+item.id+'" style="cursor: pointer; color: #dc3545;"></i>';
                        hidden = 'none';
                    }

                    testimonialImages.append(
                        `<div class="testimonial-image-preview mx-2">
                        `+imageTag+`
                        </div>`
                    );
                });
            }
        }
        else
        {
            testimonialImages.html('');
            profilePic.css('display', 'block');
            profileImagePreview.html('')
        }
    });

    let removeTestimonialImg = $('#remove_testimonial_image');

    // Attach a click event handler to the testimonial-remove-image icon
    $("body").on("click",".testimonial-remove-image",function(e) {

        var imagePreview = $(this).closest(".testimonial-image-preview");

        if($(this).attr('data-id'))
        {
            let removeids = removeTestimonialImg.val() ? removeTestimonialImg.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeTestimonialImg.val(removeids);
        }
        // Remove the image preview and display the file input
        imagePreview.remove();
    });

    $('body').on('click', '.testimonial-delete', function(e) {

        let testimonialIds = $(this).attr('data-id');
        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this testimonial?', function() {
            // This function is called when the user clicks "OK"
            testimonialDelete(testimonialIds)
        });
    });
    
    $('#search_testimonial').on('keyup', function(e) {
        testimonialList();
    });

    $('body').on('click', '.profile-remove-image', function(e) {
        if($(this).attr('data-id'))
        {
            profilePic.css('display', 'block');
        }
        removeProfilePic.val($(this).attr('data-id'));
        profileImagePreview.html('');
    });
});
function testimonialList()
{
    var search = $('#search_testimonial').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'testimonial/list',
        data: { search_testimonial: search },
        success:function(response)
        {
            $('.testimonialDataList').html(response);
        }
    });
}

function getPerPageTestimonialList(get_pagination_url) 
{
    var search = $('#search_testimonial').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:get_pagination_url,
        data: { search: search },
        success:function(data)
        {
            $('.testimonialDataList').html(data);
        }
    });   
}

function testimonialDelete(testimonialIds)
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:'testimonial/delete',
        data: { testimonial_id: testimonialIds },
        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {
                    // This function is called when the user clicks "OK"
                    testimonialList();
                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}