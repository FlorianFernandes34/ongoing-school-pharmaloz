<?php

use App\Models\Produit;

?>


<div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-4 gap-8">
    <!-- Sidebar -->
    <aside class="bg-white shadow-lg rounded-2xl p-6 space-y-6">
        <h2 class="font-bold text-xl text-gray-800">Mon compte</h2>

        <ul class="space-y-1 text-sm font-medium">
            <li>
                <?= anchor(
                    'auth/account', '<i class="fas fa-home mr-3 text-gray-400"></i> Accueil', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition'])
                ?>
            </li>
            <li>
                <?= anchor(
                    'auth/updatepassword', '<i class="fas fa-lock mr-3 text-gray-400"></i> Modifier mon mot de passe', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition'])
                ?>
            </li>

            <li>
                <?= anchor(
                    'auth/updatemail', '<i class="fas fa-envelope mr-3 text-gray-400"></i> Modifier mon email', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition'])
                ?>
            </li>

            <li>
                <?= anchor(
                    'auth/updateinfos', '<i class="fas fa-user mr-3 text-gray-400"></i> Modifier mes informations', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition'])
                ?>
            </li>

            <!-- Séparateur -->
            <li class="my-3 border-t border-gray-200"></li>
            <li>
                <?= anchor(
                    'auth/logout', '<i class="fas fa-sign-out-alt mr-3"></i> Se déconnecter', ['class' => 'flex items-center px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition'])
                ?>
            </li>

            <li>
                <?= anchor('auth/deleteaccountdata', '<i class="fas fa-trash mr-3"></i> Supprimer mon compte et mes données',
                    [
                        'class' => 'flex items-center px-4 py-3 rounded-lg text-red-700 hover:bg-red-100 transition',
                        'onclick' => 'return doubleConfirmOnClick("Voulez-vous supprimer votre compte et toutes vos données ?", "Cette action est irréversible. Continuer ?")'
                    ]
                ) ?>
            </li>
        </ul>
    </aside>


    <!-- Main Content -->
    <main class="md:col-span-3 space-y-8">
        <div>
            <?php
            $session = session();
            if ($session->getFlashdata('errorDeleteData')) {
                echo ' 
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("errorDeleteData") . '</span>
                    </div>';
            }
            ?>
        </div>

        <section id="infos" class="bg-white shadow rounded-xl p-6">
            <h3 class="text-2xl font-bold mb-4">Mes informations</h3>
            <p>Nom : <?= $user_nom?></p>
            <p>Prénom : <?= $user_prenom?></p>
            <p>Email : <?= $user_email?></p>
        </section>


        <section id="orders" class="bg-white shadow rounded-xl p-6">
            <h3 class="text-2xl font-bold mb-4">Mes commandes</h3>
            <div class="space-y-4">
                <?php
                if ($commandes->count() == 0) {
                    echo '
                        <div class="flex flex-col items-center justify-center py-12 text-center text-gray-500">
                            <i class="fas fa-receipt text-5xl mb-4 text-gray-300"></i>
                
                            <p class="text-lg font-medium text-gray-700 mb-2">
                                Aucune commande pour le moment
                            </p>
                
                            <p class="text-sm mb-6">
                                Vous n’avez pas encore passé de commande.
                            </p>
                
                            <a href="' .  base_url('') . '"
                               class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-5 rounded-lg shadow transition">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Passer une commande
                            </a>
                        </div>
                    ';
                }

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