(function ($) {
    'use strict';

    // Оболочка тестового стенда: переключение таблиц в read-only просмотрщике.
    // Логика CMS находится отдельно в admin/js/property.js.
    $('.js-db-tab').on('click', function () {
        var table = $(this).data('table');

        $('.js-db-tab').removeClass('is-active');
        $(this).addClass('is-active');
        $('.js-db-panel').removeClass('is-active');
        $('.js-db-panel[data-table-panel="' + table + '"]').addClass('is-active');
    });
}(jQuery));
