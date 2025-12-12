<?php

namespace App\Controllers;

use App\Models\Utilisateur;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Commande;

class Test extends BaseController
{

    public function getIndex() {

        echo "<h1>🧪 TestController – Menu de tests</h1>";
        echo "<p>Choisir une méthode à tester :</p>";

        /* ------------------- 👤 UTILISATEURS ------------------- */
        echo "<h2>👤 Utilisateurs</h2><ul>";
        echo "<li>" . anchor('test/testutilisateurread', '📄 Lire tous les utilisateurs') . "</li>";
        echo "<li>" . anchor('test/testutilisateurreadid/1', '🔎 Lire utilisateur par ID') . "</li>";
        echo "<li>" . anchor('test/testutilisateurcreate', '➕ Créer un utilisateur') . "</li>";
        echo "<li>" . anchor('test/testutilisateurupdate/1/nouveauNom', '✏️ Mettre à jour un utilisateur') . "</li>";
        echo "<li>" . anchor('test/testutilisateurdelete/1', '🗑️ Supprimer un utilisateur') . "</li>";
        echo "</ul>";

        /* ------------------- 🏷️ CATEGORIES ------------------- */
        echo "<h2>🏷️ Catégories</h2><ul>";
        echo "<li>" . anchor('test/testcategorieread', '📄 Lire toutes les catégories') . "</li>";
        echo "<li>" . anchor('test/testcategoriereadid/1', '🔎 Lire une catégorie par ID') . "</li>";
        echo "<li>" . anchor('test/testcategoriecreate', '➕ Créer une catégorie') . "</li>";
        echo "<li>" . anchor('test/testcategorieupdate/1/nouvelleCategorie', '✏️ Mettre à jour une catégorie') . "</li>";
        echo "<li>" . anchor('test/testcategoriedelete/1', '🗑️ Supprimer une catégorie') . "</li>";
        echo "</ul>";

        /* ------------------- 📦 PRODUITS ------------------- */
        echo "<h2>📦 Produits</h2><ul>";
        echo "<li>" . anchor('test/testproduitread', '📄 Lire tous les produits') . "</li>";
        echo "<li>" . anchor('test/testproduitreadid/1', '🔎 Lire un produit par ID') . "</li>";
        echo "<li>" . anchor('test/testproduitcreate', '➕ Créer un produit') . "</li>";
        echo "<li>" . anchor('test/testproduitupdate/1/99', '✏️ Modifier le stock d’un produit') . "</li>";
        echo "<li>" . anchor('test/testproduitdelete/1', '🗑️ Supprimer un produit') . "</li>";
        echo "</ul>";

        /* ------------------- 📜 COMMANDES ------------------- */
        echo "<h2>📜 Commandes</h2><ul>";
        echo "<li>" . anchor('test/testcommanderead', '📄 Lire toutes les commandes') . "</li>";
        echo "<li>" . anchor('test/testcommandereadid/1', '🔎 Lire une commande par ID') . "</li>";
        echo "<li>" . anchor('test/testcommandecreate', '➕ Créer une commande') . "</li>";
        echo "<li>" . anchor('test/testcommandeupdate/1/validée', '✏️ Mettre à jour une commande') . "</li>";
        echo "<li>" . anchor('test/testcommandedelete/1', '🗑️ Supprimer une commande') . "</li>";
        echo "<li>" . anchor('test/testcommandeaddproduit/1/1/2', '➕ Ajouter un produit à une commande') . "</li>";
        echo "<li>" . anchor('test/testcommandeproduits/1', '📦 Voir les produits d’une commande') . "</li>";
        echo "<li>" . anchor('test/testcommanderemoveproduit/1/1', '🗑️ Retirer un produit d’une commande') . "</li>";
        echo "</ul>";

    }

    /* ------------------- 🧑 UTILISATEURS ------------------- */

    // READ tous les utilisateurs
    public function getTestutilisateurread()
    {
        $utilisateurs = Utilisateur::all();
        echo "<h2>Liste des utilisateurs :</h2>";
        foreach ($utilisateurs as $u) {
            echo "{$u->id} - {$u->nom} {$u->prenom} ({$u->mail}) [{$u->role}]<br>";
        }
    }

    // READ utilisateur par ID
    public function getTestutilisateurreadid($id)
    {
        $user = Utilisateur::find($id);
        echo $user ? "✅ {$user->nom} {$user->prenom}" : "❌ Utilisateur non trouvé";
    }

    // CREATE utilisateur
    public function getTestutilisateurcreate()
    {
        $u = new Utilisateur();
        $u->nom = "Dupont";
        $u->prenom = "Jean";
        $u->email = "jean.dupont@example.com";
        $u->password = password_hash("secret", PASSWORD_BCRYPT);
        $u->role = "client";
        //$u->save();

        echo $u->exists ? "✅ Utilisateur ajouté" : "❌ Erreur d'insertion";
    }

    // UPDATE utilisateur
    public function getTestutilisateurupdate($id, $nouveauNom)
    {
        $u = Utilisateur::find($id);
        if (!$u) return "❌ Non trouvé";
        $u->nom = $nouveauNom;
        //$u->save();
        echo $u->wasChanged() ? "✅ Nom modifié" : "❌ Aucun changement";
    }

    // DELETE utilisateur
    public function getTestutilisateurdelete($id)
    {
        $u = Utilisateur::find($id);
        if (!$u) return "❌ Non trouvé";
        //$u->delete();
        echo !$u->exists ? "✅ Supprimé" : "❌ Erreur";
    }

    /* ------------------- 🏷️ CATEGORIES ------------------- */

    public function getTestcategorieread()
    {
        $categories = Categorie::all();
        echo "<h2>Catégories :</h2>";
        foreach ($categories as $c) {
            echo "{$c->id} - {$c->nom}<br>";
        }
    }

    public function getTestcategoriereadid($id)
    {
        $c = Categorie::find($id);
        echo $c ? "✅ {$c->nom}" : "❌ Catégorie introuvable";
    }

    public function getTestcategoriecreate()
    {
        $c = new Categorie();
        $c->nom = "Beauté";
        //$c->save();
        echo $c->exists ? "✅ Catégorie ajoutée" : "❌ Erreur";
    }

    public function getTestcategorieupdate($id, $nouveauNom)
    {
        $c = Categorie::find($id);
        if (!$c) return "❌ Introuvable";
        $c->nom = $nouveauNom;
        //$c->save();
        echo $c->wasChanged() ? "✅ Modifié" : "❌ Aucun changement";
    }

    public function getTestcategoriedelete($id)
    {
        $c = Categorie::find($id);
        if (!$c) return "❌ Introuvable";
        //$c->delete();
        echo !$c->exists ? "✅ Supprimée" : "❌ Erreur";
    }

    /* ------------------- 📦 PRODUITS ------------------- */

    public function getTestproduitread()
    {
        $produits = Produit::all();
        foreach ($produits as $p) {
            echo "{$p->id} - {$p->nom} ({$p->prix}€) - Catégorie : {$p->categorie->nom}<br>";
        }
    }

    public function getTestproduitreadid($id)
    {
        $p = Produit::find($id);
        echo $p ? "✅ {$p->nom} - {$p->prix}€" : "❌ Produit introuvable";
    }

    public function getTestproduitcreate()
    {
        $p = new Produit();
        $p->nom = "Crème hydratante";
        $p->description = "Hydrate la peau";
        $p->prix = 19.99;
        $p->image = "image.jpg";
        $p->stock = 50;
        $categorie = Categorie::where('nom', 'Hygiène')->first();
        $p->categorie()->associate($categorie);
        //$p->save();

        echo $p->exists ? "✅ Produit créé" : "❌ Erreur";
    }

    public function getTestproduitupdate($id, $nouveauStock)
    {
        $p = Produit::find($id);
        if (!$p) return "❌ Introuvable";
        $p->stock = $nouveauStock;
        //$p->save();
        echo $p->wasChanged() ? "✅ Stock modifié" : "❌ Aucun changement";
    }

    public function getTestproduitdelete($id)
    {
        $p = Produit::find($id);
        if (!$p) return "❌ Introuvable";
        //$p->delete();
        echo !$p->exists ? "✅ Supprimé" : "❌ Erreur";
    }

    /* ------------------- 📜 COMMANDES ------------------- */

    public function getTestcommanderead()
    {
        $commandes = Commande::all();
        foreach ($commandes as $c) {
            echo "{$c->id} - {$c->statut} - {$c->date_heure} - Utilisateur : {$c->utilisateur->nom}<br>";
        }
    }

    public function getTestcommandereadid($id)
    {
        $c = Commande::find($id);
        echo $c ? "✅ Commande {$c->id} - {$c->statut}" : "❌ Introuvable";
    }

    public function getTestcommandecreate()
    {
        $c = new Commande();
        $c->statut = "En cours";
        $c->date_heure = "2025-09-21 09:30:00";
        $c->creneau_retrait = '2025-09-28 17:00:00';
        $utilisateur = Utilisateur::find(3);
        $c->utilisateur()->associate($utilisateur);
        $c->save();
        echo $c->exists ? "✅ Commande créée" : "❌ Erreur";
    }

    public function getTestcommandeupdate($id, $nouveauStatut)
    {
        $c = Commande::find($id);
        if (!$c) return "❌ Introuvable";
        $c->statut = $nouveauStatut;
        $c->save();
        echo $c->wasChanged() ? "✅ Modifié" : "❌ Aucun changement";
    }

    public function getTestcommandedelete($id)
    {
        $c = Commande::find($id);
        if (!$c) return "❌ Introuvable";
        $c->delete();
        echo !$c->exists ? "✅ Supprimée" : "❌ Erreur";
    }

    public function getTestcommandeaddproduit($commandeId, $produitId, $quantite)
    {
        $commande = Commande::find($commandeId);
        if (!$commande) {
            echo "❌ Commande non trouvée.";
            return;
        }

        $commande->produits()->attach($produitId, ['quantite' => $quantite]);
        echo "✅ Produit ajouté à la commande {$commandeId} avec quantité {$quantite}";
    }

    public function getTestcommandeproduits($commandeId)
    {
        $commande = Commande::find($commandeId);
        if (!$commande) {
            echo "❌ Commande introuvable.";
            return;
        }

        echo "<h3>Produits de la commande {$commande->id} :</h3>";
        foreach ($commande->produits as $produit) {
            echo "- {$produit->nom} (Quantité : {$produit->pivot->quantite})<br>";
        }
    }

    public function getTestcommanderemoveproduit($commandeId, $produitId)
    {
        $commande = Commande::find($commandeId);
        if (!$commande) {
            echo "❌ Commande introuvable.";
            return;
        }

        $commande->produits()->detach($produitId);
        echo "✅ Produit {$produitId} retiré de la commande {$commandeId}";
    }
}
