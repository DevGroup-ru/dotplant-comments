<?php
/**
 * @var \yii\web\View $this
 */

function getStatus($id)
{
    $statuses = \DotPlant\Comments\models\Comment::getStatusesListData();
    return isset($statuses[$id]) ? $statuses[$id] : 'Unknown';
}
?>
<table>
    <tr>
        <th>ID</th>
        <td><?= $model['id'] ?></td>
    </tr>
    <tr>
        <th>Applicable property model ID</th>
        <td># <?= $model['applicable_property_model_id'] ?></td>
    </tr>
    <tr>
        <th>Model ID</th>
        <td># <?= $model['model_id'] ?></td>
    </tr>
    <tr>
        <th>User ID</th>
        <td># <?= $model['user_id'] ?></td>
    </tr>
    <tr>
        <th>Status</th>
        <td><?= getStatus($model['status']) ?></td>
    </tr>
    <tr>
        <th>Created at</th>
        <td><?= Yii::$app->formatter->asDatetime($model['created_at']) ?></td>
    </tr>
    <tr>
        <th>E-mail</th>
        <td><?= $model['email'] ?></td>
    </tr>
    <tr>
        <th>Text</th>
        <td><?= $model['text'] ?></td>
    </tr>
</table>
