<?php

use app\helpers\PermissionsHelper;
use DevGroup\DataStructure\models\ApplicablePropertyModels;
use DevGroup\Users\UsersModule;
use DotPlant\Comments\models\Comment;
use yii\db\Migration;

class m161212_065749_dotplant_comments_create_comments_table extends Migration
{
    protected $permissions = [
        'CommentsManager' => [
            'descr' => 'You cam manage comments',
            'permits' => [
                'dotplant-comments-comment-answer' => 'You can answer to the comment',
                'dotplant-comments-comment-edit' => 'You can edit the comment',
                'dotplant-comments-comment-delete' => 'You can delete the comment',
            ]
        ],
    ];

    public function up()
    {
        $tableOptions = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
            : null;
        $this->createTable(
            Comment::tableName(),
            [
                'id' => $this->primaryKey(),
                'parent_id' => $this->integer()->null(),
                'applicable_property_model_id' => $this->integer()->notNull(),
                'model_id' => $this->integer()->notNull(),
                'user_id' => $this->integer()->null(),
                'status' => $this->smallInteger()->defaultValue(Comment::STATUS_NEW),
                'created_at' => $this->integer()->null(),
                'name' => $this->string(100)->null(),
                'email' => $this->string(100)->notNull(),
                'text' => $this->text()->notNull(),
            ],
            $tableOptions
        );
        $this->addForeignKey(
            'fk-dotplant_comments_comment-parent_id-id',
            Comment::tableName(),
            'parent_id',
            Comment::tableName(),
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-dotplant_comments_comment-apm_id-apm-id',
            Comment::tableName(),
            'applicable_property_model_id',
            ApplicablePropertyModels::tableName(),
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-dotplant_comments_comment-user_id-user-id',
            Comment::tableName(),
            'user_id',
            call_user_func([UsersModule::module()->modelMap['User']['class'], 'tableName']),
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->createIndex(
            'ix-dotplant_comments_comment-apm_id-model_id-parent_id-status',
            Comment::tableName(),
            ['applicable_property_model_id', 'model_id', 'parent_id', 'status', 'created_at']
        );
        PermissionsHelper::createPermissions($this->permissions);
    }

    public function down()
    {
        PermissionsHelper::removePermissions($this->permissions);
        $this->dropTable(Comment::tableName());
    }
}
