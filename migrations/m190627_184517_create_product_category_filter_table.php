<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_category_filter`.
 * Has foreign keys to the tables:
 *
 * - `product_category`
 * - `property`
 */
class m190627_184517_create_product_category_filter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_category_filter', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->defaultValue(NULL),
            'property_id' => $this->integer()->defaultValue(NULL),
            'filter_order' => $this->integer()->notNull()->defaultValue(0),
            'filter_view' => $this->string()->defaultValue(NULL),
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            'idx-product_category_filter-category_id',
            'product_category_filter',
            'category_id'
        );

        // add foreign key for table `product_category`
        $this->addForeignKey(
            'fk-product_category_filter-category_id',
            'product_category_filter',
            'category_id',
            'product_category',
            'id',
            'CASCADE'
        );

        // creates index for column `property_id`
        $this->createIndex(
            'idx-product_category_filter-property_id',
            'product_category_filter',
            'property_id'
        );

        // add foreign key for table `property`
        $this->addForeignKey(
            'fk-product_category_filter-property_id',
            'product_category_filter',
            'property_id',
            'property',
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
            'fk-product_category_filter-category_id',
            'product_category_filter'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-product_category_filter-category_id',
            'product_category_filter'
        );

        // drops foreign key for table `property`
        $this->dropForeignKey(
            'fk-product_category_filter-property_id',
            'product_category_filter'
        );

        // drops index for column `property_id`
        $this->dropIndex(
            'idx-product_category_filter-property_id',
            'product_category_filter'
        );

        $this->dropTable('product_category_filter');
    }
}
