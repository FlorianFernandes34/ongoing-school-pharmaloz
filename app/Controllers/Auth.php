<?php

namespace App\Controllers;


use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Utilisateur;
use Illuminate\Container\Util;

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

    public function postLogin() {
        $session = session();
        $email = $this->request->getPost('email');
        $mdp = $this->request->getPost('mdp');

        $user = Utilisateur::where('email', $email)->first();

        if ($user != null) {
            if (password_verify($mdp, $user->password)) {
                if ($user->isAdmin($email, $mdp)) {
                    $session->set([
                        'connected' => true,
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'user_nom' => $user->nom,
                        'user_prenom' => $user->prenom,
                        'isAdmin' => true,
                    ]);
                    $session->set("isAdmin", true);
                    return redirect()->to('admin');
                }else {
                    $session->set([
                        'connected' => true,
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'user_nom' => $user->nom,
                        'user_prenom' => $user->prenom
                    ]);
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
        $mdp = $this->request->getPost('password');

        //Vérification de la non utilisation de l'eamil renseigné
        $users = Utilisateur::all();

        foreach ($users as $user) {
            if ($user->email == $email) {
                $session->setFlashdata('errorInsc', 'Cet email est déjà utilisé.');
                return redirect()->to('auth/inscription');
            }
        }

        $user = new Utilisateur();
        $user->nom = $nom;
        $user->prenom = $prenom;
        $user->email = $email;
        $user->password = password_hash($mdp, PASSWORD_DEFAULT);
        $user->role = 'client';

        if ($user->save()) {
            $session->setFlashdata('successInsc', 'Inscription réussie. Vous pouvez vous connecter.');
            return redirect()->to('auth/connexion');
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

    public function postUpdatepassword() {
        $session = session();
        $user = Utilisateur::find($session->get("user_id"));

        if (!$user) {
            $session->setFlashdata('errorPassChange', 'Une erreur est survenue, veuillez réessayer');
            return redirect()->to('auth/updatepassword');
        }

        $ancienMotDePasse = $this->request->getPost('current_password');
        $newMotDePasse = $this->request->getPost('new_password');

        if (password_verify($ancienMotDePasse, $user->password)) {
            $user->password = password_hash($newMotDePasse, PASSWORD_DEFAULT);
            if ($user->save()) {
                $session->setFlashdata('successPassChange', 'Mot de passe modifié avec succès.');
            } else {
                $session->setFlashdata('errorPassChange', 'Une erreur est survenue lors du changement du mot de passe.');
            }
        } else {
            $session->setFlashdata('errorPassChange', 'Le mot de passe entré ne correspond pas à votre mot de passe actuel.');
        }

        return redirect()->to('auth/updatepassword');
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

    public function postUpdatemail() {
        $session = session();
        $user = Utilisateur::find($session->get("user_id"));
        $allUsers = Utilisateur::all();

        if (!$user) {
            $session->setFlashdata('errorUpdateMail', 'Une erreur est survenue lors de la modification de l\'email, veuillez réessayer.');
            return redirect()->to('auth/updatemail');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        foreach ($allUsers as $oneUser) {
            if ($oneUser->email == $email) {
                $session->setFlashdata('errorUpdateMail', 'Cet email est déjà utilisé.');
                return redirect()->to('auth/updatemail');
            }
        }

        if (password_verify($password, $user->password)) {
            $user->email = $email;
            if ($user->save()) {
                $session->setFlashdata('successUpdateMail', 'Email modifié avec succès.');
                $session->set('user_email', $email);
            }else {
                $session->setFlashdata('errorUpdateMail', 'Une erreur est survenue lors de la modification de l\'email, veuillez réessayer.');
            }
        }else {
            $session->setFlashdata('errorUpdateMail', 'Le mot de passe entré ne correspond pas à votre mot de passe.');
        }
        return redirect()->to('auth/updatemail');
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

    public function postUpdateinfos() {
        $session = session();

        $user = Utilisateur::find($session->get('user_id'));

        if (!$user) {
            $session->setFlashdata('errorUpdateInfos', 'Une erreur est survenue lors de la modification de vos informations, veuillez réessayer.');
            return redirect()->to('auth/updatemail');
        }

        $nom = $this->request->getPost('nom');
        $prenom = $this->request->getPost('prenom');
        $password = $this->request->getPost('password');

        if (password_verify($password, $user->password)) {
            $user->nom = $nom;
            $user->prenom = $prenom;
            if ($user->save()) {
                $session->setFlashdata('successUpdateInfos', 'Vos informations ont bien été modifées.');
                $session->set('user_nom', $nom);
                $session->set('user_prenom', $prenom);
            }else {
                $session->setFlashdata('errorUpdateInfos', 'Une erreur est survenue lors de la modification de vos informations, veuillez réessayer.');
            }
        }else {
            $session->setFlashdata('errorUpdateInfos', 'Le mot de passe entré ne correspond pas à votre mot de passe.');
        }
        return redirect()->to('auth/updateinfos');
    }

    public function getLogout() {
        $session = session();

        $session->destroy();

        $session->setFlashdata('successLogout', 'Vous avez bien été déconnecté');
        return redirect()->to('auth/connexion');
    }

    public function getDeleteaccountdata() {
        $session = session();

        $user = Utilisateur::find($session->get("user_id"));

        if (!$user) {
            $session->setFlashdata('errorDeleteData', 'Une erreur est survenue. Veuillez réessayer.');
            return redirect()->to('auth/account');
        }

        try {
            $user->commandes()->delete();
            $user->delete();
            $session->setFlashdata('successDeleteData', 'Compte et données supprimée avec succès.');
        }catch (\Error $e) {
            $session->setFlashdata('errorDeleteData', 'Une erreur est survenue. Veuillez réessayer.');
            return redirect()->to('auth/account');
        }

        $session->destroy();

        return redirect()->to('auth/connexion');
    }


}