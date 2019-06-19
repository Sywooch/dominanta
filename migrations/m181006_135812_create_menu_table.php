<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 * Has foreign keys to the tables:
 *
 * - `menu`
 */
class m181006_135812_create_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'pid' => $this->integer()->defaultValue(NULL),
            'item' => $this->string()->notNull()->defaultValue(''),
            'link' => $this->string()->notNull()->defaultValue(''),
            'item_order' => $this->integer()->notNull()->defaultValue(0),
        ]);

        // creates index for column `pid`
        $this->createIndex(
            'idx-menu-pid',
            'menu',
            'pid'
        );

        // add foreign key for table `menu`
        $this->addForeignKey(
            'fk-menu-pid',
            'menu',
            'pid',
            'menu',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `menu`
        $this->dropForeignKey(
            'fk-menu-pid',
            'menu'
        );

        // drops index for column `pid`
        $this->dropIndex(
            'idx-menu-pid',
            'menu'
        );

        $this->dropTable('menu');
    }
}
