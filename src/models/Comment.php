<?php

namespace DotPlant\Comments\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%dotplant_comments_comment}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $applicable_property_model_id
 * @property integer $model_id
 * @property integer $user_id
 * @property integer $status
 * @property integer $created_at
 * @property string $name
 * @property string $email
 * @property string $text
 */
class Comment extends ActiveRecord
{
    const STATUS_SPAM = -2;
    const STATUS_DECLINED = -1;
    const STATUS_NEW = 0;
    const STATUS_APPROVED = 1;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['new'] = $scenarios['default'];
        $excludedAttributes = ['status', 'user_id', 'createa_at'];
        foreach ($scenarios['new'] as $key => $value) {
            if (in_array($value, $excludedAttributes)) {
                unset($scenarios['new'][$key]);
            }
        }
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * Get all statuses as list data
     * @return array
     */
    public static function getStatusesListData()
    {
        return [
            self::STATUS_NEW => Yii::t('dotplant.comments', 'New'),
            self::STATUS_APPROVED => Yii::t('dotplant.comments', 'Approved'),
            self::STATUS_DECLINED => Yii::t('dotplant.comments', 'Declined'),
            self::STATUS_SPAM => Yii::t('dotplant.comments', 'SPAM'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dotplant_comments_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'applicable_property_model_id', 'model_id', 'user_id', 'status', 'created_at'], 'integer'],
            [['applicable_property_model_id', 'model_id', 'email', 'text'], 'required'],
            [['email'], 'email'],
            [['email', 'name'], 'string', 'max' => 100],
            [['status'], 'in', 'range' => array_keys(static::getStatusesListData())],
            [['text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('dotplant.comments', 'ID'),
            'parent_id' => Yii::t('dotplant.comments', 'Parent'),
            'applicable_property_model_id' => Yii::t('dotplant.comments', 'Applicable property model'),
            'model_id' => Yii::t('dotplant.comments', 'Model'),
            'user_id' => Yii::t('dotplant.comments', 'User'),
            'status' => Yii::t('dotplant.comments', 'Status'),
            'created_at' => Yii::t('dotplant.comments', 'Created at'),
            'name' => Yii::t('dotplant.comments', 'Your name'),
            'email' => Yii::t('dotplant.comments', 'E-mail'),
            'text' => Yii::t('dotplant.comments', 'Text'),
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);
        $query = static::find();
        $partialAttributes = ['name', 'email'];
        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $partialAttributes)) {
                $query->andFilterWhere(['like', $key, $value]);
            } else {
                $query->andFilterWhere([$key => $value]);
            }
        }
        return new ActiveDataProvider(
            [
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
            ]
        );
    }
}
