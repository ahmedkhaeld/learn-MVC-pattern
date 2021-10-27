<?php
declare(strict_types=1);
namespace App\Controllers;

use App\View;
use PDO;


class HomeController
{
    public function index():View
    {
        try {
            $db = new PDO('mysql:host=db;dbname=my_db', 'root', 'root');

            $email='hadi@gmail.com';
            $name='hadi';
            $isActive=1;
            $createdAt=date('Y-m-d H:i:s', strtotime('10/11/2021 12:34PM'));

            $query='INSERT INTO user (email, full_name, is_active, created_at) 
                  VALUES (:email, :name, :active, :date)';
            $stmt=$db->prepare($query);
            $stmt->bindParam('name', $name);
            $stmt->bindParam('email', $email);
            $stmt->bindParam('active', $isActive, PDO::PARAM_BOOL);
            $stmt->bindParam('date', $createdAt);
            $isActive=0;

            $stmt->execute();


            $id=(int) $db->lastInsertId();


            $users=$db->query('SELECT * FROM user WHERE id='.$id)->fetch();

            foreach($users as $user){
                echo '<pre>';
                var_dump($user);
                echo '<pre>';
            }


        }catch (\PDOException $e){
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
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