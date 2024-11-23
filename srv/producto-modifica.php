<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/validaNombre.php";
require_once __DIR__ . "/../lib/php/update.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";

ejecutaServicio(function () {

 $id = recuperaIdEntero("id");
 $nombre = recuperaTexto("nombre");
 $precio = recuperaTexto("precio");
    $descripcion = recuperaTexto("descripcion");


 $nombre = validaNombre($nombre);
    $precio = validaNombre($precio);
        $descripcion = validaNombre($descripcion);

 update(
  pdo: Bd::pdo(),
  table: PRODUCTO,
  set: [PRO_NOMBRE => $nombre,
      PRO_PRECIO => $precio,
      PRO_DESCRIPCION => $descripcion],
  where: [PRO_ID => $id]
 );

 devuelveJson([
  "id" => ["value" => $id],
  "nombre" => ["value" => $nombre],
        "precio" => ["value" => $precio],
        "descripcion" => ["value" => $descripcion],
 ]);
});
