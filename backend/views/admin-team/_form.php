<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Team */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="team-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'user_id')->textInput() ?>

    <?php echo $form->field($model, 'related_id')->textInput() ?>

    <?php echo $form->field($model, 'amount_btc')->textInput() ?>

    <?php echo $form->field($model, 'amount_btc_bonus')->textInput() ?>

    <?php echo $form->field($model, 'amount_eth')->textInput() ?>

    <?php echo $form->field($model, 'amount_eth_bonus')->textInput() ?>

    <?php echo $form->field($model, 'amount_total_bonus')->textInput() ?>

    <?php echo $form->field($model, 'level')->textInput() ?>

    <?php echo $form->field($model, 'type')->textInput() ?>

    <?php echo $form->field($model, 'status')->textInput() ?>

    <?php echo $form->field($model, 'created_at')->textInput() ?>

    <?php echo $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
