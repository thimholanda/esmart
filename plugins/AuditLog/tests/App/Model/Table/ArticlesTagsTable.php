<?php
namespace AuditLog\Test\App\Model\Table;

use AuditLog\Model\Entity\ArticlesTag;
use Cake\ORM\Table;

/**
 * ArticlesTags Model
 */
class ArticlesTagsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('articles_tags');
        $this->displayField('article_id');
        $this->primaryKey(['article_id', 'tag_id']);
        $this->belongsTo('Articles', [
            'foreignKey' => 'article_id',
            'joinType' => 'INNER',
            'className' => 'AuditLog.Articles'
        ]);
        $this->belongsTo('Tags', [
            'foreignKey' => 'tag_id',
            'joinType' => 'INNER',
            'className' => 'AuditLog.Tags'
        ]);
    }
}
