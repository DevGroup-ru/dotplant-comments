<?php

use DotPlant\Emails\models\Template;
use yii\db\Migration;

class m161213_113154_dotplant_comments_add_email_template extends Migration
{
    public function up()
    {
        $this->insert(
            Template::tableName(),
            [
                'name' => 'New comment template',
                'subject_view_file' => '@DotPlant/Comments/mail/new-comment-subject.php',
                'body_view_file' => '@DotPlant/Comments/mail/new-comment-body.php',
            ]
        );
    }

    public function down()
    {
        $this->delete(Template::tableName(), ['name' => 'New comment template']);
    }
}
