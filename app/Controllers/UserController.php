<?php

namespace App\Controllers;

use PDO;
use App\Models\User;

class UserController
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index($response)
    {
        $users = $this->db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_CLASS, User::class);
        $data = [];
        foreach($users as $user){
            $u = new User();
            foreach($user as $key => $value){
                $u->$key = $value;
            }
            $data[] = $u;
        }
        return print_r($data);
//        return $response->withJson($data);

    }
    public function one($response)
    {
        $users = $this->db->query("SELECT * FROM users LIMIT 1")->fetchAll(PDO::FETCH_CLASS, User::class);
        $data = [];
        foreach($users as $user){
            $u = new User();
            foreach($user as $key => $value){
                $u->$key = $value;
            }
            $data[] = $u;
        }
        return print_r($data);
//        return $response->withJson($data);

    }
}
