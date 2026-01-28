<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Produit;

class Produits extends BaseController
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

    public function getProduitsliste($categorie) {
        $produits = null;
        $categories = Categorie::all();
        if ($categorie == 'all') {
            $produits = Produit::all();
        }else {
            $categorieProd = Categorie::where('nom_lien', $categorie)->first();
            $produits = Produit::where('categorie_id', $categorieProd->id)->get();
        }

        $data = [
            'produits' => $produits,
            'categories' => $categories,
            'currentCat' => $categorie,
            'activePage' => 'home',
            'page' => 'Nos produits'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('produits/voirproduits', $data)
            . view('template/footer', $data);
    }
}
