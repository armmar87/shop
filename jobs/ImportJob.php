<?php
namespace jobs;

use \yii\base\BaseObject;

class ImportJob extends BaseObject implements \yii\queue\JobInterface
{
    public $file;
    public $importId;
    public $storeId;

    public function execute($queue)
    {
        var_dump($this->file); die;
    }
}