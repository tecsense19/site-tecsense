$(document).ready(function() {

    // Maximum number of groups can be added
    var maxGroup = 10;
    // Add more group of input fields
    $('body').on('click', '.whyChooseAddMore', function(e) {

        if($('body').find('.whyChooseFieldGroup').length < maxGroup){

            var fieldHTML = '<div class="whyChooseFieldGroup d-flex w-100">'+$(".whyChooseFieldGroupCopy").html()+'</div>';
            $('body').find('.whyChooseFieldGroup:last').after(fieldHTML);

        } else {

            alert('Maximum '+maxGroup+' groups are allowed.');

        }
    });
    
    let removeWhyChoose = $('#remove_why_choose_service');
    // Remove fields group
    $("body").on("click",".why-choose-remove",function() {
        if($(this).attr('data-id'))
        {
            let removeids = removeWhyChoose.val() ? removeWhyChoose.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeWhyChoose.val(removeids);
        }

        $(this).parents(".whyChooseFieldGroup").remove();
    });

    $("form[name='why_choose_model_form']").validate({
        // Specify validation rules
        rules: {
            why_choose_title: {
                required: true
            },
            why_choose_description: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            why_choose_title: {
                required: "Section title is required."
            },
            why_choose_description: {
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

                        $('.why_choose_close_model').trigger('click');

                        showSuccessMessage('Success', response.message, function() {
                            // This function is called when the user clicks "OK"
                            whyChooseList();
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

    whyChooseList();

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        getPerPageWhyChooseList(url);
    });

    let whyChooseModelForm = $("form[name='why_choose_model_form']")[0];

    let whyChooseModalLg = $("#why-choose-modal-lg");

    let whyChooseId = $('#why_choose_id');

    let whyChooseImages = $('.why-choose-images'); 

    $('body').on('click', '.why-choose-edit, .why-choose-add', function(e) {
        whyChooseModelForm.reset();
        whyChooseModalLg.modal("show");
        whyChooseId.val('');

        let rowData = $(this).attr('data-whychoose') ? $(this).attr('data-whychoose') : '';
        
        if(rowData)
        {
            rowData = JSON.parse(rowData);           

            whyChooseId.val(rowData.id);
            $('#why_choose_title').val(rowData.title);
            $('#why_choose_description').val(rowData.description);

            whyChooseImages.html('');
            if(rowData.why_choose_images.length > 0)
            {
                $.each(rowData.why_choose_images, function(index, item) {            

                    // Access properties of the current item
                    let imageTag = '';

                    let hidden = '';

                    if(item.image_path)
                    {
                        imageTag = '<img src="'+ appUrl + item.image_path +'" style="width: 25px; height: 25px; margin-top: 40px;"><i class="ml-2 icon-copy fa fa-times-circle-o why-choose-remove-image" aria-hidden="true" data-id="'+item.id+'" style="margin-top: 43px; cursor: pointer; color: #dc3545;"></i>';
                        hidden = 'none';
                    }

                    let buttons = '';
                    if(index == 0)
                    {
                        buttons = '';
                    }
                    else
                    {
                        buttons = '<a href="javascript:void(0)" class="btn btn-danger why-choose-remove" data-id="'+item.id+'" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>';
                    }

                    whyChooseImages.append(

                        `<div class="whyChooseFieldGroup d-flex w-100">

                            <div class="col-md-3 col-sm-12">

                                <div class="form-group mb-2">

                                    <label>Title</label>

                                    <input type="text" name="why_choose_image_title[]" class="form-control" placeholder="Image Title" value="`+item.title+`" >

                                    <input type="hidden" name="why_choose_image_id[]" id="why_choose_image_id" value="`+item.id+`" />

                                </div>

                            </div>

                            <div class="col-md-3 col-sm-12">

                                <div class="form-group mb-2">

                                    <label style="display: `+hidden+`">Image</label>

                                    <input type="file" name="why_choose_image[]" class="form-control" style="display: `+hidden+`" />

                                    <div class="why-choose-image-preview">

                                    `+imageTag+`

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group mb-2">
                                    <label>Description</label>
                                    <textarea name="why_choose_image_description[]" class="form-control" rows="3" placeholder="Description">`+item.description+`</textarea>
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

                whyChooseImages.append(
                    `<div class="col-md-4 col-sm-12">
                        <div class="form-group mb-2">
                            <a href="javascript:void(0);" class="btn btn-success whyChooseAddMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
                        </div>
                    </div>`
                )
            }
            else
            {
                setWhyChooseSectionBlankImages()
            }
        }
        else
        {
            setWhyChooseSectionBlankImages()
        }

    });

    let removeWhyChooseImages = $('#remove_why_choose_image');
    // Attach a click event handler to the remove-image icon
    $("body").on("click",".why-choose-remove-image",function(e) {
        var imagePreview = $(this).closest(".why-choose-image-preview");
        var label = $(this).closest(".form-group").find("label");
        var fileInput = $(this).closest(".form-group").find("input[type='file']");

        if($(this).attr('data-id'))
        {
            let removeids = removeWhyChooseImages.val() ? removeWhyChooseImages.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeWhyChooseImages.val(removeids);
        }

        // Remove the image preview and display the file input

        imagePreview.remove();

        label.show();

        fileInput.show();
    });

    $('body').on('click', '.why-choose-delete', function(e) {
        let whyChooseIds = $(this).attr('data-id');

        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this record?', function() {
            // This function is called when the user clicks "OK"
            whyChooseDelete(whyChooseIds)
        });
    });    

    $('#search_why_choose').on('keyup', function(e) {
        whyChooseList();
    });
});

function setWhyChooseSectionBlankImages()
{
    let whyChooseImages = $('.why-choose-images');
    whyChooseImages.html(
        `<div class="whyChooseFieldGroup d-flex w-100 full">
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Title</label>
                    <input type="text" name="why_choose_image_title[]" class="form-control" placeholder="Image Title"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Image</label>
                    <input type="file" name="why_choose_image[]" class="form-control" />
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Description</label>
                    <textarea name="why_choose_image_description[]" class="form-control" rows="3" placeholder="Description"></textarea>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group mb-2">
                <a href="javascript:void(0);" class="btn btn-success whyChooseAddMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
            </div>
        </div>`
    )
}

function whyChooseList()
{
    var search = $('#search_why_choose').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'why/choose/list',
        data: { search_why_choose: search },
        success:function(response)
        {
            $('.whyChooseDataList').html(response);
        }
    });
}

function getPerPageWhyChooseList(get_pagination_url) 
{
    var search = $('#search_why_choose').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:get_pagination_url,
        data: { search: search },
        success:function(data)
        {
            $('.whyChooseDataList').html(data);
        }
    });
}

function whyChooseDelete(whyChooseIds)
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:'why/choose/delete',
        data: { why_choose_id: whyChooseIds },
        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {
                    // This function is called when the user clicks "OK"
                    whyChooseList();
                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}