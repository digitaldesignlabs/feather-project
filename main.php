<?php

// Load Escher
require "vendor/autoload.php";
TDM\Escher\Escher::init();

// If the URI finishes with a /, redirect to one without - unless its the root
if ($_SERVER["DOCUMENT_URI"] !== "/" && substr($_SERVER["DOCUMENT_URI"], -1) === "/") {
    $redirectTo = implode("?", array_filter(array(rtrim($_SERVER["DOCUMENT_URI"], "/"), $_SERVER["QUERY_STRING"])));
    TDM\Escher\CurrentRequest::redirectWithStatusCode(301, $redirectTo);
}

// Set up the routing table
$router = new TDM\Escher\Router();

// Sample route
$router->map("/", function () {
    return "This is Escher";
});

// Attemp to route this request
$route = $router->route($_SERVER["REQUEST_METHOD"], $_SERVER["DOCUMENT_URI"]);

// If we cannot route this request then send a 404
if (is_callable($route) === NO) {
    TDM\Escher\CurrentRequest::returnCode(404);
    exit;
}

// Attempt to fulfill the request
try {
    echo call_user_func($route);
} catch (\Exception $ignore) {
    TDM\Escher\CurrentRequest::returnCode(500);
}
