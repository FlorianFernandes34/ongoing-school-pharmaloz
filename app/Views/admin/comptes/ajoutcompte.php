<main class="p-8 max-w-3xl mx-auto">

    <!-- Retour -->
    <div class="mb-6 flex items-center">
        <a href="<?= base_url('comptgest/listecomptes') ?>"
           class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <!-- Alertes -->
    <div id="alerts">
        <?php
        $session = session();
        if ($session->getFlashdata('successAddAccount')) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">' . $session->getFlashdata('successAddAccount') . '</div>';
        } else if ($session->getFlashdata('errorAddAccount')) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">' . $session->getFlashdata('errorAddAccount') . '</div>';
        }
        ?>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ajouter un compte</h1>

    <!-- Formulaire -->
    <?= form_open('comptgest/ajoutercompte') ?>
    <div class="bg-white rounded-xl shadow border border-gray-100 p-6 space-y-4">

        <div>
            <?= form_label('Nom', 'nom', ['class' => 'block text-sm font-medium text-gray-600 mb-1']) ?>
            <?= form_input('nom', '', ['id' => 'nom', 'class' => 'w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none', 'required' => true]) ?>
        </div>

        <div>
            <?= form_label('Prénom', 'prenom', ['class' => 'block text-sm font-medium text-gray-600 mb-1']) ?>
            <?= form_input('prenom', '', ['id' => 'prenom', 'class' => 'w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none', 'required' => true]) ?>
        </div>

        <div>
            <?= form_label('Email', 'email', ['class' => 'block text-sm font-medium text-gray-600 mb-1']) ?>
            <?= form_input('email', '', ['id' => 'email', 'class' => 'w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none', 'required' => true], 'email') ?>
        </div>

        <div>
            <?= form_label('Mot de passe', 'password', ['class' => 'block text-sm font-medium text-gray-600 mb-1']) ?>
            <?= form_password('password', '', ['id' => 'password', 'class' => 'w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none', 'required' => true]) ?>
        </div>

    </div>

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-3">
        <a href="<?= base_url('admin/gestioncomptes') ?>"
           class="px-5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition">
            Annuler
        </a>
        <?= form_submit('ajouterCompte', 'Créer le compte', ['class' => 'px-6 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium shadow transition']) ?>
    </div>
    <?= form_close() ?>
</main>
