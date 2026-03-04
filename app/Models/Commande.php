<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model {
    public $timestamps = false;

    protected $fillable = [
        'creneau_retrait', 'date_heure', 'statut', 'commentaire', 'utilisateur_id'
    ];

    protected $casts = [
        'creneau_retrait' => 'datetime',
        'date_heure' => 'datetime'
    ];

    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class);
    }

    public function produits() {
        return $this->belongsToMany(Produit::class)->withPivot('quantite');
    }

    public static function getCreneauxReserves() {
        $commandes = Commande::all();
        $creneauxReserves = [];

        foreach ($commandes as $commande) {
            $creneauxReserves[] = $commande->creneau_retrait;
        }

        return $creneauxReserves;
    }

}