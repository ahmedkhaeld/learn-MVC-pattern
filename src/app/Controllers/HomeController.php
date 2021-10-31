<?php
declare(strict_types=1);
namespace App\Controllers;

use App\App;
use App\View;
use PDO;


class HomeController
{
    public function index():View
    {
        $db=App::db();
        $db2=App::db();
        $db3=App::db();
        var_dump($db===$db2, $db2===$db3,$db===$db3);
        exit;

        $email='paul@gmail.com';
        $name='paul';
        $amount=28;

        try {
            $db->beginTransaction();

            $newUserStmt = $db->prepare(
            'INSERT INTO user (email, full_name, is_active, created_at)
                   VALUES (?, ?, 1,NOW())'
            );

            $newInvoiceStmt = $db->prepare(
                'INSERT INTO invoice (amount, user_id)
               VALUES (?,?)'
            );

            $newUserStmt->execute([$email, $name]);
            $userId = (int)$db->lastInsertId();

            $newInvoiceStmt->execute([$amount, $userId]);

            $db->commit();
        }catch (\Throwable $e){
            if($db->inTransaction()) {
                $db->rollBack();
            }
        }


        $fetchStmt=$db->prepare(
        'SELECT invoice.id AS invoice_id, amount, user_id, full_name
              FROM invoice
              INNER JOIN  user ON user_id=user.id
              WHERE email=?'
        );

        $fetchStmt->execute([$email]);

        echo '<pre>';
        var_dump($fetchStmt->fetch(PDO::FETCH_ASSOC));
        echo '<pre>';





        return View::make('index');
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