<main class="flex items-start justify-center min-h-[40vh] bg-gray-100 pt-14">
    <div class="bg-white shadow rounded-xl p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold text-blue-600 mb-6 text-center">Changement de mot de passe</h1>

        <div>
            <?php
            $session = session();
            if ($session->getFlashdata('errorInsc')) {
                echo ' 
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("errorInsc") . '</span>
                    </div>';
            }else if ($session->getFlashdata('successInsc')) {
                echo ' 
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("successInsc") . '</span>
                    </div>';
            }
            ?>
        </div>

        <!--Début du formulaire de connexion-->
        <?php
        $input = [
            'class' => 'space-y-4'
        ];
        echo form_open('auth/changepassword', $input)
        ?>

        <div>
            <?php
            $input = [
                'class' => 'block text-gray-700 mb-1',
                'required' => 'true'
            ];
            echo form_label('Mot de passe :', 'password', $input);

            $input = [
                'class' => 'w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600',
                'placeholder' => '••••••••',
                'id' => 'newPassword',
                'required' => 'true',
            ];
            echo form_password('password', '', $input);


            ?>
        </div>
        <div>
            <?php
            $input = [
                'class' => 'block text-gray-700 mb-1',
                'required' => 'true'
            ];
            echo form_label('Confirmer le mot de passe :', 'confirmPassword', $input);

            $input = [
                'class' => 'w-full border rounded px-3 py-2 focus:outline-none',
                'placeholder' => '••••••••',
                'id' => 'confirmPassword',
                'required' => 'true',
                'oninput' => 'checkPassword(this.value)',
            ];
            echo form_input('confirmPassword', '', $input, 'password');
            echo '<p id="password-error" class="text-red-500 text-sm mt-1 hidden">Les mots de passe ne correspondent pas.</p>';


            $input = [
                'class' => 'w-full bg-blue-600 text-white py-2 mt-2 rounded-xl font-semibold hover:bg-blue-700',
                'id' => 'submitPassChange',
            ];
            echo form_submit('submit', 'Changer le mot de passe', $input)
            ?>
        </div>
    </div>
</main>
