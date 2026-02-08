<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Utilisateur;
use DateTime;

class Passagecommande extends BaseController {

    private function genererCreneaux($jours = 15, $pasMinutes = 5) {
        $creneaux = [];

        $now = new DateTime('now', new \DateTimeZone('Europe/Paris'));

        for ($d = 0; $d < $jours; $d++) {
            $date = (clone $now)->modify("+$d day");

            if ($date->format('W') == 0) continue;

            $ouverture = (clone $date)->setTime(9, 0);
            $fermeture = (clone $date)->setTime(19, 0);

            while ($ouverture < $fermeture) {
                if ($ouverture > $now) {
                    $creneaux[] = clone $ouverture;
                }

                $ouverture->modify("+$pasMinutes minute");
            }
        }

        return $creneaux;
    }
    public function getIndex()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('auth/connexion')->with('messageCommandeMessage', 'Veuillez vous connecter ou créer un compte pour passer commande.');
        }

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
        }else {
            return redirect()->to('/');
        }

        $data = [
            "activePage" => 'home',
            "categories" => Categorie::all(),
            "produits" => $produits,
            "total" => number_format($total, 2, ',', ' '),
            "creneaux" => $this->genererCreneaux(),
            "page" => 'Accueil'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('passagecommande/passercommande', $data)
            . view('template/footer', $data);
    }

    public function postTraitementcommande() {
        $creneau = $this->request->getPost('creneau_retrait');
        $commentaire = $this->request->getPost('commentaire');

        if ($commentaire == "") {
            $commentaire = 'Aucun commentaire';
        }

        $produits = session()->get('panier');

        $utilisateur = Utilisateur::find(session()->get('user_id'));

        if (!$utilisateur) {
            return redirect()->to('passagecommannde')->with('errorPassageCommande', 'Une erreur est survenue, veuillez réessayer.');
        }

        $commande = Commande::create([
            'statut' => 'En cours',
            'date_heure' => date('Y-m-d H:i:s'),
            'creneau_retrait' => $creneau,
            'commentaire' => $commentaire,
            'utilisateur_id' => $utilisateur->id
        ]);

        // Modification sotck_reserve des produits
        foreach ($produits as $idProd => $prod) {
            $produit = Produit::find($idProd);

            $produit->update([
                'stock_reserve' => ($produit->stock_reserve ?? 0) + $prod['qte']
            ]);
        }

        // Ajout des produits à la commande
        foreach ($produits as $idProd => $prod) {
            $commande->produits()->attach($idProd, ['quantite' => $prod['qte']]);
        }

        session()->set('cartCount', 0);
        session()->remove('panier');
        return redirect()->to('passagecommande/successcommande');
    }

    public function getSuccesscommande() {

        $data = [
            "activePage" => 'home',
            "categories" => Categorie::all(),
            "page" => 'Commande effectuée'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('passagecommande/successcommande', $data)
            . view('template/footer', $data);
    }
}
