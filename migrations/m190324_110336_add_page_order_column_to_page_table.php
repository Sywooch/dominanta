<?php

use yii\db\Migration;

/**
 * Handles adding page_order to table `page`.
 */
class m190324_110336_add_page_order_column_to_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('page', 'page_order', $this->integer()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('page', 'page_order');
    }
}
