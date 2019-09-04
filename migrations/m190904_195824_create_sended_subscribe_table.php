<?php

use yii\db\Migration;

/**
 * Handles the creation of table `sended_subscribe`.
 * Has foreign keys to the tables:
 *
 * - `subscribe`
 * - `subscriber`
 */
class m190904_195824_create_sended_subscribe_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('sended_subscribe', [
            'id' => $this->primaryKey(),
            'subscribe_id' => $this->integer()->notNull(),
            'subscriber_id' => $this->integer()->notNull(),
            'send_errors' => $this->text()->defaultValue(NULL),
        ]);

        // creates index for column `subscribe_id`
        $this->createIndex(
            'idx-sended_subscribe-subscribe_id',
            'sended_subscribe',
            'subscribe_id'
        );

        // add foreign key for table `subscribe`
        $this->addForeignKey(
            'fk-sended_subscribe-subscribe_id',
            'sended_subscribe',
            'subscribe_id',
            'subscribe',
            'id',
            'CASCADE'
        );

        // creates index for column `subscriber_id`
        $this->createIndex(
            'idx-sended_subscribe-subscriber_id',
            'sended_subscribe',
            'subscriber_id'
        );

        // add foreign key for table `subscriber`
        $this->addForeignKey(
            'fk-sended_subscribe-subscriber_id',
            'sended_subscribe',
            'subscriber_id',
            'subscriber',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `subscribe`
        $this->dropForeignKey(
            'fk-sended_subscribe-subscribe_id',
            'sended_subscribe'
        );

        // drops index for column `subscribe_id`
        $this->dropIndex(
            'idx-sended_subscribe-subscribe_id',
            'sended_subscribe'
        );

        // drops foreign key for table `subscriber`
        $this->dropForeignKey(
            'fk-sended_subscribe-subscriber_id',
            'sended_subscribe'
        );

        // drops index for column `subscriber_id`
        $this->dropIndex(
            'idx-sended_subscribe-subscriber_id',
            'sended_subscribe'
        );

        $this->dropTable('sended_subscribe');
    }
}
