<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AcessoPerfisTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('acesso_perfis');
        $this->displayField('empresa_grupo_codigo');
        $this->primaryKey(['empresa_grupo_codigo', 'acesso_perfil_codigo']);
    }
}
