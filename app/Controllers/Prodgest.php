<?php

namespace App\Controllers;


use App\Models\Categorie;
use App\Models\Produit;

class Prodgest extends BaseController {

    public function getListeproduits($numPage) {
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
            . view('admin/produits/listeproduits', $data)
            . view('template/footer', $data);
    }

    public function getAjoutproduit() {
        $data = [
            "categories" => Categorie::all(),
            "page" => 'Ajout Produit'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/produits/ajoutproduit.php', $data)
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
                    $image->move(FCPATH . 'public/img/produits', $nomImg);

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
        return redirect()->to('/prodgest/ajoutproduit');
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
            . view('admin/produits/modifproduit', $data)
            . view('template/footer', $data);
    }

    public function postModifproduit()
    {
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
}