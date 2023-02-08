<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use app\models\User;
$this->title = 'Ah, so you want to buy this car!';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="site-detail">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><i>Click on the button below to buy this beauty</i></p>
    <div class="container mt-5">
        <div class="row">
            <div class="col mt-lg-5">
                <p>From our trusted premium seller <b><?= Html::encode($car->seller->username) ?></b> to you in an eye blink!</p>
                <p>But first things first — some info:</p>
                <hr/>
                <p>This car was produced in <b><?= Html::encode($car->year) ?></b></p>
                <p>It's being travelling for <b><?= Html::encode($car->km) ?> Km</b></p>
                <p>You can contact its owner at <b><?= Html::encode($car->seller->email)?></b></p>
                <p>How much you are going to pay: <b><?= Html::encode($car->price) ?>$</b></p>
            </div>
            <div class="col">
                <?= Html::img($car->image, [
                    'alt' => 'Courtesy of user: ' . $car->seller->username,
                    'class' => 'w-100 shadow-1-strong rounded-2 mb-18'
                ]); ?>
            </div>
        </div>
        <div class="row">
            <?php 
                $user = null;
                $role = null;
                $isGuest = Yii::$app->user->isGuest;
                if (!$isGuest) {
                    $user = User::findIdentity(Yii::$app->user->id);
                    if ($user != null) {
                        $role = $user->role->role;
                    }
                } 
                $disabled = $role != 'buyer';
                echo Html::tag('button', 
                    $disabled ? 'Sorry, you need a buyer account to continue' : 'Buy me', [
                        'type' => 'button',
                        'class' => 'btn btn-primary btn-lg mt-5',
                        'data-bs-toggle' => 'modal',
                        'data-bs-target' => '#buyModal',
                        'disabled' => $disabled ? true : null ]); ?>
            
            <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buyModalLabel">Are you really, really, really sure you want to buy this car?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        \_(^ ^)_/ No refunds available \_(^ ^)_/
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <?= Html::tag('button', 'Give me that goddamn car!', [
                            'type' => 'button',
                            'class' => 'btn btn-primary',
                            'onclick' => "location.href='index.php?r=site%2Fbuy&car_id=" . $car->id . "'"
                        ]) ?>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>