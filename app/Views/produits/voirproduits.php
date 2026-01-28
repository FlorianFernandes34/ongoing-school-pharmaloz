<main class="max-w-7xl mx-auto px-6 py-10 space-y-10">

    <!-- TITRE -->
    <div>
        <h1 class="text-4xl font-bold mb-2">Nos produits</h1>
        <p class="text-gray-600">Produits disponibles sans ordonnance – Retrait en pharmacie</p>
    </div>

    <!-- FILTRES -->
    <div class="flex flex-wrap gap-3">

        <!-- TOUS -->
        <a href="<?= base_url('produits/produitsliste/all')?>" class="px-4 py-2 rounded-full transition <?= $currentCat == 'all' ? 'bg-blue-200 text-blue-800 font-semibold' : 'bg-gray-200 hover:bg-blue-100' ?>">
            Tous
        </a>

        <?php foreach ($categories as $categorie): ?>
            <a href="<?= base_url('produits/produitsliste/' . $categorie->nom_lien) ?>" class="px-4 py-2 rounded-full transition <?= $currentCat == $categorie->nom_lien ? 'bg-blue-200 text-blue-800 font-semibold' : 'bg-gray-200 hover:bg-blue-100' ?>">
                <?= $categorie->nom ?>
            </a>
        <?php endforeach; ?>
    </div>


    <!-- GRILLE PRODUITS -->
    <?php if ($produits->isEmpty()): ?>

        <div class="min-h-[60vh] flex items-center justify-center">

            <div class="bg-white rounded-xl shadow p-12 text-center space-y-5 max-w-md w-full">

                <i class="fas fa-box-open text-6xl text-gray-400"></i>

                <h3 class="text-2xl font-semibold text-gray-700">
                    Aucun produit disponible
                </h3>

                <p class="text-gray-500">
                    Aucun produit n’est disponible pour cette catégorie.
                </p>

                <a href="<?= base_url('produits/produitsliste/all') ?>"
                   class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Voir tous les produits
                </a>
            </div>
        </div>

    <?php else: ?>

        <!-- GRILLE PRODUITS -->
        <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

            <?php foreach ($produits as $produit): ?>
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">

                    <img src="<?= base_url('public/img/produits/' . $produit->image) ?>"
                         class="w-full h-48 object-cover">

                    <div class="p-5 space-y-3">

                    <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                        <?= $produit->categorie->nom ?>
                    </span>

                        <h3 class="font-semibold text-lg"><?= esc($produit->nom) ?></h3>

                        <p class="text-sm text-gray-600">
                            <?= esc($produit->description) ?>
                        </p>

                        <div class="flex justify-between items-center">

                        <span class="font-bold text-xl">
                            <?= number_format($produit->prix, 2, ',', ' ') ?> €
                        </span>

                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Ajouter
                            </button>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

</main>
