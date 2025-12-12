<main class="p-8">

    <!-- Bouton Ajouter un produit -->
    <div class="mb-6 flex justify-end">
        <?= anchor('admin/ajoutproduit', '<i class="fas fa-plus mr-2"></i> Ajouter un produit', ['class' => 'inline-flex items-center bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow transition']
        ) ?>
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
        <?php foreach ($produits as $produit): ?>
            <div class='bg-white rounded-lg shadow-md p-4 flex flex-col h-full'>
                <img src='https://via.placeholder.com/400x200'
                     alt='<?= $produit->nom ?>'
                     class='rounded-lg w-full h-40 object-cover'>

                <div class='flex-1 mt-3'>
                    <h2 class='text-xl font-bold text-gray-800'><?= $produit->nom ?></h2>
                    <p class='text-gray-600 mt-1'><?= $produit->description ?></p>
                    <p class='text-gray-800 font-semibold mt-1'>Prix : <?= $produit->prix ?> €</p>
                    <p class='text-gray-500 mt-1'>Catégorie : <?= $produit->categorie->nom ?></p>
                </div>

                <div class='flex space-x-2 mt-4'>
                    <?= anchor('admin/modifproduit/' . $produit->id, '<i class="fas fa-edit mr-2"></i> Modifier', ['class' => 'flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition flex-1']) ?>

                    <?= anchor('admin/supprimerproduit/' . $produit->id, '<i class="fas fa-trash mr-2"></i> Supprimer', ['class' => 'flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition flex-1']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center space-x-2">
        <?php if($currentPage > 1): ?>
            <a href="<?= base_url('admin/listeproduits/' . ($currentPage - 1)) ?>"
               class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo; Précédent</a>
        <?php endif; ?>

        <?php for($i=1; $i<=$lastPage; $i++): ?>
            <a href="<?= base_url('admin/listeproduits/' . $i) ?>"
               class="px-3 py-1 <?= $currentPage==$i ? 'bg-blue-500 text-white' : 'bg-gray-200' ?> rounded hover:bg-gray-300">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if($currentPage < $lastPage): ?>
            <a href="<?= base_url('admin/listeproduits/' . ($currentPage + 1)) ?>"
               class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Suivant &raquo;</a>
        <?php endif; ?>
    </div>
</main>
