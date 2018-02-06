<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TestesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('testes');
        $this->primaryKey('id');
    }

    public function beforeSave(Event $event, Entity $entity, $options) {
        var_dump($event);
        exit();
        if (!$this->_shouldProcess('create') && !$this->_shouldProcess('update')) {
            return;
        }

        if (!$entity->isNew()) {
            $original = $this->_getModelData($entity);
            $this->_original = $original;
        }
    }

}
