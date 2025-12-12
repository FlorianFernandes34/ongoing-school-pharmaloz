<?php

use App\Models\Produit;

?>


<div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-4 gap-8">
    <!-- Sidebar -->
    <aside class="bg-white shadow rounded-xl p-6 space-y-4">
        <h2 class="font-bold text-xl mb-4">Mon compte</h2>
        <ul class="space-y-2">
            <li class="block py-2 px-3 rounded hover:bg-blue-100">Mes informations</li>
            <li><a href="<?= base_url('login/logout')?>" class="block py-2 px-3 rounded hover:bg-red-100 text-red-600">Se déconnecter</a></li>
        </ul>
    </aside>


    <!-- Main Content -->
    <main class="md:col-span-3 space-y-8">
        <section id="infos" class="bg-white shadow rounded-xl p-6">
            <h3 class="text-2xl font-bold mb-4">Mes informations</h3>
            <p>Nom : <?= $user->nom?></p>
            <p>Prénom : <?= $user->prenom?></p>
            <p>Email : <?= $user->email?></p>
        </section>


        <section id="orders" class="bg-white shadow rounded-xl p-6">
            <h3 class="text-2xl font-bold mb-4">Mes commandes</h3>
            <div class="space-y-4">
                <?php
                $totalCommande = 0;
                foreach ($commandes as $commande) {
                    //Total Commande
                    $produits = $commande->produits()->get();
                    $total = 0;

                    foreach ($produits as $ligneCommande) {
                        $produit = Produit::find($ligneCommande->id);
                        $quantite = $ligneCommande->pivot->quantite;
                        $total = $quantite * $produit->prix;
                    }
                    echo '
                        <div class="border rounded p-4 flex justify-between items-center">
                            <div>
                                <p class="font-semibold">Commande ' . $commande->id . '</p>
                                <p class="text-gray-600 text-sm">Date : ' . $commande->date_heure . '</p>
                                <p class="text-gray-600 text-sm">Statut : ' . $commande->statut . '</p>
                            </div>
                            <span class="font-semibold">Total : ' . $total . '€</span>
                        </div>
                    ';
                }
                ?>
            </div>
        </section>
    </main>
</div>