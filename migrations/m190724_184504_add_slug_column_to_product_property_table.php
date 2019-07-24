<?php

use yii\db\Migration;

/**
 * Handles adding slug to table `product_property`.
 */
class m190724_184504_add_slug_column_to_product_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product_property', 'slug', $this->string()->notNull()->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product_property', 'slug');
    }
}
