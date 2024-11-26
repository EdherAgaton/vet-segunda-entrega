<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/devuelveCreated.php";
require_once __DIR__ . "/../lib/php/update.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_VENTA.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_DET_VENTA.php";
require_once __DIR__ . "/ventaEnCapturaBusca.php";
require_once __DIR__ . "/validaVenta.php";
require_once __DIR__ . "/detVentaConsulta.php";
require_once __DIR__ . "/ventaEnCapturaAgrega.php";

ejecutaServicio(function () {

 $pdo = Bd::pdo();
 $pdo->beginTransaction();

 $venta = ventaEnCapturaBusca($pdo);
 validaVenta($venta);

 $detalles = detVentaConsulta($pdo, $venta[VENT_ID]);

 // Actualiza las existencias de los productos vendidos.
 $update = $pdo->prepare(
  "UPDATE PRODUCTO
   SET PROD_EXISTENCIAS = :PROD_EXISTENCIAS
   WHERE PRO_ID = :PRO_ID"
 );
 foreach ($detalles as $detVenta) {
  $update->execute([
   ":PRO_ID" => $detVenta[PRO_ID],
   ":PROD_EXISTENCIAS" => $detVenta[PROD_EXISTENCIAS] - $detVenta[DTV_CANTIDAD]
  ]);
 }

 update(
  pdo: $pdo,
  table: VENTA,
  set: [VENT_EN_CAPTURA => 0],
  where: [VENT_ID => $venta[VENT_ID]]
 );

 ventaEnCapturaAgrega($pdo);
 $folio = $pdo->lastInsertId();

 $pdo->commit();

 devuelveCreated("/srv/venta-en-captura.php", [
  "folio" => ["value" => $folio],
  "detalles" => ["innerHTML" => ""]
 ]);
});
