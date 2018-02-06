<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EmailAcessosTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->table('email_acessos');
        $this->primaryKey(['email_acesso_chave', 'email_acesso_objeto_codigo']);
    }
}
