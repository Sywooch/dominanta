<?php

use yii\db\Migration;

/**
 * Handles adding create_time to table `page`.
 */
class m190324_110834_add_create_time_column_to_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('page', 'create_time', $this->datetime()->defaultValue(NULL));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('page', 'create_time');
    }
}
