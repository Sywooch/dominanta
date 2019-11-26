<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mail_setting`.
 */
class m180917_185330_create_mail_setting_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mail_setting', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'service_name' => $this->string()->notNull()->defaultValue(''),
            'smtp_host' => $this->string()->notNull()->defaultValue(''),
            'smtp_port' => $this->integer(5)->notNull()->defaultValue(25),
            'smtp_user' => $this->string()->notNull()->defaultValue(''),
            'smtp_password' => $this->string()->notNull()->defaultValue(''),
            'smtp_secure' => $this->string(5)->notNull()->defaultValue(''),
            'from_email' => $this->string()->notNull()->defaultValue(''),
            'from_name' => $this->string()->notNull()->defaultValue(''),
            'reply_to' => $this->string()->notNull()->defaultValue(''),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('mail_setting');
    }
}
