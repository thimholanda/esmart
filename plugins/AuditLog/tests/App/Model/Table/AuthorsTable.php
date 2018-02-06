<?php

namespace AuditLog\Test\App\Model\Table;

class AuthorsTable extends \Cake\ORM\Table
{
    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('AuditLog.Auditable');

        $this->hasMany('Articles', [
            'foreignKey' => 'author_id'
        ]);
    }
}
