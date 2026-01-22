const base_url = "https://localhost/pharmaloz";
const base_url_ajax = "https://localhost/pharmaloz/ajax/";

/*********************************
 *  MISE À JOUR DU STATUT COMMANDE
 *********************************/
document.addEventListener('DOMContentLoaded', () => {
    initUpdateDropdowns();
    initRechercheCommandes();
    initProduitCommande();
    initGestionQuantite();
    deleteCompte();
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

                const elementStatut = document.getElementById('statut' + commandeId);

                dropdown.classList.add('hidden');

                try {
                    const res = await fetch(base_url_ajax + 'updatestatut', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ idCommande: commandeId, statutCommande: newStatus })
                    });

                    const response = await res.json();
                    if (!response.success) {
                        alert(response.message);
                    }else {
                        alert(response.message);
                        if (elementStatut) elementStatut.innerHTML = `<span class="font-medium text-gray-600">Statut :</span> ${newStatus}`;
                        button.querySelector('.status-text').textContent = newStatus;
                    }

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
        const emptySearchState = document.getElementById('emptySearchState');
        const grid = document.querySelector('.grid');
        const pagination = document.getElementById('pagination');

        try {
            const res = await fetch(base_url_ajax + 'searchcommandes', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ query, type })
            });

            const response = await res.json();
            commandesContainer.innerHTML = '';

            if (response.redirect) {
                window.location.href = response.redirect;
                return;
            }

            if (response.commandes && response.commandes.length > 0) {
                pagination.classList.add('hidden');
                grid.classList.remove('hidden');
                emptySearchState.classList.add('hidden');

                commandesContainer.classList.remove('hidden');

                response.commandes.forEach(cmd => {
                    commandesContainer.innerHTML += `
                        <div class="bg-white rounded-xl shadow-md p-5 flex flex-col justify-between border border-gray-100 hover:shadow-lg transition-all">
                            <div class="flex-1 space-y-2">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    Commande N°${cmd.id}
                                </h2>
            
                                <p id="statut${cmd.id}">
                                    <span class="font-medium text-gray-600">Statut :</span>
                                    ${cmd.statut}
                                </p>
            
                                <p>
                                    <span class="font-medium text-gray-600">Date commande :</span>
                                    ${cmd.date_heure}
                                </p>
            
                                    <span class="font-medium text-gray-600">Prix total :</span>
                                    ${cmd.prix_total}€
                                </p>
            
                                    <span class="font-medium text-gray-600">Créneau retrait :</span>
                                    ${cmd.creneau_retrait}
                                </p>
            
                                <p class="mt-2 font-medium text-gray-700">Utilisateur :</p>
                                <p class="text-gray-600">
                                    ${cmd.utilisateur.nom}
                                    ${cmd.utilisateur.prenom}
                                </p>
                                <p class="text-gray-600">${cmd.utilisateur.email}</p>
                            </div>
            
                            <!-- BOUTONS -->
                            <div class="flex space-x-2 mt-4">
            
                                <!-- STATUT -->
                                <div class="relative flex-1" data-id="${cmd.id}">
                                    <button type="button"
                                            class="status-button w-full h-10 bg-blue-500 hover:bg-blue-600 text-white font-medium px-3 rounded-lg shadow
                                               flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                                        <span class="status-text flex-1 text-center">
                                            ${cmd.statut}
                                        </span>
                                        <i class="fas fa-chevron-down ml-2"></i>
                                    </button>
            
                                    <ul class="status-dropdown absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10">
                                        <li class="dropdown-item px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer" data-value="Validée">
                                            Validée
                                        </li>
                                        <li class="dropdown-item px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer" data-value="Retirée">
                                            Retirée
                                        </li>
                                        <li class="dropdown-item px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer" data-value="Annulée">
                                            Annulée
                                        </li>
                                    </ul>
                                </div>
            
                                <!-- VOIR ARTICLES -->
                                <a href="${base_url}/commgest/listeproduitscomm/${cmd.id}"
                                   class="flex-1 h-10 min-w-0 flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 rounded-lg transition whitespace-nowrap">
                                    <i class="fas fa-edit"></i>
                                    <span>Voir les articles</span>
                                </a>
                            </div>
                        </div>`;
                });

                initUpdateDropdowns();
            } else {
                grid.classList.add('hidden');
                emptySearchState.classList.remove('hidden');

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
        const res = await fetch( base_url_ajax + `infosproduits/${id}`);
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
                if (document.querySelectorAll('tbody tr').length === 0) {
                    alert('La commande est maintenant vide et a été annulée.');
                    window.location.href = 'http://localhost/pharmaloz/commgest/listecom/1';
                }
            }
        });

        // Input manuel
        if (input) input.addEventListener('input', () => {
            if(parseInt(input.value) < 0) input.value = 0;
            updateQuantite(articleId, input.value);
        });
    });
}

/*********************************
 *  FONCTION PERMETTANT LA RECUPERATION DE L'ID COMMANDE (update et delete article)
 *********************************/

function getCommandeId() {
    const main = document.querySelector('main[data-commande-id]');

    if (!main) {
        return;
    }

    return main.dataset.commandeId;
}

/*********************************
*  AJAX côté serveur
 *********************************/
async function updateQuantite(articleId, quantite) {
    const commandeId = getCommandeId();

    if (!commandeId) {
        return
    }

    try {
        const res = await fetch(base_url_ajax + `updatequantitearticlefromcommande/${commandeId}/${articleId}/${quantite}`);
        const data = await res.json();
        if (!data.success) {
            alert('Une erreur est survenue.');
        }
    } catch (e) {
        console.error(e);
    }
}

async function deleteProduit(articleId) {
    const commandeId = getCommandeId();

    if (!commandeId) {
        return
    }

    try {
        const res = await fetch(base_url_ajax + `deletearticlefromcommande/${commandeId}/${articleId}`);
        const data = await res.json();
        if(!data.success) {
            console.error('Erreur suppression produit');
        }
    } catch (e) {
        console.error(e);
    }
}

/*********************************
 *  GESTION DES COMPTES (ADMIN)
 *********************************/

async function deleteCompte() {
    const deleteButtons = document.querySelectorAll('.delete-compte');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', async () => {
            const row = btn.closest('tr');
            const compteId = row.dataset.id;

            doubleConfirmOnClick('Voulez vous supprimer ce compte ?', 'Voulez vous vraiment supprimer ce compte ?')

            try {
                const res = await fetch(base_url_ajax + `supprimercompte/${compteId}`);

                const data = await res.json();
                if(data.success) {
                    row.remove();
                    alert(data.message);
                    if (data.redirect) window.location.href = data.redirect;
                } else {
                    alert(data.message);
                }
            } catch(e) {
                console.error(e);
                alert('Erreur réseau.');
            }
        });
    });
}

/*********************************
 *  VERIFCIATION CORRESPONDANCE MDP
 *********************************/
function checkPassword(confirmValue) {
   const newPassword = document.getElementById('newPassword').value;
   const errorMsg = document.getElementById('password-error');
   const confirmField = document.getElementById('confirmPassword');
   const submitButton = document.getElementById('submitPassChange')

   if (confirmValue === "") {
       confirmField.borderColor = '#D1D5DB'
       errorMsg.classList.add('hidden');
       return;
   }

   if (confirmValue === newPassword) {
       confirmField.style.borderColor = 'green';
       errorMsg.classList.add('hidden')
       submitButton.disabled = false;
       console.log(submitButton.disabled);
   }else {
       confirmField.style.borderColor = 'red';
       errorMsg.classList.remove('hidden');
       submitButton.disabled = true;
       console.log(submitButton.disabled);
   }
}

/*********************************
 *  DIVERS
 *********************************/

function doubleConfirmOnClick(message, messageBis) {

    if (!confirm(message)) return false;

    return confirm(messageBis);
}
document.addEventListener('click', (e) => {
    if (e.target.closest('#resetSearch')) {
        window.location.href = base_url + '/commgest/listecom/1';
    }
});
