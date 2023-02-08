<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ContactForm;
use app\models\SellForm;
use app\models\User;
use app\models\Order;
use app\models\Car;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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

    public function actionIndex()
    {
        return $this->actionMarket();
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            throw new NotFoundHttpException;
        }
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->goHome();
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        User::logout();
        return $this->goHome();
    }

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

    public function actionBuy($car_id) {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException;
        }      
        $user = User::findIdentity(Yii::$app->user->id);
        if ($user === null) {
            throw new ServerErrorHttpException;
        }
        if ($user->role->role != 'buyer') {
            throw new NotFoundHttpException;
        }
        $car = Car::findById($car_id);
        if ($car === null) {
            throw new ServerErrorHttpException;
        }
        if (!$car->buy(Yii::$app->user->id)) {
            throw new ServerErrorHttpException;
        }
    }

    public function actionSell() {
        $model = new SellForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->sell()) {
                return $this->actionListSeller();
            }
            throw new ServerErrorHttpException;
        }
        return $this->render('sell', [
            'model' => $model,
        ]);
    }

    public function actionDetail($id) {
        $car = Car::findById($id);
        if ($car === null) {
            throw new NotFoundHttpException;
        }
        return $this->render('detail', [
                'car' => $car
        ]);
    }

    public function actionListbuyer() {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException;
        }

        $list = Order::listBuyerCars(Yii::$app->user->id);
        if ($list === null) {
            throw new NotFoundHttpException;
        }
        Yii::error(var_export($list, true));
        return $this->render('listbuyer', [
            'list' => $list,
        ]);
    }

    public function actionListseller() {
        if (Yii::$app->user->isGuest)
            throw new NotFoundHttpException;
        
        $list = Order::listSellerCars(Yii::$app->user->id);
        if ($list === null)
            throw new NotFoundHttpException;

        return $this->render('listseller', [
            'list' => $list,
        ]);
    }

    public function actionMarket() {
        $list = Order::listMarketCars();
        if ($list === null)
            throw new ServerErrorHttpException;;

        return $this->render('listmarket', [
            'list' => $list,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

}
