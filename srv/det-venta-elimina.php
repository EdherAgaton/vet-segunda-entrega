<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/devuelveNoContent.php";
require_once __DIR__ . "/../lib/php/delete.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_VENTA.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_DET_VENTA.php";
require_once __DIR__ . "/ventaEnCapturaBusca.php";

ejecutaServicio(function () {

 $prodId = recuperaIdEntero("prodId");

 $pdo = Bd::pdo();

 $venta = ventaEnCapturaBusca($pdo);
 if ($venta !== false) {
  delete(
   pdo: $pdo,
   from: DET_VENTA,
   where: [VENT_ID => $venta[VENT_ID], PRO_ID => $prodId]
  );
 }
 devuelveNoContent();
});
