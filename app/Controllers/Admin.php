<?php

namespace App\Controllers;

use App\Models\Categorie;

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
