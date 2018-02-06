<?php

namespace App\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use App\Model\Entity\Geral;

/*
 * Para execução do teste no cmd
 * Acessar raiz do sistema e digitar vendor\bin\phpunit --filter testeDisponibilidadeDeQuartos C:\xampp\htdocs\esmart\tests\TestCase\Model\Entity\GerQuaDisTest
 * 
 */

class GerQuaDisTest extends TestCase {

    protected $geral;

    public function setUp() {
        parent::setUp();
        $this->geral = new Geral();
    }

    public function testeDisponibilidadeDeQuartos() {

        $empresa_codigo = 1;
        /*
         * data	        evento	entrada	                variante_saida
         * 1/1/2030	0 	tipo de quarto: 1	1 a 2
         */
        $datas = array('2030-01-01');
        $evento = 0;
        $quarto_tipo_codigo = array(0 => 1);
        $quarto_codigo = null;
        $variante = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('disponivel_quarto_tipo' => array(1 => 10));
        $this->assertEquals($expected, $result);

        $variante = 2;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('resultado' => 1);
        $this->assertEquals($expected, $result);

        /*
         * data	        evento	entrada	                variante_saida
         * 1/1/2030	3 	tipo de quarto: 1	3
         */

        $evento = 3;

        $variante = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        /* $expected = array('quarto_codigo' => array(0 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '02'), 1 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '08'), 2 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '10'),
          3 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '11'), 4 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '12'), 5 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '28'),
          6 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '29'), 7 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '30'), 8 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '37'),
          9 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '38'), 10 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '39')));
          $this->assertEquals($expected, $result); */

        /*
         * data	        evento	entrada	                variante_saida
         * 1/1/2030	0 	tipo de quarto: 2	1 a 2
         */
        $datas = array('2030-01-01');
        $evento = 0;
        $quarto_tipo_codigo = array(0 => 2);
        $quarto_codigo = null;
        $variante = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('disponivel_quarto_tipo' => array(2 => 1));
        $this->assertEquals($expected, $result);

        $variante = 2;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('resultado' => 1);
        $this->assertEquals($expected, $result);

        /*
         * data	        evento	entrada	                variante_saida
         * 1/1/2030	3 	tipo de quarto: 2	3
         */
        $evento = 3;

        $variante = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('quarto_codigo' => array(0 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '14'), 1 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '33'),
                2 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '40'),
                3 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '41'), 4 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '42')));
        $this->assertEquals($expected, $result);

        /*
         * data	        evento	entrada	                variante_saida
         * 1/1/2030	0 	tipo de quarto: 1,2	1 a 2
         */
        $datas = array('2030-01-01');
        $evento = 0;
        $quarto_tipo_codigo = array(0 => 1, 1 => 2);
        $quarto_codigo = null;
        $variante = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('disponivel_quarto_tipo' => array(1 => 10, 2 => 1));
        $this->assertEquals($expected, $result);

        $variante = 2;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('resultado' => 1);
        $this->assertEquals($expected, $result);

        /*
         * data	        evento	entrada	                variante_saida
         * 1/1/2030	3 	tipo de quarto: 2	3
         */
        $evento = 3;

        $variante = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        /* $expected = array('quarto_codigo' => array(0 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '02'), 1 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '08'),
          2 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '10'),
          3 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '11'), 4 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '12'), 5 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '28'),
          6 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '29'), 7 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '30'), 8 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '37'),
          9 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '38'), 10 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '39'), 11 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '14')
          , 12 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '33'), 13 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '40'), 14 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '41')
          , 15 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '42')));
          $this->assertEquals($expected, $result); */

        /*
         * data	        evento	entrada	                variante_saida
         * 1/1/2030	0 	tipo de quarto: 1,2,2,2	3
         */
        $datas = array('2030-01-01');
        $evento = 0;
        $quarto_tipo_codigo = array(0 => 1, 1 => 2, 2 => 2, 3 => 2);
        $quarto_codigo = null;
        $variante = 2;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	entrada	        variante_saida
         * 1/1/2030	0 a 6	quarto: 4	
         */
        $datas = array('2030-01-01');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '04');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);
        /*
         * data	        evento	entrada	        variante_saida
         * 1/1/2030	0 a 6	quarto: 15	
         */
        $datas = array('2030-01-01');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '15');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	entrada	        variante_saida
         * 1/1/2030	0 a 6	quarto: 19	
         */
        $datas = array('2030-01-01');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '19');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	entrada	        variante_saida
         * 1/1/2030	0 a 6	quarto: 4	
         */
        $datas = array('2030-01-01');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '33');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	entrada	        variante_saida
         * 1/1/2030	0 a 6	quarto: 4	
         */
        $datas = array('2030-01-01');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '04', 1 => '15', 2 => '19');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	entrada	                variante_saida
         * 2/1/2030	0 	tipo de quarto: 1	1 a 2
         */
        $datas = array('2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = array(0 => 1);
        $quarto_codigo = null;
        $variante = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('disponivel_quarto_tipo' => array(1 => 14));
        $this->assertEquals($expected, $result);

        $variante = 2;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('resultado' => 1);
        $this->assertEquals($expected, $result);

        /*
         * data	        evento	entrada	                variante_saida
         * 2/1/2030	3 	tipo de quarto: 1	3
         */

        $evento = 3;

        $variante = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('quarto_codigo' => array(0 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '02'), 1 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '08'), 2 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '10'),
                3 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '11'), 4 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '12'), 5 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '19'),
                6 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '20'), 7 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '21'), 8 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '28'),
                9 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '29'), 10 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '30'), 11 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '37')
                , 12 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '38'), 13 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '39')));
        $this->assertEquals($expected, $result);

        /*
         * data	        evento	entrada	                variante_saida
         * 2/1/2030	0 	tipo de quarto: 2	1 a 2
         */
        $datas = array('2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = array(0 => 2);
        $quarto_codigo = null;
        $variante = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('disponivel_quarto_tipo' => array(2 => 1));
        $this->assertEquals($expected, $result);

        $variante = 2;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	entrada	                variante_saida
         * 2/1/2030	3 	tipo de quarto: 2	3
         */

        $evento = 3;

        $variante = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('quarto_codigo' => array(0 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '13'), 1 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '14'),
                2 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '15')));
        $this->assertEquals($expected, $result);

        /*
         * data	        evento	entrada	                variante_saida
         * 2/1/2030	0 	tipo de quarto: 1,2	1 a 2
         */
        $datas = array('2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = array(0 => 1, 1 => 2);
        $quarto_codigo = null;
        $variante = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('disponivel_quarto_tipo' => array(1 => 14, 2 => 1));
        $this->assertEquals($expected, $result);

        $variante = 2;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	entrada	                variante_saida
         * 2/1/2030	3 	tipo de quarto: 2	3
         */

        $evento = 3;

        $variante = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('quarto_codigo' => array(0 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '02'), 1 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '08'), 2 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '10'),
                3 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '11'), 4 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '12'), 5 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '19'),
                6 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '20'), 7 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '21'), 8 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '28'),
                9 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '29'), 10 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '30'), 11 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '37')
                , 12 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '38'), 13 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '39'), 14 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '13')
                , 15 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '14'), 16 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '15')
        ));
        $this->assertEquals($expected, $result);

        /*
         * data	        evento	entrada	        variante_saida
         * 2/1/2030	0 a 6	quarto: 4	
         */
        $datas = array('2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '04');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	entrada	        variante_saida
         * 2/1/2030	0 a 6	quarto: 4	
         */
        $datas = array('2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '15');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	entrada	        variante_saida
         * 1/1/2030	0 a 6	quarto: 19	
         */
        $datas = array('2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '19');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	entrada	        variante_saida
         * 2/1/2030	0 a 6	quarto: 4	
         */
        $datas = array('2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '04', 1 => '15', 2 => '19');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	                evento	entrada	                variante_saida
         * 1/1/2030, 2/1/2030	0 	tipo de quarto: 1	1 a 2
         */
        $datas = array('2030-01-01', '2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = array(0 => 1);
        $quarto_codigo = null;
        $variante = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('disponivel_quarto_tipo' => array(1 => 10));
        $this->assertEquals($expected, $result);

        $variante = 2;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('resultado' => 1);
        $this->assertEquals($expected, $result);

        /*
         * data	                evento	entrada	                variante_saida
         * 1/1/2030, 2/1/2030	3 	tipo de quarto: 1	3
         */

        $evento = 3;

        /* $variante = 3;
          $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
          $expected = array('quarto_codigo' => array(0 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '02'), 1 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '08'), 2 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '10'),
          3 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '11'), 4 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '12'), 5 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '28'),
          6 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '29'), 7 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '30'), 8 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '37'),
          9 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '38'), 10 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '39')));
          $this->assertEquals($expected, $result); */

        /*
         * data	                evento	entrada	                variante_saida
         * 1/1/2030, 2/1/2030	0 	tipo de quarto: 2	1 a 2
         */
        $datas = array('2030-01-01', '2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = array(0 => 2);
        $quarto_codigo = null;
        $variante = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('disponivel_quarto_tipo' => array(2 => 1));
        $this->assertEquals($expected, $result);

        $variante = 2;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('resultado' => 1);
        $this->assertEquals($expected, $result);

        /*
         * data	                evento	entrada	                variante_saida
         * 1/1/2030, 2/1/2030	3 	tipo de quarto: 2	3
         */

        $evento = 3;

        $variante = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('quarto_codigo' => array(0 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '14')));
        $this->assertEquals($expected, $result);

        /*
         * data	                evento	entrada	                variante_saida
         * 1/1/2030, 2/1/2030	0 	tipo de quarto: 1 e 2	1 a 2
         */
        $datas = array('2030-01-01', '2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = array(0 => 1, 1 => 2);
        $quarto_codigo = null;
        $variante = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('disponivel_quarto_tipo' => array(1 => 10, 2 => 1));
        $this->assertEquals($expected, $result);

        $variante = 2;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = array('resultado' => 1);
        $this->assertEquals($expected, $result);

        /*
         * data	                evento	entrada	                variante_saida
         * 1/1/2030, 2/1/2030	3 	tipo de quarto: 1	3
         */

        $evento = 3;

        $variante = 3;
        /* $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
          $expected = array('quarto_codigo' => array(0 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '02'), 1 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '08'),
          2 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '10'),
          3 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '11'), 4 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '12'), 5 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '28'),
          6 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '29'), 7 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '30'), 8 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '37'),
          9 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '38'), 10 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '39'), 11 => array('quarto_tipo_codigo' => '2', 'quarto_codigo' => '14')));
          $this->assertEquals($expected, $result); */


        /*
         * data	                evento	        entrada	        variante_saida
         * 1/1/2030, 2/1/2030	0 e 1 a 6	quarto: 04	
         */
        $datas = array('2030-01-01', '2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '04');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	                evento	        entrada	        variante_saida
         * 1/1/2030, 2/1/2030	0 e 1 a 6	quarto: 15	
         */
        $datas = array('2030-01-01', '2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '15');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 1;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	        evento	        entrada	        variante_saida
         * 1/1/2030	0 e 1 a 6	quarto: 4	
         */
        $datas = array('2030-01-01', '2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '19');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        /*
         * data	                evento	        entrada	           variante_saida
         * 1/1/2030, 2/1/2030	0 e 1 a 6	quarto: 4, 15, 19	
         */
        $datas = array('2030-01-01', '2030-01-02');
        $evento = 0;
        $quarto_tipo_codigo = null;
        $quarto_codigo = array(0 => '04', 1 => '15', 2 => '19');
        $variante = null;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 1;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 4;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 5;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 6;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $evento = 7;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante);
        $expected = 0;
        $this->assertEquals($expected, $result['resultado']);

        $datas = array('2030-01-01');
        $quarto_tipo_codigo = array(0 => 1);
        $documento_numero = 359;
        $quarto_item = 1;
        $quarto_codigo = null;
        $variante = 3;
        $evento = 3;
        $result = $this->geral->gerquadis($empresa_codigo, $datas, $evento, $quarto_tipo_codigo, $quarto_codigo, $variante, $documento_numero, $quarto_item);
        $expected = array('quarto_codigo' => array(0 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '02'), 1 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '08'),
                2 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '11'), 3 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '12'), 4 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '21'),
                5 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '28'), 6 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '29'), 7 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '30'),
                8 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '37'), 9 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '38'), 10 => array('quarto_tipo_codigo' => '1', 'quarto_codigo' => '39')));
        $this->assertEquals($expected, $result);
    }

}
