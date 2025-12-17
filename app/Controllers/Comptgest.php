<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Utilisateur;

class Comptgest extends BaseController {

    public function getListecomptes() {
        $data = [
            "categories" => Categorie::all(),
            "comptes" => Utilisateur::all()->where("role", "admin"),
            "page" => 'Liste des catégories',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/comptes/listecomptes', $data)
            . view('template/footer', $data);
    }

    public function getAjoutercompte() {
        $data = [
            "categories" => Categorie::all(),
            "page" => 'Liste des catégories',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/comptes/ajoutcompte', $data)
            . view('template/footer', $data);
    }

    public function postAjoutercompte() {
        $session = session();
        $nom = $this->request->getPost("nom");
        $prenom = $this->request->getPost("prenom");
        $email = $this->request->getPost("email");
        $password = $this->request->getPost("password");

        $utilisateur = new Utilisateur();
        $utilisateur->nom = $nom;
        $utilisateur->prenom = $prenom;
        $utilisateur->email = $email;
        $utilisateur->password = password_hash($password, PASSWORD_DEFAULT);
        $utilisateur->role = 'admin';

        if ($utilisateur->save()) {
            $session->setFlashdata('successAddAccount', 'Compte administrateur ajouté avec succès.');
        }else {
            $session->setFlashdata('errorAddAcount', 'Une erreur est survenue lors de l\'ajout du compte, veuillez réessayer.');
        }
        return redirect()->back();
    }
}