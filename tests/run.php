<?php

require_once '/var/www/app/src/bootstrap.php';

$passed = 0;
$failed = 0;

function check($condition, $message)
{
    global $passed, $failed;

    if ($condition) {
        $passed++;
        echo '[PASS] ' . $message . PHP_EOL;
        return;
    }

    $failed++;
    echo '[FAIL] ' . $message . PHP_EOL;
}

function requestPage($path, $body)
{
    $options = array(
        'http' => array(
            'method' => $body === null ? 'GET' : 'POST',
            'ignore_errors' => true,
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $body === null ? '' : $body,
        ),
    );

    return file_get_contents(
        'http://127.0.0.1' . $path,
        false,
        stream_context_create($options)
    );
}

try {
    $tables = db()->query("SHOW TABLES LIKE 'property_s'")->fetchAll();
    $categories = db()->query('SELECT ID_category FROM category_s')->fetchAll();
    $properties = db()->query('SELECT id FROM property_s')->fetchAll();
    check(
        count($tables) === 1 && count($categories) === 5 && count($properties) === 7,
        'Legacy-fixture доступен и использует таблицы админки.'
    );

    $baselineResponse = requestPage(
        '/admin/ajax/property/Refresh_Property_Good.php',
        http_build_query(array('category' => array(1)))
    );
    check(
        strpos($baselineResponse, 'property-field') !== false
            && strpos(ltrim($baselineResponse), '{') !== 0,
        'AJAX-обработчик сохраняет контракт с готовым HTML-ответом.'
    );
} catch (Exception $exception) {
    $failed++;
    echo '[FAIL] Проверки прерваны исключением: ' . $exception->getMessage() . PHP_EOL;
}

echo PHP_EOL . 'Результат: ' . $passed . ' успешно, ' . $failed . ' с ошибкой.' . PHP_EOL;

exit($failed > 0 ? 1 : 0);
