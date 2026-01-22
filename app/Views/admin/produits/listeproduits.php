<main class="p-8">

    <!-- BOUTON RETOUR -->
    <div class="mb-6 flex justify-between">
        <!-- Bouton Retour  -->
        <?= anchor('admin', '<i class="fas fa-arrow-left mr-2"></i> Retour', [
            'class' => "inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition"
        ]) ?>

        <!-- Bouton Ajouter un produit -->
        <?= anchor('prodgest/ajoutproduit', '<i class="fas fa-plus mr-2"></i> Ajouter un produit', [
                'class' => 'inline-flex items-center bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow transition'
        ]) ?>
    </div>

    <div>
        <?php
        $session = session();
        if ($session->getFlashdata('errorProdDelete')) {
            echo ' 
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("errorProdDelete") . '</span>
                    </div>';
        }else if ($session->getFlashdata('successProdDelete')) {
            echo ' 
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("successProdDelete") . '</span>
                    </div>';
        }
        ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php if ($produits->isEmpty()): ?>
            <div class="col-span-full flex items-center justify-center min-h-[50vh]">
                 <div class="text-center bg-white border border-gray-200 rounded-2xl shadow-sm px-8 py-10 max-w-md w-full">

                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50">
                        <i class="fas fa-box-open text-3xl text-blue-500"></i>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                        Aucun produit n'a été trouvé
                    </h2>

                    <p class="text-gray-500 mb-6">
                        Aucun produit n'a été trouvé
                    </p>

                    <a href="<?= base_url('admin') ?>"
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2.5 rounded-lg shadow transition">
                        <i class="fas fa-arrow-left"></i>
                        Retour au tableau de bord
                    </a>
                </div>
            </div>
        <?php else:?>
            <?php foreach ($produits as $produit): ?>
                <div class='bg-white rounded-lg shadow-md p-4 flex flex-col h-full'>
                    <?= img('public/img/produits/' . $produit->image . '?t=' . time(), '', ['class' => 'rounded-lg w-full h-40 object-cover', 'alt' => $produit->nom])?>

                    <div class='flex-1 mt-3'>
                        <h2 class='text-xl font-bold text-gray-800'><?= $produit->nom ?></h2>
                        <p class='text-gray-600 mt-1'><?= $produit->description ?></p>
                        <p class='text-gray-800 font-semibold mt-1'>Prix : <?= $produit->prix ?> €</p>
                        <p class='text-gray-500 mt-1'>Catégorie : <?= $produit->categorie->nom ?></p>
                        <p class='text-gray-500 mt-1'>Stock : <?= $produit->stock ?></p>
                    </div>

                    <div class='flex space-x-2 mt-4'>
                        <?= anchor('prodgest/modifproduit/' . $produit->id, '<i class="fas fa-edit mr-2"></i> Modifier', ['class' => 'flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition flex-1']) ?>

                        <?= anchor('prodgest/supprimerproduit/' . $produit->id, '<i class="fas fa-trash mr-2"></i> Supprimer', [
                                'class' => 'flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition flex-1',
                                'onclick' => 'return confirm("Voulez vous vraiment supprimer ce produit ?")'
                        ]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <!-- Pagination -->
    <?php if (!$produits->isEmpty()): ?>
        <div class="mt-8 flex justify-center space-x-2">
            <?php if($currentPage > 1): ?>
                <a href="<?= base_url('prodgest/listeproduits/' . ($currentPage - 1)) ?>"
                   class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo; Précédent</a>
            <?php endif; ?>

            <?php for($i=1; $i<=$lastPage; $i++): ?>
                <a href="<?= base_url('prodgest/listeproduits/' . $i) ?>"
                   class="px-3 py-1 <?= $currentPage==$i ? 'bg-blue-500 text-white' : 'bg-gray-200' ?> rounded hover:bg-gray-300">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if($currentPage < $lastPage): ?>
                <a href="<?= base_url('prodgest/listeproduits/' . ($currentPage + 1)) ?>"
                   class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Suivant &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif;?>
</main>
