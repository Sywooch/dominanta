<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_labels`.
 * Has foreign keys to the tables:
 *
 * - `product`
 * - `product_label`
 */
class m190810_205015_create_product_labels_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_labels', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'label_id' => $this->integer(),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_labels-product_id',
            'product_labels',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_labels-product_id',
            'product_labels',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );

        // creates index for column `label_id`
        $this->createIndex(
            'idx-product_labels-label_id',
            'product_labels',
            'label_id'
        );

        // add foreign key for table `product_label`
        $this->addForeignKey(
            'fk-product_labels-label_id',
            'product_labels',
            'label_id',
            'product_label',
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
            'fk-product_labels-product_id',
            'product_labels'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_labels-product_id',
            'product_labels'
        );

        // drops foreign key for table `product_label`
        $this->dropForeignKey(
            'fk-product_labels-label_id',
            'product_labels'
        );

        // drops index for column `label_id`
        $this->dropIndex(
            'idx-product_labels-label_id',
            'product_labels'
        );

        $this->dropTable('product_labels');
    }
}
