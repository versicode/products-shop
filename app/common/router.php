<?php

namespace router;

function resolve($uri, $uri404)
{
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

function makeAbsoluteUrl($uri)
{
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http');
    $host = $_SERVER['HTTP_HOST'];

    return "{$protocol}://{$host}/{$uri}";
}

function redirect($uri)
{
    header('Location: '.makeAbsoluteUrl($uri));
    exit;
}

function getControllerFromUri($uri)
{
    return explode('/', $uri)[0];
}

function normalizeUri($uri)
{
    return ltrim(parse_url($uri, PHP_URL_PATH), '/');
}

function getControllerFilePath($controller)
{
    return ROOT_PATH."/controllers/{$controller}.php";
}

function parseIdentifier($id, $default = false)
{
    $identifier = filter_var($id, FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 1,
        ],
    ]);

    return !$identifier && $default ? $default : $identifier;
}
