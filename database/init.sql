SET NAMES utf8mb4;

DROP TABLE IF EXISTS property_answer_s;
DROP TABLE IF EXISTS property_s;
DROP TABLE IF EXISTS category_s;

CREATE TABLE category_s (
    ID_category INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name_category VARCHAR(120) NOT NULL,
    affiliation_category INT UNSIGNED NOT NULL DEFAULT 0,
    chpu_category VARCHAR(120) NOT NULL,
    sort_category INT NOT NULL DEFAULT 0,
    PRIMARY KEY (ID_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE property_s (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name_prop VARCHAR(160) NOT NULL,
    place_prop VARCHAR(255) NOT NULL DEFAULT '',
    type_prop TINYINT UNSIGNED NOT NULL,
    cat_prop VARCHAR(255) NOT NULL DEFAULT '',
    sort_prop INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE property_answer_s (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_prop INT UNSIGNED NOT NULL,
    answer_prop VARCHAR(160) NOT NULL,
    sort_answer INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY id_prop (id_prop)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO category_s (ID_category, name_category, affiliation_category, chpu_category, sort_category) VALUES
    (1, 'Мебель', 0, 'furniture', 10),
    (2, 'Освещение', 0, 'lighting', 20),
    (3, 'Декор', 0, 'decor', 30),
    (4, 'Услуги', 0, 'services', 40),
    (11, 'Сад', 0, 'garden', 50);

-- Пустой cat_prop означает общую характеристику.
-- Несколько категорий исторически хранятся строкой через запятую.
INSERT INTO property_s (id, name_prop, place_prop, type_prop, cat_prop, sort_prop) VALUES
    (1, 'Артикул', 'Внутренний код товара', 1, '', 10),
    (2, 'Материал', 'Основной материал изделия', 2, '1,3', 20),
    (3, 'Цвета', 'Можно выбрать несколько вариантов', 3, '1,2', 30),
    (4, 'Ширина, см', 'Положительное число', 4, '1', 40),
    (5, 'Тип цоколя', 'Используйте обозначение производителя', 2, '2', 50),
    (6, 'Комментарий редактора', 'Подсказка не должна интерпретировать HTML: <текст>', 1, '', 60),
    (7, 'Морозостойкость', 'Только для категории с ID 11', 2, '11', 70);

INSERT INTO property_answer_s (id, id_prop, answer_prop, sort_answer) VALUES
    (1, 2, 'Дерево', 10),
    (2, 2, 'Металл', 20),
    (3, 2, 'Стекло', 30),
    (4, 3, 'Белый', 10),
    (5, 3, 'Чёрный', 20),
    (6, 3, 'Зелёный', 30),
    (7, 5, 'E14', 10),
    (8, 5, 'E27', 20),
    (9, 5, 'GU10', 30),
    (10, 7, 'Да', 10),
    (11, 7, 'Нет', 20);
