<?php


namespace App\Shared;


use PHPUnit\Exception;

class Globals
{
    public function jsondecode()
    {
        try {
            return file_get_contents('php://input') ?
                json_decode(file_get_contents('php://input'), false) : [];
        } catch (Exception $e){
            return [];
        }
    }
}