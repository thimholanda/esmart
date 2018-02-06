<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * ProdutoEmpresas Model
 *
 * @method \App\Model\Entity\ProdutoEmpresa get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProdutoEmpresa newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProdutoEmpresa[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProdutoEmpresa|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProdutoEmpresa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProdutoEmpresa[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProdutoEmpresa findOrCreate($search, callable $callback = null, $options = [])
 */
class ProdutoEmpresasTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('produto_empresas');
        $this->setDisplayField('empresa_codigo');
        $this->setPrimaryKey(['empresa_codigo', 'produto_codigo']);
    }

    public function findProdutoEmpresa(Query $query, array $options) {
        return $query
            ->where(['produto_codigo' => $options['produto']])
            ->where(['empresa_codigo' => $options['empresa']]);
    }

    // Cria ou modifica um cadastro de produto
    public function procadmod($produto_codigo, $modelo) {
        $modelo->produto_codigo = $produto_codigo;
        $entidade = TableRegistry::get('ProdutoEmpresas');
        if ($entidade->save($modelo)) {
            return $modelo->produto_codigo;
        }

        return false;
    }
}
