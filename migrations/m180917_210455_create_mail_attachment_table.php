<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mail_attachment`.
 * Has foreign keys to the tables:
 *
 * - `mail`
 */
class m180917_210455_create_mail_attachment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mail_attachment', [
            'id' => $this->primaryKey(),
            'mail_id' => $this->integer()->defaultValue(NULL),
            'path' => $this->string()->notNull()->defaultValue(''),
            'embed' => $this->integer(1)->notNull()->defaultValue(0),
        ]);

        // creates index for column `mail_id`
        $this->createIndex(
            'idx-mail_attachment-mail_id',
            'mail_attachment',
            'mail_id'
        );

        // add foreign key for table `mail`
        $this->addForeignKey(
            'fk-mail_attachment-mail_id',
            'mail_attachment',
            'mail_id',
            'mail',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `mail`
        $this->dropForeignKey(
            'fk-mail_attachment-mail_id',
            'mail_attachment'
        );

        // drops index for column `mail_id`
        $this->dropIndex(
            'idx-mail_attachment-mail_id',
            'mail_attachment'
        );

        $this->dropTable('mail_attachment');
    }
}
