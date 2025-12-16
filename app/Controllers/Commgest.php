<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Produit;

class Commgest extends BaseController {
    public function getListecom($numPage) {
        // Page actuelle depuis l'URL, par défaut 1
        $page = $numPage ?? 1;
        $perPage = 6; // commandes par page
        $offset = ($page - 1) * $perPage;

        // Nombre total de produits
        $total = Commande::count();

        // Récupération des produits pour la page actuelle
        $commandes = Commande::skip($offset)->take($perPage)->get();

        // Nombre de pages total
        $lastPage = ceil($total / $perPage);

        $data = [
            "categories" => Categorie::all(),
            "commandes" => $commandes,
            "page" => 'Liste des produits',
            "currentPage" => $page,
            "lastPage" => $lastPage
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/commandes/listecommandes', $data)
            . view('template/footer', $data);
    }

    public function getListeproduitscomm($id) {
        $session = session();
        $commande = Commande::find($id);

        if (!$commande) {
            $session->setFlashdata('errorCommande', 'Cette commande n\'existe pas.');
            return redirect()->back();
        }

        $data = [
            "categories" => Categorie::all(),
            "commande" => $commande,
            "page" => 'Liste des produits',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/commandes/listeproduitscommande', $data)
            . view('template/footer', $data);
    }

    public function getAjouterproduitcommande($id) {
        $commande = Commande::find($id);
        $produits = Produit::all();

        $data = [
            "categories" => Categorie::all(),
            "commande" => $commande,
            "produits" => $produits,
            "page" => 'Liste des produits',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/commandes/ajouterproduitcommande', $data)
            . view('template/footer', $data);
    }

    public function postAjoutproduitcommande() {
        $session = session();
        $idC = $this->request->getPost('idCommande');
        $idP = $this->request->getPost('produitSelect');
        $quantite = $this->request->getPost('qte');

        $commande = Commande::find($idC);

        if (!$commande) {
            $session->setFlashdata('errorAddPC', 'Cette commande n\'existe pas.');
            return redirect()->back();
        }

        $produit = Produit::find($idP);

        if (!$produit) {
            $session->setFlashdata('errorAddPC', 'Ce produit n\'existe pas.');
            return redirect()->to('admin/ajouterproduitcommande/' . $idC);
        }

        if ($commande->produits()->where('produit_id', $idP)->exists()) {
            $session->setFlashdata('errorAddPC', 'Ce produit est déjà dans la commande. Modifiez la quantité ou supprimez-le.');
            return redirect()->to('commgest/ajouterproduitcommande/' . $idC);
        }

        $commande->produits()->attach($produit, ['quantite' => $quantite]);

        if ($commande->save()) {
            $session->setFlashdata('successAddPC', 'Le produit a été ajouté avec succès.');
        }else {
            $session->setFlashdata('errorAddPC', 'Une erreur est survenue lors de l\'ajout du produit. Veuillez réessayer.');
        }

        return redirect()->to('commgest/ajouterproduitcommande/' . $idC);
    }
}