<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Produit;

class Panier extends BaseController
{
    public function getIndex()
    {
        $panier = session()->get('panier');
        $produits = [];
        $total = 0;

        if ($panier) {
            foreach ($panier as $idProd => $prod) {
                $produit = Produit::find($idProd);
                $total += $produit->prix * $prod['qte'];

                if ($produit) {
                    $produits[] = [
                        'produit' => $produit,
                        'qte' => $prod['qte']
                    ];
                }

            }
        }

        $data = [
            "activePage" => 'home',
            "categories" => Categorie::all(),
            "produits" => $produits,
            "total" => number_format($total, 2, ',', ' '),
            "page" => 'Accueil'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('panier/voirpanier', $data)
            . view('template/footer', $data);
    }

    private function countNumberProducts() {
        $panier = session()->get('panier', []);
        $nbProduits = 0;

        foreach ($panier as $prod) {
            $nbProduits += $prod['qte'];
        }

        return $nbProduits;
    }

    public function postAjouterpanier() {
        $idProd = $this->request->getPost('idProduit');

        $produit = Produit::find($idProd);

        if (!$produit) {
            return json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer.'
            ]);
        }

        $panier = session()->get('panier', []);

        if (isset($panier[$idProd])) {
            $qte = $panier[$idProd]['qte'];
            if ($qte++ >= $produit->stock) {
                return json_encode([
                    'success' => false,
                    'message' => 'La quantité demandée dépasse le stock disponible.'
                ]);
            }
            $panier[$idProd]['qte']++;
            session()->set('panier', $panier);
        }else {
            $panier[$idProd] = [
                'idProd' => $idProd,
                'qte' => 1
            ];
            session()->set('panier', $panier);
        }

        // Set number of products in session
        session()->set('cartCount', $this->countNumberProducts());

        return json_encode([
            'success' => true,
            'message' => 'Produit ajouté au panier'
        ]);
    }

    public function getUpdatepaniernumberofproducts() {
        return json_encode([
            'numberOfProducts' => $this->countNumberProducts()
        ]);
    }

    public function postRetirerunitepanier() {
        $idProd = $this->request->getPost('idProduit');

        $isEmpty = false;

        $produit = Produit::find($idProd);

        if (!$produit) {
            return json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer.'
            ]);
        }

        $panier = session()->get('panier');

        if (isset($panier[$idProd])) {
            $qte = $panier[$idProd]['qte'];
            if ($qte-- <= 1) {
                unset($panier[$idProd]);
                $isEmpty = true;
            }else {
                $panier[$idProd]['qte']--;
            }
            session()->set('panier', $panier);
            session()->set('cartCount', $this->countNumberProducts());
        }else {
            return json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer.'
            ]);
        }

        return json_encode([
            'success' => true,
            'isEmpty' => $isEmpty,
            'prixProduit' => number_format($produit->prix, 2, ',', ' '),
            'message' => 'Une unité de ce produit a bien été retirée.'
        ]);
    }

    public function postAjouterunitepanier() {
        $idProd = $this->request->getPost('idProduit');

        $produit = Produit::find($idProd);

        if (!$produit) {
            return json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer.'
            ]);
        }

        $panier = session()->get('panier');

        if (isset($panier[$idProd])) {
            $qte = $panier[$idProd]['qte'];
            if ($qte++ >= $produit->stock) {
                return json_encode([
                    'success' => false,
                    'message' => 'La quantité demandée dépasse le stock disponible.'
                ]);
            }else {
                $panier[$idProd]['qte']++;
            }
            session()->set('panier', $panier);
            session()->set('cartCount', $this->countNumberProducts());
        }else {
            return json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer.'
            ]);
        }

        return json_encode([
            'success' => true,
            'prixProduit' => number_format($produit->prix, 2, ',', ' '),
            'message' => 'Une unité de ce produit a bien été ajoutée.'
        ]);
    }

    public function postSupprimerdupanier() {
        $idProd = $this->request->getPost('idProduit');

        $produit = Produit::find($idProd);

        if (!$produit) {
            return json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer.'
            ]);
        }

        $panier = session()->get('panier');

        $totalPriceBeforeRemove = $produit->prix * $panier[$idProd]['qte'];
        unset($panier[$idProd]);

        session()->set('panier', $panier);
        session()->set('cartCount', $this->countNumberProducts());

        return json_encode([
            'success' => true,
            'totalPriceBeforeRemove' => number_format($totalPriceBeforeRemove, 2, ',', ' '),
            'message' => 'Le produit a bien été retiré du panier.'
        ]);
    }
}
