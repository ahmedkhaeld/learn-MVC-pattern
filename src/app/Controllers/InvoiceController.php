<?php
declare(strict_types=1);
namespace App\Controllers;

use App\View;

class InvoiceController
{
    public function index():View
    {
        unset($_SESSION['count']);
        return View::make('invoices/index');

    }

    public function create(): View
    {
        return View::make('invoices/create');

    }

    public function store()
    {
        $invoice=new Invoice();
        $amount=$_POST['amount'];
        $invoice->store($amount);

        var_dump($amount);
    }

}