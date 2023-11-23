$(document).ready(function() {

    // Blog category form save
    $("form[name='blog_category_model_form']").validate({
        // Specify validation rules
        rules: {
            title: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            title: {
                required: "Category name is required."
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
                            $('#category_id').val('');
                            blogCategoryList();
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

    blogCategoryList();
    $('body').on('click', '.pagination a', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');
        getPerPageBlogCategoryList(url);
    });

    let blogModelForm = $("form[name='blog_category_model_form']")[0];
    let categoryId = $('#category_id');

    $('body').on('click', '.category-edit, .category_close', function(e) {
        blogModelForm.reset();
        categoryId.val('');

        let rowData = $(this).attr('data-category');
        if(rowData)
        {
            rowData = JSON.parse(rowData);
            
            $('#category_id').val(rowData.id);
            $('#title').val(rowData.title);
        }
    });

    $('body').on('click', '.category-delete', function(e) {

        let categoryIds = $(this).attr('data-id');
        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this category?', function() {
            // This function is called when the user clicks "OK"
            deleteBlogCategory(categoryIds)
        });
    });

    $('#search_blog_category').on('keyup', function(e) {
        blogCategoryList();
    });
});

function blogCategoryList()
{
    var search = $('#search_blog_category').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'category/list',
        data: { search_blog_category: search },
        success:function(response)
        {
            $('.blogCategoryDataList').html(response);
        }
    });
}

function getPerPageBlogCategoryList(get_pagination_url) 
{
    var search = $('#search_blog_category').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:get_pagination_url,
        data: { search: search },
        success:function(data)
        {
            $('.blogCategoryDataList').html(data);
        }
    });   
}

function deleteBlogCategory(categoryIds)
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:'category/delete',
        data: { category_id: categoryIds },
        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {
                    // This function is called when the user clicks "OK"
                    blogCategoryList();
                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}