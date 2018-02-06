<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ElementosTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->addAssociations([
            'hasOne' => [
                'TelaElementos' => ['foreignKey' => 'elemento_codigo'],
                'ElementoIdiomas' => ['foreignKey' => 'elemento_codigo']
            ]
        ]);

        $this->table('elementos');
        $this->displayField('elemento_codigo');
        $this->primaryKey('elemento_codigo');
    }

}
