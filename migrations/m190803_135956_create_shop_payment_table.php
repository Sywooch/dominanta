<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_payment`.
 * Has foreign keys to the tables:
 *
 * - `shop_order`
 */
class m190803_135956_create_shop_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop_payment', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'amount' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'payed' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'hash' => $this->string()->defaultValue(NULL),
        ]);

        // creates index for column `order_id`
        $this->createIndex(
            'idx-shop_payment-order_id',
            'shop_payment',
            'order_id'
        );

        // add foreign key for table `shop_order`
        $this->addForeignKey(
            'fk-shop_payment-order_id',
            'shop_payment',
            'order_id',
            'shop_order',
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
            'fk-shop_payment-order_id',
            'shop_payment'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            'idx-shop_payment-order_id',
            'shop_payment'
        );

        $this->dropTable('shop_payment');
    }
}
