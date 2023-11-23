$(document).ready(function() {

    // Footer category form save
    $("form[name='footer_category_model_form']").validate({
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
                            footerCategoryList();
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

    footerCategoryList();
    $('body').on('click', '.pagination a', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');
        getPerPageFooterCategoryList(url);
    });

    let blogModelForm = $("form[name='footer_category_model_form']")[0];
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
            deleteBlog(categoryIds)
        });
    });

    $('#search_footer_category').on('keyup', function(e) {
        footerCategoryList();
    });
});

function footerCategoryList()
{
    var search = $('#search_footer_category').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'footer/category/list',
        data: { search_footer_category: search },
        success:function(response)
        {
            $('.footerCategoryDataList').html(response);
        }
    });
}

function getPerPageFooterCategoryList(get_pagination_url) 
{
    var search = $('#search_footer_category').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:get_pagination_url,
        data: { search: search },
        success:function(data)
        {
            $('.footerCategoryDataList').html(data);
        }
    });   
}

function deleteBlog(categoryIds)
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:'footer/category/delete',
        data: { category_id: categoryIds },
        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {
                    // This function is called when the user clicks "OK"
                    footerCategoryList();
                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}