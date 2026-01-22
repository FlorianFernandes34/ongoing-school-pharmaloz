<main class="p-8">

    <!-- BOUTONS -->
    <div class="mb-6 flex items-center justify-between">
        <!-- Bouton retour -->
        <a href="<?= base_url('admin')?>"
           class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>

        <?php if (!$commandes->isEmpty()): ?>
            <div class="flex items-center space-x-2">
                <!-- Select pour le type de recherche -->
                <select id="searchType"
                        class="border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    <option value="id">N° Commande</option>
                    <option value="email">Email</option>
                    <option value="client">Client</option>
                    <option value="statut">Statut</option>
                </select>

                <!-- Champ de recherche -->
                <input type="text" id="searchCommande" placeholder="Rechercher..." class="border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition" />
            </div>
        <?php endif;?>

    </div>

    <!-- MESSAGE ERREUR -->
    <div>
        <?php
        $session = session();
        if ($session->getFlashdata('errorCommande')) {
            echo '
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">' . $session->getFlashdata("errorCommande") . '</span>
                </div>';
        }
        ?>

        <?php if ($commandes->isEmpty()): ?>
            <div class="flex items-center justify-center min-h-[50vh]">
                <div class="text-center bg-white border border-gray-200 rounded-2xl shadow-sm px-8 py-10 max-w-md w-full">

                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50">
                        <i class="fas fa-box-open text-3xl text-blue-500"></i>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                        Aucune commande trouvée
                    </h2>

                    <p class="text-gray-500 mb-6">
                        Aucune commande n'a été trouvée
                    </p>

                    <a href="<?= base_url('admin') ?>"
                       class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2.5 rounded-lg shadow transition">
                        <i class="fas fa-arrow-left"></i>
                        Retour au tableau de bord
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- AUCUN RÉSULTAT DE RECHERCHE -->
            <div id="emptySearchState" class="hidden flex items-center justify-center min-h-[40vh]">
                <div class="text-center bg-white border border-gray-200 rounded-2xl shadow-sm px-8 py-10 max-w-md w-full">

                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50">
                        <i class="fas fa-search-minus text-3xl text-blue-500"></i>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                        Aucun résultat
                    </h2>

                    <p class="text-gray-500 mb-6">
                        Aucune commande ne correspond à votre recherche.
                    </p>

                    <button id="resetSearch"
                            class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2.5 rounded-lg shadow transition">
                        <i class="fas fa-rotate-left"></i>
                        Réinitialiser la recherche
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($commandes as $commande): ?>
                    <div class="bg-white rounded-xl shadow-md p-5 flex flex-col justify-between border border-gray-100 hover:shadow-lg transition-all">
                        <div class="flex-1 space-y-2">
                            <h2 class="text-lg font-semibold text-gray-800">
                                Commande N°<?= $commande->id ?>
                            </h2>

                            <p id="statut<?= $commande->id ?>">
                                <span class="font-medium text-gray-600">Statut :</span>
                                <?= $commande->statut ?>
                            </p>

                            <p>
                                <span class="font-medium text-gray-600">Date commande :</span>
                                <?= $commande->date_heure->format('d/m/Y H:i') ?>
                            </p>

                            <?php
                                $prixTotal = 0;
                                foreach ($commande->produits as $produit) {
                                    $prixTotal += $produit->prix * $produit->pivot->quantite;
                                }
                            ?>

                            <p>
                                <span class="font-medium text-gray-600">Prix total :</span>
                                <?=  number_format($prixTotal, 2, ',', ' ') ?>€
                            </p>

                            <p>
                                <span class="font-medium text-gray-600">Créneau retrait :</span>
                                <?= $commande->creneau_retrait->format('d/m/Y H:i') ?>
                            </p>

                            <p class="mt-2 font-medium text-gray-700">Utilisateur :</p>
                            <p class="text-gray-600">
                                <?= $commande->utilisateur->nom ?>
                                <?= $commande->utilisateur->prenom ?>
                            </p>
                            <p class="text-gray-600"><?= $commande->utilisateur->email ?></p>
                        </div>

                        <!-- BOUTONS -->
                        <div class="flex space-x-2 mt-4">

                            <!-- STATUT -->
                            <div class="relative flex-1" data-id="<?= $commande->id ?>">
                                <button type="button"
                                        class="status-button w-full h-10 bg-blue-500 hover:bg-blue-600 text-white font-medium px-3 rounded-lg shadow
                                   flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            <span class="status-text flex-1 text-center">
                                <?= $commande->statut ?>
                            </span>
                                    <i class="fas fa-chevron-down ml-2"></i>
                                </button>

                                <ul
                                        class="status-dropdown absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10">
                                    <li class="dropdown-item px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer"
                                        data-value="Validée">Validée</li>
                                    <li class="dropdown-item px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer"
                                        data-value="Retirée">Retirée</li>
                                    <li class="dropdown-item px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer"
                                        data-value="Annulée">Annulée</li>
                                </ul>
                            </div>

                            <!-- VOIR ARTICLES -->
                            <a href="<?= base_url('commgest/listeproduitscomm/' . $commande->id) ?>"
                               class="flex-1 h-10 min-w-0 flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 rounded-lg transition whitespace-nowrap">
                                <i class="fas fa-edit"></i>
                                <span>Voir les articles</span>
                            </a>

                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- PAGINATION -->
            <div id="pagination" class="mt-8 flex justify-center space-x-2">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= base_url('commgest/listecom/' . ($currentPage - 1)) ?>"
                       class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                        &laquo; Précédent
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $lastPage; $i++): ?>
                    <a href="<?= base_url('commgest/listecom/' . $i) ?>"
                       class="px-3 py-1 <?= $currentPage == $i ? 'bg-blue-500 text-white' : 'bg-gray-200' ?> rounded hover:bg-gray-300">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($currentPage < $lastPage): ?>
                    <a href="<?= base_url('commgest/listecom/' . ($currentPage + 1)) ?>"
                       class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                        Suivant &raquo;
                    </a>
                <?php endif; ?>
            </div>
        <?php endif;?>
    </div>
</main>
