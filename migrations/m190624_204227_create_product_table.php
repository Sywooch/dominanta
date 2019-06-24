<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 * Has foreign keys to the tables:
 *
 * - `product_category`
 * - `vendor`
 */
class m190624_204227_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'cat_id' => $this->integer()->defaultValue(NULL),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'product_name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'product_desc' => $this->text()->defaultValue(NULL),
            'price' => $this->decimal(10,2)->notNull()->defaultValue(0),
            'old_price' => $this->decimal(10,2)->defaultValue(NULL),
            'quantity' => $this->decimal(9,3)->defaultValue(0),
            'discount' => $this->text()->defaultValue(NULL),
            'labels' => $this->text()->defaultValue(NULL),
            'properties' => $this->text()->defaultValue(NULL),
            'unit' => $this->string()->notNull()->defaultValue('pcs'),
            'packing_quantity' => $this->decimal(9,3)->defaultValue(NULL),
            'ext_code' => $this->string()->defaultValue(NULL),
            'int_code' => $this->string()->defaultValue(NULL),
            'link' => $this->text()->defaultValue(NULL),
            'vendor_id' => $this->integer()->defaultValue(NULL),
            'title' => $this->string()->notNull()->defaultValue(''),
            'meta_keywords' => $this->text()->defaultValue(NULL),
            'meta_description' => $this->text()->defaultValue(NULL),
            'last_update' => $this->datetime()->defaultValue(NULL),
        ]);

        // creates index for column `cat_id`
        $this->createIndex(
            'idx-product-cat_id',
            'product',
            'cat_id'
        );

        // add foreign key for table `product_category`
        $this->addForeignKey(
            'fk-product-cat_id',
            'product',
            'cat_id',
            'product_category',
            'id',
            'CASCADE'
        );

        // creates index for column `vendor_id`
        $this->createIndex(
            'idx-product-vendor_id',
            'product',
            'vendor_id'
        );

        // add foreign key for table `vendor`
        $this->addForeignKey(
            'fk-product-vendor_id',
            'product',
            'vendor_id',
            'vendor',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `product_category`
        $this->dropForeignKey(
            'fk-product-cat_id',
            'product'
        );

        // drops index for column `cat_id`
        $this->dropIndex(
            'idx-product-cat_id',
            'product'
        );

        // drops foreign key for table `vendor`
        $this->dropForeignKey(
            'fk-product-vendor_id',
            'product'
        );

        // drops index for column `vendor_id`
        $this->dropIndex(
            'idx-product-vendor_id',
            'product'
        );

        $this->dropTable('product');
    }
}
