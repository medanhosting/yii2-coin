<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\WalletSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="wallet-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'user_id') ?>

    <?php echo $form->field($model, 'wallet_btc') ?>

    <?php echo $form->field($model, 'wallet_eth') ?>

    <?php echo $form->field($model, 'wallet_coin') ?>

    <?php // echo $form->field($model, 'amount_btc') ?>

    <?php // echo $form->field($model, 'bonus_btc') ?>

    <?php // echo $form->field($model, 'amount_eth') ?>

    <?php // echo $form->field($model, 'bonus_eth') ?>

    <?php // echo $form->field($model, 'amount_coin') ?>

    <?php // echo $form->field($model, 'bonus_coin') ?>

    <?php // echo $form->field($model, 'amount_bonus') ?>

    <?php // echo $form->field($model, 'amount_ico') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
