<?php

use yii\db\Migration;

/**
 * Handles the creation of table `subscriber`.
 */
class m190808_181855_create_subscriber_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('subscriber', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'email' => $this->string(),
            'hash' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('subscriber');
    }
}
