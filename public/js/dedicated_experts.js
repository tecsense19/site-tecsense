$(document).ready(function() {

    // Maximum number of groups can be added
    var maxGroup = 10;
    // Add more group of input fields
    $('body').on('click', '.addMore', function(e) {

        if($('body').find('.expertFieldGroup').length < maxGroup){

            var fieldHTML = '<div class="expertFieldGroup d-flex w-100">'+$(".expertFieldGroupCopy").html()+'</div>';
            $('body').find('.expertFieldGroup:last').after(fieldHTML);

        } else {

            alert('Maximum '+maxGroup+' groups are allowed.');

        }
    });
    
    let removeExpert = $('#remove_expert_service');
    // Remove fields group
    $("body").on("click",".expert-remove",function() {
        if($(this).attr('data-id'))
        {
            let removeids = removeExpert.val() ? removeExpert.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeExpert.val(removeids);
        }

        $(this).parents(".expertFieldGroup").remove();
    });

    $("form[name='dedicated_experts_model_form']").validate({
        // Specify validation rules
        rules: {
            expert_title: {
                required: true
            },
            expert_description: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            expert_title: {
                required: "Section title is required."
            },
            expert_description: {
                required: "Section description is required."
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

                        $('.expert_close_model').trigger('click');

                        showSuccessMessage('Success', response.message, function() {
                            // This function is called when the user clicks "OK"
                            expertList();
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

    expertList();

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        getPerPageExpertList(url);
    });

    let dedicatedExpertsModelForm = $("form[name='dedicated_experts_model_form']")[0];

    let dedicatedExpertsModalLg = $("#dedicated-experts-modal-lg");

    let dedicatedExpertsId = $('#expert_id');

    let expertImages = $('.expert-images'); 

    $('body').on('click', '.expert-edit, .expert-add', function(e) {
        dedicatedExpertsModelForm.reset();
        dedicatedExpertsModalLg.modal("show");
        dedicatedExpertsId.val('');

        let rowData = $(this).attr('data-expert') ? $(this).attr('data-expert') : '';
        
        if(rowData)
        {
            rowData = JSON.parse(rowData);           

            dedicatedExpertsId.val(rowData.id);
            $('#expert_title').val(rowData.title);
            $('#expert_description').val(rowData.description);

            expertImages.html('');
            if(rowData.expert_images.length > 0)
            {
                $.each(rowData.expert_images, function(index, item) {            

                    // Access properties of the current item
                    let imageTag = '';

                    let hidden = '';

                    if(item.image_path)
                    {
                        imageTag = '<img src="'+ appUrl + item.image_path +'" style="width: 25px; height: 25px; margin-top: 40px;"><i class="ml-2 icon-copy fa fa-times-circle-o expert-remove-image" aria-hidden="true" data-id="'+item.id+'" style="margin-top: 43px; cursor: pointer; color: #dc3545;"></i>';
                        hidden = 'none';
                    }

                    let buttons = '';
                    if(index == 0)
                    {
                        buttons = '';
                    }
                    else
                    {
                        buttons = '<a href="javascript:void(0)" class="btn btn-danger expert-remove" data-id="'+item.id+'" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>';
                    }

                    expertImages.append(

                        `<div class="expertFieldGroup d-flex w-100">

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group mb-2">

                                    <label>Title</label>

                                    <input type="text" name="expert_image_title[]" class="form-control" placeholder="Image Title" value="`+item.title+`" >

                                    <input type="hidden" name="image_id[]" id="image_id" value="`+item.id+`" />

                                </div>

                            </div>

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group mb-2">

                                    <label style="display: `+hidden+`">Image</label>

                                    <input type="file" name="expert_image[]" class="form-control" style="display: `+hidden+`" />

                                    <div class="expert-image-preview">

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

                expertImages.append(
                    `<div class="col-md-4 col-sm-12">
                        <div class="form-group mb-2">
                            <a href="javascript:void(0);" class="btn btn-success addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
                        </div>
                    </div>`
                )
            }
            else
            {
                setExpertsSectionBlankImages()
            }
        }
        else
        {
            setExpertsSectionBlankImages()
        }

    });

    let removeExpertImages = $('#remove_expert_image');
    // Attach a click event handler to the remove-image icon
    $("body").on("click",".expert-remove-image",function(e) {
        var imagePreview = $(this).closest(".expert-image-preview");
        var label = $(this).closest(".form-group").find("label");
        var fileInput = $(this).closest(".form-group").find("input[type='file']");

        if($(this).attr('data-id'))
        {
            let removeids = removeExpertImages.val() ? removeExpertImages.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeExpertImages.val(removeids);
        }

        // Remove the image preview and display the file input

        imagePreview.remove();

        label.show();

        fileInput.show();
    });

    $('body').on('click', '.expert-delete', function(e) {
        let expertIds = $(this).attr('data-id');

        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this record?', function() {
            // This function is called when the user clicks "OK"
            expertDelete(expertIds)
        });
    });    

    $('#search_expert').on('keyup', function(e) {
        expertList();
    });
});

function setExpertsSectionBlankImages()
{
    let expertImages = $('.expert-images');
    expertImages.html(
        `<div class="expertFieldGroup d-flex w-100 full">
            <div class="col-md-4 col-sm-12">
                <div class="form-group mb-2">
                    <label>Title</label>
                    <input type="text" name="expert_image_title[]" class="form-control" placeholder="Image Title"/>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group mb-2">
                    <label>Image</label>
                    <input type="file" name="expert_image[]" class="form-control" />
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

function expertList()
{
    var search = $('#search_expert').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'expert/list',
        data: { search_expert: search },
        success:function(response)
        {
            $('.expertDataList').html(response);
        }
    });
}

function getPerPageExpertList(get_pagination_url) 
{
    var search = $('#search_expert').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:get_pagination_url,
        data: { search: search },
        success:function(data)
        {
            $('.expertDataList').html(data);
        }
    });
}

function expertDelete(expertIds)
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:'expert/delete',
        data: { expert_id: expertIds },
        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {
                    // This function is called when the user clicks "OK"
                    expertList();
                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}