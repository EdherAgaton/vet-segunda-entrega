<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/fetchAll.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_VENTA.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_DET_VENTA.php";
require_once __DIR__ . "/ventaEnCapturaBusca.php";
require_once __DIR__ . "/validaVenta.php";
require_once __DIR__ . "/detVentaConsulta.php";
require_once __DIR__ . "/Bd.php";

ejecutaServicio(function () {

 $pdo = Bd::pdo();

 $venta = ventaEnCapturaBusca($pdo);
 validaVenta($venta);

 $detalles = detVentaConsulta($pdo, $venta[VENT_ID]);

 $renderDetalles = "";
 foreach ($detalles as $detVenta) {
  $encodeProdId = urlencode($detVenta[PRO_ID]);
  $prodId = htmlentities($encodeProdId);
  $prodNombre = htmlentities($detVenta[PRO_NOMBRE]);
  $precio = htmlentities("$" . number_format($detVenta[PRO_PRECIO], 2));
  $cantidad = htmlentities(number_format($detVenta[DTV_CANTIDAD], 2));
  $renderDetalles .=
   "<dt>$prodNombre</dt>
    <dd>
     <a href= 'modifica-compra.html?prodId=$prodId'>Modificar o eliminar</a>
    </dd>
    <dd>
     <dl>
      <dt>Cantidad</dt>
      <dd>$cantidad</dd>
      <dt>Precio</dt>
      <dd>$precio</dd>
     </dl>
    </dd>";
 }

 devuelveJson([
  "folio" => ["value" => $venta[VENT_ID]],
  "detalles" => ["innerHTML" => $renderDetalles]
 ]);
});
