<?php

use yii\db\Migration;

/**
 * Handles adding link to table `banner`.
 */
class m191104_095055_add_link_column_to_banner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('banner', 'link', $this->string()->defaultValue(NULL));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('banner', 'link');
    }
}
