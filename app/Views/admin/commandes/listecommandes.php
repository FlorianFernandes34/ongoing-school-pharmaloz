<main class="p-8">

    <!-- BOUTONS -->
    <div class="mb-6 flex items-center justify-between">
        <!-- Bouton retour -->
        <a href="<?= base_url('admin')?>"
           class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>

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
            <input type="text" id="searchCommande" placeholder="Rechercher..."
                   class="border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition" />
        </div>
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
    </div>

    <!-- LISTE COMMANDES -->
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

                    <?php
                    $dateCommande = new DateTime($commande->date_heure);
                    $dateFr = $dateCommande->format('d/m/Y H:i');
                    ?>
                    <p>
                        <span class="font-medium text-gray-600">Date commande :</span>
                        <?= $dateFr ?>
                    </p>

                    <?php
                    $prixTotal = 0;
                    foreach ($commande->produits as $produit) {
                        $prixTotal += $produit->prix * $produit->pivot->quantite;
                    }
                    ?>
                    <p>
                        <span class="font-medium text-gray-600">Prix total :</span>
                        <?= $prixTotal ?>€
                    </p>

                    <?php
                    $dateRetrait = new DateTime($commande->creneau_retrait);
                    $dateFr = $dateRetrait->format('d/m/Y H:i');
                    ?>
                    <p>
                        <span class="font-medium text-gray-600">Créneau retrait :</span>
                        <?= $dateFr ?>
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

    <!-- EMPTY STATE -->
    <div id="emptyState" class="hidden flex items-center justify-center min-h-[40vh]">
        <div class="text-center text-gray-500">
            <i class="fas fa-search text-4xl mb-4 text-gray-300"></i>
            <p class="text-lg font-medium">
                Aucune commande correspondant à ces critères
            </p>
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="mt-8 flex justify-center space-x-2">
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

</main>
