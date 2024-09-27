jQuery(document).ready(function($) {
    $.ajax({
        url: ajax_obj.ajax_url,
        type: 'POST',
        data: {
            action: 'get_architecture_projects'
        },
        success: function(response) {
            if (response.success) {
                console.log(response.data);
            }
        },
        error: function() {
            console.log('Error fetching projects.');
        }
    });
});
