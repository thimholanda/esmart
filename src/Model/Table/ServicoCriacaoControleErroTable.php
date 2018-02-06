<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ServicoCriacaoControleErroTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('servico_criacao_controle_erro');
        $this->primaryKey(['empresa_codigo', 'exibicao_data']);
    }
}