<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to login:</p>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ] ]); ?>
        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'auto_login')->checkbox([
            'template' => "<div class=\"col-lg-3 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>
        <div class="form-group">
            <div class="row">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
