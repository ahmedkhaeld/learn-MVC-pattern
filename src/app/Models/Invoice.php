<?php

namespace App\Models;

use App\Model;

class Invoice extends Model
{

    public function create(float $amount , int $userId):int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO invoice (amount, user_id)
               VALUES (?,?)'
        );

        $stmt->execute([$amount, $userId]);
        return (int) $this->db->lastInsertId();


    }

    public function find(int $invoiceId):array
    {
        $stmt=$this->db->prepare(
            'SELECT invoice.id, amount, full_name
            FROM invoice
            LEFT JOIN user ON user.id=user_id
            WHERE invoice.id=?'
        );

        $stmt->execute([$invoiceId]);
        $invoice=$stmt->fetch();
        return $invoice ? $invoice: [];

    }
}