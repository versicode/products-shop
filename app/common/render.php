<?php

namespace render;

function view($templateUri, $data)
{
    extract($data, EXTR_SKIP);

    $templateFile = ROOT_PATH."/views/{$templateUri}.php";

    if (is_file($templateFile)) {
        require_once $templateFile;
    } else {
        echo 'Template file not found';
    }
}
