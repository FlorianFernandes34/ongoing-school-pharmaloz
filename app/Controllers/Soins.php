<?php

namespace App\Controllers;

use App\Models\Categorie;

class Soins extends BaseController
{
    public function getIndex()
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
