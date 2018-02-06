<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Orm\TableRegistry;

/**
 * ProdutoVendaPontos Model
 *
 * @method \App\Model\Entity\ProdutoVendaPonto get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProdutoVendaPonto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProdutoVendaPonto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProdutoVendaPonto|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProdutoVendaPonto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProdutoVendaPonto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProdutoVendaPonto findOrCreate($search, callable $callback = null, $options = [])
 */
class ProdutoVendaPontosTable extends Table
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

        $this->setTable('produto_venda_pontos');
        $this->setDisplayField('empresa_codigo');
        $this->setPrimaryKey(['empresa_codigo', 'venda_ponto_codigo', 'produto_codigo']);
    }

    public function findProdutos(Query $query, array $options) {
        return $query
            ->where(['produto_codigo' => $options['produto']])
            ->where(['empresa_codigo' => $options['empresa']]);
    }

    // Cria ou modifica um cadastro de produto
    public function procadmod($produto_codigo, $modelo) {
        $modelo->produto_codigo = $produto_codigo;
        $entidade = TableRegistry::get('ProdutoVendaPontos');
        if ($entidade->save($modelo)) {
            return $modelo->produto_codigo;
        }

        return false;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('empresa_codigo')
            ->allowEmpty('empresa_codigo', 'create');

        $validator
            ->allowEmpty('venda_ponto_codigo', 'create');

        $validator
            ->allowEmpty('produto_codigo', 'create');

        $validator
            ->decimal('preco')
            ->allowEmpty('preco');

        $validator
            ->allowEmpty('servico_taxa_incide');

        $validator
            ->allowEmpty('excluido');

        $validator
            ->integer('criacao_usuario')
            ->requirePresence('criacao_usuario', 'create')
            ->notEmpty('criacao_usuario');

        $validator
            ->integer('modificacao_usuario')
            ->allowEmpty('modificacao_usuario');

        $validator
            ->dateTime('modificacao_data')
            ->allowEmpty('modificacao_data');

        $validator
            ->dateTime('criacao_data')
            ->requirePresence('criacao_data', 'create')
            ->notEmpty('criacao_data');

        return $validator;
    }
}
