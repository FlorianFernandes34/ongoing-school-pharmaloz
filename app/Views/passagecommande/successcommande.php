<main class="max-w-4xl mx-auto px-6 py-16">

    <div class="bg-white rounded-2xl shadow p-10 text-center space-y-8">

        <!-- ICON SUCCESS -->
        <div class="flex justify-center">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-green-600 text-4xl"></i>
            </div>
        </div>

        <!-- TITRE -->
        <div class="space-y-2">
            <h1 class="text-3xl font-bold text-gray-800">
                Commande validée !
            </h1>

            <p class="text-gray-600">
                Votre commande a bien été enregistrée.
            </p>
        </div>

        <!-- MESSAGE INFO -->
        <p class="text-sm text-gray-500">
            Vous pouvez venir récupérer votre commande au créneau choisi.
        </p>

        <!-- ACTIONS -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">

            <a href="<?= base_url('produits/produitsliste/all') ?>" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                <i class="fas fa-shopping-bag"></i>
                Continuer mes achats
            </a>

            <a href="<?= base_url('account') ?>" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition flex items-center justify-center gap-2">
                <i class="fas fa-receipt"></i>
                Voir mes commandes
            </a>
        </div>
    </div>
</main>
