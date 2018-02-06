<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ComunicacaoItemTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('comunicacao_item');
        $this->primaryKey(['comunicacao_numero']);
    }

}
