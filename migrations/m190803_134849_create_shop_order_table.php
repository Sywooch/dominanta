<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_order`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m190803_134849_create_shop_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop_order', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'add_time' => $this->datetime()->defaultValue(NULL),
            'delivery_date' => $this->datetime()->defaultValue(NULL),
            'issue_date' => $this->datetime()->defaultValue(NULL),
            'user_id' => $this->integer()->defaultValue(NULL),
            'fio' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'address' => $this->text()->notNull(),
            'payment_type' => $this->integer(1)->notNull()->defaultValue(0),
            'delivery_type' => $this->integer(1)->notNull()->defaultValue(0),
            'delivery_price' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'product_discount' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'delivery_discount' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'order_comment' => $this->text()->notNull()->defaultValue(NULL),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-shop_order-user_id',
            'shop_order',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-shop_order-user_id',
            'shop_order',
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
            'fk-shop_order-user_id',
            'shop_order'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-shop_order-user_id',
            'shop_order'
        );

        $this->dropTable('shop_order');
    }
}
