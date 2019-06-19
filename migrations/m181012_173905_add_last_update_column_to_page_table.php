<?php

use yii\db\Migration;
use app\models\ActiveRecord\Page;

/**
 * Handles adding last_update to table `page`.
 */
class m181012_173905_add_last_update_column_to_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('page', 'last_update', $this->datetime()->defaultValue(NULL));
        Page::updateAll(['last_update' => Page::getDbTime()]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('page', 'last_update');
    }
}
