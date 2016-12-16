<?php

namespace DotPlant\Comments\providers;

use DotPlant\Comments\models\Comment;
use DotPlant\Comments\Module;
use DotPlant\EntityStructure\helpers\PaginationHelper;
use DotPlant\Monster\DataEntity\DataEntityProvider;
use yii\data\Pagination;

/**
 * Class CommentsListProvider
 * @package DotPlant\Comments\providers
 */
class CommentsListProvider extends DataEntityProvider
{
    /**
     * @var string
     */
    public $regionKey = 'comments';

    /**
     * @var string
     */
    public $materialKey = 'list';

    /**
     * @var string
     */
    public $commentsBlockKey = 'data';

    /**
     * @var string
     */
    public $paginationBlockKey = 'pages';

    /**
     * @var int
     */
    public $applicablePropertyModelId;

    /**
     * @var int
     */
    public $modelId;

    /**
     * @inheritdoc
     */
    public function pack()
    {
        return [
            'class' => static::class,
            'entities' => $this->entities,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getEntities(&$actionData)
    {
        if ($this->applicablePropertyModelId !== null && $this->modelId !== null) {
            // @todo: Add caching
            $query = Comment::find()
                ->where(
                    [
                        'applicable_property_model_id' => $this->applicablePropertyModelId,
                        'model_id' => $this->modelId,
                        'parent_id' => null,
                        'status' => Comment::STATUS_APPROVED,
                    ]
                );
            $countQuery = clone $query;
            $pages = new Pagination(
                [
                    'defaultPageSize' => Module::module()->commentsPerPage,
                    'pageParam' => 'comments-page',
                    'totalCount' => $countQuery->count(),
                ]
            );
            $data = $query
                ->orderBy(['created_at' => SORT_DESC])
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->asArray(true)
                ->all();
            foreach ($data as $index => $row) {
                $data[$index]['children'] = $this->getChildren($row['id']);
            }
            $this->entities = [
                $this->regionKey => [
                    $this->materialKey => [
                        $this->commentsBlockKey => $data,
                        $this->paginationBlockKey => [
                            'commentsCount' => $pages->totalCount,
                            'pagesCount' => $pages->pageCount,
                            'items' => PaginationHelper::getItems($pages),
                        ],
                    ],
                ],
            ];
        }
        return $this->entities;
    }

    /**
     * Get an active children comments
     * @param int $parentId
     * @return array
     */
    protected function getChildren($parentId)
    {
        $data = Comment::find()
            ->where(
                [
                    'applicable_property_model_id' => $this->applicablePropertyModelId,
                    'model_id' => $this->modelId,
                    'parent_id' => $parentId,
                    'status' => Comment::STATUS_APPROVED,
                ]
            )
            ->orderBy(['created_at' => SORT_DESC])
            ->asArray(true)
            ->all();
        foreach ($data as $index => $row) {
            $data[$index]['children'] = $this->getChildren($row['id']);
        }
        return $data;
    }
}
