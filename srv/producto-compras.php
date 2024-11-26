<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/productoBusca.php";
require_once __DIR__ . "/validaProducto.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_ARCHIVO.php";

ejecutaServicio(function () {

 $id = recuperaIdEntero("id");

 $producto = productoBusca(Bd::pdo(), $id);
 validaProducto($producto, $id);

 $modelo =
  selectFirst(pdo: Bd::pdo(),  from: PRODUCTO,  where: [PRO_ID => $id]);

 if ($modelo === false) {
  $idHtml = htmlentities($id);
  throw new ProblemDetails(
   status: NOT_FOUND,
   title: "Pasatiempo no encontrado.",
   type: "/error/pasatiemponoencontrado.html",
   detail: "No se encontró ningún pasatiempo con el id $idHtml.",
  );
 }

 $encodeArchId = $producto[ARCH_ID] === null ? "" : urlencode($producto[ARCH_ID]);
 $htmlEncodeArchId = htmlentities($encodeArchId);
 devuelveJson([
  "id" => ["value" => $id],
  "nombre" => ["value" => $producto[PRO_NOMBRE]],
  "precio" => ["value" => "$" . number_format($producto[PRO_PRECIO], 2)],
  "descripcion" => ["value" => $producto[PRO_DESCRIPCION]],
  "imagen" => [
   "data-file" => $htmlEncodeArchId === ""
    ? ""
    : "srv/archivo.php?id=$htmlEncodeArchId"
  ]
 ]);
});
