<?php

namespace App\Controllers;


use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Utilisateur;

class Auth extends BaseController {

    public function getConnexion()
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
                    return redirect()->to('auth/account');
                }
            }else {
                $session->setFlashdata('errorLogin', 'Email ou mot de passe incorrect');
                return redirect()->to('auth/connexion');
            }
        }else {
            $session->setFlashdata('errorLogin', 'Email ou mot de passe incorrect');
            return redirect()->to('auth/connexion');
        }
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

    public function postInscription() {
        $session = session();
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

        if ($user->save()) {
            $session->setFlashdata('successInsc', 'Vous avez bien été inscrit, vous pouvez maintenant vous connecter.');
        }else {
            $session->setFlashdata('errorInsc', 'Une erreur est survenue lors de votre inscription. Veuillez réessayer.');
        }
        $user->save();

        return redirect()->to('auth/inscription');
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

    public function getUpdateinfos() {
        $data = [
            "activePage" => 'home',
            'categories' => Categorie::all(),
            "page" => 'Modifier mot de passe'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('auth/updateinfos', $data)
            . view('template/footer', $data);
    }


    public function getLogout() {
        $session = session();

        $session->remove('user');

        if ($session->has('isAdmin')) {
            $session->remove('isAdmin');
        }

        $session->setFlashdata('successLogout', 'Vous avez bien été déconnecté');
        return redirect()->to('auth/connexion');
    }


}