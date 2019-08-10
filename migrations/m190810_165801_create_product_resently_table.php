<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_resently`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `user`
 */
class m190810_165801_create_product_resently_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_resently', [
            'id' => $this->primaryKey(),
            'add_time' => $this->datetime()->defaultValue(NULL),
            'product_id' => $this->integer(),
            'hash' => $this->string(),
            'user_id' => $this->integer()->defaultValue(NULL),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_resently-product_id',
            'product_resently',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_resently-product_id',
            'product_resently',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-product_resently-user_id',
            'product_resently',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-product_resently-user_id',
            'product_resently',
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
        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_resently-product_id',
            'product_resently'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_resently-product_id',
            'product_resently'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-product_resently-user_id',
            'product_resently'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-product_resently-user_id',
            'product_resently'
        );

        $this->dropTable('product_resently');
    }
}
