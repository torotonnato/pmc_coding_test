<?php
use yii\bootstrap5\Html;
$this->title = 'Behold our glorious market';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="site-listmarket">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Here is our selected list of vehicles for sale</p>
    <div class="row">
        <?php 
            $chunks = array_chunk($list, 3);
            foreach ($chunks as $row): ?>
                <div class="col-lg-3 col-md-3 mb-2 mb-lg-0">
                    <?php foreach ($row as $car): ?>
                        <figure class="figure">
                            <div class="bg-image" >
                                <?php
                                    $img = Html::img($car->image, [
                                                    'alt' => 'Courtesy of user: ' . $car->seller->username,
                                                    'class' => 'w-100 shadow-1-strong rounded mb-4' ]);
                                    $link = Html::tag('a', $img, [
                                        'href' => '/pmc/web/index.php?r=site%2Fdetail&id=' . $car->id ]);
                                    echo $link; ?>
                                <div class="mask text-light d-flex justify-content-center flex-column text-center" style="background-color: rgba(0, 0, 0, 0.3)">
                                    <h4><?= Html::encode($car->model . ' - ' . $car->year) ?></h4>
                                    <p class="m-0"><?= Html::encode($car->km . ' Km') ?></p>
                                </div>
                            </div>
                            <?= Html::tag(
                                'figcaption', 
                                'Owner \'' . $car->seller->username . '\' sells for ' . $car->price . '$', 
                                ['class' => 'figure-caption rounded mb-4']) ?>
                        </figure>                          
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
    </div>
</div>