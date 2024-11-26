<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/recuperaBytes.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";
require_once __DIR__ . "/../lib/php/validaNombre.php";
require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/../lib/php/update.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_ARCHIVO.php";
require_once __DIR__ . "/validaImagen.php";

ejecutaServicio(function () {

 $prodId = recuperaIdEntero("id");
 $nombre = recuperaTexto("nombre");
 $precio = recuperaTexto("precio");
 $descripcion = recuperaTexto("descripcion");
 $existencias = recuperaTexto("existencias");
 $bytes = recuperaBytes("imagen");


 $nombre = validaNombre($nombre);
 $precio = validaNombre($precio);
 $descripcion = validaNombre($descripcion);
 $bytes = validaImagen($bytes);

 
 $pdo = Bd::pdo();
 $pdo->beginTransaction();

 $producto =
  selectFirst(pdo: $pdo, from: PRODUCTO, where: [PRO_ID => $prodId]);


  if ($producto === false) {
    $prodIdHtml = htmlentities($prodId);
    throw new ProblemDetails(
     status: NOT_FOUND,
     title: "Producto no encontrado.",
     type: "/error/productonoencontrado.html",
     detail: "No se encontró ningún producto con el id $prodIdHtml.",
    );
   }

   $archId = $producto[ARCH_ID];

   
 if ($bytes !== "") {
    if ($archId === null) {
     insert(pdo: $pdo, into: ARCHIVO, values: [ARCH_BYTES => $bytes]);
     $archId = $pdo->lastInsertId();
    } else {
     update(
      pdo: $pdo,
      table: ARCHIVO,
      set: [ARCH_BYTES => $bytes],
      where: [ARCH_ID => $archId]
     );
    }
   }

 update(
  pdo: $pdo,
  table: PRODUCTO,
  set: [PRO_NOMBRE => $nombre,
      PRO_PRECIO => $precio,
      PRO_DESCRIPCION => $descripcion,
      PROD_EXISTENCIAS => $existencias,
      ARCH_ID => $archId],
  where: [PRO_ID => $prodId]
 );

 $pdo->commit();

 $encodeArchId = $archId === null ? "" : urlencode($archId);
 $htmlEncodeArchId = htmlentities($encodeArchId);

 devuelveJson([
  "id" => ["value" => $prodId],
  "nombre" => ["value" => $nombre],
  "precio" => ["value" => $precio],
  "descripcion" => ["value" => $descripcion],
  "existencias" => ["value" => $existencias],
  "imagen" => [
            "data-file" => $htmlEncodeArchId === ""
             ? ""
             : "srv/archivo.php?id=$htmlEncodeArchId"
           ]
 ]);
});
