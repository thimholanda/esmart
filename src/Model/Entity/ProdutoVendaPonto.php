<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProdutoVendaPonto Entity
 *
 * @property int $empresa_codigo
 * @property string $venda_ponto_codigo
 * @property string $produto_codigo
 * @property float $preco
 * @property string $servico_taxa_incide
 * @property string $excluido
 * @property int $criacao_usuario
 * @property int $modificacao_usuario
 * @property \Cake\I18n\FrozenTime $modificacao_data
 * @property \Cake\I18n\FrozenTime $criacao_data
 */
class ProdutoVendaPonto extends Entity
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
        'venda_ponto_codigo' => false,
        'produto_codigo' => false
    ];
}
