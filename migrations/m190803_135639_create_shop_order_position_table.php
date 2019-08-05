<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_order_position`.
 * Has foreign keys to the tables:
 *
 * - `shop_order`
 * - `product`
 */
class m190803_135639_create_shop_order_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop_order_position', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'product_id' => $this->integer(),
            'quantity' => $this->decimal(10,2)->notNull()->defaultValue(1),
            'price' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'discount' => $this->decimal(10,2)->notNull()->defaultValue(0),
        ]);

        // creates index for column `order_id`
        $this->createIndex(
            'idx-shop_order_position-order_id',
            'shop_order_position',
            'order_id'
        );

        // add foreign key for table `shop_order`
        $this->addForeignKey(
            'fk-shop_order_position-order_id',
            'shop_order_position',
            'order_id',
            'shop_order',
            'id',
            'CASCADE'
        );

        // creates index for column `product_id`
        $this->createIndex(
            'idx-shop_order_position-product_id',
            'shop_order_position',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-shop_order_position-product_id',
            'shop_order_position',
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
        // drops foreign key for table `shop_order`
        $this->dropForeignKey(
            'fk-shop_order_position-order_id',
            'shop_order_position'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            'idx-shop_order_position-order_id',
            'shop_order_position'
        );

        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-shop_order_position-product_id',
            'shop_order_position'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-shop_order_position-product_id',
            'shop_order_position'
        );

        $this->dropTable('shop_order_position');
    }
}
