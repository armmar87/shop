<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%store_product}}`.
 */
class m210516_151822_create_store_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%store_product}}', [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer()->notNull(),
            'store_product_import_id' => $this->integer(),
            'upc' => $this->string(50)->unique()->notNull(),
            'title' => $this->string(150),
            'price' => $this->double(),
        ]);

        $this->createIndex(
            'idx-store_product-store_id',
            'store_product',
            'store_id'
        );

        $this->addForeignKey(
            'fk-store_product-store_id',
            'store_product',
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
        $this->dropForeignKey(
            'fk-store_product-store_id',
            'store_product'
        );

        $this->dropIndex(
            'store_product-store_id',
            'store_product'
        );

        $this->dropTable('{{%store_product}}');
    }
}
