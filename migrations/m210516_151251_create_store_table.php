<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%store}}`.
 */
class m210516_151251_create_store_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%store}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string('150')->notNull(),
        ]);

        for ($i = 1; $i < 4; $i++) {
            $this->insert('store', [
                'title' => 'Store ' . $i
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%store}}');
    }
}
