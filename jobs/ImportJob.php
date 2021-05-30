<?php
namespace app\jobs;

use app\controllers\SiteController;
use app\models\Import;
use app\models\StoreProduct;
use \yii\base\BaseObject;

class ImportJob extends BaseObject implements \yii\queue\JobInterface
{
    public $importId;
    public $storeId;

    public function execute($queue)
    {

        $import = Import::findOne($this->importId);
        $fileName = $import->created_at . '_' . $import->file_name;
        $row = 1;
        $dbColumns = [];
        $columnsData = [];
        if (($handle = fopen(\Yii::$app->basePath . '/web/uploads/' . $fileName, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row !== 1) {
                    $columnsData[] = $data;
                } else {
                    $dbColumns = $data;
                }
                $row++;
            }
            fclose($handle);
        }
        $dbData = [];
        foreach ($columnsData as $key => $columnData) {
            foreach ($columnData as $dataKey => $data) {
                $dbData[$key][$dbColumns[$dataKey]] = $data;
            }
        }
        $failed = 0;
        foreach ($dbData as $data) {
            if ($data['upc'] !== '') {
                $storeProduct = StoreProduct::find()->where([
                    'upc' => $data['upc'],
                    'store_id' => $import->store_id,
                ])->one();
                if (!$storeProduct) {
                    $storeProduct = new StoreProduct();
                    $storeProduct->store_id = $this->storeId;
                }
                $storeProduct->store_product_import_id = $import->id;
                $storeProduct->upc = $data['upc'];
                $storeProduct->title = $data['title'];
                $storeProduct->price = $data['price'];
                $storeProduct->save();
            } else {
                $failed++;
            }
            $import->failed = $failed;
        }
        $import->status = 'Done';
        $import->save();

    }
}