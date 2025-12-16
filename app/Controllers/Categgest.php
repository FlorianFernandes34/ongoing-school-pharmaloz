<?php

namespace App\Controllers;


use App\Models\Categorie;

class Categgest extends BaseController {
    public function getListecateg() {
        $data = [
            "categories" => Categorie::all(),
            "page" => 'Liste des catégories',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/categories/listecategories', $data)
            . view('template/footer', $data);
    }

    public function getAjoutcategorie() {
        $data = [
            "categories" => Categorie::all(),
            "page" => 'Ajouter une catégorie',
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('admin/categories/ajoutcategorie', $data)
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
            . view('admin/categories/modifcategorie', $data)
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
}