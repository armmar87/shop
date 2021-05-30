<?php
namespace app\jobs;

use app\models\Import;
use app\models\StoreProduct;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use \yii\base\BaseObject;

class ImportJob extends BaseObject implements \yii\queue\JobInterface
{
    public $importId;
    public $storeId;

    public function execute($queue)
    {

        $import = Import::findOne($this->importId);
        $fileName = $import->created_at . '_' . $import->file_name;
        $arr_file = explode('.', $fileName);
        switch (end($arr_file)) {
            case 'csv':
                $reader = new Csv();
                break;
            default:
                //Can be another file type;
        }
        $spreadsheet = $reader->load(\Yii::$app->basePath . '/web/uploads/' . $fileName);
        $sheetsData = $spreadsheet->getActiveSheet()->toArray();
        $productsData = $this->getProductsData($sheetsData);
        $failed = 0;
        foreach ($productsData as $data) {
            if (isset($data['upc']) && $data['upc'] !== '') {
                $storeProduct = StoreProduct::findOne([
                    'upc' => $data['upc'],
                    'store_id' => $import->store_id
                ]);
                if (!$storeProduct) {
                    $storeProduct = new StoreProduct();
                    $storeProduct->store_id = $this->storeId;
                }
                $data['import_id'] = $import->id;
                $storeProduct->createOrUpdate($data);
            } else {
                $failed++;
            }
            $import->failed = $failed;
            if ($import->status !== 'Processing') {
                $import->status = 'Processing';
                $import->save();
            }
        }
        $import->status = 'Done';
        $import->save();
    }

    private function getProductsData(array $sheetsData): array
    {
        $productsData = [];
        foreach ($sheetsData as $key => $sheetData) {
            if ($key !== 0) {
                foreach ($sheetData as $dataKey => $data) {
                    $productsData[$key][$sheetsData[0][$dataKey]] = $data;
                }
            }
        }

        return $productsData;
    }
}