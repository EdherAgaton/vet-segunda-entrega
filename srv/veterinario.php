<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_VETERINARIO.php";

ejecutaServicio(function () {

    $id = recuperaIdEntero("id");

    $modelo = selectFirst(pdo: Bd::pdo(), from: VETERINARIO, where: [VET_ID => $id]);

    if ($modelo === false) {
        $idHtml = htmlentities($id);
        throw new ProblemDetails(
            status: NOT_FOUND,
            title: "Veterinario no encontrado.",
            type: "/error/veterinario-no-encontrado.html",
            detail: "No se encontró ningún veterinario con el id $idHtml."
        );
    }

    devuelveJson([
        "id" => ["value" => $id],
        "nombre" => ["value" => $modelo[VET_NOMBRE]],
        "email" => ["value" => $modelo[VET_EMAIL]],
        "telefono" => ["value" => $modelo[VET_TELEFONO]],
        "especialidad" => ["value" => $modelo[VET_ESPECIALIDAD]],
    ]);
});
