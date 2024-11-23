<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/select.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";

ejecutaServicio(function () {

 $lista = select(pdo: Bd::pdo(),  from: PRODUCTO,  orderBy: PRO_NOMBRE);

 $render = "";
 foreach ($lista as $modelo) {
  $encodeId = urlencode($modelo[PRO_ID]);
  $id = htmlentities($encodeId);
  $nombre = htmlentities($modelo[PRO_NOMBRE]);
  $render .=
   "<dd>
     <p>
      <a href='modifica-producto.html?id=$id'>$nombre</a>
     </p>
    </dd>";
 }

 devuelveJson(["lista" => ["innerHTML" => $render]]);
});
