<!-- MAIN -->
<main class="max-w-7xl mx-auto p-8 space-y-24">

    <!-- HERO -->
    <section class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-lg">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center p-12">

            <div class="md:w-1/2 space-y-6">
                <h1 class="text-5xl font-bold leading-tight">
                    Commandez en ligne,<br>retirez en pharmacie
                </h1>

                <p class="text-lg">
                    PharmaLoz vous permet de réserver facilement vos produits
                    <strong>sans ordonnance</strong> et de les récupérer directement
                    dans votre pharmacie.
                </p>

                <div class="flex gap-4">
                    <a href="produits.html" class="inline-block px-8 py-4 bg-white text-blue-700 font-bold rounded-xl hover:bg-gray-100 transition">
                        Voir les produits
                    </a>

                    <a href="#commentcamarche" class="inline-block px-8 py-4 border border-white text-white font-semibold rounded-xl hover:bg-white hover:text-blue-700 transition">
                        Comment ça marche ?
                    </a>
                </div>
            </div>

            <div class="md:w-1/2 mt-10 md:mt-0">
                <img src="<?= base_url('public/img/pharmaloz.png') ?>" alt="Pharmacie"
            </div>

        </div>
    </section>

    <!-- FONCTIONNEMENT -->
    <section id="commentcamarche">
        <h2 class="text-3xl font-bold text-center mb-12" >Comment ça fonctionne ?</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">

            <div class="bg-white p-8 rounded-3xl shadow hover:shadow-xl transition">
                <i class="fas fa-search text-5xl text-blue-600 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">1. Choisissez vos produits</h3>
                <p class="text-gray-600">Parcourez notre catalogue de produits sans ordonnance.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow hover:shadow-xl transition">
                <i class="fas fa-shopping-cart text-5xl text-green-500 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">2. Validez votre commande</h3>
                <p class="text-gray-600">Sélectionnez votre créneau de retrait en pharmacie.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow hover:shadow-xl transition">
                <i class="fas fa-store text-5xl text-purple-500 mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">3. Retirez en pharmacie</h3>
                <p class="text-gray-600">Votre commande est prête à votre arrivée.</p>
            </div>

        </div>
    </section>

    <!-- CATEGORIES -->
    <section>
        <h2 class="text-3xl font-bold text-center mb-12">Nos catégories</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="bg-white p-8 rounded-3xl shadow-md hover:shadow-xl transition text-center hover:-translate-y-2">
                <i class="fas fa-pills text-5xl text-blue-600 mb-4"></i>
                <h3 class="text-2xl font-semibold mb-2">Médicaments</h3>
                <p class="text-gray-600 mb-4">Douleurs, fièvre, rhume, digestion...</p>
                <a href="produits.html#medicaments" class="text-blue-600 font-semibold hover:underline">
                    Voir →
                </a>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-md hover:shadow-xl transition text-center hover:-translate-y-2">
                <i class="fas fa-leaf text-5xl text-green-600 mb-4"></i>
                <h3 class="text-2xl font-semibold mb-2">Bien-être</h3>
                <p class="text-gray-600 mb-4">Sommeil, stress, vitamines, énergie.</p>
                <a href="produits.html#bien-etre" class="text-green-600 font-semibold hover:underline">
                    Voir →
                </a>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-md hover:shadow-xl transition text-center hover:-translate-y-2">
                <i class="fas fa-heart text-5xl text-pink-500 mb-4"></i>
                <h3 class="text-2xl font-semibold mb-2">Beauté & Soins</h3>
                <p class="text-gray-600 mb-4">Visage, corps, cheveux, hygiène.</p>
                <a href="produits.html#beaute" class="text-pink-600 font-semibold hover:underline">
                    Voir →
                </a>
            </div>

        </div>
    </section>

    <!-- AVANTAGES -->
    <section class="bg-gray-100 rounded-3xl p-12">
        <h2 class="text-3xl font-bold text-center mb-12">Pourquoi choisir PharmaLoz ?</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">

            <div class="space-y-4">
                <i class="fas fa-clock text-4xl text-blue-600"></i>
                <h3 class="font-semibold text-xl">Gain de temps</h3>
                <p class="text-gray-700">Plus besoin de faire la queue.</p>
            </div>

            <div class="space-y-4">
                <i class="fas fa-user-shield text-4xl text-green-500"></i>
                <h3 class="font-semibold text-xl">Conseils pharmaciens</h3>
                <p class="text-gray-700">Des professionnels à votre écoute.</p>
            </div>

            <div class="space-y-4">
                <i class="fas fa-check-circle text-4xl text-purple-500"></i>
                <h3 class="font-semibold text-xl">Sans ordonnance</h3>
                <p class="text-gray-700">Produits autorisés à la vente libre.</p>
            </div>

        </div>
    </section>

</main>
