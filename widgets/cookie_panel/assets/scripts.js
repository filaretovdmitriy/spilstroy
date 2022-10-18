let CookiePanel = function () {

    const COOKIE_NAME = 'cookie-panel-status';


    let block = $('.js-cookie-panel-block');
    let closeButton = block.find('.js-cookie-panel-close');

    return {

        /**
         * Получает значение из куки
         * @param name string название
         * @param defaultValue mixed занчение по умолчанию
         * @returns string|undefined
         */
        get: function(name, defaultValue) {
            let matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));
            return matches ? decodeURIComponent(matches[1]) : defaultValue;
        },


        /**
         * Устанавливает значение куки (старое перезаписывается)
         * @param name string название
         * @param value string занчение
         * @param options object параметры (expires)
         */
        set: function(name, value, options) {
            options = options || {};

            let expires = options.expires;

            if (typeof expires == "number" && expires) {
                let d = new Date();
                d.setTime(d.getTime() + expires * 1000);
                expires = options.expires = d;
            }
            if (expires && expires.toUTCString) {
                options.expires = expires.toUTCString();
            }

            value = encodeURIComponent(value);

            let updatedCookie = name + "=" + value;

            for (let propName in options) {
                updatedCookie += "; " + propName;
                let propValue = options[propName];
                if (propValue !== true) {
                    updatedCookie += "=" + propValue;
                }
            }

            document.cookie = updatedCookie;
        },

        /**
         * Инициализация панели кук
         */
        init() {
            if (CookiePanel.isShow() !== true) {
                return false;
            }

            closeButton.on('click', this.close);
            block.css('display', '');

            return true;
        },

        /**
         * Закрытие панели
         */
        close() {
            CookiePanel.set(COOKIE_NAME, 'hide', {'max-age': 2592000}); // На месяц
            block.hide();
        },

        /**
         * Проверка необходимости отображения панели
         */
        isShow() {
            return CookiePanel.get(COOKIE_NAME, true) === true;
        }
    };
}();

CookiePanel.init();
