<?php

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/src/bootstrap.php';

header('Content-Type: text/html; charset=utf-8');

// Упрощённая обезличенная копия реального legacy-обработчика.
function property($property)
{
    $place = '';
    if ($property['place_prop'] != '') {
        $place = '<div class="field-help">' . $property['place_prop'] . '</div>';
    }

    $idProp = $property['id'];
    $allOption = '';

    if ($property['type_prop'] == '1') {
        $result = '<div class="property-field name_select_rielt" data-property="' . $idProp . '" data-property-id="' . $idProp . '">
            <div class="field-label name">' . $property['name_prop'] . '</div>
            ' . $place . '
            <input type="text" class="text-input add-inp ag_pole_good" placeholder="' . $property['name_prop'] . '">
        </div>';
    } elseif ($property['type_prop'] == '2') {
        $answers = db()->query(
            "SELECT * FROM property_answer_s WHERE id_prop = '" . $idProp . "' ORDER BY sort_answer"
        );

        while ($answer = $answers->fetch()) {
            $allOption .= '<option value="' . $answer['id'] . '">' . $answer['answer_prop'] . '</option>';
        }

        $result = '<div class="property-field name_select_rielt" data-property="' . $idProp . '" data-property-id="' . $idProp . '">
            <div class="field-label name">' . $property['name_prop'] . '</div>
            ' . $place . '
            <select class="text-input ag_pole_good">
                <option value="">Не выбрано</option>' . $allOption . '
            </select>
        </div>';
    } elseif ($property['type_prop'] == '3') {
        $answers = db()->query(
            "SELECT * FROM property_answer_s WHERE id_prop = '" . $idProp . "' ORDER BY sort_answer"
        );
        $checkboxes = '';

        while ($answer = $answers->fetch()) {
            $checkboxes .= '<label class="choice line_chek">
                <input type="checkbox">
                <span class="ckeck_param" data-val="' . $answer['id'] . '">' . $answer['answer_prop'] . '</span>
            </label>';
        }

        $result = '<div class="property-field name_select_rielt" data-property="' . $idProp . '" data-property-id="' . $idProp . '">
            <div class="field-label name">' . $property['name_prop'] . '</div>
            ' . $place . '
            <div class="choice-grid checkbox_property ag_pole_good">' . $checkboxes . '</div>
        </div>';
    } elseif ($property['type_prop'] == '4') {
        $result = '<div class="property-field name_select_rielt" data-property="' . $idProp . '" data-property-id="' . $idProp . '">
            <div class="field-label name">' . $property['name_prop'] . '</div>
            ' . $place . '
            <input type="text" inputmode="decimal" class="text-input add-inp ag_pole_good" placeholder="Числовое значение">
        </div>';
    } else {
        $result = '';
    }

    return $result;
}


// Legacy-алгоритм намеренно содержит несколько связанных ошибок
// upd. -- Ошибки исправлены 
$category = isset($_POST['category']) && is_array($_POST['category'])
    ? $_POST['category']
    : array();

$selectedCategories = array();

foreach ($category as $categoryId) {
    if (!is_scalar($categoryId)) {
        continue;
    }

    $categoryId = trim((string) $categoryId);

    if ($categoryId === '' || !preg_match('/^[0-9]+$/', $categoryId)) {
        continue;
    }

    $categoryId = (string) (int) $categoryId;

    if ($categoryId !== '0') {
        $selectedCategories[$categoryId] = true;
    }
}

$result = '';

$properties = db()->query(
    'SELECT * FROM property_s ORDER BY sort_prop'
);

while ($property = $properties->fetch()) {
    $catProp = trim((string) $property['cat_prop']);

    // Пустой cat_prop означает общую характеристику.
    $showProperty = ($catProp === '');

    if (!$showProperty && !empty($selectedCategories)) {
        $propertyCategories = explode(',', $catProp);

        foreach ($propertyCategories as $propertyCategoryId) {
            $propertyCategoryId = trim($propertyCategoryId);

            if (isset($selectedCategories[$propertyCategoryId])) {
                $showProperty = true;
                break;
            }
        }
    }

    if ($showProperty) {
        $result .= property($property);
    }
}

echo $result === '' ? 'no' : $result;
