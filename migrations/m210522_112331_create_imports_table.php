<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%imports}}`.
 */
class m210522_112331_create_imports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%imports}}', [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer()->notNull(),
            'failed' => $this->integer()->defaultValue(0),
            'status' => "ENUM('New', 'Processing', 'done') NOT NULL DEFAULT 'New'",
            'file_name' => $this->string(100),
            'created_at' => $this->dateTime(),
        ]);

        $this->createIndex(
            'idx-imports-store_id',
            'imports',
            'store_id'
        );

        $this->addForeignKey(
            'fk-imports-store_id',
            'imports',
            'store_id',
            'store',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%imports}}');
    }
}
