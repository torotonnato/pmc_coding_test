<?php
use app\models\User;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;
$this->title = 'Sign up';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to sign up:</p>
    <?php $form = ActiveForm::begin([
        'id' => 'signup-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ] ]); ?>
    <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'autocomplete' => 'off']) ?>
    <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off']) ?>
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'role')->radioList(['buyer' => "I want to buy", 'seller' => "I am a seller"], ['class' => 'offset-lg-0 offset-sm-0']) ?>
    <?= $form->field($model, 'auto_login')->checkbox([
        'template' => "<div class=\"col-lg-3 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>" ]) ?>
    <div class="form-group">
        <div class="row">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
    </div>
    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
        'template' => '<div class="row"><div class="col">{image}</div><div class="col">{input}</div></div>',
        'class' => 'col-lg-1 col-form-label mr-lg-3' ]) ?>
    <?php ActiveForm::end(); ?>
</div>
