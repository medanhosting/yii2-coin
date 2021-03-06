<?php
/**
 * @var $this yii\web\View
 */
use backend\assets\BackendAsset;
use backend\models\SystemLog;
use backend\widgets\Menu;
use common\models\TimelineEvent;
use common\helpers\CoinbaseHelper;
use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\log\Logger;
use yii\widgets\Breadcrumbs;

$bundle = BackendAsset::register($this);
$rateUsd = Yii::$app->keyStorage->get('coin.rate-usd', '0.2'); // TODO add to migrate
//$coinbaseHelper = new CoinbaseHelper();
//var_dump($coinbaseHelper->createAddress());die;
$script = <<< JS
    function showPrice() {
        //console.log('show');
        $.ajax({
             async: true,
             url: '/site/rate',
             contentType: 'application/json',
             data: {},
             success: function(result) {
                //console.log(result);
                if (result && result.data){
                    var data = result.data
                    var length = data.length;
                     for(var i = 0;i<length;i++){
                        if(data[i].base == 'BTC'){
                            $("#base-btc-usd").html(data[i].amount);
                        }
                        if(data[i].base == 'ETH'){
                            $("#base-eth-usd").html(data[i].amount);
                        }
                     }
                }
                return true;
             }
          });
    }
    setInterval (showPrice, 60000 );

JS;

$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);
?>
<?php $this->beginContent('@backend/views/layouts/base.php'); ?>
<div class="wrapper">
    <!-- header logo: style can be found in header.less -->
    <header class="main-header">
        <a href="<?php echo Yii::$app->urlManagerFrontend->createAbsoluteUrl('/') ?>" class="logo">
            <!-- Add the class icon to your logo image or logo icon to add the margining -->
            <?php echo Yii::$app->name ?>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only"><?php echo Yii::t('backend', 'Toggle navigation') ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li id="coin-btc" class="coin-menu">
                        <a href="#">
                        <i class="fa fa-bitcoin"></i>
                        <?php

                                                
                        //var_dump($amount);die;
                        ?>
                        <span>
                            1 BTC = <span id="base-btc-usd"><?php echo Yii::$app->keyStorage->get('coin.rate-btc-usd') ?></span> USD
                        </span>
                         </a>
                    </li>
                    
                     <li id="coin-eth" class="coin-menu">
                        <a href="#">
                        <i class="fa"></i>
                        <span>
                            1 ETH = <span id="base-eth-usd"><?php echo Yii::$app->keyStorage->get('coin.rate-eth-usd') ?></span> USD
                        </span>
                        </a>
                    </li>  
                    <li id="coin-btc" class="coin-menu">
                        <a href="#">
                        <i class="fa"></i>
                        <span>
                            1 <?php echo Yii::$app->keyStorage->get('coin.code', 'TKC') ?> = <?php echo Yii::$app->keyStorage->get('coin.rate-usd', '0.2'); ?> USD
                        </span>
                        </a>
                    </li>

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo Yii::$app->user->identity->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'img/coin_logo.png')) ?>"
                                 class="user-image">
                            <span><?php echo Yii::$app->user->identity->username ?> <i class="caret"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header light-blue">
                                <img src="<?php echo Yii::$app->user->identity->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'img/coin_logo.png')) ?>"
                                     class="img-circle" alt="User Image"/>
                                <p>
                                    <?php echo Yii::$app->user->identity->username ?>
                                    <small>
                                        <?php echo Yii::t('backend', 'Member since {0, date, short}', Yii::$app->user->identity->created_at) ?>
                                    </small>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <?php echo Html::a(Yii::t('backend', 'Profile'), ['/sign-in/profile'], ['class' => 'btn btn-default btn-flat']) ?>
                                </div>
                                <div class="pull-left">
                                    <?php echo Html::a(Yii::t('backend', 'Account'), ['/sign-in/account'], ['class' => 'btn btn-default btn-flat']) ?>
                                </div>
                                <div class="pull-right">
                                    <?php echo Html::a(Yii::t('backend', 'Logout'), ['/logout'], ['class' => 'btn btn-default btn-flat', 'data-method' => 'post']) ?>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <?php echo $this->render('_sidebar.php') ?>
    

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?php echo $this->title ?>
                <?php if (isset($this->params['subtitle'])): ?>
                    <small><?php echo $this->params['subtitle'] ?></small>
                <?php endif; ?>
            </h1>

            <?php echo Breadcrumbs::widget([
                'tag' => 'ol',
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php if (Yii::$app->session->hasFlash('alert')): ?>
                <?php echo Alert::widget([
                    'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                    'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
                ]) ?>
            <?php endif; ?>
            <?php echo $content ?>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<?php $this->endContent(); ?>
