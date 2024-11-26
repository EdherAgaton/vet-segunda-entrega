<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_ARCHIVO.php";

ejecutaServicio(function () {

 $id = recuperaIdEntero("id");

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

 $encodeArchId = $modelo[ARCH_ID] === null ? "" : urlencode($modelo[ARCH_ID]);
 $htmlEncodeArchId = htmlentities($encodeArchId);
 devuelveJson([
  "id" => ["value" => $id],
  "nombre" => ["value" => $modelo[PRO_NOMBRE]],
  "precio" => ["value" => $modelo[PRO_PRECIO]],
  "descripcion" => ["value" => $modelo[PRO_DESCRIPCION]],
  "existencias" => ["value" => $modelo[PROD_EXISTENCIAS]],
  "imagen" => [
   "data-file" => $htmlEncodeArchId === ""
    ? ""
    : "srv/archivo.php?id=$htmlEncodeArchId"
  ]
 ]);
});
