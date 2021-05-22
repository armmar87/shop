<?php

namespace app\controllers;

use app\models\Import;
use app\models\ImportForm;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use function Couchbase\defaultDecoder;

class ImportController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionView()
    {
        return $this->render('index');
    }

    public function actionUpload()
    {

        $importForm = new ImportForm();

        if (Yii::$app->request->isPost) {
            $importForm->importFiles = UploadedFile::getInstances($importForm, 'importFiles');

            if ($importForm->validate()) {
                foreach ($importForm->importFiles as $file) {
                    $file->saveAs('uploads/' . $file->name);
                    $import = new Import();
                    $import->file_name = $file->name;
                    $import->store_id = 2;
                    $import->save();
                }
                return $this->redirect(['view']);
            }
        }

        return $this->render('index', ['model' => $importForm]);
    }
}
