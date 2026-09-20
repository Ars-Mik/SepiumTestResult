(function ($) {
    'use strict';

    function collectPropertyValues($properties) {
        var values = {};

        $properties.find('.name_select_rielt').each(function () {
            var $field = $(this);
            var propertyId = $field.attr('data-property-id');
            var $multiple = $field.find('.checkbox_property');

            if ($multiple.length) {
                var selectedValues = [];

                $multiple.find('.line_chek').each(function () {
                    var $choice = $(this);
                    var $checkbox = $choice.find('input[type="checkbox"]');

                    if ($checkbox.prop('checked')) {
                        selectedValues[selectedValues.length] =
                            $choice.find('.ckeck_param').attr('data-val');
                    }
                });

                values[propertyId] = selectedValues;
                return;
            }

            var $control = $field
                .find('input.ag_pole_good, select.ag_pole_good')
                .first();

            if ($control.length) {
                values[propertyId] = $control.val();
            }
        });

        return values;
    }

    function restorePropertyValues($properties, values) {
        $properties.find('.name_select_rielt').each(function () {
            var $field = $(this);
            var propertyId = $field.attr('data-property-id');

            if (!Object.prototype.hasOwnProperty.call(values, propertyId)) {
                return;
            }

            var $multiple = $field.find('.checkbox_property');

            if ($multiple.length) {
                var selectedValues = values[propertyId];

                $multiple.find('.line_chek').each(function () {
                    var $choice = $(this);
                    var answerId = $choice.find('.ckeck_param').attr('data-val');

                    $choice.find('input[type="checkbox"]').prop(
                        'checked',
                        $.inArray(answerId, selectedValues) !== -1
                    );
                });

                return;
            }

            $field
                .find('input.ag_pole_good, select.ag_pole_good')
                .first()
                .val(values[propertyId]);
        });
    }

    // Выбор категории в товаре и обновление блока характеристик.
    $('body').on('change', '.js-category', function () {
        var category = [];
        var $properties = $('.property_all');

        $(this).closest('.add_good_name_category')
            .toggleClass('category_checked is-selected', this.checked);

        $('.category_checked').each(function () {
            category[category.length] = $(this).attr('data-category-id');
        });

        var propertyValues = collectPropertyValues($properties);

        $properties.addClass('is-loading').attr('aria-busy', 'true');

        $.ajax({
            type: 'POST',
            url: './admin/ajax/property/Refresh_Property_Good.php',
            dataType: 'html',
            data: { category: category },
            success: function (data) {
                if (data != 'no') {
                    $properties.html(data);
                    restorePropertyValues($properties, propertyValues);
                }
            },
            error: function () {
                $properties.html(
                    '<div class="error-state">Не удалось обновить характеристики.</div>'
                );
            },
            complete: function () {
                $properties.removeClass('is-loading').attr('aria-busy', 'false');
            }
        });
    });
}(jQuery));