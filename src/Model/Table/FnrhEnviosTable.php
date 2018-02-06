<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;

class FnrhEnviosTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('fnrh_envios');
        $this->primaryKey(['fnrh_codigo', 'fnrh_servico']);
    }

    public function findByFnrhCodigo($fnrh_codigo) {
        $connection = ConnectionManager::get('default');
        return $connection->execute("SELECT * FROM fnrh_envios fe LEFT JOIN fnrh_mensagens fm ON fm.retorno_mensagem_codigo "
                        . " =  fe.retorno_mensagem WHERE fe.fnrh_codigo = :fnrh_codigo", ['fnrh_codigo' => $fnrh_codigo])->fetchAll('assoc');
    }

}
