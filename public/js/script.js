//MISE A JOUR DU STATUT

document.addEventListener('DOMContentLoaded', initUpdateDropdowns);

function initUpdateDropdowns() {
    const buttons = document.querySelectorAll('.status-button');

    buttons.forEach(button => {
        const container = button.parentElement; // conteneur avec data-id
        const dropdown = button.nextElementSibling;

        // Toggle dropdown
        button.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        // Quand on clique sur un item
        dropdown.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', async function () {
                const newStatus = this.dataset.value;
                const commandeId = container.dataset.id;

                // Update visuel
                button.querySelector('.status-text').textContent = newStatus;

                // mettre à jour le texte "Statut : ..." en haut
                const elementStatut = document.getElementById('statut' + commandeId)
                if (elementStatut) {
                    elementStatut.innerHTML = "<span class=\"font-medium text-gray-600\">Statut :</span> " + newStatus;
                }

                dropdown.classList.add('hidden');

                // AJAX pour la BDD
                const data = await fetch('https://localhost/pharmaloz/admin/updatestatut', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams({
                        idCommande: commandeId,
                        statutCommande: newStatus
                    })
                });


                const response = await data.json();

                if (response.success) {
                    alert('Statut mis à jour avec succès.')
                } else {
                    alert('Une erreur est survenue lors de la mise à jour du statut.')
                }
            });
        });

        // Fermer dropdown si clic en dehors
        document.addEventListener('click', e => {
            if (!container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
}

//RECHERCHE DES COMMANDES

const searchInput = document.getElementById('searchCommande');
const searchType = document.getElementById('searchType');

searchInput.addEventListener('input', async function() {
    const query = this.value.trim();
    const type = searchType.value;

    const commandesContainer = document.querySelector('.grid');

    // On envoie la requête même si query est vide
    try {
        const res = await fetch('https://localhost/pharmaloz/admin/searchcommandes', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                query: query,
                type: type
            })// query vide = toutes commandes côté serveur
        });

        const response = await res.json();

        commandesContainer.innerHTML = ''; // on vide avant d'ajouter

        if (response.commandes && response.commandes.length > 0) {
            response.commandes.forEach(cmd => {
                commandesContainer.innerHTML += `
                <div class="bg-white rounded-xl shadow-md p-5 flex flex-col justify-between border border-gray-100 hover:shadow-lg transition-all">
                    <div class="flex-1 space-y-2">
                        <h2 class="text-lg font-semibold text-gray-800">Commande N°${cmd.id}</h2>
                        <p id="statut${cmd.id}"><span class="font-medium text-gray-600">Statut :</span> ${cmd.statut}</p>
                        <p><span class="font-medium text-gray-600">Date de commande :</span> ${cmd.date_heure}</p>
                        <p><span class="font-medium text-gray-600">Prix total :</span> ${cmd.prix_total}€</p>
                        <p><span class="font-medium text-gray-600">Créneau retrait :</span> ${cmd.creneau_retrait}</p>
                        <p class="mt-2 font-medium text-gray-700">Utilisateur :</p>
                        <p class="text-gray-600">${cmd.utilisateur.nom} ${cmd.utilisateur.prenom}</p>
                        <p class="text-gray-600">${cmd.utilisateur.email}</p>

                        <div class="flex space-x-2 mt-4">
                            <div class="relative flex-1" data-id="${cmd.id}">
                                <button type="button" class="status-button w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-lg shadow flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                                    <span class="status-text">${cmd.statut}</span>
                                    <i class="fas fa-chevron-down ml-2"></i>
                                </button>
                                <ul class="status-dropdown absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10">
                                    <li class="dropdown-item px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer" data-value="Validée">Validée</li>
                                    <li class="dropdown-item px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer" data-value="Retirée">Retirée</li>
                                    <li class="dropdown-item px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer" data-value="Annulée">Annulée</li>
                                </ul>
                            </div>

                            <a href="https://localhost/pharmaloz/admin/articlescomm/${cmd.id}" class="flex-1 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-lg transition">
                                <i class="fas fa-edit mr-1"></i> Voir les articles
                            </a>
                        </div>
                    </div>
                </div>`;
            });
            initUpdateDropdowns();
        } else {
            commandesContainer.innerHTML = '<p>Aucune commande correspondant à ces critères n\'a été trouvée.</p>';
        }
    } catch (err) {
        console.error(err);
        alert('Une erreur est survenue lors de la recherche.');
    }
});

