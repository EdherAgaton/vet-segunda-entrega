<?php

require_once __DIR__ . "/../lib/php/BAD_REQUEST.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaBytes.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/validaNombre.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/../lib/php/devuelveCreated.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";
require_once __DIR__ . "/TABLA_ARCHIVO.php";
require_once __DIR__ . "/validaImagen.php";

ejecutaServicio(function () {

 $nombre = recuperaTexto("nombre");

 $precio = recuperaTexto("precio");

 $descripcion = recuperaTexto("descripcion");

 $existencias = recuperaTexto("existencias");

 $bytes = recuperaBytes("imagen");

 $nombre = validaNombre($nombre);

 $precio = validaNombre($precio);

 $descripcion = validaNombre($descripcion);

 $bytes = validaImagen($bytes);

 if ($bytes === "") {
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Imagen vacía.",
   type: "/error/imagenvacia.html",
   detail: "Selecciona un archivo que no esté vacío."
  );
 }

 $pdo = Bd::pdo();
 $pdo->beginTransaction();

 insert(pdo: $pdo,  into: ARCHIVO,  values: [ARCH_BYTES => $bytes]);
 $archId = $pdo->lastInsertId();

 
 insert(
  pdo: $pdo,
  into: PRODUCTO,
  values:  [PRO_NOMBRE => $nombre, PRO_PRECIO => $precio, PRO_DESCRIPCION => $descripcion,PROD_EXISTENCIAS => $existencias,  ARCH_ID => $archId]
 );
 $id = $pdo->lastInsertId();

 $pdo->commit();


 $encodeId = urlencode($id);
 $encodeArchId = urlencode($archId);
 $htmlEncodeArchId = htmlentities($encodeArchId);
 //insert(pdo: $pdo, into: PRODUCTO, values: [PRO_NOMBRE => $nombre, PRO_PRECIO => $precio, PRO_DESCRIPCION => $descripcion]);
 //$id = $pdo->lastInsertId();


 devuelveCreated("/srv/producto.php?id=$encodeId", [
  "id" => ["value" => $id],
  "nombre" => ["value" => $nombre],
  "precio" => ["value" => $precio],
  "descripcion" => ["value" => $descripcion],
  "imagen" => ["data-file" => "srv/archivo.php?id=$htmlEncodeArchId"]
 ]);
});
