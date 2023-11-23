$(document).ready(function() {

    // Footer category form save
    $("form[name='footer_menu_model_form']").validate({
        // Specify validation rules
        rules: {
            menu_id: {
                required: true
            },
            menu_title: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            menu_id: {
                required: "Menu category is required."
            },
            menu_title: {
                required: "Menu title is required."
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
                            $('#menu_id').val('');
                            footerMenuList();
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

    footerMenuList();
    $('body').on('click', '.pagination a', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');
        getPerPageFooterMenuList(url);
    });

    let blogModelForm = $("form[name='footer_menu_model_form']")[0];
    let menuId = $('#menu_id');

    $('body').on('click', '.menu-edit, .menu_close', function(e) {
        blogModelForm.reset();
        menuId.val('');

        let rowData = $(this).attr('data-menu');
        if(rowData)
        {
            rowData = JSON.parse(rowData);
            
            $('#menu_id').val(rowData.id);
            $('#category_id').val(rowData.category_id);
            $('#menu_title').val(rowData.menu_title);
            $('#menu_link').val(rowData.menu_link);
        }
    });

    $('body').on('click', '.menu-delete', function(e) {

        let menuIds = $(this).attr('data-id');
        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this category?', function() {
            // This function is called when the user clicks "OK"
            deleteBlog(menuIds)
        });
    });

    $('#search_menu').on('keyup', function(e) {
        footerMenuList();
    });
});

function footerMenuList()
{
    var search = $('#search_menu').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'menu/list',
        data: { search_menu: search },
        success:function(response)
        {
            $('.footerMenuDataList').html(response);
        }
    });
}

function getPerPageFooterMenuList(get_pagination_url) 
{
    var search = $('#search_menu').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:get_pagination_url,
        data: { search: search },
        success:function(data)
        {
            $('.footerMenuDataList').html(data);
        }
    });   
}

function deleteBlog(menuIds)
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:'menu/delete',
        data: { menu_id: menuIds },
        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {
                    // This function is called when the user clicks "OK"
                    footerMenuList();
                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}