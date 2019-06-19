<?php

use yii\db\Migration;

/**
 * Handles adding sitemap_inc to table `page`.
 */
class m190311_200320_add_sitemap_inc_column_to_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('page', 'sitemap_inc', $this->integer(1)->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('page', 'sitemap_inc');
    }
}
