<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/select.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_ARCHIVO.php";

ejecutaServicio(function () {

 $pdo = Bd::pdo();

 $lista = select(pdo: $pdo, from: PRODUCTO, orderBy: PRO_NOMBRE);

 $render = "";
 foreach ($lista as $modelo) {
  $encodeId = urlencode($modelo[PRO_ID]);
  $id = htmlentities($encodeId);
  $nombre = htmlentities($modelo[PRO_NOMBRE]);
  $precio = htmlentities("$" . number_format($modelo[PRO_PRECIO], 2));
  $existencias = htmlentities(number_format($modelo[PROD_EXISTENCIAS], 2));
  $encodeArchId = $modelo[ARCH_ID] === null ? "" : urlencode($modelo[ARCH_ID]);
  $archId = $encodeArchId === "" ? "" : htmlentities($encodeArchId);
  $src = $archId === "" ? "" : "srv/archivo.php?id=$archId";
  $render .=
   "<img
        style='width: 20%; aspect-ratio:16/9; object-fit: cover'
        alt='Imagen del producto' src='$src'>
        
        <dt>$nombre</dt>
    <dd>
     <a href='agrega-carrito.html?id=$id'>Agregar al carrito</a>
    </dd>
    <dd>
     <dl>
      <dt>Precio</dt>
      <dd>$precio</dd>
      <dt>Existencias</dt>
      <dd>$existencias</dd>
     </dl>
    </dd>";
 }
 devuelveJson(["lista" => ["innerHTML" => $render]]);
});
