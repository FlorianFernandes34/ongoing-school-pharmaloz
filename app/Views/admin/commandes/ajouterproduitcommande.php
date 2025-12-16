<main class="p-8 max-w-5xl mx-auto">

    <!-- BOUTONS -->
    <div class="mb-8 flex items-center">
        <a href="<?= base_url('commgest/listeproduitscomm/' . $commande->id) ?>"
           class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la commande
        </a>
    </div>

    <div>
        <?php
        $session = session();
        if ($session->getFlashdata('errorAddPC')) {
            echo '
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("errorAddPC") . '</span>
                    </div>';
        } else if ($session->getFlashdata('successAddPC')) {
            echo '
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("successAddPC") . '</span>
                    </div>';
        }
        ?>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ajouter un article à la commande</h1>

    <!-- Sélection article avec recherche -->
    <div class="bg-white rounded-xl shadow border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Sélection de l’article</h2>

        <?= form_open('commgest/ajoutproduitcommande')?>

        <!-- ID COMMANDE (hidden) -->
        <?= form_hidden('idCommande', strval($commande->id))?>

        <!-- Barre de recherche -->
        <div class="mb-4 max-w-md">
            <?= form_label('Rechercher un produit :', '', ['class' => 'block text-sm font-medium text-gray-600 mb-1']) ?>
            <?= form_input('', '', [
                'id'          => 'searchProduit',
                'placeholder' => 'Ex : Paracétamol, gel, ibuprofène...',
                'class'       => 'w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none'
            ]) ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <?= form_label('Produit :', 'produitSelect', ['class' => 'block text-sm font-medium text-gray-600 mb-1'])?>
                <?php
                $produitsListe = [];
                foreach ($produits as $produit) {
                    $produitsListe[$produit->id] = $produit->nom;
                }
                echo form_dropdown('produitSelect', $produitsListe, '', ['id' => 'produitSelect', 'class' => 'w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none']);
                ?>
            </div>

            <div>
                <?= form_label('Quantité', 'qte', ['class' => 'block text-sm font-medium text-gray-600 mb-1'])?>
                <?= form_input('qte', '1', ['id' => 'qteInput', 'class' => 'w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none', 'min' => '1'], 'number')?>
            </div>
        </div>
    </div>

    <!-- Récap produit -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Récapitulatif</h2>

        <div class="space-y-2 text-gray-700">
            <p id="denominationProduit"><span class="font-medium" >Produit :</span > Paracétamol 500mg</p>
            <p id="produitPu"><span class="font-medium" >Prix unitaire :</span> 2.50 €</p>
            <p id="qteProduit"><span class="font-medium">Quantité :</span> 2</p>
            <p class="text-lg font-semibold" id="total"><span class="font-medium">Total :</span> 5.00 €</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end space-x-3">
        <a href="<?= base_url('commgest/listeproduitscomm/' . $commande->id) ?>"
           class="px-5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition">
            Annuler
        </a>

        <?= form_submit('ajouterProduit', 'Ajouter à la commande', ['class' => 'px-6 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium shadow transition'])?>
        <?= form_close()?>
    </div>

    <!-- JS filtrage produit -->
    <script>
        const select = document.getElementById('produitSelect');

        // Création de l'input intégré au-dessus du select
        const input = document.getElementById('searchProduit');

        const options = Array.from(select.options);

        input.addEventListener('input', () => {
            const value = input.value.toLowerCase();
            let firstVisible = null;

            options.forEach(option => {
                const match = option.text.toLowerCase().includes(value);
                option.hidden = !match;

                if(match && !firstVisible) firstVisible = option;
            });

            // Sélectionne automatiquement la première option visible
            if(firstVisible) {
                select.value = firstVisible.value;
            }
        });
    </script>

</main>
