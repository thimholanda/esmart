<?php

namespace App\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use App\Model\Entity\Estadia;

/*
 * Para execução do teste no cmd
 * Acessar raiz do sistema e digitar vendor\bin\phpunit
 * 
 */

class EstQuaAloDisTest extends TestCase {

    protected $estadia;

    public function setUp() {
        parent::setUp();
        $this->estadia = new Estadia();
    }

    public function testeAlocacaoDeQuartos() {

        //linha 2
        $empresa_codigo = 1;
        $documento_numero = 567;
        $quarto_item = 1;
        $estadia_data = array('2031-01-01');
        $quarto_codigo = array(0 => '01');
        $quarto_tipo_codigo = array(0 => 1);
        $quarto_tipo_comprado = 1;
        $quarto_tipo_valida = 1;
        $usuario_codigo = 1;

        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 3
        $quarto_item = 2;
        $quarto_codigo = array(0 => '03');
        $quarto_tipo_codigo = array(0 => 2);
        $quarto_tipo_valida = 0;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 4
        $quarto_item = 3;
        $quarto_tipo_codigo = array(0 => 2);
        $quarto_tipo_valida = 1;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = -1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 5
        $documento_numero = 568;
        $quarto_item = 1;
        $estadia_data = array('2031-01-01', '2031-01-02');
        $quarto_codigo = array(0 => '08', 1 => '08');
        $quarto_tipo_codigo = array(0 => 1, 1 => 1);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 6
        $quarto_item = 2;
        $quarto_codigo = array(0 => '04', 1 => '13');
        $quarto_tipo_codigo = array(0 => 2, 1 => 2);
        $quarto_tipo_comprado = 2;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 7
        $quarto_item = 3;
        $quarto_codigo = array(0 => '14', 1 => null);
        $quarto_tipo_codigo = array(0 => 2, 1 => null);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 8
        $documento_numero = 569;
        $quarto_item = 1;
        $estadia_data = array('2031-01-01', '2031-01-02', '2031-01-03');
        $quarto_codigo = array(0 => '10', 1 => '20', 2 => '30');
        $quarto_tipo_codigo = array(0 => 1, 1 => 1, 2 => 1);
        $quarto_tipo_comprado = 1;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 9
        $quarto_item = 2;
        $quarto_codigo = array(0 => '11', 1 => '15', 2 => '25');
        $quarto_tipo_codigo = array(0 => 1, 1 => 2, 2 => 3);
        $quarto_tipo_comprado = 3;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);

        //Força a resposta sim do usuario
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, 0, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 10
        $quarto_item = 3;
        $quarto_codigo = array(0 => '12', 1 => null, 2 => '43');
        $quarto_tipo_codigo = array(0 => 1, 1 => null, 2 => 3);
        $quarto_tipo_comprado = 3;
        //Força a resposta sim do usuario passando o quarto_valida como 0
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, 0, $usuario_codigo);
        $expected = 1;

        $this->assertEquals($expected, $result['retorno']);

        $estadia_data = array('2031-01-01', '2031-01-02', '2031-01-03');
        //linha 11
        $documento_numero = 570;
        $quarto_item = 1;
        $quarto_codigo = array(0 => '21', 1 => '21', 2 => '21');
        $quarto_tipo_codigo = array(0 => 1, 1 => 1, 2 => 1);
        $quarto_tipo_comprado = 1;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 12
        $quarto_item = 2;
        $quarto_codigo = array(0 => '22', 1 => '22', 2 => '22');
        $quarto_tipo_codigo = array(0 => 2, 1 => 2, 2 => 2);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        //Força a resposta sim do usuario
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, 0, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 13
        $quarto_item = 3;
        $quarto_codigo = array(0 => '28', 1 => '28', 2 => '28');
        $quarto_tipo_codigo = array(0 => 1, 1 => 1, 2 => 1);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 14
        $documento_numero = 571;
        $estadia_data = array('2031-01-01', '2031-01-02', '2031-01-03', '2031-01-04');
        $quarto_item = 1;
        $quarto_codigo = array(0 => '29', 1 => '29', 2 => '29', 3 => '29');
        $quarto_tipo_codigo = array(0 => null, 1 => null, 2 => null, 3 => null);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 15
        $quarto_item = 2;
        $quarto_codigo = array(0 => '37', 1 => '37', 2 => '37', 3 => '37');
        $quarto_tipo_codigo = array(0 => 1, 1 => 1, 2 => 1, 3 => 1);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 16
        $documento_numero = 572;
        $estadia_data = array('2031-01-01', '2031-01-02', '2031-01-03', '2031-01-04');
        $quarto_item = 1;
        $quarto_codigo = array(0 => '25', 1 => '29', 2 => '25', 3 => '01');
        $quarto_tipo_codigo = array(0 => 3, 1 => 1, 2 => 3, 3 => 1);
        $quarto_tipo_comprado = 3;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = -1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 17
        $quarto_item = 2;
        $quarto_codigo = array(0 => '21', 1 => '21', 2 => '21', 3 => '21');
        $quarto_tipo_codigo = array(0 => 1, 1 => 1, 2 => 1, 1 => 1);
        $quarto_tipo_comprado = 1;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = -1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 18
        $quarto_item = 3;
        $quarto_codigo = array(0 => null, 1 => '17', 2 => '22', 3 => '17');
        $quarto_tipo_codigo = array(0 => null, 1 => 4, 2 => 2, 3 => 4);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = -1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 19
        $documento_numero = 573;
        $estadia_data = array('2031-01-01', '2031-01-02', '2031-01-03');
        $quarto_item = 1;
        $quarto_codigo = array(0 => '22', 1 => '22', 2 => '22');
        $quarto_tipo_codigo = array(0 => 2, 1 => 2, 2 => 2);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = -1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 20
        $quarto_item = 2;
        $quarto_codigo = array(0 => null, 1 => '22', 2 => null);
        $quarto_tipo_codigo = array(0 => null, 1 => 2, 2 => null);
        $quarto_tipo_comprado = 2;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = -1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 21
        $quarto_item = 3;
        $quarto_codigo = array(0 => '36', 1 => '36', 2 => '36');
        $quarto_tipo_codigo = array(0 => 5, 1 => 5, 2 => 5);
        $usuario_codigo = 2;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = -1;
        $this->assertEquals($expected, $result['retorno']);


        //linha 22
        $documento_numero = 570;
        $quarto_item = 1;
        $quarto_codigo = array(0 => '38', 1 => '01', 2 => '08');
        $quarto_tipo_codigo = array(0 => 1, 1 => 1, 2 => 1);
        $quarto_tipo_comprado = 1;
        $usuario_codigo = 1;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 23
        $quarto_item = 2;
        $quarto_codigo = array(0 => '39', 1 => '03', 2 => '34');
        $quarto_tipo_codigo = array(0 => 1, 1 => 2, 2 => 3);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);

        //Força a resposta sim do usuario
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, 0, $usuario_codigo);
        $expected = 1;

        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 24
        $quarto_item = 3;
        $quarto_codigo = array(0 => '02', 1 => null, 2 => '16');
        $quarto_tipo_codigo = array(0 => 1, 1 => null, 2 => 3);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        //Força a resposta sim do usuario
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, 0, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 25
        $documento_numero = 571;
        $estadia_data = array('2031-01-01', '2031-01-02', '2031-01-03', '2031-01-04');
        $quarto_item = 1;
        $quarto_codigo = array(0 => null, 1 => null, 2 => null, 3 => null);
        $quarto_tipo_codigo = array(0 => null, 1 => null, 2 => null, 3 => null);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 26
        $quarto_item = 2;
        $quarto_codigo = array(0 => null, 1 => '24', 2 => null, 3 => null);
        $quarto_tipo_codigo = array(0 => null, 1 => 2, 2 => null, 3 => null);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        //Força a resposta sim do usuario
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, 0, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 27
        $quarto_item = 3;
        $quarto_codigo = array(0 => null, 1 => null, 2 => null, 3 => null);
        $quarto_tipo_codigo = array(0 => null, 1 => null, 2 => null, 3 => null);
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;

        //linha 28
        $documento_numero = 574;
        $quarto_item = 1;
        $estadia_data = array('2031-01-01', '2031-01-02', '2031-01-03', '2031-01-04', '2031-01-05');
        $quarto_codigo = array(0 => null, 1 => null, 2 => '13', 3 => null, 4 => null);
        $quarto_tipo_codigo = array(0 => null, 1 => null, 2 => 2, 3 => null, 4 => null);
        $quarto_tipo_comprado = 2;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 1;
        $this->assertEquals($expected, $result['retorno']);

        //linha 29
        $quarto_item = 2;
        $quarto_codigo = array(0 => '33', 1 => '40', 2 => '41', 3 => '42', 4 => null);
        $quarto_tipo_codigo = array(0 => 2, 1 => 2, 2 => 2, 3 => 2, 4 => null);
        $usuario_codigo = 99;

        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 0;
        $this->assertEquals($expected, $result['retorno']);

        //linha 30
        $quarto_item = 3;
        $quarto_codigo = array(0 => '45', 1 => '45', 2 => '45', 3 => '45', 4 => '45');
        $quarto_tipo_codigo = array(0 => 5, 1 => 5, 2 => 5, 3 => 5, 4 => null);
        $quarto_tipo_valida = 0;
        $result = $this->estadia->estquaalo($empresa_codigo, $documento_numero, $quarto_item, $estadia_data, $quarto_codigo, $quarto_tipo_codigo, $quarto_tipo_comprado, $quarto_tipo_valida, $usuario_codigo);
        $expected = 0;
        $this->assertEquals($expected, $result['retorno']);
    }

}
