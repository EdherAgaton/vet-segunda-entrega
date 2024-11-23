<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_MASCOTA.php";

ejecutaServicio(function () {

    $id = recuperaIdEntero("id");

    $modelo = selectFirst(pdo: Bd::pdo(), from: MASCOTA, where: [MAS_ID => $id]);

    if ($modelo === false) {
        $idHtml = htmlentities($id);
        throw new ProblemDetails(
            status: NOT_FOUND,
            title: "Mascota no encontrada.",
            type: "/error/mascota-no-encontrada.html",
            detail: "No se encontró ninguna mascota con el id $idHtml."
        );
    }

    devuelveJson([
        "id" => ["value" => $id],
        "nombre" => ["value" => $modelo[MAS_NOMBRE]],
        "especie" => ["value" => $modelo[MAS_ESPECIE]],
        "raza" => ["value" => $modelo[MAS_RAZA]],
        "fecha_nac" => ["value" => $modelo[MAS_FECHA_NAC]],
    ]);
});
