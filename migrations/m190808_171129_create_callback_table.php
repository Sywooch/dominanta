<?php

use yii\db\Migration;

/**
 * Handles the creation of table `callback`.
 */
class m190808_171129_create_callback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('callback', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'add_time' => $this->datetime()->defaultValue(NULL),
            'fio' => $this->string(),
            'phone' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('callback');
    }
}
