<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_cross`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `product`
 */
class m190810_164603_create_product_cross_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_cross', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'cross_id' => $this->integer(),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_cross-product_id',
            'product_cross',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_cross-product_id',
            'product_cross',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `cross_id`
        $this->createIndex(
            'idx-product_cross-cross_id',
            'product_cross',
            'cross_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_cross-cross_id',
            'product_cross',
            'cross_id',
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
        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_cross-product_id',
            'product_cross'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_cross-product_id',
            'product_cross'
        );

        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_cross-cross_id',
            'product_cross'
        );

        // drops index for column `cross_id`
        $this->dropIndex(
            'idx-product_cross-cross_id',
            'product_cross'
        );

        $this->dropTable('product_cross');
    }
}
