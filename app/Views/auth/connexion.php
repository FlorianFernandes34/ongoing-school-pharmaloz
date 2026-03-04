<main class="flex items-start justify-center min-h-[40vh] bg-gray-100 pt-14">
    <div class="bg-white shadow rounded-xl p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold text-blue-600 mb-6 text-center">Connexion à PharmaLoz</h1>

        <div>
            <?php
                $session = session();
                if ($session->getFlashdata('errorLogin')) {
                    echo ' 
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("errorLogin") . '</span>
                    </div>';
                }else if ($session->getFlashdata('successLogout')) {
                    echo ' 
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("successLogout") . '</span>
                    </div>';
                }else if ($session->getFlashdata('successInsc')) {
                    echo ' 
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("successInsc") . '</span>
                    </div>';
                }else if ($session->getFlashdata('successDeleteData')) {
                    echo ' 
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("successDeleteData") . '</span>
                    </div>';
                }elseif ($session->getFlashdata('successChangePassword')) {
                    echo ' 
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            <span class="block sm:inline">' . $session->getFlashdata("successChangePassword") . '</span>
                        </div>
                    ';
                }else if ($session->getFlashdata('messageCommandeMessage')) {
                    echo ' 
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">' . $session->getFlashdata("messageCommandeMessage") . '</span>
                    </div>';
                }
            ?>
        </div>

        <!--Début du formulaire de connexion-->
        <?php
            $input = [
                'class' => 'space-y-4'
            ];
            echo form_open('auth/login', $input)
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
                echo form_submit('submit', 'Se connecter', $input)
            ?>
            <p class="text-center text-gray-600 mt-4"><a href="<?= base_url('auth/mailchangepassword')?>" class="text-blue-600 hover:underline">Mot de passe oublié ? </a></p>

        </div>

        <p class="text-center text-gray-600 mt-4">Pas encore de compte ? <a href="<?= base_url('auth/inscription')?>" class="text-blue-600 hover:underline">Inscrivez-vous</a></p>
    </div>
</main>
