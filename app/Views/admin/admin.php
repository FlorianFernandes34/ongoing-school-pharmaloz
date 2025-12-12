<main class="flex flex-col items-center mt-16 space-y-6">


    <?php

        $input = [
            'class' => 'w-64 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition'
        ];
        echo anchor('admin/listeproduits/1', 'Liste des produits', $input);

        $input = [
            'class' => 'w-64 bg-green-500 hover:bg-green-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition'
        ];
        echo anchor('admin/ajoutcateg', 'Liste des catégories', $input);

        $input = [
            'class' => 'w-64 bg-green-500 hover:bg-green-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition'
        ];
        echo anchor('admin/ajoutcateg', 'Ajouter une catégorie', $input);

        $input = [
            'class' => 'w-64 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition'
        ];
        echo anchor('admin/modifcateg', 'Modifer une catégorie', $input);

        $input = [
            'class' => 'w-64 bg-red-500 hover:bg-red-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md text-center transition'
        ];
        echo anchor('admin/ajoutadmin', 'Ajouter un admin', $input);
    ?>
</main>