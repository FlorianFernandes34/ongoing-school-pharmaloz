<main class="p-8">

    <!-- BOUTONS -->
    <div class="mb-6 flex justify-between items-center">
        <?= anchor('admin', '<i class="fas fa-arrow-left mr-2"></i> Retour', [
            'class' => "inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg shadow-sm transition"
        ]) ?>

        <?= anchor('admin/ajoutcategorie', '<i class="fas fa-plus mr-2"></i> Ajouter une catégorie', [
            'class' => 'inline-flex items-center bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition',
        ]) ?>
    </div>

    <!-- Messages flash -->
    <div>
        <?php
        $session = session();
        if ($session->getFlashdata('errorCategDelete')) {
            echo '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 text-sm">' . $session->getFlashdata("errorCategDelete") . '</div>';
        } else if ($session->getFlashdata('successCategDelete')) {
            echo '<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 text-sm">' . $session->getFlashdata("successCategDelete") . '</div>';
        }
        ?>
    </div>

    <!-- Liste des catégories -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($categories as $categorie): ?>
            <div class="bg-white rounded-xl shadow-md p-5 flex flex-col justify-between border border-gray-100 hover:shadow-lg transition-all">

                <!-- Titre -->
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3 truncate"><?= $categorie->nom ?></h2>
                </div>

                <!-- Boutons -->
                <div class="flex space-x-2 mt-4">
                    <?= anchor('admin/modifcategorie/' . $categorie->id, '<i class="fas fa-edit mr-1"></i> Modifier', [
                        'class' => 'flex-1 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-lg transition'
                    ]) ?>

                    <?= anchor('admin/supprimercategorie/' . $categorie->id, '<i class="fas fa-trash mr-1"></i> Supprimer', [
                        'class' => 'flex-1 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-3 rounded-lg transition',
                        'onclick' => 'return confirm("Voulez vous vraiment supprimer cette catégorie ?")'
                    ]) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>
