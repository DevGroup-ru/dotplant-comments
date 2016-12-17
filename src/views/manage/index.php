<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('dotplant.comments', 'Comments');
$this->params['breadcrumbs'] = [$this->title];
$statuses = \DotPlant\Comments\models\Comment::getStatusesListData();
$applicablePropertyModels = \DevGroup\DataStructure\models\ApplicablePropertyModels::find()
    ->select(['name', 'id'])
    ->indexBy('id')
    ->column();

?>
<div class="box">
    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?=
        GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $model,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'applicable_property_model_id',
                        'filter' => $applicablePropertyModels,
                        'value' => function ($model, $key, $index, $column) use ($applicablePropertyModels) {
                            return $applicablePropertyModels[$model->{$column->attribute}];
                        }
                    ],
                    'model_id',
                    [
                        'attribute' => 'status',
                        'filter' => $statuses,
                        'value' => function ($model, $key, $index, $column) use ($statuses) {
                            return $statuses[$model->{$column->attribute}];
                        },
                    ],
                    'created_at:datetime',
                    'name',
                    'email:email',
                    [
                        'class' => \DevGroup\AdminUtils\columns\ActionColumn::class,
                        'buttons' => [
                            'edit' => [
                                'url' => 'edit',
                                'icon' => 'pencil',
                                'class' => 'btn-primary',
                                'label' => \DevGroup\AdminUtils\AdminModule::t('app', 'Edit'),
                            ]
                        ],
                    ],
                ],
            ]
        );
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>
