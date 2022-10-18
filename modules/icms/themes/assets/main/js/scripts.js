$(document).ready(function () {

    $('.user').click(function () {
        if ($(".user i").hasClass('actv')) {
            $(".user i").removeClass("actv");
            $('.subnav_footer').hide();
        } else {
            $(".user i").addClass("actv");
            $(".subnav_footer").addClass("animated bounceInUp");
            $('.subnav_footer').toggle();
        }
        return false;
    });

    $('.action_buttons .back').click(function () {
        window.history.back();
    });

});



var Helper = function () {
    return {
        /**
         * Разбирает GET строку в объект
         * @param string url GET параметры (строка .serialize()) для разбора
         * @returns Object разобранная строка
         */
        parse_url: function (url) {
            url = url.replace(/^\?/g, '').replace(/\+/g, ' ').replace(/%2B/g, '+');
            parameters = {};

            pieces = url.split('&');

            for (i = 0; i < pieces.length; i++) {
                name = decodeURI(pieces[i].replace(/=.*?$/g, ''));
                value = decodeURI(pieces[i].replace(/^.*?=/g, '').replace(/%26/g, '&').replace(/%2F/g, '/').replace(/%3A/g, ':'));
                parameters[name] = value;
            }
            return parameters;
        }
    };
}();