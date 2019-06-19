<?php

use yii\db\Migration;
use app\models\ActiveRecord\Role;
use app\models\ActiveRecord\User;

/**
 * Handles the creation of table `user`.
 * Has foreign keys to the tables:
 *
 * - `role`
 */
class m180915_115617_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'email' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'access_token' => $this->string()->notNull()->defaultValue(''),
            'role_id' => $this->integer()->defaultValue(NULL),
            'create_time' => $this->datetime()->defaultValue(NULL),
            'last_activity' => $this->datetime()->defaultValue(NULL),
            'language' => $this->string()->notNull()->defaultValue('ru-RU'),
            'timeZone' => $this->string()->notNull()->defaultValue('Europe/Moscow'),
            'realname' => $this->string()->notNull()->defaultValue(''),
            'phone' => $this->string()->notNull()->defaultValue(''),
        ]);

        // creates index for column `role_id`
        $this->createIndex(
            'idx-user-role_id',
            'user',
            'role_id'
        );

        // add foreign key for table `role`
        $this->addForeignKey(
            'fk-user-role_id',
            'user',
            'role_id',
            'role',
            'id',
            'CASCADE'
        );

        $admin = new User;
        $admin->status = User::STATUS_ACTIVE;
        $admin->email = 'info@inter-projects.ru';
        $admin->role_id = Role::findOne(['is_admin' => 1])->id;
        $admin->setPassword('root123456');
        $admin->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `role`
        $this->dropForeignKey(
            'fk-user-role_id',
            'user'
        );

        // drops index for column `role_id`
        $this->dropIndex(
            'idx-user-role_id',
            'user'
        );

        $this->dropTable('user');
    }
}
