<main class="flex items-start justify-center min-h-[40vh] bg-gray-100 pt-14">
    <div class="bg-white shadow rounded-xl p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold text-blue-600 mb-6 text-center">Inscription à PharmaLoz</h1>

        <!--Début du formulaire de connexion-->
        <?php
        $input = [
            'class' => 'space-y-4'
        ];
        echo form_open('login/inscription', $input)
        ?>


        <div>
            <?php
                $input = [
                    'class' => 'block text-gray-700 mb-1',
                    'required' => 'true'
                ];
                echo form_label('Nom :', 'nom', $input);
                $input = [
                    'class' => 'w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600',
                    'placeholder' => 'Votre nom',
                    'required' => 'true',
                ];
                echo form_input('nom', '', $input, 'text');
            ?>
        </div>
        <div>
            <?php
                $input = [
                    'class' => 'block text-gray-700 mb-1',
                    'required' => 'true'
                ];
                echo form_label('Prénom :', 'prenom', $input);
                $input = [
                    'class' => 'w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600',
                    'placeholder' => 'Votre prénom',
                    'required' => 'true',
                ];
                echo form_input('prenom', '', $input);
            ?>
        </div>
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
                'class' => 'block text-gray-700 mb-1',
                'required' => 'true'
            ];
            echo form_label('Mot de passe :', 'mdp', $input);

            $input = [
                'class' => 'w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600',
                'placeholder' => '••••••••',
                'required' => 'true',
            ];
            echo form_input('mdp', '', $input, 'password');

            $input = [
                'class' => 'w-full bg-blue-600 text-white py-2 mt-2 rounded-xl font-semibold hover:bg-blue-700',
            ];
            echo form_submit('submit', 'S\'inscrire', $input)
            ?>
        </div>

        <p class="text-center text-gray-600 mt-4">Vous avez déjà un compte ? <a href="<?= base_url('login')?>" class="text-blue-600 hover:underline">Connectez-vous</a></p>
    </div>
</main>
