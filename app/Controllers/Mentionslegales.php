<?php

namespace App\Controllers;

use App\Models\Categorie;

class Mentionslegales extends BaseController {
    public function getIndex() {
        $data = [
            "activePage" => 'home',
            'categories' => Categorie::all(),
            "page" => 'Mentions Légales',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('mentionslegales', $data)
            . view('template/footer', $data);
    }

}