<?php

use yii\db\Migration;

/**
 * Handles adding notify to table `user`.
 */
class m181226_192531_add_notify_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'notify', $this->string()->notNull()->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'notify');
    }
}
