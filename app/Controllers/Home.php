<?php

namespace App\Controllers;

use App\Models\Categorie;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            "activePage" => 'home',
            "categories" => Categorie::all(),
            "page" => 'Accueil'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('index.php', $data)
            . view('template/footer', $data);
    }
}
