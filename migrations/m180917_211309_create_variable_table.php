<?php

use yii\db\Migration;

/**
 * Handles the creation of table `variable`.
 */
class m180917_211309_create_variable_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('variable', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'value' => $this->text()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('variable');
    }
}
