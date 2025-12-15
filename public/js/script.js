/*********************************
 *  MISE À JOUR DU STATUT COMMANDE
 *********************************/
document.addEventListener('DOMContentLoaded', () => {
    initUpdateDropdowns();
    initRechercheCommandes();
    initProduitCommande();
    initGestionQuantite();
});

function initUpdateDropdowns() {
    const buttons = document.querySelectorAll('.status-button');
    if (!buttons.length) return;

    buttons.forEach(button => {
        const container = button.parentElement;
        const dropdown = button.nextElementSibling;

        button.addEventListener('click', e => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        dropdown.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', async () => {
                const newStatus = item.dataset.value;
                const commandeId = container.dataset.id;

                button.querySelector('.status-text').textContent = newStatus;
                const elementStatut = document.getElementById('statut' + commandeId);
                if (elementStatut) elementStatut.innerHTML = `<span class="font-medium text-gray-600">Statut :</span> ${newStatus}`;

                dropdown.classList.add('hidden');

                try {
                    const res = await fetch('http://localhost/pharmaloz/admin/updatestatut', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ idCommande: commandeId, statutCommande: newStatus })
                    });

                    const response = await res.json();
                    if (!response.success) alert('Erreur lors de la mise à jour du statut.');
                } catch (e) {
                    console.error(e);
                    alert('Erreur réseau.');
                }
            });
        });

        document.addEventListener('click', e => {
            if (!container.contains(e.target)) dropdown.classList.add('hidden');
        });
    });
}

/*********************************
 *  RECHERCHE COMMANDES
 *********************************/
function initRechercheCommandes() {
    const searchInput = document.getElementById('searchCommande');
    const searchType = document.getElementById('searchType');
    if (!searchInput || !searchType) return;

    searchInput.addEventListener('input', async () => {
        const query = searchInput.value.trim();
        const type = searchType.value;
        const commandesContainer = document.querySelector('.grid');
        const emptyState = document.getElementById('emptyState');

        try {
            const res = await fetch('http://localhost/pharmaloz/admin/searchcommandes', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ query, type })
            });

            const response = await res.json();
            commandesContainer.innerHTML = '';

            if (response.commandes && response.commandes.length > 0) {
                emptyState.classList.add('hidden');
                commandesContainer.classList.remove('hidden');

                response.commandes.forEach(cmd => {
                    commandesContainer.innerHTML += `
                        <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100">
                            <h2 class="text-lg font-semibold">Commande N°${cmd.id}</h2>
                            <p id="statut${cmd.id}">
                                <span class="font-medium">Statut :</span> ${cmd.statut}
                            </p>
                        </div>`;
                });

                initUpdateDropdowns();
            } else {
                commandesContainer.classList.add('hidden');
                emptyState.classList.remove('hidden');
            }
        } catch (e) {
            console.error(e);
            alert('Erreur lors de la recherche.');
        }
    });
}

/*********************************
 *  AJOUT PRODUIT À COMMANDE
 *********************************/
function initProduitCommande() {
    const selectProduit = document.getElementById('produitSelect');
    const qteInput = document.getElementById('qteInput');
    const searchInput = document.getElementById('searchProduit');
    if (!selectProduit || !qteInput) return;

    const options = Array.from(selectProduit.options);

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const value = searchInput.value.toLowerCase();
            let firstVisible = null;
            options.forEach(option => {
                const match = option.text.toLowerCase().includes(value);
                option.hidden = !match;
                if (match && !firstVisible) firstVisible = option;
            });
            if (firstVisible) {
                selectProduit.value = firstVisible.value;
                changeProduitInfos();
            }
        });
    }

    selectProduit.addEventListener('change', changeProduitInfos);
    qteInput.addEventListener('input', changeProduitTotal);

    if (selectProduit.value) changeProduitInfos();
}

async function changeProduitInfos() {
    const selectProduit = document.getElementById('produitSelect');
    const id = selectProduit.value;
    if (!id) return;

    try {
        const res = await fetch(`http://localhost/pharmaloz/admin/infosproduits/${id}`);
        const data = await res.json();
        if (!data.success) return;

        const denomination = document.getElementById('denominationProduit');
        const puElement = document.getElementById('produitPu');

        denomination.innerHTML = `<span class="font-medium">Produit :</span> ${data.denomination}`;
        puElement.innerHTML = `<span class="font-medium">Prix unitaire :</span> ${data.puProduit} €`;
        puElement.dataset.pu = data.puProduit;

        changeProduitTotal();
    } catch (e) {
        console.error(e);
    }
}

function changeProduitTotal() {
    const qteInput = document.getElementById('qteInput');
    const puElement = document.getElementById('produitPu');
    if (!qteInput || !puElement) return;

    const qte = parseInt(qteInput.value, 10) || 0;
    const pu = parseFloat(puElement.dataset.pu) || 0;

    document.getElementById('qteProduit').innerHTML =
        `<span class="font-medium">Quantité :</span> ${qte}`;
    document.getElementById('total').innerHTML =
        `<span class="font-medium">Total :</span> ${(qte * pu).toFixed(2)} €`;
}

/*********************************
 *  GESTION QUANTITE + SUPPRESSION PRODUITS
 *********************************/
function initGestionQuantite() {
    const rows = document.querySelectorAll('tbody tr');
    if (!rows.length) return;

    rows.forEach(row => {
        const input = row.querySelector('input[type="number"]');
        const btnPlus = row.querySelector('.qty-plus');
        const btnMinus = row.querySelector('.qty-minus');
        const btnDelete = row.querySelector('.delete-article');
        const articleId = row.dataset.articleId;

        // Incrément
        if (btnPlus) btnPlus.addEventListener('click', () => {
            input.value = parseInt(input.value || 0) + 1;
            updateQuantite(articleId, input.value);
        });

        // Décrément
        if (btnMinus) btnMinus.addEventListener('click', () => {
            input.value = Math.max(0, parseInt(input.value || 0) - 1);
            updateQuantite(articleId, input.value);
        });

        // Supprimer produit
        if (btnDelete) btnDelete.addEventListener('click', () => {
            if(confirm("Supprimer ce produit de la commande ?")) {
                row.remove();
                deleteProduit(articleId);
            }
        });

        // Input manuel
        if (input) input.addEventListener('input', () => {
            if(parseInt(input.value) < 0) input.value = 0;
            updateQuantite(articleId, input.value);
        });
    });
}

/* ================================
   AJAX côté serveur
================================ */
async function updateQuantite(articleId, quantite) {
    try {
        const res = await fetch(`/admin/updatequantitecommande/${articleId}/${quantite}`);
        const data = await res.json();
        if (!data.success) {
            console.error('Une erreur est survenue.');
        }
    } catch (e) {
        console.error(e);
    }
}

async function deleteProduit(articleId) {
    try {
        const res = await fetch(`/admin/deleteproduitcommande/${articleId}`);
        const data = await res.json();
        if(!data.success) {
            console.error('Erreur suppression produit');
        }
    } catch (e) {
        console.error(e);
    }
}
