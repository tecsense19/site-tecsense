$(document).ready(function(){

       $("form[name='why_choose_detail_model_form']").validate({
        // Specify validation rules
        
            rules: {
                service_id: {
                    required: true
                },
                why_choose_detail_title: {
                    required: true
                },
                why_choose_detail_description: {
                    required: true
                }
            },
            // Specify validation error messages
            messages: {
                service_id: {
                    required: "Our Service is required."
                },
                why_choose_detail_title: {
                    required: "Why Choose Title is required."
                },
                why_choose_detail_description: {
                    required: "Why Choose Description is required."
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
                            $('.whychoose_close_model').trigger('click');
                            showSuccessMessage('Success', response.message, function() {
                                // This function is called when the user clicks "OK"
                                whychoosedetaillist()
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

       whychoosedetaillist()

    // click to modal open
    let servicedetail_images = $('.why-choose-detail-images');
    let whychooseDetailModelForm = $("form[name='why_choose_detail_model_form']")[0];
    $("body").on("click",".why-choose-edit, .whychoosedetail-add", function(){
        
        whychooseDetailModelForm.reset();
        $("#why-choose-detail-modal-xl").modal("show");

        let rowData = $(this).attr('data-why_choose-detail') ? $(this).attr('data-why_choose-detail') : '';
        // setBlankImages();
        if(rowData)
        {
            rowData = JSON.parse(rowData);
            console.log(rowData);      

            $('#why_choose_detail_id').val(rowData.id);

            $('#why_choose_id').val(rowData.why_choose_id);

            $('#why_choose_detail_description').val(rowData.why_choose_detail_description);

            $('#why_choose_detail_title').val(rowData.why_choose_detail_title);

            $('.profile_image_preview').html('');

            if(rowData.why_choose_detail_pic)
            {
                $('.profile_image_preview').html('<img src="'+ appUrl + rowData.why_choose_detail_pic +'" style="width: 25px; height: 25px;" />');
            }

            servicedetail_images.html('');
            if(rowData.why_choose_detail_images.length > 0)
            {
                $.each(rowData.why_choose_detail_images, function(index, item) {            

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

    // service detail search
    $('#search_Why_choose_detail').on('keyup', function(e) {
        whychoosedetaillist();
    });

    // service detail delete
    $('body').on('click', '.why-choose-detail-delete', function(e) {

        let whyChooseIds = $(this).attr('data-id');

        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this serviceDetail?', function() {
            // This function is called when the user clicks "OK"
            deleteWhyChooseDetail(whyChooseIds)
        });

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

    // Attach a click event handler to the remove-image icon
    let removeImages = $('#remove_why_choose_detail_pic');
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

    // Remove fields group
    let removeServices = $('#remove_why_choose_detail');
    $("body").on("click",".remove",function() { 
       if($(this).attr('data-id'))
       {
           let removeids = removeServices.val() ? removeServices.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')

           removeServices.val(removeids);

       }
       $(this).parents(".fieldGroup").remove();
   });
});


// service detail get
function whychoosedetaillist(){
    var search = $('#search_Why_choose_detail').val();
    console.log(search);

    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url: 'whychoosedetail/list',

        data: { search_Why_choose_detail: search },

        success:function(response)
        {

            $('.whychoosedetailDataList').html(response);

        }

    });
}


function setBlankImages()
{
    let serviceImages = $('.why-choose-detail-images');
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



// service detail delete ajax call
function deleteWhyChooseDetail(whyChooseIds){
    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url:'whychoosedetail/delete',

        data: { why_choose_id: whyChooseIds },

        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {

                    // This function is called when the user clicks "OK"

                    whychoosedetaillist();

                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}