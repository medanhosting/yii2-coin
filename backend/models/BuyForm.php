<?php
namespace backend\models;

use cheatsheet\Time;
use common\commands\AddToEventCommand;
use common\commands\AddToPlusTeamCommand;
use common\commands\SendEmailCommand;
use common\models\User;
use common\models\Wallet;
use common\models\Buy;
use common\models\UserToken;
use backend\modules\user\Module;
use yii\base\Exception;
use yii\base\Model;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * BuyForm form
 */
class BuyForm extends Model
{
    /**
     * @var
     */
    public $type;

    /**
     * @var
     */
    public $amount_coin;

    /**
     * @var
     */
    public $token;

    /**
     * @var
     */
    public $amount;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['amount', 'amount_coin', 'token', 'type'], 'required'],
            [['token'], 'string'],
            [['amount', 'type'], 'number'],
            [['amount_coin'], 'number', 'min' => 200],
            ['token', 'validateToken'],
            ['amount_coin', 'validateAmount'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'amount_coin' => Yii::t('backend', 'Amount of TKC'),
            'amount' => Yii::t('backend', 'Amount'),
            'token' => Yii::t('backend', 'Token'),
        ];
    }

    public function validateAmount()
    {
        $wallet = Wallet::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
        if (!$this->hasErrors()) {
            if($this->amount_coin >= 200){
                if ($this->type == 1) {
                    $amount_tmp = $this->amount_coin * Yii::$app->keyStorage->get('coin.rate-btc');
                    if ($wallet->amount_btc < $amount_tmp) {
                        $valid = $wallet->amount_btc / Yii::$app->keyStorage->get('coin.rate-btc');
                        if($valid >= 200) {
                            $this->addError('amount_coin', Yii::t('backend', 'Not enough amount BTC. Please enter a value of amount less than or equal to ' . round($valid, 0) . ' TKC'));
                        }else{
                            $this->addError('amount_coin', Yii::t('backend', 'Not enough amount BTC. Please deposit to buy TKC.'));
                        }
                    }
                }

                if ($this->type == 2) {
                    $amount_tmp = $this->amount_coin * Yii::$app->keyStorage->get('coin.rate-eth');
                    if ($wallet->amount_eth < $amount_tmp) {
                        $valid = $wallet->amount_eth / Yii::$app->keyStorage->get('coin.rate-eth');
                        if($valid >= 200) {
                            $this->addError('amount_coin', Yii::t('backend', 'Not enough amount ETH. Please enter a value of amount less than or equal to ' . round($valid, 0) . ' TKC'));
                        }else{
                            $this->addError('amount_coin', Yii::t('backend', 'Not enough amount ETH. Please deposit to buy TKC.'));
                        }
                    }
                }
            }
        }
    }

    public function validateToken()
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            $token = UserToken::find()
                ->byUser($user->id)
                ->byType(UserToken::TYPE_BUY)
                ->byToken($this->token)
                ->notExpired()
                ->one();
            if (!$token) {
                $this->addError('token', Yii::t('backend', 'Token is incorrect.'));
            }
        }
    }

    /**
     *
     *
     * @return Buy|null the saved model or null if saving fails
     */
    public function save()
    {
        $user = Yii::$app->user->identity;

        if ($this->validate()) {

            $token = UserToken::find()
                ->byUser($user->id)
                ->byType(UserToken::TYPE_BUY)
                ->byToken($this->token)
                ->notExpired()
                ->one();

            if (!$token) {
                throw new NotFoundHttpException("Wrong token!");
            }

            // Check wallet
            $wallet = Wallet::find()->byUser($user->id)->one();

            if (!$wallet) {
                throw new NotFoundHttpException("System error!");
            }

            $sold = Yii::$app->keyStorage->get('coin.sold');
            $total = Yii::$app->keyStorage->get('coin.total');
            $remain = $total - $sold;

            if ($remain < $this->amount_coin) {
                throw new BadRequestHttpException("System out TKC!");
            }

            if ($this->type == 1) {
                // Check btc
                $rateCoinBtc = Yii::$app->keyStorage->get('coin.rate-btc');
                $btc = $rateCoinBtc * $this->amount_coin;
                $this->amount = $btc;
                if ($wallet->amount_btc < $btc) {
                    throw new BadRequestHttpException("Not enough BTC!");
                }

                $wallet->amount_btc -= $btc;
            } else {
                // Check btc
                $rateCoinBtc = Yii::$app->keyStorage->get('coin.rate-eth');
                $eth = $rateCoinBtc * $this->amount_coin;
                $this->amount = $eth;
                if ($wallet->amount_eth < $eth) {
                    throw new BadRequestHttpException("Not enough ETH!");
                }

                $wallet->amount_eth -= $eth;
            }
            $wallet->amount_coin += $this->amount_coin;
            $wallet->amount_ico += $this->amount_coin;
            $wallet->save();

            $sold += $this->amount_coin;
            Yii::$app->keyStorage->set('coin.sold', $sold);

            $buy = new Buy();
            $buy->user_id = $user->id;
            $buy->amount_coin = $this->amount_coin;
            $buy->amount = $this->amount;
            $buy->type = $this->type;
            $buy->token = $this->token;
            //var_dump($buy->getErrors());die;
            if ($buy->save()) {
                $tokenAccess = UserToken::create(
                    $user->id,
                    UserToken::TYPE_ACTIVATION,
                    Time::SECONDS_IN_A_DAY
                );

                Yii::$app->commandBus->handle(new AddToPlusTeamCommand([
                    'related_id' => $user->id,
                    'type' => $this->type,
                    'amount' => $buy->amount,
                ]));

                Yii::$app->commandBus->handle(new SendEmailCommand([
                    'subject' => Yii::t('backend', 'Buy TickCoin'),
                    'view' => 'buy',
                    'to' => $user->email,
                    'params' => [
                        'amount' => $buy->amount_coin,
                        'logo' => Url::to('@backendUrl/img/coin_logo.png'),
                        'url' => Url::to(['/wallet/me', 'token' => $tokenAccess->token], true)
                    ]
                ]));

                return $buy;
            } else {
                var_dump($buy->getErrors());
                die;
                throw new Exception("Couldn't be  bought");
            };
        }
        return null;
    }

}
