
function toggleFavorite(input) {
    var isChecked = input.is(':checked');
    var type = input.data('type');
    var id = input.data('id');
    var url = isChecked ? '/user/favorite/' : '/user/unfavorite/';
    url = mainurl+url + type + '/' + id;
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var redirectUrl = input.data('redirect'); // Get the value of the data-redirect attribute
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            _token: csrfToken,
        },
        success: function (data) {
            // Handle successful response
            if (data.status == 'success') {
                console.log(data.message);
                // If data-redirect attribute exists and has a value, redirect the user
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
                // Do something here in response to a successful favorite/unfavorite action
            } else {
                // Handle error response
                console.log(data.error);
                // Show an error message to the user or do something else in response to an error
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle Ajax error
            console.log(textStatus, errorThrown);
            // Show an error message to the user or do something else in response to an Ajax error
        }
    });
}
