// Create a closure
(function () {
    // Your base, I'm in it!
    var originalAddClassMethod = jQuery.fn.addClass;

    jQuery.fn.addClass = function () {
        // Execute the original method.
        var result = originalAddClassMethod.apply(this, arguments);

        // trigger a custom event
        jQuery(this).trigger('cssClassChanged');

        // return the original result
        return result;
    }
})();
//Script to remove scrolling-header
$('document').ready(function () {
    var isScrolled = false;
    var logoImage = $('#logoImg');
    $("#masthead").bind('cssClassChanged', function () {
        var classList = $('#masthead').attr('class').split(" ");
        var indexOfScrolled = $.inArray('navbar-scrolled', classList);
        if (indexOfScrolled != -1) {
            $("#masthead").removeClass('navbar-scrolled');
        }
    });
    $('.technology_images img').addClass('img-circle img-thumbnail img-responsive');
    $('#emailSuccess').hide();
    $('#nameError').hide();
    $('#emailError').hide();
    $('#emailInvalidError').hide();
    $('#messageError').hide();
    var $window = $(window);
    var logo_nav = $('#logo-nav');
    var main_text = $('#main-text');

    $('.close-alert').on('click', function () {
        $(this).parent().hide();
    });
    function checkWidth() {
        var windowsize = $window.width();
        //console.log(windowsize);
        if (windowsize < 400 || (windowsize < 995 && windowsize > 750)) {
            logo_nav.css('width', '76%');
            if (windowsize < 400)
                main_text.php('GAMES. WEB.<BR />VR. AR.');
        } else {
            logo_nav.css('width', '100%');
            main_text.php('GAMES. WEB.<BR />VR. AR.');
        }
    }
    // Execute on load
    checkWidth();
    // Bind event listener
    $(window).resize(checkWidth);
    setTimeout(function () { $('#launch-content').css('display', 'block'); }, 2000);
});

function sendMail() {
    var nameValidationVal = validateName();
    var emailValidationVal = validateEmail();
    var messageValidationVal = validateMessage();
    if ((nameValidationVal + emailValidationVal + messageValidationVal) == 3) {
        var clientName = $('#name').val();
        var clientEmail = $('#email').val();
        var clientEmailSubject = $('#subject').val();
        var clientMessage = $('#message').val();
        var clientEmailBody = "Client Name : " + clientName + "\nClient Email : " + clientEmail + "\nClient Message : " + clientMessage;

        var mailSendingUrl = 'sendMail.php',
            data = {
                'clientName': clientName,
                'clientEmail': clientEmail,
                'clientEmailSubject': clientEmailSubject,
                'clientMessage': clientMessage,
                'clientEmailBody': clientEmailBody
            };
        $.post(mailSendingUrl, data, function (response) {
            // Response div goes here.
            //alert("action performed successfully"+response);
        });
        $('#name').val("");
        $('#email').val("");
        $('#subject').val("");
        $('#message').val("");
        $('#emailSuccess').show();
        setTimeout(function () { $('#emailSuccess').css('display', 'none') }, 3000);
    }
}

function validateName() {
    if ($('#name').val().trim() == '') {
        $('#nameError').show();
        return -1;
    } else {
        $('#nameError').hide();
        return 1;
    }
}

function validateEmail() {
    if ($('#email').val().trim() == '') {
        $('#emailError').show();
        return -1;
    } else {
        $('#emailError').hide();
        if (!isValidEmailAddress($('#email').val())) {
            $('#emailInvalidError').show();
            return -1;
        } else {
            $('#emailInvalidError').hide();
            return 1;
        }
    }
}

function validateMessage() {
    if ($('#message').val().trim() == '') {
        $('#messageError').show();
        return -1;
    } else {
        $('#messageError').hide();
        return 1;
    }
}

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}