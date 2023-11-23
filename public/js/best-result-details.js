$(document).ready(function(){

    $("form[name='best_result_detail_model_form']").validate({
        // Specify validation rules
        
        rules: {
            service_id: {
                required: true
            },
            text: {
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
                        $('.feature_close_model').trigger('click');
                        showSuccessMessage('Success', response.message, function() {
                            // This function is called when the user clicks "OK"
                            bestresultdetaillist();
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

    bestresultdetaillist();

    let best_result_detail_images = $('.best-result-detail-images');
    let bestResultDetailModelForm = $("form[name='best_result_detail_model_form']")[0];

    $("body").on("click",".best-result-detia-edit, .best-result-detial-add",function() {

        bestResultDetailModelForm.reset();
        $("#best-result-detail-modal-xl").modal("show");

        let rowData = $(this).attr('data-best-result-detail') ? $(this).attr('data-best-result-detail') : '';
        if(rowData){

            rowData = JSON.parse(rowData);

            console.log(rowData);
            $('#best_result_detail_id').val(rowData.id);
            $('#text').val(rowData.text);
            $('#service_id').val(rowData.service_id);
            
            best_result_detail_images.html('');
            if(rowData.best_result_detail_images.length > 0)
            {
                $.each(rowData.best_result_detail_images, function(index, item) {            

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

                    best_result_detail_images.append(

                        `<div class="fieldGroup d-flex w-100">

                            <div class="col-md-4 col-sm-12">

                                <div class="form-group mb-2">

                                    <label>Step Title</label>

                                    <input type="text" name="step_title[]" class="form-control" placeholder="Step Title" value="`+item.step_title+`" >

                                </div>

                            </div>

                            <div class="col-md-3 col-sm-12">

                                <div class="form-group mb-2">

                                    <label>Title</label>

                                    <input type="text" name="image_title[]" class="form-control" placeholder="Title" value="`+item.title+`" >

                                    <input type="hidden" name="image_id[]" id="image_id" value="`+item.id+`" />

                                </div>

                            </div>

                            <div class="col-md-3 col-sm-12">

                                <div class="form-group mb-2">

                                    <label style="display: `+hidden+`">Image</label>

                                    <input type="file" name="service_image[]" class="form-control" placeholder="Service Image" style="display: `+hidden+`" />

                                    <div class="image-preview">

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

                best_result_detail_images.append(
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
    let removeServices = $('#remove_result_detail');
    $("body").on("click",".remove",function() { 
       if($(this).attr('data-id'))
       {
           let removeids = removeServices.val() ? removeServices.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')

           removeServices.val(removeids);

       }
       $(this).parents(".fieldGroup").remove();
    });

    // service detail search
    $('#search_best_result_detail').on('keyup', function(e) {
        bestresultdetaillist();
    });

    // Attach a click event handler to the remove-image icon
    let removeImages = $('#remove_result_detail_pic');
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


    $('body').on('click', '.best-result-detail-delete', function(e) {

        let serviceIds = $(this).attr('data-id');

        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this bestResultDetail?', function() {

            // This function is called when the user clicks "OK"

            deleteBestResultDetail(serviceIds)

        });

    });
});


function setBlankImages()
{
    let serviceImages = $('.best-result-detail-images');
    serviceImages.html(
        `<div class="fieldGroup d-flex w-100 full">
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Title</label>
                    <input type="text" name="step_title[]" class="form-control" placeholder="Step Title"/>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Title</label>
                    <input type="text" name="image_title[]" class="form-control" placeholder="Title"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Image</label>
                    <input type="file" name="service_image[]" class="form-control" placeholder="Service Image"/>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group mb-2">
                <a href="javascript:void(0);" class="btn btn-success addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
            </div>
        </div>`
    )
}

// service detail get
function bestresultdetaillist(){
    var search = $('#search_best_result_detail').val();

    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url: 'bestresultdetail/list',

        data: { search_best_result_detail: search },

        success:function(response)
        {

            $('.bestresultdetailDataList').html(response);

        }

    });
}


// service detail delete ajax call
function deleteBestResultDetail(serviceIds){
    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url:'bestresultdetail/delete',

        data: { Best_result_id: serviceIds },

        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {

                    // This function is called when the user clicks "OK"

                    bestresultdetaillist();

                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}