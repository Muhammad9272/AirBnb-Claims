$(document).ready(function () {
 //User Dashboard make links active   
    var currentUrl = window.location.href.split(/[?#]/)[0];// Get the current URL path
    // Loop through each navbar link
    $('.user-dashboard__sidebar .nav-item').each(function() {
        // alert(linkUrl);
        // Get the link's URL path
        var linkUrl = $(this).find('.nav-link').attr('href');
         console.log(currentUrl,linkUrl);
        // Check if the current URL path matches the link's URL path
        if (currentUrl === linkUrl) {
            // Add the active class to the link
            $(this).addClass('ts-nav-active');
        }
    });


    $('.apt__light-dark-mode').click(function () {
        // Get the mode value from the data attribute
        var currentMode = $(this).data('layout-mode');

        // Toggle the mode value (assuming it switches between 'dark' and 'light')
        var newMode = currentMode === 'dark' ? 'light' : 'dark';

        // Get the data-href value containing the route URL
        var url = $(this).data('href');
        // Get the CSRF token value from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Make an AJAX request to store the new mode value in the database
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: csrfToken, // Include the CSRF token
                mode: newMode
            },
            success: function (response) {
                window.location.reload(); // Reload the page
                // Handle the success response if needed
                console.log('Mode value stored in the database successfully');
            },
            error: function (error) {
                // Handle the error response if needed
                console.error('Error storing mode value in the database', error);
            }
        });
    });


});    
