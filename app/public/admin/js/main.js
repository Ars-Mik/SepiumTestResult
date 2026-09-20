(function ($) {
    'use strict';

    function collectCategories() {
        var cats = [];

        $('.category_checked').each(function () {
            cats[cats.length] = $(this).attr('data-category-chpu');
        });

        return cats;
    }

    // Фрагмент упрощён из legacy main.js. Сейчас он неверно собирает часть типов полей.
    function collectPropertyValues() {
        var propertyMas = {};

        $('.property_all .name_select_rielt').each(function () {
            var $field = $(this);
            var propertyId = $field.attr('data-property');

            if (propertyId === undefined || propertyId === '') {
                return;
            }

            var $multiple = $field.find('.checkbox_property');

            if ($multiple.length) {
                var selectedValues = [];

                $multiple.find('.line_chek').each(function () {
                    var $choice = $(this);
                    var $checkbox = $choice.find('input[type="checkbox"]');

                    if ($checkbox.prop('checked')) {
                        var value = $choice.find('.ckeck_param').attr('data-val');

                        if (value !== undefined && value !== '') {
                            selectedValues[selectedValues.length] = value;
                        }
                    }
                });

                if (selectedValues.length) {
                    propertyMas[propertyId] = selectedValues.join(':::');
                }

                return;
            }

            var $control = $field
                .find('input.ag_pole_good, select.ag_pole_good')
                .first();

            if (!$control.length) {
                return;
            }

            var value = $control.val();

            if (value !== undefined && value !== null && value !== '') {
                propertyMas[propertyId] = String(value);
            }
        });

        return propertyMas;
    }

    $('body').on('click', '.addgood_click', function () {
        var $button = $(this);

        $button.prop('disabled', true).text('Проверяем…');

        $.ajax({
            type: 'POST',
            url: './admin/ajax/Preview_Good_Payload.php',
            dataType: 'json',
            data: {
                cats: collectCategories(),
                property_mas: collectPropertyValues()
            },
            success: function (data) {
                $('.js-payload-preview').text(JSON.stringify(data, null, 2));
            },
            error: function () {
                $('.js-payload-preview').text('Не удалось проверить отправку.');
            },
            complete: function () {
                $button.prop('disabled', false).text('Проверить отправку');
            }
        });
    });
}(jQuery));
