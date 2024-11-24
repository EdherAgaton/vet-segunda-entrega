<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/select.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_ARCHIVO.php";

ejecutaServicio(function () {

 $lista = select(pdo: Bd::pdo(),  from: PRODUCTO,  orderBy: PRO_NOMBRE);

 $render = "";
 foreach ($lista as $modelo) {
  $encodeId = urlencode($modelo[PRO_ID]);
  $id = htmlentities($encodeId);
  $nombre = htmlentities($modelo[PRO_NOMBRE]);
  $encodeArchId = $modelo[ARCH_ID] === null ? "" : urlencode($modelo[ARCH_ID]);
  $archId = $encodeArchId === "" ? "" : htmlentities($encodeArchId);
  $src = $archId === "" ? "" : "srv/archivo.php?id=$archId";
  $render .=
   "<div style='display: flex; flex-direction: row-reverse;
      align-items: center; gap: 0.5rem'>
     <dt style='flex: 1 1 0'>
      <a href='modifica-producto.html?id=$id'>$nombre</a>
     </dt>
     <dd style='flex: 1 1 0; margin: 0'>
      <a href='modifica-producto.html?id=$id'><img
        style='width: 50%; aspect-ratio:16/9; object-fit: cover'
        alt='Imagen del producto' src='$src'></a>
     </dd>
    </div>";
 }

 devuelveJson(["lista" => ["innerHTML" => $render]]);
});
