<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model {
    public $timestamps = false;

    public function categorie() {
        return $this->belongsTo(Categorie::Class);
    }

    public function commandes() {
        return $this->belongsToMany(Commande::Class)->withPivot('quantite');
    }
}