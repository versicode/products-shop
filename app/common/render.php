<?php

namespace render;

function view($templateUri, $data = [], $layoutUri = false)
{
    extract($data, EXTR_SKIP);

    $templateFile = buildAndCheckTemplateFilePath($templateUri);

    require_once $layoutUri ? buildAndCheckTemplateFilePath($layoutUri)
                            : $templateFile;
}

function buildAndCheckTemplateFilePath($templateUri)
{
    $templateFile = ROOT_PATH."/views/{$templateUri}.php";

    return !is_file($layoutTemplateFile) ? $templateFile
                                         : die('Layout template file not found');
}
