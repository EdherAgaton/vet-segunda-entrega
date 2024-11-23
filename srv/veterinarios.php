<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/select.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_VETERINARIO.php";

ejecutaServicio(function () {

    // Obtener todos los veterinarios ordenados por nombre
    $lista = select(pdo: Bd::pdo(), from: VETERINARIO, orderBy: VET_NOMBRE);

    $render = "";
    foreach ($lista as $modelo) {
        // Asegúrate de usar correctamente el ID
        $encodeId = urlencode($modelo[VET_ID]);
        $id = htmlentities($encodeId);
        $nombre = htmlentities($modelo[VET_NOMBRE]);

        // Generar el enlace con el ID correcto en la URL
        $render .=
            "<dd>
                <p>
                    <a href='modifica-veterinario.html?id=$id'>$nombre</a>
                </p>
            </dd>";
    }

    // Devolver el HTML generado en formato JSON
    devuelveJson(["lista" => ["innerHTML" => $render]]);
});
