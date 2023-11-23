$(document).ready(function(){

    // Maximum number of groups can be added

    var maxGroup = 10;

    

    // Add more group of input fields

    $('body').on('click', '.tec-addMore', function(e) {

        

        if($('body').find('.tecFieldGroup').length < maxGroup){

            var fieldHTML = '<div class="tecFieldGroup d-flex w-100">'+$(".tecFieldGroupCopy").html()+'</div>';

            $('body').find('.tecFieldGroup:last').after(fieldHTML);

        }else{

            alert('Maximum '+maxGroup+' groups are allowed.');

        }

    });

    

    let removeTechnology = $('#remove_technology');



    // Remove fields group

    $("body").on("click",".tec-remove",function() { 



        if($(this).attr('data-id'))

        {

            let removeids = removeTechnology.val() ? removeTechnology.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')

            removeTechnology.val(removeids);

        }



        $(this).parents(".tecFieldGroup").remove();

    });



    $("form[name='technology_model_form']").validate({

        // Specify validation rules

        rules: {

            technology_name: {

                required: true

            },

            technology_description: {

                required: true

            }

        },

        // Specify validation error messages

        messages: {

            technology_name: {

                required: "Technology name is required."

            },

            technology_description: {

                required: "Technology description is required."

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

                            technologyList();

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



    technologyList();

    $('body').on('click', '.pagination a', function(e) {



        e.preventDefault();



        var url = $(this).attr('href');

        getPerPageTechnologyList(url);

    });



    let technologyModelForm = $("form[name='technology_model_form']")[0];

    let technologyModalLg = $("#technology-modal-lg");

    let technologyImages = $('.technology-images');

    let technologyId = $('#technology_id');



    $('body').on('click', '.technology-edit, .technology-add', function(e) {



        technologyModelForm.reset();

        technologyModalLg.modal("show");

        removeTechnology.val('');

        technologyId.val('');



        let rowData = $(this).attr('data-technology') ? $(this).attr('data-technology') : '';

        if(rowData)

        {

            rowData = JSON.parse(rowData);

            

            $('#technology_id').val(rowData.id);

            $('#technology_name').val(rowData.technology_name);

            $('#technology_description').val(rowData.technology_description);



            technologyImages.html('');

            $.each(rowData.technology_images, function(index, item) {            

                // Access properties of the current item



                let imageTag = '';

                let hidden = '';

                if(item.image_path)

                {

                    imageTag = '<img src="'+ appUrl + item.image_path +'" style="width: 25px; height: 25px; margin-top: 40px;"><i class="ml-2 icon-copy fa fa-times-circle-o tec-remove-image" aria-hidden="true" data-id="'+item.id+'" style="margin-top: 43px; cursor: pointer; color: #dc3545;"></i>';

                    hidden = 'none';

                }



                let buttons = '';

                if(index == 0)

                {

                    buttons = '';

                }

                else

                {

                    buttons = '<a href="javascript:void(0)" class="btn btn-danger tec-remove" data-id="'+item.id+'" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>';

                }



                technologyImages.append(

                    `<div class="tecFieldGroup d-flex w-100">

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group mb-2">

                                <label>Title</label>

                                <input type="text" name="tec_image_title[]" class="form-control" placeholder="Image Title" value="` + item.title +`" />

                                <input type="hidden" name="tec_image_id[]" id="image_id" value="`+ item.id +`" />

                            </div>

                        </div>

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group mb-2">

                                <label style="display: `+ hidden +`">Image</label>

                                <input type="file" name="tec_image[]" class="form-control" style="display: `+ hidden +`" />

                                <div class="tec-image-preview">

                                `+ imageTag +`

                                </div>

                            </div>

                        </div>

                        <div class="col-md-4 col-sm-12">

                            <div class="form-group mb-2">

                                `+ buttons +`

                            </div>

                        </div>

                    </div>`

                );

            });

            technologyImages.append(

                `<div class="col-md-4 col-sm-12">

                    <div class="form-group mb-2">

                        <a href="javascript:void(0);" class="btn btn-success tec-addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>

                    </div>

                </div>`

            )

        }

        else

        {

            technologyImages.html(

                `<div class="tecFieldGroup d-flex w-100 full">

                    <div class="col-md-4 col-sm-12">

                        <div class="form-group mb-2">

                            <label>Title</label>

                            <input type="text" name="tec_image_title[]" class="form-control" placeholder="Image Title" />

                        </div>

                    </div>

                    <div class="col-md-4 col-sm-12">

                        <div class="form-group mb-2">

                            <label>Image</label>

                            <input type="file" name="tec_image[]" class="form-control" />

                        </div>

                    </div>

                </div>

                <div class="col-md-4 col-sm-12">

                    <div class="form-group mb-2">

                        <a href="javascript:void(0);" class="btn btn-success tec-addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>

                    </div>

                </div>`

            )

        }

    });



    let removeTecImages = $('#remove_tec_image');



    // Attach a click event handler to the tec-remove-image icon

    $("body").on("click",".tec-remove-image",function(e) {



        var imagePreview = $(this).closest(".tec-image-preview");

        var label = $(this).closest(".form-group").find("label");

        var fileInput = $(this).closest(".form-group").find("input[type='file']");



        if($(this).attr('data-id'))

        {

            let removeids = removeTecImages.val() ? removeTecImages.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')

            removeTecImages.val(removeids);

        }

        // Remove the image preview and display the file input

        imagePreview.remove();

        label.show();

        fileInput.show();

    });



    $('body').on('click', '.technology-delete', function(e) {



        let technologyIds = $(this).attr('data-id');

        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this technology?', function() {

            // This function is called when the user clicks "OK"

            technologyDelete(technologyIds)

        });

    });

    

    $('#search_technology').on('keyup', function(e) {

        technologyList();

    });

});

function technologyList()

{

    var search = $('#search_technology').val();

    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url: 'technology/list',

        data: { search_technology: search },

        success:function(response)

        {

            $('.technologyDataList').html(response);

        }

    });

}



function getPerPageTechnologyList(get_pagination_url) 

{

    var search = $('#search_technology').val();

    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url:get_pagination_url,

        data: { search: search },

        success:function(data)

        {

            $('.technologyDataList').html(data);

        }

    });   

}



function technologyDelete(technologyIds)

{

    $.ajax({

        type:'post',

        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},

        url:'technology/delete',

        data: { technology_id: technologyIds },

        success:function(response)

        {

            if(response.success)

            {

                showSuccessMessage('Success', response.message, function() {

                    // This function is called when the user clicks "OK"

                    technologyList();

                });

            }

            else

            {

                showErrorMessage('Error', response.message)

            }

        }

    });

}