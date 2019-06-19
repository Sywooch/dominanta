<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mail`.
 * Has foreign keys to the tables:
 *
 * - `mail_setting`
 */
class m180917_210222_create_mail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mail', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(1)->notNull()->defaultValue(0),
            'mail_setting_id' => $this->integer()->defaultValue(NULL),
            'create_time' => $this->datetime()->defaultValue(NULL),
            'send_time' => $this->datetime()->defaultValue(NULL),
            'to_email' => $this->string()->notNull()->defaultValue(''),
            'subject' => $this->string()->notNull()->defaultValue(''),
            'body_text' => $this->text(),
            'body_html' => $this->text(),
            'send_errors' => $this->text(),
        ]);

        // creates index for column `mail_setting_id`
        $this->createIndex(
            'idx-mail-mail_setting_id',
            'mail',
            'mail_setting_id'
        );

        // add foreign key for table `mail_setting`
        $this->addForeignKey(
            'fk-mail-mail_setting_id',
            'mail',
            'mail_setting_id',
            'mail_setting',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `mail_setting`
        $this->dropForeignKey(
            'fk-mail-mail_setting_id',
            'mail'
        );

        // drops index for column `mail_setting_id`
        $this->dropIndex(
            'idx-mail-mail_setting_id',
            'mail'
        );

        $this->dropTable('mail');
    }
}
