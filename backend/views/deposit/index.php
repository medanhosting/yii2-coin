<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\DepositSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deposits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-index">

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user_id',
                'label' => 'Member',
                'value' => function ($model) {
                    return $model->user_id && $model->user ? $model->user->username : null;
                },
            ],
            'sender',
            'receiver',
            'amount',
            // 'type',
            // 'status',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{update} {view}'
                'template' => '{view}'

            ],
        ],
    ]); ?>

</div>
