<body class="bg-gray-50 text-gray-800">

<!-- HEADER AVEC MENU COMPLET -->
<header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-6xl mx-auto flex justify-between items-center p-4">
        <a href="<?= base_url()?>" class="text-2xl font-bold text-blue-600 hover:text-blue-700">PharmaLoz</a>
        <nav class="hidden md:flex items-center space-x-6 font-medium relative">
            <a href="<?= base_url()?>" class="hover:text-blue-600 px-3 py-2 rounded-lg transition hover:bg-blue-50">Accueil</a>
            <div class="group relative">
                <a href="<?= base_url('produits')?>" class="hover:text-blue-600 px-3 py-2 rounded-lg transition hover:bg-blue-50">Produits</a>
                <!-- Sous-menu catégories -->
                <div class="absolute left-0 top-full mt-2 w-48 bg-white border rounded-lg shadow-lg hidden group-hover:block">
                    <?= anchor('produits/all', 'Tous nos produits', ['class' => 'block px-4 py-2 hover:bg-blue-50']) ?>
                    <?php
                        foreach ($categories as $category) {
                            $categoryName = $category->nom;
                            $categoryName = iconv('UTF-8', 'ASCII//TRANSLIT', $categoryName);
                            $categoryName = strtolower($categoryName);
                            $categorieFormat = preg_replace("/[^a-z0-9]/", "", $categoryName);

                            echo anchor('produits/'. $categorieFormat, $category->nom, ['class' => 'block px-4 py-2 hover:bg-blue-50']);
                        }
                    ?>
                </div>
            </div>
            <?php
                $session = session();

                if ($session->get('connected')) {
                    if ($session->get('isAdmin')) {
                        echo anchor('admin', '<i class="fa-solid fa-user"></i>');
                    }else {
                        echo  anchor('auth/account', '<i class="fa-solid fa-user"></i>', ['class' => 'hover:text-blue-600 px-3 py-2 rounded-lg transition hover:bg-blue-50']);
                    }
                }else {
                    echo anchor('auth/connexion', '<i class="fa-solid fa-user"></i>', ['class' => 'hover:text-blue-600 px-3 py-2 rounded-lg transition hover:bg-blue-50']);
                }
            ?>
            <a href="<?= base_url('panier')?>" class="relative px-3 py-2 rounded-lg transition hover:bg-blue-50">
                <!-- Logo panier FontAwesome -->
                <i class="fas fa-shopping-cart text-gray-800 hover:text-blue-600 text-xl"></i>
                <span id="cart-count" class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">0</span>
            </a>
        </nav>
        <!-- Hamburger menu mobile -->
        <div class="md:hidden">
            <button id="menu-btn" class="focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Menu mobile caché -->
    <div id="mobile-menu" class="md:hidden hidden bg-white border-t">
        <a href="index.html" class="block px-6 py-3 hover:bg-blue-50">Accueil</a>
        <a href="produits.html" class="block px-6 py-3 hover:bg-blue-50">Produits</a>
        <a href="panier.html" class="block px-6 py-3 hover:bg-blue-50">Panier</a>
        <a href="admin.html" class="block px-6 py-3 hover:bg-blue-50">Admin</a>
    </div>
</header>

<script>
    const btn = document.getElementById('menu-btn');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    let cartCount = 0;
    const cartCountEl = document.getElementById('cart-count');
    function updateCartCount(count) {
        cartCountEl.textContent = count;
    }
    updateCartCount(cartCount);
</script>