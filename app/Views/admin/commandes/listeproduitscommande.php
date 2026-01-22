<main class="p-8" data-commande-id="<?= $commande->id ?>">

    <div class="mb-8 flex items-center">
        <a href="<?= base_url('commgest/listecom/1') ?>"
           class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <!-- Tableau des articles -->
    <?php if($commande->produits()->count() > 0):?>
        <div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Prix Unitaire</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Quantité</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catégorie</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                <?php foreach ($commande->produits as $article): ?>
                    <tr class="hover:bg-gray-50 transition" data-article-id="<?= $article->id ?>">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800"><?= $article->nom ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= $article->prix ?> €</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="flex items-center space-x-2">
                                <?php if ($commande->statut != "Retirée" && $commande->statut != "Annulée"):?>
                                    <button type="button" class="qty-minus bg-gray-200 hover:bg-gray-300 text-gray-700 rounded px-2 py-1 transition">-</button>
                                    <input type="number" class="w-16 text-center border border-gray-300 rounded py-1" value="<?= $article->pivot->quantite ?>" min="0">
                                    <button type="button" class="qty-plus bg-gray-200 hover:bg-gray-300 text-gray-700 rounded px-2 py-1 transition">+</button>
                                <?php else:?>
                                    <?= $article->pivot->quantite ?>
                                <?php endif?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= $article->categorie->nom ?></td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($commande->statut != "Retirée" && $commande->statut != "Annulée"):?>
                                <button type="button" class="delete-article text-red-500 hover:text-red-700 font-bold text-lg transition">&times;</button>
                            <?php endif?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif?>

    <!-- Empty state -->
    <?php if ($commande->produits->count() == 0): ?>
        <div class="mt-16 flex items-center justify-center">
            <div class="text-center text-gray-500">
                <i class="fas fa-box-open text-5xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">Aucun article disponible pour cette commande</p>
            </div>
        </div>
    <?php endif ?>

    <?php if ($commande->statut == "Retirée" || $commande->statut == "Annulée"):?>
        <div class="mt-10 flex justify-center">
            <div class="w-full max-w-md bg-gray-50 border border-dashed border-gray-300 rounded-xl p-6 text-center">
                <i class="fas fa-plus-circle text-4xl text-blue-500 mb-4"></i>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Ajouter un produit</h2>
                <p class="text-sm text-gray-500 mb-4">
                    Impossible de modifier une commande annulée ou déjà retirée.
                </p>
            </div>
        </div>
    <?php else:?>
        <div class="mt-10 flex justify-center">
            <div class="w-full max-w-md bg-gray-50 border border-dashed border-gray-300 rounded-xl p-6 text-center">
                <i class="fas fa-plus-circle text-4xl text-blue-500 mb-4"></i>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Ajouter un produit</h2>
                <p class="text-sm text-gray-500 mb-4">
                    Ajouter un produit à cette commande
                </p>
                <a href="<?= base_url('commgest/ajouterproduitcommande/' . $commande->id) ?>"
                   class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-5 rounded-lg shadow transition">
                    <i class="fas fa-plus mr-2"></i> Nouveau produit
                </a>
            </div>
        </div>
    <?php endif?>

</main>
