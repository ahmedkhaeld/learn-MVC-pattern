<?php
require __DIR__ . '/../vendor/autoload.php';
session_start();

const STORAGE_PATH = __DIR__ . '/../storage';
const VIEW_PATH = __DIR__ . '/../views';
try {
    $router = new App\Router();

    $router->get('/', [App\Controllers\HomeController::class, 'index'])
        ->post('/upload', [App\Controllers\HomeController::class, 'upload'])
        ->get('/download', [App\Controllers\HomeController::class, 'download'])
        ->get('/invoices', [App\Controllers\InvoiceController::class, 'index'])
        ->get('/invoices/create', [App\Controllers\InvoiceController::class, 'create'])
        ->post('/invoices/create', [App\Controllers\InvoiceController::class, 'store']);


    echo $router->resolve($_SERVER['REQUEST_URI'], strtolower($_SERVER['REQUEST_METHOD']));
}catch (\App\Exceptions\RouteNotFoundException $e){
//    header('HTTP/1.1 404 Not Found');
    http_response_code(404);
    echo \App\View::make('error/404');
}