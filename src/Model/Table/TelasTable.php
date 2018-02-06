<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TelasTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('telas');
        $this->displayField('tela_codigo');
        $this->primaryKey('tela_codigo');
    }

}
