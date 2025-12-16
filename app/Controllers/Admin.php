<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Produit;
use DateTime;

class Admin extends BaseController
{
    public function getIndex() {
        $data = [
            "categories" => Categorie::all(),
            "page" => 'Admin'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/admin.php', $data)
            . view('template/footer', $data);
    }

}
