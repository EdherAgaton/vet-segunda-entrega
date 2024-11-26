<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/recuperaDecimal.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/../lib/php/devuelveCreated.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_VENTA.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_DET_VENTA.php";
require_once __DIR__ . "/validaCantidad.php";
require_once __DIR__ . "/productoBusca.php";
require_once __DIR__ . "/validaProducto.php";
require_once __DIR__ . "/ventaEnCapturaBusca.php";
require_once __DIR__ . "/validaVenta.php";

ejecutaServicio(function () {

 $prodId = recuperaIdEntero("id");
 $cantidad = recuperaDecimal("cantidad");

 $cantidad = validaCantidad($cantidad);

 $pdo = Bd::pdo();

 $producto = productoBusca($pdo, $prodId);
 validaProducto($producto, $prodId);

 $venta = ventaEnCapturaBusca($pdo);
 validaVenta($venta);

 insert(
  pdo: Bd::pdo(),
  into: DET_VENTA,
  values: [
   VENT_ID => $venta[VENT_ID],
   PRO_ID => $prodId,
   DTV_CANTIDAD => $cantidad,
   DTV_PRECIO => $producto[PRO_PRECIO],
  ]
 );

 $encodeProdId = urlencode($prodId);
 devuelveCreated("/srv/det-venta.php?id=$encodeProdId", [
  "prodId" => ["value" => $prodId],
  "prodNombre" => ["value" => $producto[PRO_NOMBRE]],
  "precio" => ["value" => "$" . number_format($producto[PRO_PRECIO], 2)],
  "cantidad" => ["valueAsNumber" => $cantidad],
 ]);
});
