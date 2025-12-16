<main class="flex justify-center mt-16 px-4">

    <div class="w-full max-w-2xl">

        <!-- Bouton retour -->
        <div class="mb-6">
            <?= anchor('prodgest/listeproduits/1', '<i class="fas fa-arrow-left mr-2"></i> Retour', ['class' => "inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition"]) ?>
        </div>

        <?php
        $input = [
            'class' => 'bg-white p-10 rounded-lg shadow-md w-full space-y-6',
            'enctype' => 'multipart/form-data'
        ];
        echo form_open('prodgest/ajoutproduit', $input);
        ?>

        <!-- MESSAGES -->
        <div>
            <?php
            $session = session();
            if ($session->getFlashdata('errorProdAdd')) {
                echo '
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("errorProdAdd") . '</span>
                    </div>';
            } elseif ($session->getFlashdata('successProdAdd')) {
                echo '
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("successProdAdd") . '</span>
                    </div>';
            }
            ?>
        </div>

        <!-- NOM DU PRODUIT -->
        <div>
            <?= form_label('Nom du produit', 'nomProduit', ['class' => 'block text-gray-700 font-semibold mb-2']) ?>
            <?= form_input('nomProduit', '', [
                'class' => 'w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500',
                'id' => 'nomProduit',
                'placeholder' => 'Nom du produit',
                'required' => true
            ]) ?>
        </div>

        <!-- DESCRIPTION -->
        <div>
            <?= form_label('Description du produit', 'descProduit', ['class' => 'block text-gray-700 font-semibold mb-2']) ?>
            <?= form_textarea('descProduit', '', [
                'class' => 'w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500',
                'rows' => 5,
                'id' => 'description',
                'placeholder' => 'Description du produit',
                'required' => true
            ]) ?>
        </div>

        <!-- PRIX -->
        <div>
            <?= form_label('Prix du produit (€)', 'prixProduit', ['class' => 'block text-gray-700 font-semibold mb-2']) ?>
            <?= form_input('prixProduit', '', [
                'class' => 'w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500',
                'id' => 'prixProduit',
                'placeholder' => '0.00',
                'step' => '0.01',
                'required' => true
            ], 'number') ?>
        </div>

        <!-- STOCK -->
        <div>
            <?= form_label('Stock du produit', 'stockProduit', ['class' => 'block text-gray-700 font-semibold mb-2']) ?>
            <?= form_input('stockProduit', '0', [
                'class' => 'w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500',
                'id' => 'prixProduit',
                'placeholder' => '0',
                'required' => true
            ], 'number') ?>
        </div>

        <!-- IMAGE -->
        <div>
            <?= form_label('Image du produit', 'imageProduit', ['class' => 'block text-gray-700 font-semibold mb-2']) ?>
            <?= form_upload('imageProduit', '', [
                'class' => 'w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500',
                'id' => 'imageProduit'
            ]) ?>
        </div>

        <!-- CATÉGORIE -->
        <div>
            <?= form_label('Catégorie du produit', 'categProduit', ['class' => 'block text-gray-700 font-semibold mb-2']) ?>

            <?php
            $categOptions = [];
            foreach ($categories as $categorie) {
                $categOptions[$categorie['id']] = $categorie['nom'];
            }
            ?>

            <?= form_dropdown('categProduit', $categOptions, '', [
                'class' => 'w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500',
                'id' => 'categProduit',
                'required' => true
            ]) ?>
        </div>

        <!-- BOUTON SUBMIT -->
        <div>
            <?= form_submit('ajoutProduit', 'Ajouter le produit', [
                'class' =>
                    'w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 rounded-lg transition'
            ]) ?>
        </div>

        <?= form_close() ?>
    </div>
</main>
