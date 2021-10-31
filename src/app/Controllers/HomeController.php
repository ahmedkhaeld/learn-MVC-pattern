<?php
declare(strict_types=1);
namespace App\Controllers;

use App\App;
use App\Models\Invoice;
use App\Models\SignUp;
use App\Models\User;
use App\View;
use PDO;


class HomeController
{
    public function index():View
    {

        $email='cr7@gmail.com';
        $name='ronaldo';
        $amount=28;

        $userModel=new User();
        $invoiceModel= new Invoice();

        $invoiceId= (new SignUp($userModel,$invoiceModel))->register(
            [
                'email'=>$email,
                'name'=>$name,
            ],
            [
                'amount'=>$amount,
            ]
        );



        return View::make('index', ['invoice'=>$invoiceModel->find($invoiceId)]);
    }

    public function download()
    {
        header('Content-Type: png');
        header('Content-Disposition: attachment; filename="a kiss.png"');

        readfile(STORAGE_PATH . '/a kiss.png');
    }

    public function upload()
    {

        // file destination in our storage
        $filePath=STORAGE_PATH . '/' . $_FILES['receipt']['name'];
        // move uploaded file from temporary directory to our storage so file will be persistent in the app
        move_uploaded_file($_FILES['receipt']['tmp_name'], $filePath);

       header('Location: /');
       exit;

    }

}