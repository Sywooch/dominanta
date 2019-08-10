<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_label`.
 */
class m190810_204837_create_product_label_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_label', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(NULL),
            'label' => $this->string()->notNull(),
            'font_color' => $this->string()->defaultValue(NULL),
            'bg_color' => $this->string()->defaultValue(NULL),
            'link' => $this->string()->defaultValue(NULL),
            'widget' => $this->string()->defaultValue(NULL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product_label');
    }
}
