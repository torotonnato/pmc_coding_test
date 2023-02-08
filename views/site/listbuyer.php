<?php
use yii\bootstrap5\Html;
$this->title = 'These are the cars you bought to this day';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="site-listbuyer">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <?php 
            $chunks = array_chunk($list, 3);
            foreach ($chunks as $row): ?>
                <div class="col-lg-3 col-md-3 mb-2 mb-lg-0">
                    <?php foreach ($row as $order): $car = $order->car; ?>
                        <figure class="figure">
                            <div class="bg-image" >
                                <?php
                                    echo Html::img($car->image, [
                                            'alt' => 'Courtesy of user: ' . $car->seller->username,
                                            'class' => 'w-100 shadow-1-strong rounded mb-4' ]); ?>
                                <div class="mask text-light d-flex justify-content-center flex-column text-center" style="background-color: rgba(0, 0, 0, 0.3)">
                                    <h4><?= Html::encode($car->model . ' - ' . $car->year) ?></h4>
                                    <p class="m-0"><?= Html::encode($car->km . ' Km') ?></p>
                                </div>
                            </div>
                            <?= Html::tag(
                                'figcaption', 
                                'Owner \'' . $car->seller->username . '\' sold this one for ' . $car->price . '$', 
                                ['class' => 'figure-caption rounded mb-4']) ?>
                        </figure>                          
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
    </div>
</div>