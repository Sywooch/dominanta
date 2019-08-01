<?php

use yii\db\Migration;

/**
 * Handles the creation of table `delivery_address`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m190801_192747_create_delivery_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('delivery_address', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'address_name' => $this->string(),
            'address' => $this->text(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-delivery_address-user_id',
            'delivery_address',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-delivery_address-user_id',
            'delivery_address',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-delivery_address-user_id',
            'delivery_address'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-delivery_address-user_id',
            'delivery_address'
        );

        $this->dropTable('delivery_address');
    }
}
