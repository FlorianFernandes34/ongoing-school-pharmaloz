<!-- FOOTER -->
<footer class="bg-gray-50 border-t mt-20">

    <div class="max-w-7xl mx-auto px-8 py-14 grid grid-cols-1 md:grid-cols-4 gap-10 text-gray-700">

        <!-- Logo -->
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-clinic-medical text-blue-600 text-3xl"></i>
                <span class="text-2xl font-bold text-gray-800">PharmaLoz</span>
            </div>
        </div>

        <!-- Navigation -->
        <div>
            <h4 class="font-semibold text-lg mb-4">Navigation</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="<?= base_url('/') ?>" class="hover:text-blue-600">Accueil</a></li>
                <li><a href="<?= base_url('/produits') ?>" class="hover:text-blue-600">Produits</a></li>
                <li><a href="<?= base_url('/categories') ?>" class="hover:text-blue-600">Catégories</a></li>
                <li><a href="<?= base_url('/contact') ?>" class="hover:text-blue-600">Contact</a></li>
            </ul>
        </div>

        <!-- Infos légales -->
        <div>
            <h4 class="font-semibold text-lg mb-4">Informations</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="<?= base_url('/mentions-legales') ?>" class="hover:text-blue-600">Mentions légales</a></li>
                <li><a href="<?= base_url('/cgu') ?>" class="hover:text-blue-600">Conditions générales</a></li>
                <li><a href="<?= base_url('/confidentialite') ?>" class="hover:text-blue-600">Confidentialité</a></li>
            </ul>
        </div>

        <!-- Pratique -->
        <div>
            <h4 class="font-semibold text-lg mb-4">Pratique</h4>
            <ul class="space-y-2 text-sm">
                <li class="flex items-center gap-2">
                    <i class="fas fa-store text-blue-600"></i>
                    <span>Retrait en pharmacie</span>
                </li>
                <li class="flex items-center gap-2">
                    <i class="fas fa-clock text-blue-600"></i>
                    <span>Lun–Sam : 9h–19h</span>
                </li>
            </ul>
        </div>

    </div>

    <!-- Bas de footer -->
    <div class="border-t py-4 text-center text-sm text-gray-500">
        © <?= Date('Y')?> <span class="font-semibold">PharmaLoz</span> — Tous droits réservés.
    </div>

</footer>

<!-- JavaScript -->
<script src="<?= base_url('public/js/script.js')?>"></script>

</body>
</html>
