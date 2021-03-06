<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use common\models\Wallet;
use common\models\search\WalletSearch;
use common\models\search\DepositSearch;
use common\models\search\WithdrawSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\StringHelper;
use backend\models\SendForm;
use backend\models\SendBtc;
use backend\models\SendEth;
use common\commands\SendEmailCommand;
use yii\helpers\Url;
use common\helpers\CoinbaseHelper;

/**
 * WalletController implements the CRUD actions for Wallet model.
 */
class WalletController extends Controller
{

    public $defaultAction = 'me';

    public function actionMe()
    {

        $sendBtc = new SendBtc();
        $sendEth = new SendEth();
        $sendCoin = new SendForm();

        if ($sendBtc->load(Yii::$app->request->post()) && $sendBtc->send()) {
            Yii::$app->getSession()->setFlash('alert-wallet', [
                'body' => Yii::t(
                    'backend',
                    'You has been successfully deposited. Check your email for further instructions.'
                ),
                'options' => ['class' => 'alert-success']
            ]);
        }

        if ($sendEth->load(Yii::$app->request->post()) && $sendEth->send()) {
            Yii::$app->getSession()->setFlash('alert-wallet', [
                'body' => Yii::t(
                    'backend',
                    'You has been successfully deposited. Check your email for further instructions.'
                ),
                'options' => ['class' => 'alert-success']
            ]);
        }

        if ($sendCoin->load(Yii::$app->request->post()) && $sendCoin->send()) {
            Yii::$app->getSession()->setFlash('alert-wallet', [
                'body' => Yii::t(
                    'backend',
                    'You has been successfully deposited. Check your email for further instructions.'
                ),
                'options' => ['class' => 'alert-success']
            ]);
        }

        $user = Yii::$app->user->identity;
        if (!Yii::$app->user->isGuest) {
            if ($user && $user->has2fa && !Yii::$app->session->get('authen_2fa')) {
                return $this->redirect(['/authen']);
            }
        }

        $coin = new CoinbaseHelper();
        //$addresses = $coin->createAddress();
        //var_dump($addresses);die;
        $wallet = $this->findModel(Yii::$app->user->identity->id);
        if (!$wallet) {
            //Create wallet
            $wallet = new Wallet();
            $addresses = $coin->createAddress();
            $wallet->user_id = Yii::$app->user->identity->id;
            if ($addresses) {
                $wallet->wallet_coin = $coin->createWalletCoin();
                if (isset($addresses['BTC'])) {
                    $wallet->wallet_btc = $addresses['BTC']->getAddress();
                }
                if (isset($addresses['ETH'])) {
                    $wallet->wallet_eth = $addresses['ETH']->getAddress();
                }
            }
            $wallet->save();
            ///var_dump($wallet->getErrors());die;
        }

        if (!$wallet->wallet_btc) {
            // Create wallet btc. Call API
            $addresses = $coin->createAddress('BTC');
            if ($addresses && isset($addresses['BTC'])) {
                $wallet->wallet_btc = $addresses['BTC']->getAddress();;
            }
            $wallet->save();
        }

        if (!$wallet->wallet_coin) {
            // Create wallet coin
            $wallet->wallet_coin = $coin->createWalletCoin();
            $wallet->save();
        }

        if (!$wallet->wallet_eth) {
            // Create wallet eth
            $addresses = $coin->createAddress('ETH');
            if ($addresses && isset($addresses['ETH'])) {
                $wallet->wallet_eth = $addresses['ETH']->getAddress();
                $wallet->save();
            }
        }

        $wallet_btc = $wallet->wallet_btc;
        $wallet_eth = $wallet->wallet_eth;
        $wallet_coin = $wallet->wallet_coin;

        $searchModel = new WalletSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Deposit
        $searchDeposit = new DepositSearch();
        $dataDeposit = $searchDeposit->searchUser($user->id);
        $dataDeposit->sort = [
            'defaultOrder' => ['created_at' => SORT_DESC]
        ];

        // Withdraw
        $searchWithdraw = new WithdrawSearch();
        $dataWithdraw = $searchWithdraw->searchUser($user->id);
        $dataWithdraw->sort = [
            'defaultOrder' => ['created_at' => SORT_DESC]
        ];

        return $this->render('me', [
            'searchModel' => $searchModel,
            'searchWithdraw' => $searchWithdraw,
            'searchDeposit' => $searchDeposit,
            'dataProvider' => $dataProvider,
            'dataDeposit' => $dataDeposit,
            'dataWithdraw' => $dataWithdraw,
            'wallet_btc' => $wallet_btc,
            'wallet_eth' => $wallet_eth,
            'wallet_coin' => $wallet_coin,
            'wallet' => $wallet,
            'modelBtc' => $sendBtc,
            'modelEth' => $sendEth,
            'modelCoin' => $sendCoin,
        ]);
    }

    /**
     * Finds the Wallet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @return Wallet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id)
    {
//        $userIds = Yii::$app->authManager->getUserIdsByRole(User::ROLE_ADMINISTRATOR);
//        if (in_array($user_id, $userIds)) {
//            return null;
//        }
        if (($model = Wallet::find()->where(['user_id' => $user_id])->limit(1)->one()) !== null) {
            return $model;
        } else {
            return null;
        }
    }

    /**
     * Lists all Wallet models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new WalletSearch();
        $dataProvider = $searchModel->searchManager(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Withdraw model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
}
