<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model {
    public $timestamps = false;

    public function commandes() {
         return $this->hasMany(Commande::Class);
    }

    public function isAdmin($user,$pwd) {
        $user = $this->where('email', $user)->first();

        if (!$user) {
            return false;
        }

        if (!password_verify($pwd, $user->password)) {
            return false;
        }else {
            return $user->role == 'admin';
        }
    }

}