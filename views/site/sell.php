<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
$this->title = 'Sell a car';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="site-sell">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to sell your car:</p>
    <?php $form = ActiveForm::begin([
        'id' => 'sell-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
        'options' => ['enctype' => 'multipart/form-data'] ]); ?>
        <?= $form->field($model, 'model')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'year')->textInput() ?>
        <?= $form->field($model, 'price')->textInput() ?>
        <?= $form->field($model, 'km')->textInput() ?>
        <?= $form->field($model, 'image')->fileInput() ?>
        <div class="form-group">
            <div class="row">
                <?= Html::submitButton('Sell now!', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
