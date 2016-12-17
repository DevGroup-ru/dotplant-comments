<?php

namespace DotPlant\Comments\models;

use DotPlant\Comments\Module;
use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use DotPlant\Emails\models\Template;
use Yii;

class CommentsConfiguration extends BaseConfigurationModel
{
    public $commentsPerPage = 10;
    public $allowAnswer = true;
    public $allowForGuest = false;
    public $email;
    public $emailTemplateId;

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
            [['email'], 'string'],
            [['emailTemplateId'], 'exist', 'targetClass' => Template::class, 'targetAttribute' => 'id'],
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
            'email' => Yii::t('dotplant.comments', 'E-mails list'),
            'emailTemplateId' => Yii::t('dotplant.comments', 'E-mail template'),
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
                    'layout' => \Yii::$app->params['admin.layout'],
                    'commentsPerPage' => $this->commentsPerPage,
                    'allowAnswer' => $this->allowAnswer,
                    'allowForGuest' => $this->allowForGuest,
                    'email' => $this->email,
                    'emailTemplateId' => $this->emailTemplateId,
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
