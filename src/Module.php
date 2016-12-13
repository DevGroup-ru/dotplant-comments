<?php

namespace DotPlant\Comments;

class Module extends \yii\base\Module
{
    public $commentsPerPage = 10;
    public $allowAnswer = true;
    public $allowForGuest = false;
    public $email;
    public $emailTemplateId;

    /**
     * @return self Module instance in application
     */
    public static function module()
    {
        $module = \Yii::$app->getModule('comments');
        if ($module === null) {
            $module = \Yii::createObject(self::class, ['comments']);
        }
        return $module;
    }
}
