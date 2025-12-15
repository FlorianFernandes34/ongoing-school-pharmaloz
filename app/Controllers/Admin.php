<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Produit;
use DateTime;

class Admin extends BaseController
{
    /*
     * GESTION DE PRODUITS
     */
    public function getIndex() {
        $data = [
            "categories" => Categorie::all(),
            "page" => 'Admin'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/admin.php', $data)
            . view('template/footer', $data);
    }

    public function getListeproduits($numPage)
    {
        // Page actuelle depuis l'URL, par défaut 1
        $page = $numPage ?? 1;
        $perPage = 8; // produits par page
        $offset = ($page - 1) * $perPage;

        // Nombre total de produits
        $total = Produit::count();

        // Récupération des produits pour la page actuelle
        $produits = Produit::skip($offset)->take($perPage)->get();

        // Nombre de pages total
        $lastPage = ceil($total / $perPage);

        $data = [
            "categories" => Categorie::all(),
            "produits" => $produits,
            "page" => 'Liste des produits',
            "currentPage" => $page,
            "lastPage" => $lastPage
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/listeproduits', $data)
            . view('template/footer', $data);
    }

    public function getAjoutproduit() {
        $data = [
            "categories" => Categorie::all(),
            "page" => 'Ajout Produit'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/ajoutproduit.php', $data)
            . view('template/footer', $data);
    }

    public function postAjoutproduit() {
        $session = session();

        $nomProduit = $this->request->getPost('nomProduit');
        $description = $this->request->getPost('descProduit');
        $prix = $this->request->getPost('prixProduit');
        $stock = $this->request->getPost('stockProduit');
        $image = $this->request->getFile('imageProduit');
        $categorie = $this->request->getPost('categProduit');

        $produit = new Produit();
        $produit->nom = $nomProduit;
        $produit->description = $description;
        $produit->prix = $prix;
        $produit->stock = $stock;

        //Catégorie
        $cat = Categorie::find($categorie);
        $produit->categorie()->associate($cat);

        //Traitement de l'image
        if ($image && $image->isValid() && !$image->getError()) {
            // Vérification de l'extension autorisée
            $authorizedExtension = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
            if (in_array($image->getMimeType(), $authorizedExtension)) {

                // Vérification de la taille (max 10 Mo)
                $maximumSize = 10 * 1024 * 1024;
                if ($image->getSize() <= $maximumSize) {
                    // Génération d'un nom unique pour le fichier
                    $nomImg = $image->getRandomName();
                    $image->move(FCPATH . 'img/produits', $nomImg);

                    $produit->image = $nomImg;
                    $produit->save();

                    $session->setFlashdata('successProdAdd', 'Le produit a bien été ajouté avec l\'image.');

                } else {
                    $session->setFlashdata('errorProdAdd', 'La taille maximale de l\'image doit être de 10Mo.');
                }
            } else {
                $session->setFlashdata('errorProdAdd', 'Seules les images en .JPG, .JPEG, .PNG et .WEBP sont acceptées.');
            }
        } else {
            $produit->image = "placeholder.jpg";
            if ($produit->save()) {
                $session->setFlashdata('successProdAdd', 'Le produit a bien été enregistré.');
            } else {
                $session->setFlashdata('errorProdAdd', 'Une erreur est survenue lors de l\'enregistrement du produit.');
            }
        }
        return redirect()->to('/admin/ajoutproduit');
    }

    public function getModifproduit($id) {
        $session = session();

        if (!$id) {
            $session->setFlashdata('errorModifProduit', 'Une erreur est surevnue, veuillez réessayer.');
            return redirect()->back();
        }

        $produit = Produit::find($id);

        if (!$produit) {
            $session->setFlashdata('errorModifProduit', 'Ce produit n\'existe pas.');
            return redirect()->back();
        }

        $data = [
            "categories" => Categorie::all(),
            "produit" => $produit,
            "page" => 'Modifier un produit',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/modifproduit', $data)
            . view('template/footer', $data);
    }

    public function postModifproduit() {
        $session = session();

        $id = $this->request->getPost('idProduit');
        $nomProduit = $this->request->getPost('nomProduit');
        $description = $this->request->getPost('descProduit');
        $prix = $this->request->getPost('prixProduit');
        $stock = $this->request->getPost('stockProduit');
        $image = $this->request->getFile('imageProduit');
        $categorie = $this->request->getPost('categProduit');

        $produit = Produit::find($id);

        if (!$produit) {
            $session->setFlashdata('errorProdUpdate', 'Ce produit n\'existe pas.');
            return redirect()->back();
        }

        $produit->nom = $nomProduit;
        $produit->description = $description;
        $produit->prix = $prix;
        $produit->stock = $stock;

        //Catégorie
        $cat = Categorie::find($categorie);
        $produit->categorie()->associate($cat);

        //Traitement de l'image
        if ($image && $image->isValid() && !$image->getError()) {
            // Vérification de l'extension autorisée
            $authorizedExtension = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
            if (in_array($image->getMimeType(), $authorizedExtension)) {
                // Vérification de la taille (max 10 Mo)
                $maximumSize = 5 * 1024 * 1024;
                if ($image->getSize() <= $maximumSize) {
                    //Suppression de l'ancien fichier si ce n'est pas le placeholder
                    $ancienNomImg = $produit->image;
                    if ($ancienNomImg != "placeholder.jpg") {
                        unlink(FCPATH . 'public/img/produits/' . $ancienNomImg);
                    }
                    $nomImg = $image->getRandomName();
                    $image->move(FCPATH . 'public/img/produits', $nomImg);
                    $produit->image = $nomImg;
                    $produit->save();

                    $session->setFlashdata('successProdUpdate', 'Le produit a bien été modifé.');
                } else {
                    $session->setFlashdata('errorProdUpdate', 'La taille maximale de l\'image est de 5Mo.');
                }
            } else {
                $session->setFlashdata('errorProdUpdate', 'Seules les images en .JPG, .JPEG, .PNG et .WEBP sont acceptées.');
            }
        } else {
            if ($produit->save()) {
                $session->setFlashdata('successProdUpdate', 'Le produit a bien été modifié.');
            } else {
                $session->setFlashdata('errorProdUpdate', 'Une erreur est survenue lors de la modification du produit.');
            }
        }
        return redirect()->back();
    }

    public function getSupprimerproduit($id) {
        $session = session();

        if (!$id) {
            $session->setFlashdata('errorProdDelete', 'Une erreur est survenue, veuillez réessayer.');
            return redirect()->back();
        }

        $produit = Produit::find($id);

        if (!$produit) {
            $session->setFlashdata('errorProdDelete', 'Ce produit n\'existe pas.');
            return redirect()->back();
        }

        if ($produit->image != "placeholder.jpg") {
            unlink(FCPATH . 'public/img/produits/' . $produit->image);
        }

        if ($produit->delete()) {
            $session->setFlashdata('successProdDelete', 'Le produit a bien été supprimé.');
        }else {
            $session->setFlashdata('errorProdDelete', 'Une erreur est survenue lors de la suppression. Veuillez réessayer.');
        }
        return redirect()->back();
    }

    /*
     * GESTION DE CATEGORIES
     */
    public function getListecateg() {
        $data = [
            "categories" => Categorie::all(),
            "page" => 'Liste des catégories',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/listecategories', $data)
            . view('template/footer', $data);
    }

    public function getAjoutcategorie() {
        $data = [
            "categories" => Categorie::all(),
            "page" => 'Ajouter une catégorie',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/ajoutcategorie', $data)
            . view('template/footer', $data);

    }

    public function postAjoutcategorie() {
        $session = session();

        $nomCategorie = $this->request->getPost('nomCateg');

        $categorie = new Categorie();
        $categorie->nom = $nomCategorie;

        if ($categorie->save()) {
            $session->setFlashdata('successCategAdd', 'La catégorie a été ajoutée avec succès.');
        }else {
            $session->setFlashdata('errorCategAdd', 'Une erreur est survenue lors de l\'ajout de la catégorie. Veuillez réessayer.');
        }
        return redirect()->back();
    }

    public function getModifcategorie($id) {
        $session = session();

        if (!$id) {
            $session->setFlashdata('errorModifCateg', 'Une erreur est surevnue, veuillez réessayer.');
            return redirect()->back();
        }

        $categorie = Categorie::find($id);

        if (!$categorie) {
            $session->setFlashdata('errorModifCateg', 'Cette catégorie n\'existe pas.');
            return redirect()->back();
        }

        $data = [
            "categories" => Categorie::all(),
            "categorie" => $categorie,
            "page" => 'Modifier un produit',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/modifcategorie', $data)
            . view('template/footer', $data);
    }

    public function postModifcategorie() {
        $session = session();

        $id = $this->request->getPost('idCategorie');
        $nomCategorie = $this->request->getPost('nomCateg');

        $categorie = Categorie::find($id);

        if (!$categorie) {
            $session->setFlashdata('errorModifCateg', 'Cette catégorie n\'existe pas.');
            return redirect()->back();
        }

        $categorie->nom = $nomCategorie;

        if ($categorie->save()) {
            $session->setFlashdata('successModifCateg', 'Cette catégorie a été modifée avec succès.');
        }else {
            $session->setFlashdata('errorModifCateg', 'Une erreur est survenue lors de la modifcation. Veuillez réessayer.');
        }
        return redirect()->back();
    }

    public function getSupprimercategorie($id) {
        $session = session();

        if (!$id) {
            $session->setFlashdata('errorCategDelete', 'Une erreur est survenue, veuillez réessayer.');
            return redirect()->back();
        }

        $categ = Categorie::find($id);

        if (!$categ) {
            $session->setFlashdata('errorCategDelete', 'Cette catégorie n\'existe pas.');
            return redirect()->back();
        }

        if ($categ->produits()->count() != 0) {
            $session->setFlashdata('errorCategDelete', 'Impossible de supprimer une catégorie avec laquelle des produits sont reliés. Veuillez supprimer ces produits pour supprimer cette catégorie.');
            return redirect()->back();
        }else {
            if ($categ->delete()) {
                $session->setFlashdata('successCategDelete', 'Catégorie supprimée avec succès.');
            }else {
                $session->setFlashdata('errorCategDelete', 'Une erreur est survenue, veuillez réessayer.');
            }
        }
        return redirect()->back();
    }

    /*
     * GESTION DES COMMANDES
     */
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
            . view('admin/listecommandes', $data)
            . view('template/footer', $data);
    }

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

    public function getArticlescomm($id) {
        // Page actuelle depuis l'URL, par défaut 1
        $commande = Commande::find($id);

        $data = [
            "categories" => Categorie::all(),
            "commande" => $commande,
            "page" => 'Liste des produits',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/articlescommande', $data)
            . view('template/footer', $data);
    }


}
