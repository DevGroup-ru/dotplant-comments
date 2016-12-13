<?php

namespace DotPlant\Comments\controllers;

use DotPlant\Comments\models\Comment;
use DotPlant\Comments\Module;
use DotPlant\Emails\helpers\EmailHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class CommentController extends Controller
{
    public function actionIndex()
    {
        $model = new Comment;
        $form = ActiveForm::begin(['action' => ['add']]);
        echo $form->field($model, 'applicable_property_model_id');
        echo $form->field($model, 'model_id');
        echo $form->field($model, 'parent_id');
        echo $form->field($model, 'email');
        echo $form->field($model, 'text')->textarea();
        echo Html::submitButton();
        ActiveForm::end();
    }

    public function actionAdd()
    {
        if (\Yii::$app->user->isGuest && !Module::module()->allowForGuest) {
            throw new ForbiddenHttpException;
        }
        $model = new Comment(['scenario' => 'new']);
        if ($model->load(\Yii::$app->request->post())) {
            if (!\Yii::$app->user->isGuest) {
                $model->user_id = \Yii::$app->user->id;
                $model->email = \Yii::$app->user->identity->email;
            }
            if (!(Module::module()->allowAnswer || \Yii::$app->user->can('dotplant-comments-comment-answer'))) {
                $model->parent_id = null;
            }
            $model->status = Comment::STATUS_NEW;
            if ($model->save()) {
                // send message
                if (!empty(Module::module()->email) && !empty(Module::module()->emailTemplateId)) {
                    EmailHelper::sendNewMessage(
                        Module::module()->email,
                        Module::module()->emailTemplateId,
                        [
                            'model' => $model->attributes,
                        ]
                    );
                }
                return $model->id;
            } else {
                var_dump($model->errors);
            }
        } else {
            throw new BadRequestHttpException;
        }
    }
}
