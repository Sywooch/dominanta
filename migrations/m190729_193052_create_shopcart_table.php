<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shopcart`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `product`
 */
class m190729_193052_create_shopcart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shopcart', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(NULL),
            'hash' => $this->string()->defaultValue(NULL),
            'product_id' => $this->integer(),
            'quantity' => $this->decimal(10,2)->notNull()->defaultValue(1),
            'last_update' => $this->datetime()->defaultValue(NULL),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-shopcart-user_id',
            'shopcart',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-shopcart-user_id',
            'shopcart',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `product_id`
        $this->createIndex(
            'idx-shopcart-product_id',
            'shopcart',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-shopcart-product_id',
            'shopcart',
            'product_id',
            'product',
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
            'fk-shopcart-user_id',
            'shopcart'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-shopcart-user_id',
            'shopcart'
        );

        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-shopcart-product_id',
            'shopcart'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-shopcart-product_id',
            'shopcart'
        );

        $this->dropTable('shopcart');
    }
}
