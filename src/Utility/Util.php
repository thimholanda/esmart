<?php

namespace App\Utility;

use Cake\Network\Session;
use App\Model\Entity\Geral;

class Util {

    public function array_filter_($var, $ky, $diaria_qtd) {
        $var_tmp = array();
        foreach ($var as $ky1 => $vl1) {
            if ($vl1[$ky] >= $diaria_qtd)
                $var_tmp[] = $vl1;
        }
        return $var_tmp;
    }

    public function min_by_key($arr, $key) {
        $min = array();
        foreach ($arr as $val) {
            if (!isset($val[$key]) and is_array($val)) {
                $min2 = min_by_key($val, $key);
                $min[$min2] = 1;
            } elseif (!isset($val[$key]) and ! is_array($val)) {
                return false;
            } elseif (isset($val[$key])) {
                $min[$val[$key]] = 1;
            }
        }
        return min(array_keys($min));
    }

// -------------------FIM DA FUNCAO-------------------------
// -------------------FIM DA FUNCAO-------------------------
    public static function convertDataSql($strData) {
        if ($strData == "") {
            return null;
        } else {
            $strData = trim($strData);
            if (strripos($strData, "/") > 0) {
                $data_conv = explode(' ', $strData);
                $dateTemp = explode('/', $data_conv[0]);
                if (array_key_exists(1, $data_conv))
                    $data_conv = $dateTemp[2] . '-' . $dateTemp[1] . '-' . $dateTemp[0] . ' ' . $data_conv[1];
                else
                    $data_conv = $dateTemp[2] . '-' . $dateTemp[1] . '-' . $dateTemp[0];
            } else {
                $data_conv = $strData;
            }

            return $data_conv;
        }
    }

    public function convertDataDMY($strData, $formato = null) {
        if ($strData == '0000-00-00 00:00:00' || $strData == '0000-00-00') {
            return "";
        }

        if (is_array($strData)) {
            for ($i = 0; $i < sizeof($strData); $i++)
                $strData[$i] = Util::convertDataDMY($strData[$i]);
            $data_conv = implode(',', $strData);
        } else {
            if (strripos($strData, "-") > 0) {
                if ($formato != null)
                    $data_conv = date($formato, strtotime($strData));
                else
                    $data_conv = date('d/m/Y', strtotime($strData));
            } else {
                if ($strData == null)
                    $data_conv = "";
                else
                    $data_conv = $strData;
            }
        }
        return $data_conv;
    }

    public static function convertDataHoraSql($strData) {
        $data_hora_str = explode(" ", $strData);
        if (sizeof($data_hora_str) > 1) {
            $hora = $data_hora_str[1];
        } else {
            $hora = "00:00";
        }

        $strData = trim($strData);
        if (strripos($strData, "/") > 0) {
            $data_conv = explode(' ', $strData);
            $dateTemp = explode('/', $data_conv[0]);
            if (array_key_exists(1, $data_conv))
                $data_conv = $dateTemp[2] . '-' . $dateTemp[1] . '-' . $dateTemp[0] . ' ' . $data_conv[1];
            else
                $data_conv = $dateTemp[2] . '-' . $dateTemp[1] . '-' . $dateTemp[0];
        } else {
            $data_conv = $strData;
        }

        //$data_conv .= " " . $hora;
        return $data_conv;
    }

    /*
     * Compara datas independente do formato passado
     * @return 1 (data1>data2)
     *          -1 (data1<data2)
     *          0 (data1=data2)
     */

    public function comparaDatas($data1, $data2) {
        if (Util::geraTimestamp($data1) > Util::geraTimestamp($data2)) {
            return 1;
        } else if (Util::geraTimestamp($data1) < Util::geraTimestamp($data2)) {
            return -1;
        } else {
            return 0;
        }
    }

    // Cria uma função que retorna o timestamp de uma data no formato DD/MM/AAAA
    function geraTimestamp($data) {
        //Verifica se está no formato brasileiro
        if (strripos($data, "/") > 0) {
            $partes = explode('/', $data);
            return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
            //Se nao estiver, apenas retornoa o timestamp
        } else {
            return strtotime($data);
        }
    }

    public function NDias($data_inicial, $data_final) {
        $time_inicial = strtotime($data_inicial);
        $time_final = strtotime($data_final);

        // Calcula a diferenÃ§a de segundos entre as duas datas:
        $diferenca = $time_final - $time_inicial; // 19522800 segundos
        // Calcula a diferenÃ§a de dias
        $dias = (int) round($diferenca / (60 * 60 * 24)); // 225 dias
        return $dias;
    }

    public function NHoras($data_inicial, $data_final) {
        $time_inicial = strtotime($data_inicial);
        $time_final = strtotime($data_final);

        // Calcula a diferenÃ§a de segundos entre as duas datas:
        $diferenca = $time_final - $time_inicial; // 19522800 segundos
        // Calcula a diferenÃ§a de horas
        $horas = (int) round($diferenca / (60 * 60)); // 225 dias
        return $horas;
    }

// -------------------FIM DA FUNCAO-------------------------
// Funções diversas de auxilio.
    public function diasemana($data) {
        $ano = (int) substr("$data", 0, 4);
        $mes = (int) trim(substr("$data", 5, -3));
        $dia = (int) trim(substr("$data", 8, 9));

        $diasemana = date("w", mktime(0, 0, 0, $mes, $dia, $ano)); //or die('erro: '.$data.' - '.$dia.' '.$mes.' '.$ano);

        switch ((int) $diasemana) {
            case 0: $diasemana = "Dom";
                break;
            case 1: $diasemana = "Seg";
                break;
            case 2: $diasemana = "Ter";
                break;
            case 3: $diasemana = "Qua";
                break;
            case 4: $diasemana = "Qui";
                break;
            case 5: $diasemana = "Sex";
                break;
            case 6: $diasemana = "Sáb";
                break;
        }

        return($diasemana);
    }

// -------------------FIM DA FUNCAO-------------------------

    /*
      CÃ³digo escrito por Talianderson Dias
      em caso de dÃºvidas, mande um email para talianderson.web@gmail.com
     */
    public function MostreSemanas() {
        $semanas = "DSTQQSS";

        for ($i = 0; $i < 7; $i++)
            echo "<td>" . $semanas{$i} . "</td>";
    }

// -------------------FIM DA FUNCAO-------------------------

    public function GetNumeroDias($mes) {
        $numero_dias = array(
            '01' => 31, '02' => 28, '03' => 31, '04' => 30, '05' => 31, '06' => 30,
            '07' => 31, '08' => 31, '09' => 30, '10' => 31, '11' => 30, '12' => 31
        );

        if (((date('Y') % 4) == 0 and ( date('Y') % 100) != 0) or ( date('Y') % 400) == 0) {
            $numero_dias['02'] = 29;  // altera o numero de dias de fevereiro se o ano for bissexto
        }

        return $numero_dias[$mes];
    }

// -------------------FIM DA FUNCAO-------------------------

    public function GetNomeMes($mes) {
        $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março",
            '04' => "Abril", '05' => "Maio", '06' => "Junho",
            '07' => "Julho", '08' => "Agosto", '09' => "Setembro",
            '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro"
        );

        if ($mes >= 01 && $mes <= 12)
            return $meses[$mes];
        return "Mês deconhecido";
    }

// -------------------FIM DA FUNCAO-------------------------
    public function MostreCalendario($mes) {
        $numero_dias = GetNumeroDias($mes); // retorna o nÃºmero de dias que tem o mÃªs desejado
        $nome_mes = GetNomeMes($mes);
        $diacorrente = 0;

        $diasemana = jddayofweek(cal_to_jd(CAL_GREGORIAN, $mes, "01", date('Y')), 0);  // funÃ§Ã£o que descobre o dia da semana

        echo "<table border = 0 cellspacing = '0' align = 'center'>";
        echo "<tr>";
        echo "<td colspan = 7><h3>" . $nome_mes . "</h3></td>";
        echo "</tr>";
        echo "<tr>";
        MostreSemanas(); // funÃ§Ã£o que mostra as semanas aqui
        echo "</tr>";
        for ($linha = 0; $linha < 6; $linha++) {
            echo "<tr>";
            for ($coluna = 0; $coluna < 7; $coluna++) {
                echo "<td width = 30 height = 30 ";
                if (($diacorrente == ( date('d') - 1) && date('m') == $mes)) {
                    echo " id = 'dia_atual' ";
                } else {
                    if (($diacorrente + 1) <= $numero_dias) {
                        if ($coluna < $diasemana && $linha == 0) {
                            echo " id = 'dia_branco' ";
                        } else {
                            echo " id = 'dia_comum' ";
                        }
                    } else {
                        echo " ";
                    }
                }
                echo " align = \"center\" valign = \"center\">";

                /* TRECHO IMPORTANTE: A PARTIR DESTE TRECHO Ã‰ MOSTRADO UM DIA DO CALENDÃRIO (MUITA ATENÃ‡ÃƒO NA HORA DA MANUTENÃ‡ÃƒO) */

                if ($diacorrente + 1 <= $numero_dias) {
                    if ($coluna < $diasemana && $linha == 0) {
                        echo " ";
                    } else {
                        echo "<a href = " . $_SERVER["PHP_SELF"] . "?mes=$mes&dia=" . ($diacorrente + 1) . ">" . ++$diacorrente . "</a>";
                    }
                } else {
                    break;
                }
                /* FIM DO TRECHO MUITO IMPORTANTE */
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

// -------------------FIM DA FUNCAO-------------------------

    public function MostreCalendarioCompleto() {
        echo "<table align = \"center\">";
        $cont = 1;
        for ($j = 0; $j < 4; $j++) {
            echo "<tr>";
            for ($i = 0; $i < 3; $i++) {

                echo "<td>";
                MostreCalendario(($cont < 10 ) ? "0" . $cont : $cont );

                $cont++;
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

// -------------------FIM DA FUNCAO-------------------------
//MostreCalendario('05');
//echo "<br/>"
//MostreCalendarioCompleto();
    public function valorPorExtenso($valor = 0, $complemento = true) {
        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);

        for ($i = 0; $i < count($inteiro); $i++)
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];


        // $fim identifica onde que deve se dar junÃ§Ã£o de centenas por "e" ou por "," ;)
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);

        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;

            if ($complemento == true) {
                $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
                if ($valor == "000")
                    $z++;
                elseif ($z > 0)
                    $z--;
                if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                    $r .= (($z > 1) ? " de " : "") . $plural[$t];
            }
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        return($rt ? $rt : "zero");
    }

// -------------------FIM DA FUNCAO-------------------------

    public function calculaIdade($nascimento_data) {
        // Separa em dia, mês e ano
        list($dia, $mes, $ano) = explode('/', $data);

        // Descobre que dia é hoje e retorna a unix timestamp
        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        // Descobre a unix timestamp da data de nascimento
        $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

        $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
        return $idade;
    }

//date_add($date, date_interval_create_from_date_string('10 days'));
    public function stringData($data) {
        # Split para dia, mes, ano, hora, minuto e segundo da data inicial
        $_split_datehour = explode(' ', $data);
        if (stripos($_split_datehour[0], "/") > 0)
            $_split_data = explode("/", $_split_datehour[0]);
        else
            $_split_data = explode("-", $_split_datehour[0]);

        $_split_hour = explode(":", $_split_datehour[1]);

        if (stripos($_split_datehour[0], "/") > 0)
            $days = $_split_data[0];
        else
            $days = $_split_data[2];

        $hours = $_split_hour[0];
        $mins = $_split_hour[1];
        $secs = $_split_hour[2];

        $retorno = "";
        $retorno .= ($days > 0) ? $days . ' days ' : "";
        $retorno .= ($hours > 0) ? $hours . ' hours ' : "";
        $retorno .= ($mins > 0) ? $mins . ' minutes ' : "";
        $retorno .= ($secs > 0) ? $secs . ' seconds ' : "";

        return $retorno;
    }

    public function somaData($data1, $data2) {
        $data = new \DateTime(convertDataSql($data1));
        date_add($data, date_interval_create_from_date_string(stringData(convertDataSql($data2))));
        $retorno = date_format($data, 'Y-m-d H:i:s');
        return $retorno;
    }

    public function somaHora($data1, $horas, $convert_data = 1) {
        if ($convert_data)
            $data = new \DateTime(Util::convertDataSql($data1));
        else
            $data = new \DateTime($data1);

        date_add($data, date_interval_create_from_date_string(intval($horas) . " hours"));
        $retorno = date_format($data, 'Y-m-d H:i:s');

        return $retorno;
    }

    public function somaDias($data1, $dias, $convert_data = 1) {
        if ($convert_data)
            $data = new \DateTime(Util::convertDataSql($data1));
        else
            $data = new \DateTime($data1);
        date_add($data, date_interval_create_from_date_string($dias . " days"));
        $retorno = date_format($data, 'Y-m-d H:i:s');
        return $retorno;
    }

    public function somaMinuto($minuto) {
        $data = new \DateTime();
        date_add($data, date_interval_create_from_date_string($minuto . " minutes"));
        $retorno = date_format($data, 'H:i');
        return $retorno;
    }

    public function tempoData($dataini, $datafim) {
        # Split para dia, mes, ano, hora, minuto e segundo da data inicial
        $_split_datehour = explode(' ', $dataini);
        if (stripos($_split_datehour[0], "/") > 0)
            $_split_data = explode("/", $_split_datehour[0]);
        else
            $_split_data = explode("-", $_split_datehour[0]);

        $_split_hour = explode(":", $_split_datehour[1]);
        # Coloquei o parse (integer) caso o timestamp nao tenha os segundos, dai ele fica como 0
        $dtini = mktime($_split_hour[0], $_split_hour[1], (integer) $_split_hour[2], $_split_data[1], $_split_data[0], $_split_data[2]);

        # Split para dia, mes, ano, hora, minuto e segundo da data final
        $_split_datehour = explode(' ', $datafim);
        if (stripos($_split_datehour[0], "/") > 0)
            $_split_data = explode("/", $_split_datehour[0]);
        else
            $_split_data = explode("-", $_split_datehour[0]);

        $_split_hour = explode(":", $_split_datehour[1]);

        if (stripos($_split_datehour[0], "/") > 0)
            $dtfim = mktime($_split_hour[0], $_split_hour[1], (integer) $_split_hour[2], $_split_data[1], $_split_data[0], $_split_data[2]);
        else
            $dtfim = mktime($_split_hour[0], $_split_hour[1], (integer) $_split_hour[2], $_split_data[1], $_split_data[2], $_split_data[0]);

        # Diminui a datafim que é a maior com a dataini
        $time = ($dtfim - $dtini);

        # Recupera os dias
        $days = floor($time / 86400);
        # Recupera as horas
        $hours = floor(($time - ($days * 86400)) / 3600);
        # Recupera os minutos
        $mins = floor(($time - ($days * 86400) - ($hours * 3600)) / 60);
        # Recupera os segundos
        $secs = floor($time - ($days * 86400) - ($hours * 3600) - ($mins * 60));

        # Monta o retorno no formato
        # 5d 10h 15m 20s
        # somente se os itens forem maior que zero
        $retorno = "";
        $retorno .= ($days > 0) ? $days . 'd ' : "";
        $retorno .= ($hours > 0) ? $hours . 'h ' : "";
        $retorno .= ($mins > 0) ? $mins . 'm ' : "";
        $retorno .= ($secs > 0) ? $secs . 's ' : "";

        # Se o dia for maior que 3 fica vermelho
        if ($days > 3) {
            return "<span style='color:red'>" . $retorno . "</span>";
        }
        return $retorno;
    }

    public function addDate($date, $day) {
        $sum = strtotime(date("Y-m-d", strtotime("$date")) . " +$day days");
        $dateTo = date('Y-m-d', $sum);
        return $dateTo;
    }

    public function addYear($date, $year) {
        $sum = strtotime(date("Y-m-d", strtotime("$date")) . " +$year years");
        $dateTo = date('Y-m-d', $sum);
        return $dateTo;
    }

    public function print_a($arrays) {
        $retorno = "";

        if (is_array($arrays)) {
            foreach ($arrays AS $key => $value) {
                if (is_array($value))
                    foreach ($value AS $key1 => $value1)
                        $retorno = $retorno . "1] $key1 = " . $value1 . "<br>";
                else
                    $retorno = $retorno . "(root)$key = " . $value . "<br>";
            }
        } else
            $retorno = var_dump($arrays);

        return $retorno;
    }

    public function xml_encode($mixed, $domElement = null, $DOMDocument = null) {
        if (is_null($DOMDocument)) {
            $DOMDocument = new DOMDocument;
            $DOMDocument->formatOutput = true;
            xml_encode($mixed, $DOMDocument, $DOMDocument);
            echo $DOMDocument->saveXML();
        } else {
            // To cope with embedded objects 
            if (is_object($mixed)) {
                $mixed = get_object_vars($mixed);
            }
            if (is_array($mixed)) {
                foreach ($mixed as $index => $mixedElement) {
                    if (is_int($index)) {
                        if ($index === 0) {
                            $node = $domElement;
                        } else {
                            $node = $DOMDocument->createElement($domElement->tagName);
                            $domElement->parentNode->appendChild($node);
                        }
                    } else {
                        $plural = $DOMDocument->createElement($index);
                        $domElement->appendChild($plural);
                        $node = $plural;
                        if (!(rtrim($index, 's') === $index)) {
                            $singular = $DOMDocument->createElement(rtrim($index, 's'));
                            $plural->appendChild($singular);
                            $node = $singular;
                        }
                    }

                    xml_encode($mixedElement, $node, $DOMDocument);
                }
            } else {
                $mixed = is_bool($mixed) ? ($mixed ? 'true' : 'false') : $mixed;
                $domElement->appendChild($DOMDocument->createTextNode($mixed));
            }
        }
    }

    public function json_gera($mixed) {
        $var_string = "{";
        foreach ($mixed AS $key => $value) {
            if (is_array($value)) {
                $var_string = $var_string . "\"$key\":[";
                $var_string = $var_string . Util::json_gera($value);
                $var_string = $var_string . "],";
            } else {
                $var_string = $var_string . "\"$key\":\"$value\",";
            }
        }
        $var_string = substr($var_string, 0, strlen($var_string) - 1);
        $var_string = $var_string . "}";
        return $var_string;
    }

    public function IntervalDays($CheckIn, $CheckOut) {
        $CheckInX = explode("-", $CheckIn);
        $CheckOutX = explode("-", $CheckOut);
        $date1 = mktime(0, 0, 0, $CheckInX[1], $CheckInX[2], $CheckInX[0]);
        $date2 = mktime(0, 0, 0, $CheckOutX[1], $CheckOutX[2], $CheckOutX[0]);
        $interval = ($date2 - $date1) / (3600 * 24);

        return $interval;
    }

    public function germonrot($arr_gertelmon) {
        $geral = new Geral();
        $retorno = array();
        foreach ($arr_gertelmon AS $ky => $vl) {
            $retorno['rot_' . $vl['elemento_codigo']] = $geral->gercamrot($arr_gertelmon, $vl['elemento_codigo']);
        }
        return $retorno;
    }

    public function germonpro($arr_gertelmon) {
        $geral = new Geral();
        $retorno = array();
        foreach ($arr_gertelmon AS $ky => $vl) {
            $retorno['pro_' . $vl['elemento_codigo']] = $geral->gercampro($arr_gertelmon, $vl['elemento_codigo']);
        }
        return $retorno;
    }

    public function germonfor($arr_gertelmon) {
        $geral = new Geral();
        $retorno = array();
        foreach ($arr_gertelmon AS $ky => $vl) {
            $retorno['for_' . $vl['elemento_codigo']] = $geral->gercamfor($arr_gertelmon, $vl['elemento_codigo']);
        }
        return $retorno;
    }

    public function germonval($arr_gertelmon) {
        $geral = new Geral();
        $retorno = array();

        foreach ($arr_gertelmon AS $ky => $vl) {
            if ($geral->gercamval($arr_gertelmon, $vl['elemento_codigo']) == 'date' || $geral->gercamval($arr_gertelmon, $vl['elemento_codigo']) == 'birthdate' || $geral->gercamval($arr_gertelmon, $vl['elemento_codigo']) == 'futuradata' || $geral->gercamval($arr_gertelmon, $vl['elemento_codigo']) == 'futuradata2') {
                $data_validation_optional = " data-validation-optional='true' ";

                if ($vl['campo_propriedade'] == 4)
                    $data_validation_optional = " data-validation-optional='false' ";

                $retorno['val_' . $vl['elemento_codigo']] = $geral->gercamval($arr_gertelmon, $vl['elemento_codigo']) != '' ?
                        "data-validation='" . $geral->gercamval($arr_gertelmon, $vl['elemento_codigo']) . "'  data-validation-format='dd/mm/yyyy'  $data_validation_optional " : "";
            } else {
                if ($vl['campo_propriedade'] != 4)
                    $retorno['val_' . $vl['elemento_codigo']] = $geral->gercamval($arr_gertelmon, $vl['elemento_codigo']) != '' ?
                            "data-validation='" . $geral->gercamval($arr_gertelmon, $vl['elemento_codigo']) . "' data-validation-optional='true' " : "";
                else
                    $retorno['val_' . $vl['elemento_codigo']] = $geral->gercamval($arr_gertelmon, $vl['elemento_codigo']) != '' ?
                            "data-validation='" . $geral->gercamval($arr_gertelmon, $vl['elemento_codigo']) . "' " : "";
            }
        }

        return $retorno;
    }

    public function germonpad($arr_gertelmon) {
        $geral = new Geral();
        $retorno = array();
        foreach ($arr_gertelmon AS $ky => $vl) {
            if ($vl['campo_padrao_valor'] != null) {
                $retorno['campo_padrao_valor_' . $vl['elemento_codigo']] = 1;
                $retorno['padrao_valor_' . $vl['elemento_codigo']] = $vl['padrao_valor'];
            } else {
                $retorno['campo_padrao_valor_' . $vl['elemento_codigo']] = 0;
                $retorno['padrao_valor_' . $vl['elemento_codigo']] = '';
            }
        }
        return $retorno;
    }

    public function verificaAsterisco($propriedade) {

        if (strpos($propriedade, 'required') !== false || strpos($propriedade, "optional='false'") !== false)
            return '*';

        return '';
    }

    function gerdiauti($data) {
// Converte $data em um UNIX TIMESTAMP
        $timestamp = strtotime($data);
// Calcula qual o dia da semana de $data
// O resultado será um valor numérico:
// 1 -> Segunda ... 7 -> Domingo
        $dia = date('N', $timestamp);
// Se for sábado (6) ou domingo (7), calcula a próxima segunda-feira
        if ($dia >= 6) {
            return false;
        } else {
// Não é sábado nem domingo, mantém a data de entrada
            return true;
        }
    }

    //COnverte valor brasileiro para americano
    function uticonval_br_us($valor) {
        $session = new Session();
        if ($session->read('decimal_separador') == ',') {
            $var2 = str_replace(".", "", $valor); //Retirou todos os pontos
            return str_replace(",", ".", $var2); //Substitui vírgulas por pontos
        } else {
            return str_replace(",", "", $valor);
        }
    }

    //COnverte valor brasileiro para americano
    function uticonval_us_br($valor) {
        $session = new Session();
        if ($session->read('decimal_separador') == ',') {
            return str_replace(".", ",", $valor); //Retirou todos os pontos
        }
    }

    function title_select_multiple($array_dominio, $valores) {
        $label_status = "";
        foreach ($array_dominio as $value) {
            if (in_array($value['valor'], $valores))
                $label_status .= $value['rotulo'] . ', ';
        }
        if ($label_status != "")
            $label_status = substr($label_status, 0, -2);
        return $label_status;
    }

    public static function date_sort($a, $b) {
        return strtotime($a) - strtotime($b);
    }

    public static function date_compare($a, $b) {
        $t1 = strtotime($a[0]);
        $t2 = strtotime($b[0]);
        return $t1 - $t2;
    }

    public function encontra_rotulo_gercamdom($lista_gercamdom, $valor_buscado) {
        $elemento_rotulo = "";
        $indice_elemento = array_search($valor_buscado, array_column($lista_gercamdom, 'valor'));
        if ($indice_elemento !== false)
            $elemento_rotulo = $lista_gercamdom[$indice_elemento]['rotulo'];
        
        return $elemento_rotulo;
    }

}
