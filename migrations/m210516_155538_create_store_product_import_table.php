<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%store_product_import}}`.
 */
class m210516_155538_create_store_product_import_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%store_product_import}}', [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer()->notNull(),
            'failed' => $this->integer()->defaultValue(0),
            'status' => "ENUM('New', 'Processing', 'done') NOT NULL DEFAULT `New`",
            'created_at' => $this->dateTime(),
        ]);

        $this->createIndex(
            'idx-store_product_import-store_id',
            'store_product_import',
            'store_id'
        );

        $this->addForeignKey(
            'fk-store_product_import-store_id',
            'store_product_import',
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
        $this->dropTable('{{%store_product_import}}');
    }
}
