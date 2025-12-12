<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Utilisateur;

class Login extends BaseController
{

    public function getIndex()
    {
        $data = [
            "activePage" => 'home',
            'categories' => Categorie::all(),
            "page" => 'Connexion'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('auth/connexion', $data)
            . view('template/footer', $data);
    }

    public function getInscription() {
        $data = [
            "activePage" => 'home',
            'categories' => Categorie::all(),
            "page" => 'Inscription'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('auth/inscription', $data)
            . view('template/footer', $data);
    }

    public function getAccount() {
        $session = session();
        $userid = $session->get("user")->id;
        $commandes = Commande::where("utilisateur_id", $userid)->get();

        $data = [
            "activePage" => 'home',
            'categories' => Categorie::all(),
            "page" => 'Compte',
            "user" => $session->get('user'),
            "commandes" => $commandes
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('account', $data)
            . view('template/footer', $data);
    }

    public function postLogin() {
        $session = session();
        $email = $this->request->getPost('email');
        $mdp = $this->request->getPost('mdp');

        $user = Utilisateur::where('email', $email)->first();

        if ($user != null) {
            if (password_verify($mdp, $user->password)) {
                if ($user->isAdmin($email, $mdp)) {
                    $session->set("user", $user);
                    $session->set("isAdmin", true);
                    return redirect()->to('admin');
                }else {
                    $session->set('user', $user);
                    return redirect()->to('login/account');
                }
            }else {
                $session->setFlashdata('errorLogin', 'Email ou mot de passe incorrect');
                return redirect()->to('login');
            }
        }else {
            $session->setFlashdata('errorLogin', 'Email ou mot de passe incorrect');
            return redirect()->to('login');
        }
    }

    public function postInscription() {
        $nom = $this->request->getPost('nom');
        $prenom = $this->request->getPost('prenom');
        $email = $this->request->getPost('email');
        $mdp = $this->request->getPost('mdp');

        $user = new Utilisateur();
        $user->nom = $nom;
        $user->prenom = $prenom;
        $user->email = $email;
        $user->password = password_hash($mdp, PASSWORD_DEFAULT);
        $user->role = 'client';
        $user->save();

        return redirect()->to('login/inscription');
    }

    public function getLogout() {
        $session = session();

        $session->remove('user');
        if ($session->has('isAdmin')) {
            $session->remove('isAdmin');
        }

        $session->setFlashdata('successLogout', 'Vous avez bien été déconnecté');
        return redirect()->to('login');
    }
}
