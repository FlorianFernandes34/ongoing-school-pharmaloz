<div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-4 gap-8">
    <!-- Sidebar -->
    <aside class="bg-white shadow-lg rounded-2xl p-6 space-y-6">
        <h2 class="font-bold text-xl text-gray-800">Mon compte</h2>

        <ul class="space-y-1 text-sm font-medium">
            <li>
                <?= anchor('account', '<i class="fas fa-home mr-3 text-gray-400"></i> Accueil', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition']) ?>
            </li>
            <li>
                <?= anchor('account/updatepassword', '<i class="fas fa-lock mr-3 text-gray-400"></i> Modifier mon mot de passe', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition']) ?>
            </li>
            <li>
                <?= anchor('account/updatemail', '<i class="fas fa-envelope mr-3 text-gray-400"></i> Modifier mon email', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition']) ?>
            </li>
            <li>
                <?= anchor('account/updateinfos', '<i class="fas fa-user mr-3 text-gray-400"></i> Modifier mes informations', ['class' => 'flex items-center px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition']) ?>
            </li>
            <li class="my-3 border-t border-gray-200"></li>
            <li>
                <?= anchor('auth/logout', '<i class="fas fa-sign-out-alt mr-3"></i> Se déconnecter', ['class' => 'flex items-center px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition']) ?>
            </li>
            <li>
                <?= anchor('auth/deleteaccountdata', '<i class="fas fa-trash mr-3"></i> Supprimer mon compte et mes données', [
                    'class' => 'flex items-center px-4 py-3 rounded-lg text-red-700 hover:bg-red-100 transition',
                    'onclick' => 'return doubleConfirmOnClick("Voulez-vous supprimer votre compte et toutes vos données ?", "Cette action est irréversible. Continuer ?")'
                ]) ?>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="md:col-span-3 space-y-8">
        <div>
            <?php
            $session = session();
            if ($session->getFlashdata('errorPassChange')) {
                echo ' 
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("errorPassChange") . '</span>
                    </div>';
            }else if ($session->getFlashdata('successPassChange')) {
                echo ' 
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("successPassChange") . '</span>
                    </div>';
            }
            ?>
        </div>

        <section class="bg-white shadow rounded-xl p-6">
            <h3 class="text-2xl font-bold mb-4">Modifier mon mot de passe</h3>

            <?= form_open('auth/updatepassword', ['class' => 'space-y-5']) ?>

            <div>
                <?= form_label('Mot de passe actuel', 'current_password', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>
                <?= form_password('current_password', '', ['class' => 'w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400', 'required' => 'true']) ?>
            </div>

            <div>
                <?= form_label('Nouveau mot de passe', 'new_password', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>
                <?= form_password('new_password', '', ['id' => 'newPassword','class' => 'w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400', 'required' => 'true']) ?>
            </div>

            <div>
                <?= form_label('Confirmer le nouveau mot de passe', 'confirm_password', ['class' => 'block text-sm font-medium text-gray-700 mb-1']) ?>
                <?= form_password('confirm_password', '', ['id' => 'confirmPassword', 'oninput' => 'checkPassword(this.value)','class' => 'w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400', 'required' => 'true']) ?>
                <p id="password-error" class="text-red-500 text-sm mt-1 hidden">Les mots de passe ne correspondent pas.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <?= form_submit('submit', 'Mettre à jour', ['id' => 'submitPassChange', 'class' => 'px-6 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition']) ?>
            </div>

            <?= form_close() ?>
        </section>
    </main>
</div>
