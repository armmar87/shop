<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ImportForm extends Model
{
    public $importFiles;
    public $store_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
//            ['store_id', 'required'],
//            ['store_id', 'number'],
            [
                ['importFiles'],
                'file',
                'maxSize' => 5120000,
                'skipOnEmpty' => false,
                'extensions' => 'csv',
                'maxFiles' => 10,
                'checkExtensionByMimeType' => false
            ]
        ];
    }
}
