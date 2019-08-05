<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_review`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `user`
 * - `user`
 */
class m190805_211758_create_product_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_review', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'product_id' => $this->integer(),
            'add_time' => $this->datetime()->defaultValue(NULL),
            'user_id' => $this->integer()->defaultValue(NULL),
            'reviewer' => $this->string()->defaultValue(NULL),
            'review_text' => $this->text()->defaultValue(NULL),
            'approver' => $this->integer()->defaultValue(NULL),
            'approved' => $this->datetime()->defaultValue(NULL),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_review-product_id',
            'product_review',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_review-product_id',
            'product_review',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-product_review-user_id',
            'product_review',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-product_review-user_id',
            'product_review',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `approver`
        $this->createIndex(
            'idx-product_review-approver',
            'product_review',
            'approver'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-product_review-approver',
            'product_review',
            'approver',
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
            'fk-product_review-product_id',
            'product_review'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_review-product_id',
            'product_review'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-product_review-user_id',
            'product_review'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-product_review-user_id',
            'product_review'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-product_review-approver',
            'product_review'
        );

        // drops index for column `approver`
        $this->dropIndex(
            'idx-product_review-approver',
            'product_review'
        );

        $this->dropTable('product_review');
    }
}
