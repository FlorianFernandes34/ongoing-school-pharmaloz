<div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-4 gap-8">
    <!-- Sidebar -->
    <aside class="bg-white shadow-lg rounded-2xl p-6 space-y-6">
        <h2 class="font-bold text-xl text-gray-800">Mon compte</h2>

        <ul class="space-y-1 text-sm font-medium">
            <li>
                <?= anchor('account', '<i class="fas fa-home mr-3 text-gray-400"></i> Accueil', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition']) ?>
            </li>
            <li>
                <?= anchor('account/updatepassword', '<i class="fas fa-lock mr-3 text-gray-400"></i> Modifier mon mot de passe', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition']) ?>
            </li>
            <li>
                <?= anchor('account/updatemail', '<i class="fas fa-envelope mr-3 text-gray-400"></i> Modifier mon email', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition']) ?>
            </li>
            <li>
                <?= anchor('account/updateinfos', '<i class="fas fa-user mr-3 text-gray-400"></i> Modifier mes informations', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition']) ?>
            </li>
            <li class="my-3 border-t border-gray-200"></li>
            <li>
                <?= anchor('auth/logout', '<i class="fas fa-sign-out-alt mr-3"></i> Se déconnecter', ['class' => 'flex items-center px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition']) ?>
            </li>
            <li>
                <?= anchor('auth/deleteaccountdata', '<i class="fas fa-trash mr-3"></i> Supprimer mon compte et mes données', [
                    'class' => 'flex items-center px-4 py-3 rounded-lg text-red-700 hover:bg-red-100 transition',
                    'onclick' => 'return doubleConfirmOnClick("Voulez-vous supprimer votre compte et toutes vos données ?", "Cette action est irréversible. Continuer ?")'
                ]) ?>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="md:col-span-3 space-y-8">

        <section class="bg-white shadow rounded-xl p-6">

            <h3 class="text-2xl font-bold mb-6">Détails de la commande</h3>

            <?php if($commande == "Aucune commande"):?>
                <div class="flex flex-col items-center justify-center text-center py-16">
                    <div class="w-20 h-20 flex items-center justify-center rounded-full bg-red-50 mb-6">
                        <i class="fas fa-receipt text-3xl text-red-400"></i>
                    </div>

                    <h4 class="text-xl font-semibold text-gray-800 mb-2">
                        Commande introuvable
                    </h4>

                    <p class="text-gray-500 mb-6 max-w-md">
                        Cette commande n’existe pas ou ne vous appartiens pas.
                        Vous pouvez consulter l’historique de vos commandes depuis votre compte.
                    </p>

                    <div class="flex gap-3">
                        <?= anchor('account', 'Voir mes commandes', [
                                'class' => 'inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition'
                        ]) ?>

                        <?= anchor('produits/produitsliste/all', 'Retour boutique', [
                                'class' => 'inline-flex items-center px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition'
                        ]) ?>
                    </div>
                </div>
            <?php else:?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                    <div>
                        <p class="text-sm text-gray-500">Numéro de commande</p>
                        <p class="font-semibold text-gray-800"><?= $commande->id ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Statut</p>
                        <?php if ($commande->statut == "Retirée" || $commande->statut == "Validée"): ?>
                            <span class="inline-block px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 font-medium">
                            <?= $commande->statut ?>
                        </span>
                        <?php else: ?>
                            <span class="inline-block px-3 py-1 text-sm rounded-full bg-red-100 text-red-700 font-medium">
                            <?= $commande->statut ?>
                        </span>
                        <?php endif ?>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Date et heure</p>
                        <p class="font-semibold text-gray-800"><?= $commande->date_heure->format('d/m/Y H:i') ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Créneau de retrait</p>
                        <p class="font-semibold text-gray-800"><?= $commande->creneau_retrait->format('d/m/Y H:i') ?></p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Commentaire</p>

                        <?php if(!empty($commande->commentaire)): ?>
                            <p class="font-semibold text-gray-800">
                                <?= nl2br(esc($commande->commentaire)) ?>
                            </p>
                        <?php else: ?>
                            <p class="text-gray-400 italic">Aucun commentaire</p>
                        <?php endif ?>
                    </div>

                </div>

                <!-- Produits -->
                <div class="mb-6">

                    <h4 class="text-lg font-semibold mb-3">Produits commandés</h4>

                    <div class="divide-y divide-gray-200 border rounded-lg">
                        <?php $total = 0 ?>
                        <?php foreach ($commande->produits as $article): ?>
                            <?php // (CALCUL TOTAL COMMANDE)
                            $total += $article->prix * $article->pivot->quantite;
                            ?>
                            <div class="flex justify-between items-center p-4">
                                <div>
                                    <p class="font-medium"><?= $article->nom ?></p>
                                    <p class="text-sm text-gray-500">Quantité : <?= $article->pivot->quantite ?></p>
                                </div>
                                <p class="font-semibold"><?= number_format($article->prix, 2, ',', ' ')?>€</p>
                            </div>
                        <?php endforeach ?>
                    </div>

                </div>

                <!-- Total -->
                <div class="flex justify-between items-center border-t pt-4">
                    <p class="text-lg font-semibold">Total</p>
                    <p class="text-xl font-bold text-blue-600"><?= number_format($total, 2, ',', ' ')?>€</p>
                </div>
            <?php endif?>



        </section>

    </main>
</div>
