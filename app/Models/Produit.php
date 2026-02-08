<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model {
    public $timestamps = false;

    protected $fillable = ['nom', 'prix', 'description', 'image', 'categorie_id', 'stock', 'stock_reserve'];

    public function categorie() {
        return $this->belongsTo(Categorie::Class);
    }

    public function commandes() {
        return $this->belongsToMany(Commande::Class)->withPivot('quantite');
    }
}