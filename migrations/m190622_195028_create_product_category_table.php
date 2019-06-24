<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_category`.
 * Has foreign keys to the tables:
 *
 * - `product_category`
 */
class m190622_195028_create_product_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_category', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'pid' => $this->integer()->defaultValue(NULL),
            'category_name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'category_description' => $this->text()->defaultValue(NULL),
            'title' => $this->text()->defaultValue(NULL),
            'meta_keywords' => $this->text()->defaultValue(NULL),
            'meta_description' => $this->text()->defaultValue(NULL),
            'last_update' => $this->datetime()->defaultValue(NULL),
            'link' => $this->string()->defaultValue(NULL),
        ]);

        // creates index for column `pid`
        $this->createIndex(
            'idx-product_category-pid',
            'product_category',
            'pid'
        );

        // add foreign key for table `product_category`
        $this->addForeignKey(
            'fk-product_category-pid',
            'product_category',
            'pid',
            'product_category',
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
            'fk-product_category-pid',
            'product_category'
        );

        // drops index for column `pid`
        $this->dropIndex(
            'idx-product_category-pid',
            'product_category'
        );

        $this->dropTable('product_category');
    }
}
