<main class="flex items-start justify-center min-h-[40vh] bg-gray-100 pt-14">
    <div class="bg-white shadow rounded-xl p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold text-blue-600 mb-6 text-center">Changer votre mot de passe</h1>

        <?php
            $session = session();
            if ($session->getFlashdata('errorCodeValidation')) {
                echo ' 
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("errorCodeValidation") . '</span>
                    </div>
                ';
            }elseif ($session->getFlashdata('errorChangePassword')) {
                echo ' 
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("errorChangePassword") . '</span>
                    </div>
                ';
            }
        ?>


        <?php
        $input = [
            'class' => 'space-y-4'
        ];
        echo form_open('auth/mailchangepassword', $input)
        ?>

        <div>
            <?php
            $input = [
                'class' => 'block text-gray-700 mb-1',
                'required' => 'true'
            ];
            echo form_label('Email :', 'email', $input);
            $input = [
                'class' => 'w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600',
                'placeholder' => 'votre@email.com',
                'required' => 'true',
            ];
            echo form_input('email', '', $input, 'email');
            ?>
        </div>
        <div>
            <?php
            $input = [
                'class' => 'w-full bg-blue-600 text-white py-2 rounded-xl font-semibold hover:bg-blue-700',
            ];
            echo form_submit('submit', 'Envoyer un code de réinitialisation', $input)
            ?>
        </div>

        <p class="text-center text-gray-600 mt-4"><a href="<?= base_url('auth/connexion')?>" class="hover:underline">Retour</a></p>
    </div>
</main>
