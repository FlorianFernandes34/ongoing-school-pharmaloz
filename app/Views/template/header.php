<body class="bg-gray-50 text-gray-800">

<!-- HEADER -->
<header class="bg-white shadow sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-6">

        <!-- LOGO -->
        <a href="<?= base_url() ?>" class="flex items-center gap-3">
            <span class="text-2xl font-bold text-blue-600">PharmaLoz</span>
        </a>


        <nav class="hidden md:flex items-center gap-2 font-medium">

            <a href="<?= base_url() ?>" class="px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                Accueil
            </a>

            <div class="relative group">
                <a href="<?= base_url('produits/produitsliste/all') ?>" class="px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2">
                    Produits
                    <i class="fas fa-chevron-down text-xs"></i>
                </a>

                <div class="absolute left-0 top-full pt-2 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">

                    <div class="bg-white border rounded-xl shadow-lg">

                        <?= anchor('produits/produitsliste/all', 'Tous les produits', ['class'=>'block px-4 py-2 hover:bg-blue-50']) ?>

                        <?php
                        foreach ($categories as $category) {

                            echo anchor('produits/produitsliste/'.$category->nom_lien, $category->nom, ['class'=>'block px-4 py-2 hover:bg-blue-50']
                            );
                        }
                        ?>
                    </div>
                </div>
            </div>
        </nav>

        <!-- ACTIONS -->
        <div class="flex items-center gap-4">

            <!-- PANIER -->
            <a href="<?= base_url('panier') ?>" class="relative p-2 rounded-lg hover:bg-blue-50">
                <i class="fas fa-shopping-cart text-xl"></i>
                <?php
                    $panier = session()->get('panier');
                ?>
                <span id="cart-count" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                    <?= session()->get('cartCount') ?? 0 ?>
                </span>
            </a>

            <!-- COMPTE -->
            <?php
            $session = session();

            if ($session->get('connected')) {
                if ($session->get('isAdmin')) {
                    echo anchor('admin',
                            '<i class="fa-solid fa-user-shield"></i>',
                            ['class'=>'p-2 rounded-lg hover:bg-blue-50']
                    );
                } else {
                    echo anchor('account',
                            '<i class="fa-solid fa-user"></i>',
                            ['class'=>'p-2 rounded-lg hover:bg-blue-50']
                    );
                }
            } else {
                echo anchor('auth/connexion',
                        '<i class="fa-solid fa-user"></i>',
                        ['class'=>'p-2 rounded-lg hover:bg-blue-50']
                );
            }
            ?>

            <!-- BURGER -->
            <button id="menu-btn" class="md:hidden text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div id="mobile-menu" class="md:hidden hidden bg-white border-t">
        <div class="px-6 py-4 space-y-3">
            <a href="<?= base_url() ?>" class="block hover:text-blue-600">Accueil</a>
            <a href="<?= base_url('produitsliste/all') ?>" class="block hover:text-blue-600">Produits</a>
            <a href="<?= base_url('panier') ?>" class="block hover:text-blue-600">Panier</a>

            <?php
            if ($session->get('connected')) {
                echo anchor('account','Mon compte',['class'=>'block hover:text-blue-600']);
            } else {
                echo anchor('auth/connexion','Connexion',['class'=>'block hover:text-blue-600']);
            }
            ?>
        </div>
    </div>
</header>
