<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * QuartoTipo Entity
 *
 * @property int $empresa_codigo
 * @property int $quarto_tipo_codigo
 * @property string $quarto_tipo_nome
 * @property int $adulto_quantidade
 * @property int $crianca_quantidade
 * @property int $acesso_sequencia_codigo
 * @property string $quarto_tipo_curto_nome
 * @property int $excluido
 * @property string $modificacao_usuario
 * @property \Cake\I18n\FrozenTime $criacao_data
 * @property string $criacao_usuario
 * @property \Cake\I18n\FrozenTime $modificacao_data
 */
class QuartoTipo extends Entity
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
        'quarto_tipo_codigo' => false,
        'empresa_codigo' => false
    ];
}
