<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $importModel app\models\Import */
/* @var $stores app\models\Import */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Import!</h1>

        <p class="lead">Import files for shops</p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin([
                        'action' => 'site/upload',
                        'id' => 'upload-form',
                        'method' => 'post',
                        'options' => ['enctype' => 'multipart/form-data']
                    ]);
                ?>

                <?= $form->field($importModel, 'store_id')->dropDownList($stores) ?>

                <?= $form->field($importModel, 'importFiles[]')->fileInput(['multiple' => true, 'accept' => 'csv/*']) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    </div>
</div>
