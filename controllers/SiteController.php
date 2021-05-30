<?php

namespace app\controllers;

use app\jobs\ImportJob;
use app\models\Import;
use app\models\ImportForm;
use app\models\Store;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $importModel = new ImportForm();
        $allStores = Store::find()->all();

        $stores = [];
        foreach ($allStores as $store) {
            $stores[$store->id] = $store->title;
        }

        return $this->render('index', compact('importModel', 'stores'));
    }

    public function actionUpload()
    {
        $importForm = new ImportForm();
        if (Yii::$app->request->isPost) {
            $importForm->importFiles = UploadedFile::getInstances($importForm, 'importFiles');
            if ($importForm->validate()) {
                if (!is_dir($this->uploadFilePath())) {
                    mkdir($this->uploadFilePath());
                }
                if (!is_dir(Yii::$app->basePath . '/runtime/queue')) {
                    mkdir(Yii::$app->basePath . '/runtime/queue');
                }
                foreach ($importForm->importFiles as $file) {
                    $time = time();
                    $fileName = $time . '_' . $file->name;
                    if ($importId = (new Import())->store($file->name, $time)) {
                        $file->saveAs($this->uploadFilePath() . $fileName);
                    }
                    Yii::$app->queue->push(new ImportJob([
                        'importId' => $importId,
                        'storeId' => $_POST['ImportForm']['store_id']
                    ]));
                }
                return $this->redirect(['imports']);
            }
        }

        return $this->redirect(['index']);
    }

    public function uploadFilePath(): string
    {
        return Yii::$app->basePath . '/web/uploads/';
    }


    public function actionImports()
    {
        var_dump('actionImports'); die;

        return $this->render('index', compact('importModel', 'stores'));
    }
}
