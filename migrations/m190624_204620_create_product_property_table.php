<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_property`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `property`
 */
class m190624_204620_create_product_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_property', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->defaultValue(NULL),
            'property_id' => $this->integer()->defaultValue(NULL),
            'property_value' => $this->string()->notNull(),
            'property_order' => $this->integer()->notNull()->defaultValue(1),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_property-product_id',
            'product_property',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_property-product_id',
            'product_property',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `property_id`
        $this->createIndex(
            'idx-product_property-property_id',
            'product_property',
            'property_id'
        );

        // add foreign key for table `property`
        $this->addForeignKey(
            'fk-product_property-property_id',
            'product_property',
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
        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_property-product_id',
            'product_property'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_property-product_id',
            'product_property'
        );

        // drops foreign key for table `property`
        $this->dropForeignKey(
            'fk-product_property-property_id',
            'product_property'
        );

        // drops index for column `property_id`
        $this->dropIndex(
            'idx-product_property-property_id',
            'product_property'
        );

        $this->dropTable('product_property');
    }
}
