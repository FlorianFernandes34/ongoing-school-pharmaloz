<main class="p-8">

    <!-- BOUTONS -->
    <div class="mb-6 flex justify-between items-center">
        <?= anchor('admin', '<i class="fas fa-arrow-left mr-2"></i> Retour', [
            'class' => "inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg shadow-sm transition"
        ]) ?>

        <?= anchor('categgest/ajoutcategorie', '<i class="fas fa-plus mr-2"></i> Ajouter une catégorie', [
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
    <?php if ($categories->isEmpty()): ?>?>
        <div class="col-span-full flex items-center justify-center min-h-[50vh]">
            <div class="text-center bg-white border border-gray-200 rounded-2xl shadow-sm px-8 py-10 max-w-md w-full">

                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50">
                    <i class="fas fa-box-open text-3xl text-blue-500"></i>
                </div>

                <h2 class="text-xl font-semibold text-gray-800 mb-2">
                    Aucune catégories n'a été trouvée
                </h2>

                <p class="text-gray-500 mb-6">
                    Aucune catégories n'a été trouvée
                </p>

                <a href="<?= base_url('admin') ?>"
                   class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2.5 rounded-lg shadow transition">
                    <i class="fas fa-arrow-left"></i>
                    Retour au tableau de bord
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($categories as $categorie): ?>
                <div class="bg-white rounded-xl shadow-md p-5 flex flex-col justify-between border border-gray-100 hover:shadow-lg transition-all">

                    <!-- Titre -->
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3 truncate"><?= $categorie->nom ?></h2>
                    </div>

                    <!-- Boutons -->
                    <div class="flex space-x-2 mt-4">
                        <?= anchor('categgest/modifcategorie/' . $categorie->id, '<i class="fas fa-edit mr-1"></i> Modifier', [
                                'class' => 'flex-1 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-lg transition'
                        ]) ?>

                        <?= anchor('categgest/supprimercategorie/' . $categorie->id, '<i class="fas fa-trash mr-1"></i> Supprimer', [
                                'class' => 'flex-1 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-3 rounded-lg transition',
                                'onclick' => 'return confirm("Voulez vous vraiment supprimer cette catégorie ?")'
                        ]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


</main>
