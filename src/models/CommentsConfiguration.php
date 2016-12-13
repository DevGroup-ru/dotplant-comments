<?php

namespace DotPlant\Comments\models;

use DotPlant\Comments\Module;
use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use Yii;

class CommentsConfiguration extends BaseConfigurationModel
{
    public $commentsPerPage = 10;
    public $allowAnswer = true;
    public $allowForGuest = false;

    /**
     * @inheritdoc
     */
    public function getModuleClassName()
    {
        return Module::class;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['commentsPerPage'], 'integer', 'min' => -1],
            [['allowAnswer', 'allowForGuest'], 'boolean'],
            [['allowAnswer', 'allowForGuest'], 'filter', 'filter' => 'boolval'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'commentsPerPage' => Yii::t('dotplant.comments', 'Comments per page'),
            'allowAnswer' => Yii::t('dotplant.comments', 'Allow to leave an answer'),
            'allowForGuest' => Yii::t('dotplant.comments', 'Guest can leave a message'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function webApplicationAttributes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function consoleApplicationAttributes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function commonApplicationAttributes()
    {
        return [
            'components' => [
                'i18n' => [
                    'translations' => [
                        'dotplant.comments' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                            'basePath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'messages',
                        ]
                    ]
                ],
            ],
            'modules' => [
                'comments' => [
                    'class' => Module::class,
                    'commentsPerPage' => $this->commentsPerPage,
                    'allowAnswer' => $this->allowAnswer,
                    'allowForGuest' => $this->allowForGuest,
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function appParams()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function aliases()
    {
        return [
            '@DotPlant/Comments' => realpath(dirname(__DIR__)),
        ];
    }
}
