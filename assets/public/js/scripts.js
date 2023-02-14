
jQuery(document).ready(function($) {
    // toggle listing
    $('.submit-toggle').on('click', function (event) {
        $(".dp-modal").toggleClass('open');
    });
    $('.close-modal').on('click', function (event) {
        $(".dp-modal").removeClass('open');
    });
});