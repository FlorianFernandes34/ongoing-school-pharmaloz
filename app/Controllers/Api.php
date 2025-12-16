<?php

namespace App\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use DateTime;

class Api extends BaseController {
    public function postUpdatestatut()
    {
        $id = $this->request->getPost('idCommande');
        $nouveauStatut = $this->request->getPost('statutCommande');

        $commande = Commande::find($id);

        if (!$commande) {
            return json_encode([
                'success' => false]);
        }

        $commande->statut = $nouveauStatut;

        if ($commande->save()) {
            return json_encode([
                'success' => true,
            ]);
        }else {
            return json_encode([
                'success' => false,
            ]);
        }
    }

    public function postSearchcommandes() {
        $type = $this->request->getPost('type');
        $query = trim($this->request->getPost('query'));


        // Requête de base avec relations chargées
        $commandes = Commande::with('utilisateur', 'produits');

        if ($query === '') {
            // Champ vide → toutes les commandes
            $commandes = $commandes->limit(6)->get();
        } else {
            switch ($type) {
                case 'id':
                    $cmd = $commandes->where('id', $query)->first();
                    $commandes = $cmd ? collect([$cmd]) : collect([]);
                    break;
                case 'email':
                    $commandes = $commandes->whereHas('utilisateur', function($q) use($query) {
                        $q->where('email', 'LIKE', "%$query%");
                    })->get();
                    break;
                case 'client':
                    $commandes = $commandes->whereHas('utilisateur', function($q) use($query) {
                        // on cherche nom OU prénom correspondant
                        $q->where('nom', 'LIKE', "%$query%")
                            ->orWhere('prenom', 'LIKE', "%$query%");
                    })->get();
                    break;
                case 'statut':
                    $commandes = $commandes->where('statut', 'LIKE', "%$query%")->get();
                    break;
                default:
                    $commandes = collect([]);
                    break;
            }
        }

        // Préparer le JSON
        $result = [];
        foreach ($commandes as $cmd) {
            //Date au format francais
            $dateCommande = new DateTime($cmd->date_heure);
            $dateFrCommande = $dateCommande->format('d/m/Y H:i');
            $dateRetrait = new DateTime($cmd->creneau_retrait);
            $dateFrRetrait = $dateRetrait->format('d/m/Y H:i');

            $result[] = [
                'id' => $cmd->id,
                'statut' => $cmd->statut,
                'date_heure' => $dateFrCommande,
                'prix_total' => $cmd->produits->sum(fn($p) => $p->prix * $p->pivot->quantite),
                'creneau_retrait' => $dateFrRetrait,
                'utilisateur' => [
                    'nom' => $cmd->utilisateur->nom,
                    'prenom' => $cmd->utilisateur->prenom,
                    'email' => $cmd->utilisateur->email
                ]
            ];
        }

        return $this->response->setJSON(['commandes' => $result, 'success' => true]);
    }

    public function getInfosproduits($id) {
        if (!$id) {
            return json_encode([
                'success' => false
            ]);
        }

        $produit = Produit::find($id);

        if (!$produit) {
            return json_encode([
                'success' => false
            ]);
        }

        return json_encode([
            'success' => true,
            'denomination' => $produit->nom,
            'puProduit' => $produit->prix,
        ]);
    }

    public function getUpdatequantitearticlefromcommande($commandeID, $produitId, $newQte) {
        $commande = Commande::find($commandeID);

        if (!$commande) {
            return json_encode([
                'success' => false
            ]);
        }

        $produit = Produit::find($produitId);

        if (!$produit) {
            return json_encode([
                'success' => false
            ]);
        }

        $commande->produits()->where('produits.id', $produitId)->update(['quantite' => $newQte]);

        if ($commande->save()) {
            return json_encode([
                'success' => true
            ]);
        }else {
            return json_encode([
                'success' => false
            ]);
        }
    }

    public function getDeletearticlefromcommande($commandeId, $produitId)
    {
        $commande = Commande::find($commandeId);

        if (!$commande) {
            return json_encode([
                'success' => false
            ]);
        }

        $produit = Produit::find($produitId);

        if (!$produit) {
            return json_encode([
                'success' => false
            ]);
        }

        $commande->produits()->detach($produitId);

        if ($commande->produits()->count() === 0) {
            $commande->statut = "Annulée";
            $commande->save();
        }

        return json_encode([
            'success' => true
        ]);

    }
}