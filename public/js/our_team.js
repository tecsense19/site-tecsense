$(document).ready(function() {
    // Maximum number of groups can be added
    var maxGroup = 10;
        
    // Add more group of input fields
    $('body').on('click', '.team-addMore', function(e) {
        
        if($('body').find('.teamFieldGroup').length < maxGroup){
            var fieldHTML = '<div class="teamFieldGroup d-flex w-100">'+$(".teamFieldGroupCopy").html()+'</div>';
            $('body').find('.teamFieldGroup:last').after(fieldHTML);
        }else{
            alert('Maximum '+maxGroup+' groups are allowed.');
        }
    });

    let removeTeam = $('#remove_team');

    // Remove fields group
    $("body").on("click",".team-remove",function() { 

        if($(this).attr('data-id'))
        {
            let removeids = removeTeam.val() ? removeTeam.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeTeam.val(removeids);
        }

        $(this).parents(".teamFieldGroup").remove();
    });

    let removeTeamImages = $('#remove_team_image');

    // Attach a click event handler to the about-remove-image icon
    $("body").on("click",".team-remove-image",function(e) {

        var imagePreview = $(this).closest(".team-image-preview");
        var label = $(this).closest(".form-group").find("label");
        var fileInput = $(this).closest(".form-group").find("input[type='file']");

        if($(this).attr('data-id'))
        {
            let removeids = removeTeamImages.val() ? removeTeamImages.val() + ',' + $(this).attr('data-id') : $(this).attr('data-id')
            removeTeamImages.val(removeids);
        }
        // Remove the image preview and display the file input
        imagePreview.remove();
        label.show();
        fileInput.show();
    });

    $("form[name='team_form']").validate({
        // Specify validation rules
        rules: {
            team_text: {
                required: true
            },
            team_title: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            team_text: {
                required: "Text is required."
            },
            team_title: {
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
                            getTeamDetails();
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

    getTeamDetails();
});

function getTeamDetails()
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'our/team/details',
        data: { },
        success:function(response)
        {
            let teamImages = $('.team-images');

            if(response.success)
            {
                let rowData = response.data;
                if(rowData)
                {
                    $('#team_id').val(rowData.id);
                    $('#team_text').val(rowData.text);
                    $('#team_title').val(rowData.title);
                    $('#remove_team').val('');
                    $('#remove_team_image').val('');

                    $('.team-image-preview').html('');
                    if(rowData.section_image)
                    {
                        $('.team-image-preview').html('<img src="'+ appUrl + rowData.section_image+'" style="width: 100px; height: 100px;">')
                    }

                    teamImages.html('');
                    $.each(rowData.team_images, function(index, item) {            
                        // Access properties of the current item

                        let imageTag = '';
                        let hidden = '';
                        if(item.image_path)
                        {
                            imageTag = '<img src="'+ appUrl + item.image_path+'" style="width: 25px; height: 25px; margin-top: 40px;"><i class="ml-2 icon-copy fa fa-times-circle-o team-remove-image" aria-hidden="true" data-id="'+item.id+'" style="margin-top: 43px; cursor: pointer; color: #dc3545;"></i>';
                            hidden = 'none';
                        }

                        let buttons = '';
                        if(index == 0)
                        {
                            buttons = '';
                        }
                        else
                        {
                            buttons = '<a href="javascript:void(0)" class="btn btn-danger team-remove" data-id="'+item.id+'" style="margin-top: 32px;"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></a>';
                        }

                        teamImages.append(
                            `<div class="teamFieldGroup d-flex w-100">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label>Title</label>
                                        <input type="text" name="team_image_title[]" class="form-control" placeholder="Image Title" value="`+item.title+`" />
                                        <input type="hidden" name="team_image_id[]" id="image_id" value="`+item.id+`" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label style="display: `+hidden+`">Image</label>
                                        <input type="file" name="team_image[]" class="form-control" style="display: `+hidden+`" />
                                        <div class="team-image-preview">
                                        `+imageTag+`
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label>Description</label>
                                        <textarea class="form-control" name="team_description[]" id="team_description" rows="3" placeholder="Description">`+ item.description +`</textarea>
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
                    teamImages.append(
                        `<div class="col-md-3 col-sm-12">
                            <div class="form-group mb-2">
                                <a href="javascript:void(0);" class="btn btn-success team-addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
                            </div>
                        </div>`
                    )
                }
                else
                {
                    teamImageAdd();
                }
            }
            else
            {
                teamImageAdd();
            }
        }
    });
}

function teamImageAdd() 
{
    let teamImages = $('.team-images');
    teamImages.html(
        `<div class="teamFieldGroup d-flex w-100 full">
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Title</label>
                    <input type="text" name="team_image_title[]" class="form-control" placeholder="Image Title"/>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group mb-2">
                    <label>Image</label>
                    <input type="file" name="team_image[]" class="form-control" />
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group mb-2">
                    <label>Description</label>
                    <textarea class="form-control" name="team_description[]" id="team_description" rows="3" placeholder="Description"></textarea>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-12">
            <div class="form-group mb-2">
                <a href="javascript:void(0);" class="btn btn-success team-addMore" style="margin-top: 32px;"><i class="custicon plus"></i> Add</a>
            </div>
        </div>`
    );
}