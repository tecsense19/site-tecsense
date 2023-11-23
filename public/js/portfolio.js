$(document).ready(function(){
    
    $("form[name='portfolio_model_form']").submit(function (e) {
        e.preventDefault(); // Prevent the form from submitting in the default way

        var formData = new FormData(this);

        $.ajax({
            type:'post',
            headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
            url: $('#portfolio_model_form').attr('action'),
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.success)
                {
                    $('#portfolio_model_form')[0].reset();
                    $('.portfolio_close_model').trigger('click');

                    showSuccessMessage('Success', response.message, function() {
                        // This function is called when the user clicks "OK"
                        portfolioList();
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
    });

    portfolioList();

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');
        getPerPagePortfolioList(url);
    });

    let portfolioModelForm = $("form[name='portfolio_model_form']")[0];
    let portfolioModalLg = $("#portfolio-modal-lg");

    $('body').on('click', '.portfolio-add', function(e) {
        portfolioModelForm.reset();
        portfolioModalLg.modal("show");
    });

    $('body').on('click', '.portfolio-delete', function(e) {
        let portfolioIds = $(this).attr('data-id');
        showDeleteConfirmation('Confirm', 'Are you sure you want to delete this portfolio?', function() {
            // This function is called when the user clicks "OK"
            deletePortfolio(portfolioIds)
        });
    });
});

function portfolioList()
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url: 'portfolio/list',
        data: { },
        success:function(response)
        {
            $('.portfolioDataList').html(response);
        }
    });
}

function getPerPagePortfolioList(get_pagination_url) 
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:get_pagination_url,
        data: {  },
        success:function(data)
        {
            $('.portfolioDataList').html(data);
        }
    });
}

function deletePortfolio(portfolioIds)
{
    $.ajax({
        type:'post',
        headers: {'X-CSRF-TOKEN': jQuery('input[name=_token]').val()},
        url:'portfolio/delete',
        data: { portfolio_id: portfolioIds },
        success:function(response)
        {
            if(response.success)
            {
                showSuccessMessage('Success', response.message, function() {
                    // This function is called when the user clicks "OK"
                    portfolioList();
                });
            }
            else
            {
                showErrorMessage('Error', response.message)
            }
        }
    });
}