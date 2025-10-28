<?php



if (!function_exists('Upper')) {

    function Upper($text) {
        return mb_strtoupper($text, 'UTF8');
    }

}
if (!function_exists('showActions')) {

    function showActions($text, $name , $actions) {
        $text = trim($text);
        if ($text=="" || $text=="*"){
            return "";
        }
        $options = explode("|", $text);
        $html = '<ul class="list-group">';
        foreach($options as $value){
            $checked = in_array($value, $actions) ? 'checked="checked"':'';
            $html .= '<li class="list-group-item"> <input '.$checked.' name="permissions['.$name.']['.$value.']" style="margin-right:5px;" type="checkbox"  /> '.$value."</li>";
        }
        $html .= "</ul>";
        return $html;
        
    }

}
if (!function_exists('Abc')) {

    function Abc($text) {
        dd($text);
        return ucfirst($text);
    }

}

if (!function_exists('Lower')) {

    function Lower($text) {
        return mb_strtolower($text, 'UTF8');
    }

}

if(!function_exists('CamelCase')) {
    function CamelCase($text) {
        return ucfirst(Lower($text));
    }
}

if (!function_exists('setLabel')) {

    function setLabel($text) {
        return str_replace(" ", "_",  mb_strtolower($text, 'UTF8')) ;
    }

}

if (!function_exists('removeLabel')) {

    function removeLabel($text) {
        return str_replace("_", " ",  mb_strtolower($text, 'UTF8')) ;
    }

}


if (!function_exists('showFloat')) {

    function showFloat($monto, $dec = 2) {//se7ho
        return number_format($monto, $dec, ',', '.');
    }

}

if (!function_exists('saveFloat')) {

    function saveFloat($monto) {//se7ho
        return str_replace(',', '.', str_replace('.', '', $monto));
    }

}

if (!function_exists('saveDate')) {

    function saveDate($date, $separador = "/") {
        $date = explode($separador, $date);
        return $date[2] . '-' . $date[1] . '-' . $date[0];
    }

}



if (!function_exists('showDate')) {

    function showDate($date) {
        $date = explode("-", $date);
        return $date[2].'/'.$date[1]."/".$date[0];
    }

}
if (!function_exists('showDateFull')) {

    function showDateFull($date_full) {
        $date = explode(" ", $date_full);
        $time = $date[1];
        $date = explode("-", $date[0]);
        
        
        return $date[2].'/'.$date[1]."/".$date[0]. ' '.$time;
    }

}
if (!function_exists('showNamePng')) {

    function showNamePng($name) {
        $name = explode(".", $name);
        return Upper($name[0]);
    }

}
if (!function_exists('showPhone')) {

    function showPhone($num) {
        $num1 = Str::replace('-', '',Str::replace(' ', '', Str::replace('.', '', trim($num))));
        if ( strlen($num1)==11 ){
            $num1 = substr($num1, 0, 4).'-'.substr($num1, 4, 7);
        }
        return $num1;
    }

}




if (!function_exists('checkCta')) {

    function checkCta($banco, $oficina, $digitos, $num_cuenta) {
        $pesos1 = array(3, 2, 7, 6, 5, 4, 3, 2);
        $pesos2 = array(3, 2, 7, 6, 5, 4, 3, 2, 7, 6, 5, 4, 3, 2);
        $cuenta = $banco . $oficina . $digitos . $num_cuenta;
        if (strlen($cuenta) != 20) {
            return false;
        }
        $campos1 = $banco . $oficina;
        $campos2 = $oficina . $num_cuenta;
        $digitos1 = (int) $campos1;
        $digitos2 = (int) $campos2;
        $suma1 = 0;
        $suma2 = 0;
        for ($i = 0; $i < 8; $i++) {
            $digito = (int) (($digitos1 / pow(10.0, (7 - $i) * 1.0))) % 10;
            $suma1 += $pesos1[$i] * $digito;
        }
        for ($i = 0; $i < 14; $i++) {
            $digito = (int) (($digitos2 / pow(10.0, (13 - $i) * 1.0))) % 10;
            $suma2 += $pesos2[$i] * $digito;
        }
        $digito1 = (11 - ($suma1 % 11));
        $digito2 = (11 - ($suma2 % 11));
        if ($digito1 >= 10 || $digito1 < 1) {
            $digito1 = $digito1 % 10;
        }
        if ($digito2 >= 10 || $digito2 < 1) {
            $digito2 = $digito2 % 10;
        }
        //echo $digito1 . '-' . $digito2;
        $cuentaValidada = $banco . $oficina . $digito1 . $digito2 . $num_cuenta;
        return $cuenta == $cuentaValidada;
    }

}

if (!function_exists('showActions')) {

    function showActions($text) {//se7ho
        if ($text == '*') {
            return __("All");
        }
        return __(ucwords(str_replace("_", " ", $text)));
    }

}


