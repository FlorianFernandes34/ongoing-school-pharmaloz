<main class="p-8 max-w-6xl mx-auto">

    <!-- Header -->
    <div class="mb-6 flex items-center">
        <a href="<?= base_url('admin') ?>"
           class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <!-- Tableau des comptes -->
    <div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rôle</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($comptes as $compte): ?>
                    <tr class="hover:bg-gray-50 transition" data-id="<?= $compte->id?>">
                        <td class="px-6 py-4 text-sm text-gray-800"><?= $compte->nom?>  <?= $compte->prenom?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= $compte->email?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= $compte->role?></td>
                        <td class="px-6 py-4 text-center">
                            <button class="delete-compte text-red-500 hover:text-red-700 font-medium">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>

</main>
