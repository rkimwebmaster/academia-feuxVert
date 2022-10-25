
$(document).ready(function () {
    $('#accordion .head').click(function () {
        var head = $(this);

        // remove any active head
        head.siblings('.head').removeClass('active');

        var section = head.next('.section');
        //remove .section to exclude from hide all
        section.removeClass('section');

        //hide sibling sections
        head.siblings('.section').hide();

        // set .section class back
        section.addClass('section');

        if( !section.css(':visible')) {
            // set as active and show section
            head.addClass('active');
            section.fadeIn(500);
        };

    });
});