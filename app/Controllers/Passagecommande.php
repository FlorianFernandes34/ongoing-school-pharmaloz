<?php

namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Utilisateur;
use DateTime;

class Passagecommande extends BaseController {

    private function genererCreneaux($jours = 15, $pasMinutes = 5, array $creneauxReserves = []) {
        $creneaux = [];

        $now = new DateTime('now', new \DateTimeZone('Europe/Paris'));

        // normaliser les créneaux réservés en string Y-m-d H:i
        $reserves = [];
        foreach ($creneauxReserves as $c) {
            if ($c instanceof DateTime) {
                $reserves[] = $c->format('Y-m-d H:i');
            } else {
                $reserves[] = (new DateTime($c))->format('Y-m-d H:i');
            }
        }

        for ($d = 0; $d < $jours; $d++) {
            $date = (clone $now)->modify("+$d day");

            $ouverture = (clone $date)->setTime(9, 0);
            $fermeture = (clone $date)->setTime(19, 0);

            while ($ouverture < $fermeture) {
                if ($ouverture > $now) {

                    $key = $ouverture->format('Y-m-d H:i');
                    $estReserve = in_array($key, $reserves);

                    $creneaux[] = [
                        'date' => clone $ouverture,
                        'disponible' => !$estReserve,
                        'label' => $estReserve ? 'Créneau déjà réservé' : null
                    ];
                }

                $ouverture->modify("+$pasMinutes minute");
            }
        }

        return $creneaux;
    }

    private function sendMail($utilisateur, $produits, $creneau, $commande, $commentaire) {
        $mail = $utilisateur->email;

        $total = 0;
        $lignesProduits = '';

        foreach ($produits as $idProd => $prod) {
            $produit = Produit::find($idProd);
            $prix = $produit->prix;
            $qte = $prod['qte'];
            $sousTotal = $prix * $qte;
            $total += $sousTotal;

            $lignesProduits .= "
                <tr>
                    <td style='padding:8px 0;'>{$produit->nom}</td>
                    <td style='padding:8px 0;text-align:center;'>{$qte}</td>
                    <td style='padding:8px 0;text-align:right;'>".number_format($prix,2,',',' ')." €</td>
                </tr>
            ";
        }

        $creneauFormat = (new DateTime($creneau))->format('d/m/Y H:i');

        $message = "
            <div style='font-family:Arial,sans-serif;background:#f5f7fb;padding:30px'>
                <div style='max-width:600px;margin:auto;background:#ffffff;border-radius:10px;padding:30px'>
                    
                    <h2 style='color:#2563eb;margin-top:0'>Confirmation de votre commande</h2>
                    
                    <p>Bonjour {$utilisateur->prenom},</p>
                    
                    <p>
                        Votre commande <strong>#{$commande->id}</strong> a bien été validée.
                    </p>
            
                    <h3 style='margin-bottom:10px'>Détails du retrait</h3>
                    <p><strong>Créneau :</strong> {$creneauFormat}</p>
                    <p><strong>Commentaire :</strong> {$commentaire}</p>
            
                    <h3 style='margin:20px 0 10px'>Produits commandés</h3>
                    <table style='width:100%;border-collapse:collapse'>
                        <thead>
                            <tr style='border-bottom:1px solid #e5e7eb'>
                                <th align='left'>Produit</th>
                                <th align='center'>Qté</th>
                                <th align='right'>Prix</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$lignesProduits}
                        </tbody>
                    </table>
            
                    <div style='margin-top:20px;border-top:1px solid #e5e7eb;padding-top:15px;text-align:right'>
                        <strong>Total : ".number_format($total,2,',',' ')." €</strong>
                    </div>
            
                    <p style='margin-top:25px'>
                        Votre commande sera prête au créneau indiqué.<br>
                        Merci de vous présenter à la pharmacie avec votre numéro de commande.
                    </p>
            
                    <p style='margin-top:30px;color:#6b7280;font-size:13px'>
                        Merci pour votre confiance.<br>
                        <strong>PharmaLoz</strong>
                    </p>
                </div>
            </div>
        ";

        $emailService = \Config\Services::email();
        $emailService->setTo($mail);
        $emailService->setSubject("PharmaLoz - Confirmation de votre commande #{$commande->id}");
        $emailService->setMessage($message);
        $emailService->setMailType('html');
        $emailService->send();
    }

    public function getIndex()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('auth/connexion')->with('messageCommandeMessage', 'Veuillez vous connecter ou créer un compte pour passer commande.');
        }

        $panier = session()->get('panier');
        $produits = [];
        $total = 0;

        if ($panier) {
            foreach ($panier as $idProd => $prod) {
                $produit = Produit::find($idProd);
                $total += $produit->prix * $prod['qte'];

                if ($produit) {
                    $produits[] = [
                        'produit' => $produit,
                        'qte' => $prod['qte']
                    ];
                }
            }
        }else {
            return redirect()->to('/');
        }
        $creneauxReserves = Commande::getCreneauxReserves();
        $creneaux = $this->genererCreneaux(15, 10, $creneauxReserves);

        $data = [
            "activePage" => 'home',
            "categories" => Categorie::all(),
            "produits" => $produits,
            "total" => number_format($total, 2, ',', ' '),
            "creneaux" => $creneaux,
            "page" => 'Accueil'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('passagecommande/passercommande', $data)
            . view('template/footer', $data);
    }

    public function postTraitementcommande() {
        $creneau = $this->request->getPost('creneau_retrait');
        $commentaire = $this->request->getPost('commentaire');

        if ($commentaire == "") {
            $commentaire = 'Aucun commentaire';
        }

        $produits = session()->get('panier');

        $utilisateur = Utilisateur::find(session()->get('user_id'));

        if (!$utilisateur) {
            return redirect()->to('passagecommannde')->with('errorPassageCommande', 'Une erreur est survenue, veuillez réessayer.');
        }

        $creneauxResevers = Commande::getCreneauxReserves();
        $creneauChoisi = (new DateTime($creneau))->format('Y-m-d H:i');

        foreach ($creneauxResevers as $c) {
            $cFormat = ($c instanceof DateTime) ? $c->format('Y-m-d H:i') : (new DateTime($c))->format('Y-m-d H:i');

            if ($cFormat === $creneauChoisi) {
                return redirect()->to('passagecommande')->with('errorPassageCommande', 'Le créneau choisi est déjà réservé, veuillez en choisir un autre.');
            }
        }

        $commande = Commande::create([
            'statut' => 'Validée',
            'date_heure' => date('Y-m-d H:i:s'),
            'creneau_retrait' => $creneau,
            'commentaire' => $commentaire,
            'utilisateur_id' => $utilisateur->id
        ]);

        // Modification sotck_reserve des produits
        foreach ($produits as $idProd => $prod) {
            $produit = Produit::find($idProd);

            $produit->update([
                'stock_reserve' => ($produit->stock_reserve ?? 0) + $prod['qte']
            ]);
        }

        // Ajout des produits à la commande
        foreach ($produits as $idProd => $prod) {
            $commande->produits()->attach($idProd, ['quantite' => $prod['qte']]);
        }

        session()->set('cartCount', 0);
        session()->remove('panier');

        // Envoi mail
        $this->sendMail($utilisateur, $produits, $creneau, $commande, $commentaire);

        return redirect()->to('passagecommande/successcommande');
    }

    public function getSuccesscommande() {

        $data = [
            "activePage" => 'home',
            "categories" => Categorie::all(),
            "page" => 'Commande effectuée'
        ];

        return view('template/head', $data)
            . view('template/header', $data)
            . view('passagecommande/successcommande', $data)
            . view('template/footer', $data);
    }
}
