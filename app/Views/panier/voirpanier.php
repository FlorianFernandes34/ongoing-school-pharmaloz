<main class="max-w-6xl mx-auto px-6 py-10 space-y-10">
    <div id="toast-panier-success" class="fixed top-6 left-1/2 -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg opacity-0 pointer-events-none transition-all duration-300 z-50">
        .
    </div>
    <div id="toast-panier-error" class="fixed top-6 left-1/2 -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg opacity-0 pointer-events-none transition-all duration-300 z-50">
        .
    </div>

    <!-- TITRE -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-1">Votre panier</h1>
        </div>

        <a href="<?= base_url('produits/produitsliste/all')?>" class="text-blue-600 hover:underline flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Continuer mes achats
        </a>
    </div>

    <div id="container-on-cart-empty" class="min-h-[50vh] flex items-center justify-center" style="display: none">
        <div class="text-center space-y-4">
            <i class="fas fa-shopping-cart text-6xl text-gray-400"></i>
            <h3 class="text-2xl font-semibold text-gray-700">Votre panier est vide</h3>
            <p class="text-gray-500">Ajoutez des produits pour les retrouver ici</p>
        </div>
    </div>

    <?php if (empty($produits)): ?>
        <div class="min-h-[50vh] flex items-center justify-center">
            <div class="text-center space-y-4">
                <i class="fas fa-shopping-cart text-6xl text-gray-400"></i>
                <h3 class="text-2xl font-semibold text-gray-700">Votre panier est vide</h3>
                <p class="text-gray-500">Ajoutez des produits pour les retrouver ici</p>
            </div>
        </div>
    <?php else:?>
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- LISTE PRODUITS -->
            <div id="cart-container" class="lg:col-span-2 space-y-6">
                <?php foreach ($produits as $produit): ?>
                    <div class="cart-item bg-white rounded-xl shadow p-5 flex gap-5">

                        <img src="<?= base_url('public/img/produits/' . $produit['produit']->image) ?>" class="w-32 h-32 object-cover rounded-lg">

                        <div class="flex-1 space-y-2">
                            <h3 class="text-lg font-semibold"><?= $produit['produit']->nom ?></h3>
                            <p class="text-sm text-gray-600">
                                <?= $produit['produit']->description ?>
                            </p>

                            <div class="flex items-center justify-between mt-4">

                                <!-- QUANTITÉ -->
                                <div class="flex items-center gap-3">
                                    <button class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300" onclick="removeOneFromCart(<?= $produit['produit']->id ?>)">
                                        <i class="fas fa-minus"></i>
                                    </button>

                                    <span id="qteFieldFor-<?= $produit['produit']->id ?>" class="font-semibold"><?= $produit['qte'] ?></span>

                                    <button class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300" onclick="addOneToCart(<?= $produit['produit']->id ?>)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                <!-- PRIX -->
                                <span class="product-total-price font-bold text-lg">
                                <?= number_format($produit['produit']->prix, 2, ',', ' ') ?>€
                            </span>
                            </div>
                        </div>

                        <!-- SUPPRIMER -->
                        <button class="text-red-500 hover:text-red-700" onclick="deleteFromCart(<?= $produit['produit']->id ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php endforeach?>
            </div>

            <!-- RÉCAP PANIER -->
            <aside id="recap-panier" class="bg-white rounded-xl shadow p-6 space-y-6 h-fit">

                <h2 class="text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-receipt"></i>
                    Récapitulatif
                </h2>

                <div class="space-y-3 text-gray-700">
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span><span id="cart-total"><?= $total ?></span>€</span>
                    </div>
                </div>

                <button class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-credit-card"></i>
                    Valider la commande
                </button>
            </aside>
        </section>
    <?php endif ?>


</main>
