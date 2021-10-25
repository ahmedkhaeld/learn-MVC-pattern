<?php
declare(strict_types=1);
namespace App\Controllers;

use App\View;

class HomeController
{
    public function index():View
    {
        return View::make('index', ['foo'=>'bar']);
    }

    public function upload()
    {
        echo '<pre>';
        var_dump($_FILES);
        echo '<pre>';
        // file destination in our storage
        $filePath=STORAGE_PATH . '/' . $_FILES['receipt']['name'];
        // move uploaded file from temporary directory to our storage so file will be persistent in the app
        move_uploaded_file($_FILES['receipt']['tmp_name'], $filePath);

        echo '<pre>';
        var_dump(pathinfo($filePath));
        echo '<pre>';

    }

}