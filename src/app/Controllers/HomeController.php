<?php
declare(strict_types=1);
namespace App\Controllers;

use App\App;
use App\Container;
use App\Services\InvoiceService;
use App\View;


class HomeController
{
    public function index():View
    {
        (new Container())->get(InvoiceService::class)->process([], 25);
        return View::make('index');
    }




}