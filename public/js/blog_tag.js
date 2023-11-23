$(document).ready(function() {

    // Blog category form save
    $("form[name='blog_tag_model_form']").validate({
        // Specify validation rules
        rules: {
            title: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            title: {
                required: "Tag name is required."
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
                            $('#tag_id').val('');
                            blogTagList();
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

    blogTagList();
    $('body').on('click', '.pagination a', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');
        getPerPageBlogTagList(url);
    });

    let blogModelForm = $("form[name='blog_tag_model_form']")[0];
    let tagId = $('#tag_id');

    $('body').on('click', '.tag-edit, .tag_close', function(e) {
        blogModelForm.reset();
        tagId.val('');

        let rowData = $(this).attr('data-tag');
        if(rowData)
        {
            rowData = JSON.parse(rowData);
            
            $('#tag_id').val(rowData.id);
            $('#title').val(rowData.title);
        }
    });

    $('body').on('click', '.tag-delete', function(e) {

        let tagIds = $(this).attr('data-id');
        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this tag?', function() {
            // This function is called when the user clicks "OK"
            deleteBlog(tagIds)
        });
    });

    $('#search_tag').on('keyup', function(e) {
        blogTagList();
    });
});

function blogTagList()
{
    var search = $('#search_tag').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'tag/list',
        data: { search_tag: search },
        success:function(response)
        {
            $('.blogTagDataList').html(response);
        }
    });
}

function getPerPageBlogTagList(get_pagination_url) 
{
    var search = $('#search_tag').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:get_pagination_url,
        data: { search: search },
        success:function(data)
        {
            $('.blogTagDataList').html(data);
        }
    });   
}

function deleteBlog(tagIds)
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:'tag/delete',
        data: { tag_id: tagIds },
        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {
                    // This function is called when the user clicks "OK"
                    blogTagList();
                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}