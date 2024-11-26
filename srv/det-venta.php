<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_VENTA.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_DET_VENTA.php";
require_once __DIR__ . "/productoBusca.php";
require_once __DIR__ . "/validaProducto.php";
require_once __DIR__ . "/ventaEnCapturaBusca.php";
require_once __DIR__ . "/validaVenta.php";

ejecutaServicio(function () {

 $prodId = recuperaIdEntero("prodId");

 $pdo = Bd::pdo();

 $venta = ventaEnCapturaBusca($pdo);
 validaVenta($venta);

 $producto = productoBusca($pdo, $prodId);
 validaProducto($producto, $prodId);

 $detVenta = selectFirst(
  pdo: $pdo,
  from: DET_VENTA,
  where: [
   VENT_ID => $venta[VENT_ID],
   PRO_ID => $prodId
  ]
 );

 if ($detVenta === false) {
  $htmlId = htmlentities($prodId);
  throw new ProblemDetails(
   status: NOT_FOUND,
   type: "/error/detalledeventanoencontrado.html",
   title: "Detalle de venta no encontrado.",
   detail: "No se encontró ningún detalle de venta con el id de producto "
    . $htmlId . ".",
  );
 }

 devuelveJson([
  "prodId" => ["value" => $prodId],
  "prodNombre" => ["value" => $producto[PRO_NOMBRE]],
  "precio" => ["value" => "$" . number_format($detVenta[DTV_PRECIO], 2)],
  "cantidad" => ["valueAsNumber" => $detVenta[DTV_CANTIDAD]],
 ]);
});
