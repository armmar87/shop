<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * ContactForm is the model behind the contact form.
 */
class Import extends ActiveRecord
{
    public static function tableName() {
        return 'imports';
    }

    public function rules() {
        return [

        ];
    }

    public function attributeLabels() {
        return [

        ];
    }

    /**
     * @param string $fileName
     * @return integer
     */
    public function store(string $fileName, string $timestamp): int
    {
        $this->file_name = $fileName;
        $this->store_id = $_POST['ImportForm']['store_id'];
        $this->created_at = $timestamp;
        $this->save();

        return $this->id;
    }
}
