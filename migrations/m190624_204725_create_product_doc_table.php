<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_doc`.
 * Has foreign keys to the tables:
 *
 * - `product`
 */
class m190624_204725_create_product_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_doc', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->defaultValue(NULL),
            'doc_order' => $this->integer()->notNull()->defaultValue(1),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            'idx-product_doc-product_id',
            'product_doc',
            'product_id'
        );

        // add foreign key for table `product`
        $this->addForeignKey(
            'fk-product_doc-product_id',
            'product_doc',
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
            'fk-product_doc-product_id',
            'product_doc'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-product_doc-product_id',
            'product_doc'
        );

        $this->dropTable('product_doc');
    }
}
