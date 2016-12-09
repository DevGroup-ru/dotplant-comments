<?php

namespace DotPlant\Comments;

class Module extends \yii\base\Module
{
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
