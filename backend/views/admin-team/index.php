<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Teams';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('Create Team', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'user_id',
                'label' => 'User',
                'value' => function ($model) {
                    return $model->user_id && $model->userRefer ? $model->userRefer->username : null;
                },
            ],
            [
                'attribute' => 'related_id',
                'label' => 'Refer',
                'value' => function ($model) {
                    return $model->related_id && $model->refer ? $model->refer->username : null;
                },
            ],
            //'amount_btc',
            'amount_btc_bonus',
            // 'amount_eth',
            'amount_eth_bonus',
            // 'amount_total_bonus',
            'level',
            // 'type',
            // 'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
