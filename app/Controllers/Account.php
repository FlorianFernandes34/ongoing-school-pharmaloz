<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Utilisateur;
use DateTime;

class Account extends BaseController {

    public function getIndex() {
        $session = session();
        $userid = $session->get('user_id');
        $commandes = Commande::where("utilisateur_id", $userid)->get();

        $data = [
            "activePage" => 'home',
            'categories' => Categorie::all(),
            "page" => 'Compte',
            "user_id" => $session->get('user_id'),
            "user_nom" => $session->get('user_nom'),
            "user_prenom" => $session->get('user_prenom'),
            "user_email" => $session->get('user_email'),
            "commandes" => $commandes
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('account', $data)
            . view('template/footer', $data);
    }

    public function getUpdatemail() {
        $data = [
            "activePage" => 'home',
            'categories' => Categorie::all(),
            "page" => 'Modifier mot de passe'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('auth/updatemail', $data)
            . view('template/footer', $data);
    }

    public function getUpdatepassword() {
        $data = [
            "activePage" => 'home',
            'categories' => Categorie::all(),
            "page" => 'Modifier mot de passe'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('auth/updatepassword', $data)
            . view('template/footer', $data);
    }

    public function getUpdateinfos() {
        $session = session();
        $data = [
            "activePage" => 'home',
            "user_nom" => $session->get('user_nom'),
            "user_prenom" => $session->get('user_prenom'),
            'categories' => Categorie::all(),
            "page" => 'Modifier mot de passe'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('auth/updateinfos', $data)
            . view('template/footer', $data);
    }

    public function getVoircommande($id) {
        $commande = Commande::find($id);

        $data = [
            "activePage" => 'home',
            'categories' => Categorie::all(),
            "page" => 'Voir une commande',
            'commande' => $commande,
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('auth/voircommande', $data)
            . view('template/footer', $data);
    }
}