<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProdutoEmpresa Entity
 *
 * @property int $empresa_codigo
 * @property string $produto_codigo
 * @property string $descricao
 * @property string $uso_condicao
 * @property string $adicional
 * @property float $preco
 * @property string $servico_taxa_incide
 * @property int $max_qtd
 * @property string $excluido
 * @property \Cake\I18n\FrozenTime $criacao_data
 * @property int $criacao_usuario
 * @property \Cake\I18n\FrozenTime $modificacao_data
 * @property int $modificacao_usuario
 */
class ProdutoEmpresa extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'empresa_codigo' => false,
        'produto_codigo' => false
    ];
}
