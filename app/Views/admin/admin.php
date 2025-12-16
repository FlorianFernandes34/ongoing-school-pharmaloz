<main class="flex flex-col items-center mt-16 space-y-6">


    <?php

        $input = [
            'class' => 'w-64 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition'
        ];
        echo anchor('prodgest/listeproduits/1', 'Liste des produits', $input);

        $input = [
            'class' => 'w-64 bg-green-500 hover:bg-green-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition'
        ];
        echo anchor('categgest/listecateg', 'Liste des catégories', $input);

        $input = [
            'class' => 'w-64 bg-purple-500 hover:bg-purple-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition'
        ];
        echo anchor('commgest/listecom/1', 'Liste des commandes', $input);

        $input = [
            'class' => 'w-64 bg-red-500 hover:bg-red-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition'
        ];
        echo anchor('admin/ajoutadmin', 'Ajouter un admin', $input);
    ?>
</main>