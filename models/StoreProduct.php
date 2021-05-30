<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * ContactForm is the model behind the contact form.
 */
class StoreProduct extends ActiveRecord
{
    public static function tableName() {
        return 'store_product';
    }

    public function createOrUpdate(array $data)
    {
        $this->store_product_import_id = $data['import_id'];
        $this->upc = $data['upc'];
        $this->title = $data['title'] ?? NULL;
        $this->price = $data['price'] ?? NULL;
        $this->save();
    }
}
