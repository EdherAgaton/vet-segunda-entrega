<?php

require_once __DIR__ . "/recuperaTexto.php";

/**
 * Recupera el valor decimal de un parámetro (que
 * puede tener fracciones) enviado al servidor por
 * medio de GET, POST o cookie.
 * 
 * Si el parámetro no se recibe, devuekve false
 * 
 * Si se recibe una cadena vacía, se devuelve null.
 * 
 * Si parámetro no se puede convertir a entero,
 * devuelve 0.
 */
function recuperaDecimal(string $parametro): false|null|float
{
 $valor = recuperaTexto($parametro);
 if ($valor === false) {
  return false;
 } elseif ($valor === "") {
  return null;
 } else {
  return (float) trim($valor);
 }
 return $valor === null|| $valor === ""
  ? null
  : trim($valor);
}
