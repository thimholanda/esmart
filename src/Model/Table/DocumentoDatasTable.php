<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class DocumentoDatasTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('documento_datas');
        $this->displayField('empresa_codigo');
        $this->primaryKey(['empresa_codigo', 'documento_tipo_codigo', 'quarto_item', 'documento_numero', 'data']);
    }

    public function beforeSave($event, $entity, $options) {
        if ($entity->isNew() && !$entity->quarto_item) {
            $entity->quarto_item = 1;
        }
        return true;
    }

}
