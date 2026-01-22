<?php

namespace App\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\Utilisateur;
use DateTime;

class Ajax extends BaseController {
    public function postUpdatestatut()
    {
        $id = $this->request->getPost('idCommande');
        $nouveauStatut = $this->request->getPost('statutCommande');

        $commande = Commande::find($id);

        if (!$commande) {
            return json_encode([
                'success' => false,
                'message' => 'Cette commande n\'existe pas'
            ]);
        }

        if ($commande->statut == 'Annulée') {
            if ($nouveauStatut == 'Validée') {
                return json_encode([
                    'success' => false,
                    'message' => 'Impossible de modifier le statut de cette commande car elle a été annulée.'
                ]);
            }else if ($nouveauStatut == 'Retirée') {
                return json_encode([
                    'success' => false,
                    'message' => 'Impossible de modifier le statut de cette commande car elle a été annulée.'
                ]);
            }
        }else if ($commande->statut == 'Retirée') {
            if ($nouveauStatut == 'Annulée') {
                return json_encode([
                    'success' => false,
                    'message' => 'Impossible d\'annuler une commande retirée.'
                ]);
            }else if ($nouveauStatut == 'Validée') {
                return json_encode([
                    'success' => false,
                    'message' => 'Impossible de valider une commande déjà retirée.'
                ]);
            }
        }

        $commande->statut = $nouveauStatut;

        if ($commande->save()) {
            return json_encode([
                'success' => true,
                'message' => 'Statut modifié avec succès.'
            ]);
        }else {
            return json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer.'
            ]);
        }
    }

    public function postSearchcommandes() {
        $type = $this->request->getPost('type');
        $query = trim($this->request->getPost('query'));


        // Requête de base avec relations chargées
        $commandes = Commande::with('utilisateur', 'produits');

        if ($query === '') {
            return $this->response->setJSON([
                'redirect' => base_url('commgest/listecom/1')
            ]);
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
            $date_commande = $cmd->date_heure->format('d/m/Y H:i');
            $date_retrait = $cmd->creneau_retrait->format('d/m/Y H:i');
            $result[] = [
                'id' => $cmd->id,
                'statut' => $cmd->statut,
                'date_heure' => $date_commande,
                'prix_total' => number_format($cmd->produits->sum(fn($p) => $p->prix * $p->pivot->quantite), 2, ',', ' '),
                'creneau_retrait' => $date_retrait,
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

    public function getSupprimercompte($id) {
        if (!$id) {
            return json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer.'
            ]);
        }

        $accountNumber = Utilisateur::where('role', 'admin')->count();

        if ($accountNumber === 1) {
            return json_encode([
                'success' => false,
                'message' => 'Impossible de supprimer le dernier compte administrateur.'
            ]);
        }

        $compte = Utilisateur::find($id);

        if (!$compte) {
            return json_encode([
                'success' => false,
                'message' => 'Ce compte n\'existe pas.'
            ]);
        }

        if ($compte->delete()) {
            return json_encode([
                'success' => true,
                'message' => 'Compte supprimé avec succès.'
            ]);
        }else {
            return json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression du compte. Veuillez réessayer.'
            ]);
        }
    }
}