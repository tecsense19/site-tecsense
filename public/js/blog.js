$(document).ready(function(){

    CKEDITOR.editorConfig = function( config ) {
        config.toolbar = [
            { name: 'document', items: [ 'Source', '-', 'Save', 'NewPage', 'ExportPdf', 'Preview', 'Print', '-', 'Templates' ] },
            { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
            { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
            { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
            '/',
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
            { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
            { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
            '/',
            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
            { name: 'about', items: [ 'About' ] }
        ];
    };

    CKEDITOR.replace('editor');

    // Blog form save
    $("form[name='blog_model_form']").validate({
        // Specify validation rules
        rules: {
            blog_title: {
                required: true
            },
            slug: {
                required: true
            }
        },
        // Specify validation error messages
        messages: {
            blog_title: {
                required: "Blog title is required."
            },
            slug: {
                required: "Slug is required."
            }
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form, event) {
            // form.submit();
            event.preventDefault();
            var formData = new FormData(form);
            formData.append('blog_content', CKEDITOR.instances.editor.getData());

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
                        $('.blog_close_model').trigger('click');
                        showSuccessMessage('Success', response.message, function() {
                            // This function is called when the user clicks "OK"
                            blogList();
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

    blogList();
    $('body').on('click', '.pagination a', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');
        getPerPageBlogList(url);
    });

    let blogModelForm = $("form[name='blog_model_form']")[0];
    let blogModalXl = $("#blog-modal-xl");
    let blogId = $('#blog_id');

    $('body').on('click', '.blog-edit, .blog-add', function(e) {
        blogModelForm.reset();
        blogModalXl.modal("show");
        blogId.val('');

        let rowData = $(this).attr('data-blog');
        if(rowData)
        {
            rowData = JSON.parse(rowData);
            
            $('#blog_id').val(rowData.id);
            $('#blog_title').val(rowData.blog_title);
            $('#slug').val(rowData.blog_slug);
            $('#blog_category').val(rowData.blog_category);
            $('#blog_tag').val(rowData.blog_tag);
            $('#seo_meta_title').val(rowData.seo_meta_title);
            $('#seo_meta_description').val(rowData.seo_meta_description);
            $('#seo_meta_keyword').val(rowData.seo_meta_keyword);

            // Get the CKEditor instance
            var editor = CKEDITOR.instances.editor;

            // Check if the editor exists and set its content
            if (editor) {
                editor.setData(rowData.blog_content);
            }
        }
    });

    $('body').on('click', '.blog-delete', function(e) {

        let blogIds = $(this).attr('data-id');
        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this blog?', function() {
            // This function is called when the user clicks "OK"
            deleteBlog(blogIds)
        });
    });
});

function blogList()
{
    var search = $('#search_blog').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'blog/list',
        data: { search_blog: search },
        success:function(response)
        {
            $('.blogDataList').html(response);
        }
    });
}

function getPerPageBlogList(get_pagination_url) 
{
    var search = $('#search_blog').val();
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:get_pagination_url,
        data: { search: search },
        success:function(data)
        {
            $('.blogDataList').html(data);
        }
    });   
}

function deleteBlog(blogIds)
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:'blog/delete',
        data: { blog_id: blogIds },
        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {
                    // This function is called when the user clicks "OK"
                    blogList();
                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}