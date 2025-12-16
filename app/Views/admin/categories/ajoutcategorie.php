<main class="flex justify-center mt-16 px-4">

    <div class="w-full max-w-2xl">

        <!-- Bouton retour -->
        <div class="mb-6">
            <?= anchor('categgest/listecateg', '<i class="fas fa-arrow-left mr-2"></i> Retour', ['class' => "inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition"]) ?>
        </div>

        <?php
        $input = [
            'class' => 'bg-white p-10 rounded-lg shadow-md w-full space-y-6',
            'enctype' => 'multipart/form-data'
        ];
        echo form_open('categgest/ajoutcategorie', $input);
        ?>

        <!-- MESSAGES -->
        <div>
            <?php
            $session = session();
            if ($session->getFlashdata('errorCategAdd')) {
                echo '
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("errorCategAdd") . '</span>
                    </div>';
            } else if ($session->getFlashdata('successCategAdd')) {
                echo '
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("successCategAdd") . '</span>
                    </div>';
            }
            ?>
        </div>

        <!-- NOM DE LA CATEGORIE -->
        <div>
            <?= form_label('Nom Catégorie', 'nomCateg', ['class' => 'block text-gray-700 font-semibold mb-2']) ?>
            <?= form_input('nomCateg', '', [
                'class' => 'w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500',
                'id' => 'nomCateg',
                'placeholder' => 'Nom de la catégorie',
                'required' => true
            ]) ?>
        </div>

        <!-- BOUTON SUBMIT -->
        <div>
            <?= form_submit('ajoutCategorie', 'Ajouter le produit', [
                'class' =>
                    'w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 rounded-lg transition'
            ]) ?>
        </div>

        <?= form_close() ?>
    </div>
</main>
