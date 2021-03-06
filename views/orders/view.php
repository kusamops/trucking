<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = 'Счет-фактура заказа №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'route_id',
                'label' => 'Маршрут',
                'value' => function ($model) {
                    $Route = \app\models\Routes::find()
                            ->where(['id' => $model->route_id])->one();
                    return $Route['route_name'];
                },
            ],
            'start_date',
            'end_date',
            [
                'label' => 'Дистанция (км.)',
                'value' => function ($model) {
                    $Route = \app\models\Routes::find()
                            ->where(['id' => $model->route_id])->one();
                    return $Route['distance'];
                },
            ],
            [
                'label' => 'Время в дороге (ч:м:с)',
                'value' => function ($model) {
                    $Route = \app\models\Routes::find()
                            ->where(['id' => $model->route_id])->one();
                    return $Route['time_estimate'];
                },
            ],
            [
                'label' => 'Цена заказа (грн.)',
                'value' => function ($model) {
                    $driversOrders = \app\models\DriversOrders::find()
                            ->where(['order_id' => $model->id])->all();
                    
                    $prices = [];
                    foreach ($driversOrders as $driver) {
                        array_push($prices, \app\models\Salaries::find()
                            ->where(['drivers_orders_id' => $driver['id']])->one()['salary']);
                    }
                    return array_sum($prices);
                },
            ],
        ],
    ]) ?>

</div>
