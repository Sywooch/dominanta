<?php

use yii\db\Migration;
use app\models\ActiveRecord\Role;

/**
 * Handles the creation of table `role`.
 * Has foreign keys to the tables:
 *
 * - `role`
 */
class m180915_104910_create_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('role', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'pid' => $this->integer()->defaultValue(NULL),
            'role_name' => $this->string()->notNull(),
            'is_admin' => $this->integer(1)->notNull()->defaultValue(0),
        ]);

        // creates index for column `pid`
        $this->createIndex(
            'idx-role-pid',
            'role',
            'pid'
        );

        // add foreign key for table `role`
        $this->addForeignKey(
            'fk-role-pid',
            'role',
            'pid',
            'role',
            'id',
            'CASCADE'
        );

        Role::createAndSave([
            'role_name' => 'Administrator',
            'status'    => Role::STATUS_ACTIVE,
            'is_admin'  => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `role`
        $this->dropForeignKey(
            'fk-role-pid',
            'role'
        );

        // drops index for column `pid`
        $this->dropIndex(
            'idx-role-pid',
            'role'
        );

        $this->dropTable('role');
    }
}
