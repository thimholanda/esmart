<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AcessoControlesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('acesso_controles');
        $this->displayField('empresa_grupo_codigo');
        $this->primaryKey(['empresa_grupo_codigo', 'acesso_perfil_codigo', 'empresa_codigo', 'objeto_codigo']);
    }
}
