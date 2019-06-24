<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_photo`.
 * Has foreign keys to the tables:
 *
 * - `product`
 */
class m190624_204705_create_product_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_photo', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->defaultValue(NULL),
            'photo_order' => $this->integer()->notNull()->defaultValue(1),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_photo-product_id',
            'product_photo',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_photo-product_id',
            'product_photo',
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
        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-product_photo-product_id',
            'product_photo'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_photo-product_id',
            'product_photo'
        );

        $this->dropTable('product_photo');
    }
}
