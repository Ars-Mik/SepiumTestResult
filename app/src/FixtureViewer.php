<?php

/**
 * Read-only viewer for the small demo fixture. It is not part of the task logic.
 */
class FixtureViewer
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function tables()
    {
        return array(
            'property_s' => array(
                'columns' => array('id', 'name_prop', 'place_prop', 'type_prop', 'cat_prop', 'sort_prop'),
                'rows' => $this->fetch(
                    'SELECT id, name_prop, place_prop, type_prop, cat_prop, sort_prop FROM property_s ORDER BY sort_prop, id'
                ),
            ),
            'property_answer_s' => array(
                'columns' => array('id', 'id_prop', 'answer_prop', 'sort_answer'),
                'rows' => $this->fetch(
                    'SELECT id, id_prop, answer_prop, sort_answer FROM property_answer_s ORDER BY id_prop, sort_answer, id'
                ),
            ),
            'category_s' => array(
                'columns' => array('ID_category', 'name_category', 'affiliation_category', 'chpu_category', 'sort_category'),
                'rows' => $this->fetch(
                    'SELECT ID_category, name_category, affiliation_category, chpu_category, sort_category FROM category_s ORDER BY sort_category, ID_category'
                ),
            ),
        );
    }

    private function fetch($sql)
    {
        return $this->connection->query($sql)->fetchAll();
    }
}
