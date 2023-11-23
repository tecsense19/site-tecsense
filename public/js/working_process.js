$(document).ready(function() {
    
    $("form[name='working_process_form']").validate({
        // Specify validation rules
        rules: {
            working_process_text: {
                required: true
            },
            working_process_title: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            working_process_text: {
                required: "Text is required."
            },
            working_process_title: {
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
                            getWorkingProcessDetails();
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

    $('body').on('click', '.working-process-delete', function(e) {
        let processImageIds = $(this).attr('data-id');
        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this image?', function() {
            // This function is called when the user clicks "OK"
            deleteProcessImg(processImageIds)
        });
    });

    getWorkingProcessDetails();
});

function getWorkingProcessDetails()
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'working/process/details',
        data: { },
        success:function(response)
        {
            let workingProcessImages = $('.working-process-images');

            if(response.success)
            {
                let rowData = response.data;
                if(rowData)
                {
                    $('#working_process_id').val(rowData.id);
                    $('#working_process_text').val(rowData.text);
                    $('#working_process_title').val(rowData.title);

                    workingProcessImages.html('');
                    $.each(rowData.process_images, function(index, item) {            
                        // Access properties of the current item

                        workingProcessImages.append(
                            `<div class="col-lg-6 col-md-6 col-sm-12 mb-30">
                                <div class="da-card">
                                    <div class="da-card-photo">
                                        <img src="`+ appUrl + item.image_path +`" alt="">
                                        <div class="da-overlay">
                                            <div class="da-social">
                                                <ul class="clearfix">
                                                    <li><a href="#" class="working-process-delete" data-id="`+ item.id +`"><i class="fa fa-trash"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                        );
                    });
                }
            }
        }
    });
}

function deleteProcessImg(processImageIds)
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:'working/process/delete',
        data: { working_process_id: processImageIds },
        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {
                    // This function is called when the user clicks "OK"
                    getWorkingProcessDetails();
                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}