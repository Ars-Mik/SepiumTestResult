<?php

require_once dirname(__DIR__) . '/src/bootstrap.php';

$categories = db()->query(
    'SELECT ID_category, name_category, chpu_category FROM category_s ORDER BY sort_category, ID_category'
)->fetchAll();
$fixtureTables = (new FixtureViewer(db()))->tables();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Характеристики товара | Demo CMS</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <main class="page">
        <header class="assignment-card">
            <div class="assignment-intro">
                <div class="assignment-meta">
                    <span class="assignment-label">Тестовое задание</span>
                </div>
                <h1>Исправьте вывод и отправку характеристик</h1>
                <p class="assignment-context">Ниже находятся форма товара и данные БД. Выбранные в форме категории должны определять, какие характеристики показаны пользователю.</p>
                <div class="assignment-problem">
                    <b>Сейчас работает неправильно</b>
                    <span>Без выбранных категорий показаны все характеристики. После выбора набор характеристик и данные для отправки формируются неверно.</span>
                </div>
            </div>

            <div class="assignment-body">
                <section class="assignment-section">
                    <h2>Что должно работать</h2>
                    <div class="assignment-steps" role="list">
                        <div role="listitem">
                            <b><i>1</i>Показывать нужные характеристики</b>
                            <span>Связь хранится в <code>property_s.cat_prop</code>: пустое значение означает общую характеристику, остальные значения — ID категорий через запятую.</span>
                            <span class="assignment-detail">При выборе категорий JavaScript передаёт их ID в параметре <code>category</code>. PHP возвращает готовый HTML для блока <code>.property_all</code>.</span>
                        </div>
                        <div role="listitem">
                            <b><i>2</i>Не сбрасывать значения</b>
                            <span>Если характеристика осталась после AJAX-обновления, введённое или выбранное значение должно сохраниться.</span>
                        </div>
                        <div role="listitem">
                            <b><i>3</i>Правильно собирать данные</b>
                            <span>Кнопка отправляет категории и только заполненные характеристики, которые сейчас показаны в форме. В базу она ничего не записывает.</span>
                            <span class="assignment-detail">В <code>cats</code> передаются значения <code>chpu_category</code> выбранных категорий. В <code>property_mas</code> ключом служит ID характеристики. В мультивыборе ID соединяются через <code>:::</code>; пустые значения пропускаются, числовой <code>0</code> сохраняется.</span>
                        </div>
                    </div>
                </section>

                <section class="assignment-notes" aria-label="Ограничения">
                    <p><b>Обязательно:</b> некорректный <code>category</code> не вызывает PHP-ошибок; текст из БД не интерпретируется как HTML/JavaScript.</p>
                    <p><b>Не менять:</b> PHP 5.6, MySQL 5.7, jQuery, данные БД, существующие тесты, форматы запросов и DOM-классы. Свои тесты добавлять можно.</p>
                </section>

                <section class="assignment-files">
                    <h2>Основные файлы задания</h2>
                    <div><code>app/public/admin/views/addNewGood.tpl.php</code><span>Первоначальный вывод характеристик</span></div>
                    <div><code>app/public/admin/js/property.js</code><span>Обновление после выбора категорий</span></div>
                    <div><code>app/public/admin/js/main.js</code><span>Сбор данных по кнопке</span></div>
                    <div><code>app/public/admin/ajax/property/Refresh_Property_Good.php</code><span>Отбор характеристик и HTML-ответ</span></div>
                </section>
            </div>
        </header>

        <div class="workspace">
            <?php include __DIR__ . '/admin/views/addNewGood.tpl.php'; ?>

            <aside class="database-panel database-window">
                <header class="database-appbar">
                    <div class="database-brand"><span>DB</span><strong>Database Browser</strong></div>
                    <span class="read-only-badge">Read only</span>
                </header>

                <div class="database-breadcrumb">
                    <span>Сервер: <b>db</b></span><i>›</i><span>База данных: <b>catalog_demo</b></span>
                </div>

                <header class="database-header">
                    <p><strong>Тестовые данные MySQL</strong> · таблицы доступны только для просмотра</p>
                    <small>type: 1 текст · 2 выбор · 3 мультивыбор · 4 число</small>
                </header>

                <div class="database-browser">
                    <nav class="database-tabs" aria-label="Таблицы базы данных">
                        <div class="schema-name"><span>▾</span> catalog_demo</div>
                        <?php $tableIndex = 0; ?>
                        <?php foreach ($fixtureTables as $tableName => $table): ?>
                            <button class="database-tab js-db-tab<?php echo $tableIndex === 0 ? ' is-active' : ''; ?>" type="button" data-table="<?php echo h($tableName); ?>">
                                <i>▦</i><?php echo h($tableName); ?><span><?php echo count($table['rows']); ?></span>
                            </button>
                            <?php $tableIndex++; ?>
                        <?php endforeach; ?>
                    </nav>

                    <div class="database-tables">
                        <?php $tableIndex = 0; ?>
                        <?php foreach ($fixtureTables as $tableName => $table): ?>
                            <section class="database-table-panel js-db-panel<?php echo $tableIndex === 0 ? ' is-active' : ''; ?>" data-table-panel="<?php echo h($tableName); ?>">
                                <div class="table-name"><span>Таблица:</span><strong><?php echo h($tableName); ?></strong><em><?php echo count($table['rows']); ?> строк</em></div>
                                <div class="table-scroll">
                                    <table>
                                        <thead>
                                            <tr>
                                                <?php foreach ($table['columns'] as $column): ?>
                                                    <th><?php echo h($column); ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($table['rows'] as $row): ?>
                                                <?php $isGlobal = $tableName === 'property_s' && $row['cat_prop'] === ''; ?>
                                                <tr<?php echo $isGlobal ? ' class="is-global-row"' : ''; ?>>
                                                    <?php foreach ($table['columns'] as $column): ?>
                                                        <td<?php echo $column === 'cat_prop' && $row[$column] === '' ? ' class="is-true"' : ''; ?>><?php echo h($row[$column]); ?></td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                            <?php $tableIndex++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="/assets/app.js"></script>
    <script src="/admin/js/property.js?v=<?php echo filemtime(__DIR__ . '/admin/js/property.js'); ?>"></script>
    <script src="/admin/js/main.js?v=<?php echo filemtime(__DIR__ . '/admin/js/main.js'); ?>"></script>
</body>
</html>
