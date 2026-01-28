<main class="flex items-start justify-center min-h-[40vh] bg-gray-100 pt-14">
    <div class="bg-white shadow rounded-xl p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold text-blue-600 mb-6 text-center">Changer votre mot de passe</h1>
        <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            <div class="flex items-start gap-2">
                <i class="fas fa-info-circle mt-0.5"></i>
                <p>
                    Si un compte est rattaché à adresse e-mail,
                    <strong>un code de vérification vous sera envoyé</strong>.
                    Pensez à vérifier vos spams.
                </p>
            </div>
        </div>



        <?php
        $input = [
            'class' => 'space-y-4'
        ];
        echo form_open('auth/codechangepassword', $input)
        ?>

        <div>
            <?php
            $input = [
                'class' => 'block text-gray-700 mb-1',
                'required' => 'true'
            ];
            echo form_label('Code reçu :', 'code', $input);
            $input = [
                'class' => 'w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600',
                'placeholder' => '123456',
                'required' => 'true',
            ];
            echo form_input('code', '', $input, 'number');
            ?>
        </div>
        <div>
            <?php
            $input = [
                'class' => 'w-full bg-blue-600 text-white py-2 rounded-xl font-semibold hover:bg-blue-700',
            ];
            echo form_submit('submit', 'Vérifier le code', $input)
            ?>
        </div>

        <p class="text-center text-gray-600 mt-4"><a href="<?= base_url('auth/mailchangepassword')?>" class="hover:underline">Retour</a></p>
    </div>
</main>
