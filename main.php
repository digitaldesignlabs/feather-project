<?php

// Load Feather
require "vendor/autoload.php";
DDL\Feather\Feather::init(__DIR__);

// If the URI finishes with a /, redirect to one without - unless its the root
if ($_SERVER["DOCUMENT_URI"] !== "/" && substr($_SERVER["DOCUMENT_URI"], -1) === "/") {
    $redirectTo = implode("?", array_filter(array(rtrim($_SERVER["DOCUMENT_URI"], "/"), $_SERVER["QUERY_STRING"])));
    DDL\Feather\Response::redirectWithStatusCode(301, $redirectTo);
}

// int void main()
call_user_func(function () {

    // Set up the routing table
    $router = new DDL\Feather\Router();

    // Sample route
    $router->map("/", function () {
        return "This is Feather";
    });

    // Attemp to route this request
    $route = $router->route($_SERVER["REQUEST_METHOD"], $_SERVER["DOCUMENT_URI"]);

    // If we cannot route this request then send a 404
    if (is_callable($route) === NO) {
        return DDL\Feather\Response::returnCode(404);
    }

    // Attempt to fulfill the request
    try {
        echo call_user_func($route);
    } catch (\Exception $ignore) {
        DDL\Feather\Response::returnCode(500);
    }
});
