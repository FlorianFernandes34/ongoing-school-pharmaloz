<main class="max-w-5xl mx-auto px-6 py-10 space-y-10">

    <!-- TITRE -->
    <div class="space-y-2">
        <h1 class="text-4xl font-bold">Validation de commande</h1>
        <p class="text-gray-600">Vérifiez vos articles et choisissez votre créneau de retrait</p>
    </div>

    <?php if (session()->getFlashdata("errorPassageCommande")) :?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline"><?= session()->getFlashdata("errorPassageCommande") ?></span>;
        </div>
    <?php endif?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <section class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-xl shadow p-6 space-y-6">

                <h2 class="text-2xl font-semibold flex items-center gap-2">
                    <i class="fas fa-shopping-basket text-blue-600"></i>
                    Résumé de votre commande
                </h2>

                <!-- LISTE PRODUITS -->
                <div class="space-y-4">

                    <!-- ITEM -->
                    <?php foreach ($produits as $article) : ?>
                        <div class="flex justify-between items-center border-b pb-3">

                            <div>
                                <p class="font-semibold"><?= $article['produit']->nom ?></p>
                                <p class="text-sm text-gray-500">Quantité : <?= $article['qte'] ?></p>
                            </div>

                            <p class="font-semibold"><?= number_format($article['produit']->prix, 2, ',', ' ') ?> €</p>
                        </div>
                    <?php endforeach?>
                </div>

                <!-- TOTAL -->
                <div class="flex justify-between text-xl font-bold pt-4">
                    <span>Total</span>
                    <span id="checkout-total"><?= $total ?> €</span>
                </div>
            </div>
        </section>

        <aside class="bg-white rounded-xl shadow p-6 space-y-6 h-fit">

            <h2 class="text-2xl font-semibold flex items-center gap-2">
                <i class="fas fa-calendar-check text-blue-600"></i>
                Retrait
            </h2>

            <!-- FORM -->
            <form method="POST" action="<?= base_url('passagecommande/traitementcommande') ?>" class="space-y-5">

                <!-- CRENEAU -->
                <div class="space-y-2">
                    <label for="creneau_retrait" class="font-semibold">Créneau de retrait</label>

                    <select name="creneau_retrait" required class="w-full border rounded-lg p-2">
                        <?php foreach ($creneaux as $creneau): ?>
                            <option value="<?= $creneau->format('Y-m-d H:i:s') ?>">
                                <?= $creneau->format('d/m/Y H:i') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- COMMENTAIRE -->
                <div class="space-y-2">
                    <label for="commentaire" class="font-semibold">Commentaire (optionnel)</label>

                    <textarea name="commentaire" maxlength="200" rows="4" placeholder="Ex : Je passe vers 18h" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none resize-none"></textarea>
                </div>

                <!-- BTN VALIDATION -->
                <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2 font-semibold">
                    <i class="fas fa-check"></i>
                    Confirmer la commande
                </button>
            </form>
        </aside>
    </div>
</main>
