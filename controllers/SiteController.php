<?php

namespace app\controllers;

use app\models\Import;
use app\models\ImportForm;
use app\models\Store;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;
use function Couchbase\defaultDecoder;


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
                foreach ($importForm->importFiles as $file) {

                    $time = time();
                    $fileName = $time . '_' . $file->name;
                    if (!is_dir(Yii::$app->basePath . '/web/uploads/')) {
                        mkdir(Yii::$app->basePath . '/web/uploads/');
                    }
                    if ((new Import())->store($file->name, $time)) {
                        $file->saveAs(Yii::$app->basePath . '/web/uploads/' . $fileName);
                    }
                }
                return $this->redirect(['imports']);
            }
        }

        return $this->redirect(['index']);
    }

    public function actionImports()
    {
        var_dump('actionImports'); die;

        return $this->render('index', compact('importModel', 'stores'));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
