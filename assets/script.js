$(document).ready(function() {
    $('#inpsydetable').DataTable({
        pageLength : 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']]
    });
    
    // Click on element with 'popup' class 
    jQuery('.popup').on('click', function () {

        // Save ID in simple var
        var id = jQuery(this)
            .attr('rel');
        
        $.ajax({
            url: ajax_url,
            data: {'action': 'getUserDetails', 'id': id},
            success: function (msg) { 
                $('.modal-body').html(msg);
            jQuery.noConflict();
            $ = jQuery;
            // Display Modal
            $('#userModal').modal('show');  
            },
            error: function (jqXHR, textStatus, errorThrown) { 
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                $('.modal-body').html(msg);
                jQuery.noConflict();
                $ = jQuery;
                // Display Modal
                $('#userModal').modal('show'); 
                }
        });
            
        return false;
        
        });
    
    
} );