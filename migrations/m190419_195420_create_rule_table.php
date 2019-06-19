<?php

use yii\db\Migration;
use app\components\helpers\ModelsHelper;
use app\models\ActiveRecord\Rule;

/**
 * Handles the creation of table `rule`.
 * Has foreign keys to the tables:
 *
 * - `role`
 */
class m190419_195420_create_rule_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('rule', [
            'id' => $this->primaryKey(),
            'role_id' => $this->integer()->defaultValue(NULL),
            'model' => $this->string()->notNull(),
            'is_view' => $this->integer(1)->notNull()->defaultValue(0),
            'is_add' => $this->integer(1)->notNull()->defaultValue(0),
            'is_edit' => $this->integer()->notNull()->defaultValue(0),
            'is_delete' => $this->integer(1)->notNull()->defaultValue(0),
        ]);

        // creates index for column `role_id`
        $this->createIndex(
            'idx-rule-role_id',
            'rule',
            'role_id'
        );

        // add foreign key for table `role`
        $this->addForeignKey(
            'fk-rule-role_id',
            'rule',
            'role_id',
            'role',
            'id',
            'CASCADE'
        );

        $models = ModelsHelper::get();

        foreach ($models AS $modelname => $model) {
            Rule::createAndSave([
                'role_id'  => 1,
                'model'    => $modelname,
                'is_view' => 1,
                'is_add' => 1,
                'is_edit' => 1,
                'is_delete' => 1,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `role`
        $this->dropForeignKey(
            'fk-rule-role_id',
            'rules'
        );

        // drops index for column `role_id`
        $this->dropIndex(
            'idx-rule-role_id',
            'rules'
        );

        $this->dropTable('rule');
    }
}
