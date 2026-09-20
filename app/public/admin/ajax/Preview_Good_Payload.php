<?php

require_once dirname(dirname(dirname(__DIR__))) . '/src/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$cats = isset($_POST['cats']) && is_array($_POST['cats'])
    ? array_values($_POST['cats'])
    : array();
$propertyMas = isset($_POST['property_mas']) && is_array($_POST['property_mas'])
    ? $_POST['property_mas']
    : array();

// Диагностический endpoint: показывает полученные параметры и ничего не записывает.
echo json_encode(array(
    'cats' => $cats,
    'property_mas' => $propertyMas,
), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
