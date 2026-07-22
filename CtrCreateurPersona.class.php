<?php
/**
 * Contrôleur de l'application Créateur de Personnages.
 *
 * Convention :
 *   - Dossier  : appCreateurPersona
 *   - Classe   : CtrCreateurPersona
 *   - Namespace: appCreateurPersona
 *
 * @see GUIDE.md du template
 */

namespace appCreateurPersona;

use systeme\routeur\Route;
use systeme\controleur\Controleur;
use motif\modele\Motif;
use appCreateurPersona\modele\Persona;

class CtrCreateurPersona extends Controleur
{
    public function __construct(Motif $motif)
    {
        parent::__construct($motif);

        $s = DIRECTORY_SEPARATOR;

        // Liste des personnages
        $this->routeur->addRoute(new Route(
            $this->nomApplication,
            "index",
            "",
            "",
            __DIR__ . "{$s}vue{$s}index.php"
        ));

        // Création / Modification
        $this->routeur->addRoute(new Route(
            $this->nomApplication,
            "editer",
            "{id:[a-zA-Z0-9_\-àâäéèêëïîôöùûüç ]*}", // id = nom du dossier (peut être vide)
            "",
            __DIR__ . "{$s}vue{$s}form.php"
        ));

        // Suppression
        $this->routeur->addRoute(new Route(
            $this->nomApplication,
            "supprimer",
            "{id:[a-zA-Z0-9_\-àâäéèêëïîôöùûüç ]+}",
            "",
            __DIR__ . "{$s}vue{$s}index.php" // redirige, pas de vue dédiée
        ));
    }

    /**
     * Affiche le layout complet (header + body + footer via motif).
     */
    public function afficherPage($main)
    {
        // Menu latéral
        $t = [];
        $t['Liste'] = [
            'lien'  => $this->routeur->getRoute('index')->generateUri(),
            'count' => '',
        ];
        $t['Nouveau personnage'] = [
            'lien'  => $this->routeur->getRoute('editer')->generateUri(['id' => '']),
            'count' => '',
        ];
        $this->motif['aside'] = $t;

        // CSS / JS spécifiques à l'app
        $dossier = '/appCreateurPersona/vue/resources';
        $this->motif->ajoutFichier($dossier);

        $body = $this->renduPage('body', compact('main'));
        echo $this->renduPage('page', compact('body'));
    }

    /**
     * Page d'accueil : liste des personnages.
     */
    public function index(array $variables = [])
    {
        $model = Persona::lister();
        $main  = $this->renduPage('index', compact('model'));
        $this->afficherPage($main);
    }

    /**
     * Formulaire de création / modification.
     * - GET  → affiche le formulaire
     * - POST → traite, sauvegarde et redirige
     */
    public function editer(array $variables = [])
    {
        $id    = $variables['id'] ?? '';
        $model = Persona::trouver($id); // null si introuvable, objet vide si nouveau

        if ($model === null && $id !== '') {
            $this->flashErreur('Personnage introuvable.');
            $this->redirigerRoute(['Callback' => 'index', 'variableCallback' => []]);
            return;
        }

        if (array_key_exists('form', $variables)) {

            if ($this->refuserSiCsrfInvalide()) {
                $options = Persona::getOptions();
                $main = $this->renduPage('form', compact('model', 'options'));
                $this->afficherPage($main);
                return;
            }

            // --- Récupération des données du formulaire ---
            $model['nom']                   = trim($_POST['nom'] ?? '');
            $model['genre']                 = $_POST['genre'] ?? '';
            $model['age']                   = $_POST['age'] ?? '';
            $model['date_de_naissance']     = $_POST['date_de_naissance'] ?? '';
            $model['heure_de_naissance']    = $_POST['heure_de_naissance'] ?? '';
            $model['lieu_de_naissance']     = $_POST['lieu_de_naissance'] ?? '';
            $model['description_courte']    = $_POST['description_courte'] ?? '';

            $model['traits']                = $_POST['traits'] ?? '';
            $model['ton']                   = $_POST['ton'] ?? '';

            // Multi-sélection style_reponse
            $styles = $_POST['style_reponse'] ?? [];
            if (!is_array($styles)) {
                $styles = [];
            }
            $model['style_reponse'] = json_encode(array_values($styles), JSON_UNESCAPED_UNICODE);

            $model['description_physique']  = $_POST['description_physique'] ?? '';
            $model['corpulence']            = $_POST['corpulence'] ?? '';
            $model['Silhouette']            = $_POST['Silhouette'] ?? '';
            $model['poids']                 = $_POST['poids'] ?? '';
            $model['taille']                = $_POST['taille'] ?? '';
            $model['Poitrine']              = $_POST['Poitrine'] ?? '';
            $model['Bonnet']                = $_POST['Bonnet'] ?? '';
            $model['Taille_mensuration']    = $_POST['Taille_mensuration'] ?? '';
            $model['Hanche']                = $_POST['Hanche'] ?? '';
            $model['cheveux']               = $_POST['cheveux'] ?? '';
            $model['couleur_cheveux']       = $_POST['couleur_cheveux'] ?? '';
            $model['yeux']                  = $_POST['yeux'] ?? '';
            $model['taille_sexe']           = $_POST['taille_sexe'] ?? '';
            $model['diametre_sexe']         = $_POST['diametre_sexe'] ?? '';
            $model['traits_distinctifs']    = $_POST['traits_distinctifs'] ?? '';
            $model['style_vestimentaire']   = $_POST['style_vestimentaire'] ?? '';

            $model['backstory']             = $_POST['backstory'] ?? '';
            $model['evenements_cles_secrets'] = $_POST['evenements_cles_secrets'] ?? '';
            $model['objectifs']             = $_POST['objectifs'] ?? '';

            if (trim($model['nom']) === '') {
                $this->flashErreur('Le nom du personnage est obligatoire.');
                $options = Persona::getOptions();
                $main = $this->renduPage('form', compact('model', 'options'));
                $this->afficherPage($main);
                return;
            }

            if ($model->sauvegarder()) {
                $this->flashSucces("Personnage « {$model['nom']} » enregistré avec succès (character.json, memory.json, rules.md générés).");
            } else {
                $this->flashErreur('Erreur lors de la sauvegarde.');
            }

            $this->redirigerRoute(['Callback' => 'index', 'variableCallback' => []]);
            return;
        }

        // GET → affichage du formulaire
        $options = Persona::getOptions();
        $main = $this->renduPage('form', compact('model', 'options'));
        $this->afficherPage($main);
    }

    /**
     * Suppression d'un personnage.
     */
    public function supprimer(array $variables = [])
    {
        $id = $variables['id'] ?? '';

        if ($id === '') {
            $this->flashErreur('Aucun personnage spécifié.');
            $this->redirigerRoute(['Callback' => 'index', 'variableCallback' => []]);
            return;
        }

        if (Persona::supprimer($id)) {
            $this->flashSucces("Personnage « {$id} » supprimé.");
        } else {
            $this->flashErreur('Impossible de supprimer le personnage.');
        }

        $this->redirigerRoute(['Callback' => 'index', 'variableCallback' => []]);
    }
}
