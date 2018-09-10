<?php

namespace router;

function resolve($uri, $uri404)
{
    global $config;

    $actions = [];

    $controller = getControllerFromUri($uri);

    $action = function ($action, $cb) use (&$actions, &$controller) {
        $actions[$controller.'/'.$action] = $cb;
    };

    $controllerPath = getControllerFilePath($controller);

    if (is_file($controllerPath)) {
        require_once $controllerPath;

        if (isset($actions[$uri])) {
            return $actions[$uri];
        }
    }

    $controller = getControllerFromUri($uri404);

    require_once getControllerFilePath($controller);

    return $actions[$uri404];
}

function getControllerFromUri($uri)
{
    return explode('/', $uri)[0];
}

function normalizeUri($uri)
{
    return ltrim($uri, '/');
}

function getControllerFilePath($controller)
{
    return ROOT_PATH."/controllers/{$controller}.php";
}
