<?php
/**
 * Modèle Persona — application Créateur de Personnages.
 *
 * Stockage : fichiers JSON dans data/personnages/{nom}/character.json
 * Génère aussi memory.json et rules.md.
 */

namespace appCreateurPersona\modele;

use systeme\objets\DataDictionary;
use systeme\objets\Item;

class Persona extends Item
{
    /** Dossier de base des personnages (absolu) */
    public static function getDossierPersonnages(): string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'personnages';
    }

    public function __construct()
    {
        self::getDictionaire()->clearDefinition();

        // Identité
        self::getDictionaire()->addDefinition('nom', DataDictionary::TYPE_CHAINE, ['min' => 1, 'max' => 100], false, 'Nom', null, '.+');
        self::getDictionaire()->addDefinition('genre', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 80], true, 'Genre', null, '.*');
        self::getDictionaire()->addDefinition('age', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 20], true, 'Âge', null, '.*');
        self::getDictionaire()->addDefinition('date_de_naissance', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 20], true, 'Date de naissance', null, '.*');
        self::getDictionaire()->addDefinition('heure_de_naissance', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 10], true, 'Heure de naissance', null, '.*');
        self::getDictionaire()->addDefinition('lieu_de_naissance', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 150], true, 'Lieu de naissance', null, '.*');
        self::getDictionaire()->addDefinition('description_courte', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 500], true, 'Description courte', null, '.*');

        // Personnalité (stockées en JSON brut)
        self::getDictionaire()->addDefinition('traits', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 500], true, 'Traits', null, '.*');
        self::getDictionaire()->addDefinition('ton', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 100], true, 'Ton', null, '.*');
        self::getDictionaire()->addDefinition('style_reponse', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 300], true, 'Style de réponse', null, '.*'); // JSON array serialisé

        // Apparence
        self::getDictionaire()->addDefinition('description_physique', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 2000], true, 'Description physique', null, '.*');
        self::getDictionaire()->addDefinition('corpulence', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 50], true, 'Corpulence', null, '.*');
        self::getDictionaire()->addDefinition('Silhouette', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 50], true, 'Silhouette', null, '.*');
        self::getDictionaire()->addDefinition('poids', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 20], true, 'Poids', null, '.*');
        self::getDictionaire()->addDefinition('taille', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 20], true, 'Taille', null, '.*');
        self::getDictionaire()->addDefinition('Poitrine', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 20], true, 'Poitrine', null, '.*');
        self::getDictionaire()->addDefinition('Bonnet', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 20], true, 'Bonnet', null, '.*');
        self::getDictionaire()->addDefinition('Taille_mensuration', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 20], true, 'Taille (mensuration)', null, '.*');
        self::getDictionaire()->addDefinition('Hanche', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 20], true, 'Hanche', null, '.*');
        self::getDictionaire()->addDefinition('cheveux', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 50], true, 'Style cheveux', null, '.*');
        self::getDictionaire()->addDefinition('couleur_cheveux', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 50], true, 'Couleur cheveux', null, '.*');
        self::getDictionaire()->addDefinition('yeux', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 50], true, 'Yeux', null, '.*');
        self::getDictionaire()->addDefinition('taille_sexe', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 20], true, 'Taille sexe', null, '.*');
        self::getDictionaire()->addDefinition('diametre_sexe', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 20], true, 'Diamètre sexe', null, '.*');
        self::getDictionaire()->addDefinition('traits_distinctifs', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 500], true, 'Traits distinctifs', null, '.*');
        self::getDictionaire()->addDefinition('style_vestimentaire', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 300], true, 'Style vestimentaire', null, '.*');

        // Background
        self::getDictionaire()->addDefinition('backstory', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 5000], true, 'Backstory', null, '.*');
        self::getDictionaire()->addDefinition('evenements_cles_secrets', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 3000], true, 'Événements clés / Secrets', null, '.*');
        self::getDictionaire()->addDefinition('objectifs', DataDictionary::TYPE_CHAINE, ['min' => 0, 'max' => 1000], true, 'Objectifs', null, '.*');

        parent::__construct();
        return $this;
    }

    /**
     * Liste tous les personnages (dossiers présents).
     * @return Persona[]
     */
    public static function lister(): array
    {
        $dossier = self::getDossierPersonnages();
        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
            return [];
        }

        $resultat = [];
        $dossiers = array_filter(scandir($dossier), function ($d) use ($dossier) {
            return $d !== '.' && $d !== '..' && is_dir($dossier . DIRECTORY_SEPARATOR . $d);
        });

        foreach ($dossiers as $nom) {
            $p = self::trouver($nom);
            if ($p !== null) {
                $resultat[] = $p;
            }
        }

        // Tri alphabétique par nom
        usort($resultat, function ($a, $b) {
            return strcasecmp($a['nom'] ?? '', $b['nom'] ?? '');
        });

        return $resultat;
    }

    /**
     * Charge un personnage par son nom (dossier).
     * Si $nom est vide → nouvel objet vide.
     */
    public static function trouver($nom): ?self
    {
        if ($nom === '' || $nom === null) {
            return new self();
        }

        $fichier = self::getDossierPersonnages() . DIRECTORY_SEPARATOR . $nom . DIRECTORY_SEPARATOR . 'character.json';
        if (!file_exists($fichier)) {
            return null;
        }

        $json = json_decode(file_get_contents($fichier), true);
        if (!is_array($json)) {
            return null;
        }

        $p = new self();
        $p->hydraterDepuisJson($json);
        return $p;
    }

    /**
     * Remplit l'objet à partir du JSON stocké.
     */
    public function hydraterDepuisJson(array $data): void
    {
        $this['nom'] = $data['nom'] ?? '';
        $this['genre'] = $data['genre'] ?? '';
        $this['age'] = $data['age'] ?? '';
        $this['date_de_naissance'] = $data['date_de_naissance'] ?? '';
        $this['heure_de_naissance'] = $data['heure_de_naissance'] ?? '';
        $this['lieu_de_naissance'] = $data['lieu_de_naissance'] ?? '';
        $this['description_courte'] = $data['description_courte'] ?? '';

        $perso = $data['personnalite'] ?? [];
        $this['traits'] = is_array($perso['traits'] ?? null) ? implode(', ', $perso['traits']) : ($perso['traits'] ?? '');
        $this['ton'] = $perso['ton'] ?? '';
        $styles = $perso['style_reponse'] ?? [];
        $this['style_reponse'] = is_array($styles) ? json_encode($styles, JSON_UNESCAPED_UNICODE) : (string)$styles;

        $app = $data['apparence'] ?? [];
        $this['description_physique'] = $app['description_physique'] ?? '';
        $mens = $app['Mensuration_corpulence'] ?? [];
        $this['corpulence'] = $mens['corpulence'] ?? '';
        $this['Silhouette'] = $mens['Silhouette'] ?? '';
        $this['poids'] = $mens['poids'] ?? '';
        $this['taille'] = $mens['taille'] ?? '';
        $this['Poitrine'] = $mens['Poitrine'] ?? '';
        $this['Bonnet'] = $mens['Bonnet'] ?? '';
        $this['Taille_mensuration'] = $mens['Taille'] ?? '';
        $this['Hanche'] = $mens['Hanche'] ?? '';
        $this['cheveux'] = $app['cheveux'] ?? '';
        $this['couleur_cheveux'] = $app['couleur_cheveux'] ?? '';
        $this['yeux'] = $app['yeux'] ?? '';
        $sexe = $app['taille_sexe_erection'] ?? [];
        $this['taille_sexe'] = $sexe['taille'] ?? '';
        $this['diametre_sexe'] = $sexe['Diamètre'] ?? ($sexe['Diametre'] ?? '');
        $this['traits_distinctifs'] = $app['traits_distinctifs'] ?? '';
        $this['style_vestimentaire'] = $app['style_vestimentaire'] ?? '';

        $this['backstory'] = $data['backstory'] ?? '';
        $this['evenements_cles_secrets'] = $data['evenements_cles_secrets'] ?? '';
        $this['objectifs'] = $data['objectifs'] ?? '';
    }

    /**
     * Construit le tableau complet prêt à être enregistré en character.json
     */
    public function toCharacterArray(): array
    {
        $styles = json_decode($this['style_reponse'] ?? '[]', true);
        if (!is_array($styles)) {
            $styles = [];
        }

        $traits = array_filter(array_map('trim', explode(',', $this['traits'] ?? '')));

        return [
            'nom' => $this['nom'] ?? '',
            'genre' => $this['genre'] ?? '',
            'age' => $this['age'] ?? '',
            'date_de_naissance' => $this['date_de_naissance'] ?? '',
            'heure_de_naissance' => $this['heure_de_naissance'] ?? '',
            'lieu_de_naissance' => $this['lieu_de_naissance'] ?? '',
            'description_courte' => $this['description_courte'] ?? '',
            'personnalite' => [
                'traits' => array_values($traits),
                'ton' => $this['ton'] ?? '',
                'style_reponse' => $styles,
            ],
            'apparence' => [
                'description_physique' => $this['description_physique'] ?? '',
                'Mensuration_corpulence' => [
                    'corpulence' => $this['corpulence'] ?? '',
                    'Silhouette' => $this['Silhouette'] ?? '',
                    'poids' => $this['poids'] ?? '',
                    'taille' => $this['taille'] ?? '',
                    'Poitrine' => $this['Poitrine'] ?? '',
                    'Bonnet' => $this['Bonnet'] ?? '',
                    'Taille' => $this['Taille_mensuration'] ?? '',
                    'Hanche' => $this['Hanche'] ?? '',
                ],
                'cheveux' => $this['cheveux'] ?? '',
                'couleur_cheveux' => $this['couleur_cheveux'] ?? '',
                'yeux' => $this['yeux'] ?? '',
                'taille_sexe_erection' => [
                    'taille' => $this['taille_sexe'] ?? '',
                    'Diamètre' => $this['diametre_sexe'] ?? '',
                ],
                'traits_distinctifs' => $this['traits_distinctifs'] ?? '',
                'style_vestimentaire' => $this['style_vestimentaire'] ?? '',
            ],
            'backstory' => $this['backstory'] ?? '',
            'evenements_cles_secrets' => $this['evenements_cles_secrets'] ?? '',
            'objectifs' => $this['objectifs'] ?? '',
            'generated_date' => date('c'),
        ];
    }

    /**
     * Sauvegarde le personnage + génère memory.json et rules.md
     */
    public function sauvegarder(): bool
    {
        $nom = trim($this['nom'] ?? '');
        if ($nom === '') {
            return false;
        }

        // Nettoyage nom pour dossier
        $nomDossier = preg_replace('/[^a-zA-Z0-9_\-àâäéèêëïîôöùûüç ]/u', '', $nom);
        $nomDossier = trim(str_replace(' ', '_', $nomDossier));
        if ($nomDossier === '') {
            return false;
        }

        $chemin = self::getDossierPersonnages() . DIRECTORY_SEPARATOR . $nomDossier;
        if (!is_dir($chemin)) {
            mkdir($chemin, 0755, true);
        }

        $data = $this->toCharacterArray();
        $data['nom'] = $nom; // garder le nom original

        // character.json
        file_put_contents(
            $chemin . DIRECTORY_SEPARATOR . 'character.json',
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // memory.json
        $memory = [
            'utilisateur_nom' => null,
            'faits_importants' => [],
            'derniere_interaction' => null,
            'preferences' => [],
            'historique_resume' => [],
        ];
        file_put_contents(
            $chemin . DIRECTORY_SEPARATOR . 'memory.json',
            json_encode($memory, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // rules.md
        $styles = $data['personnalite']['style_reponse'] ?? [];
        $styleStr = empty($styles) ? 'Immersif' : implode(' + ', $styles);

        $rules = "# Règles d'Incarnation - {$nom}\n\n";
        $rules .= "Tu es **{$nom}**.\n\n";
        $rules .= "**Ton :** " . ($data['personnalite']['ton'] ?? 'Naturel') . "\n";
        $rules .= "**Style de réponse :** {$styleStr}\n\n";
        $rules .= "**Règles importantes :**\n";
        $rules .= "- Reste toujours dans le personnage.\n";
        $rules .= "- Ne brise jamais l'immersion sauf demande explicite de l'utilisateur.\n";
        $rules .= "- Adapte ton style selon : {$styleStr}.\n\n";
        $rules .= "---\n";
        $rules .= "*Généré le " . date('d/m/Y à H:i') . "*\n";

        file_put_contents($chemin . DIRECTORY_SEPARATOR . 'rules.md', $rules);

        return true;
    }

    /**
     * Supprime un personnage (dossier complet)
     */
    public static function supprimer(string $nom): bool
    {
        $chemin = self::getDossierPersonnages() . DIRECTORY_SEPARATOR . $nom;
        if (!is_dir($chemin)) {
            return false;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($chemin, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        return rmdir($chemin);
    }

    /**
     * Charge les options des menus déroulants
     */
    public static function getOptions(): array
    {
        $fichier = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'options.json';
        if (file_exists($fichier)) {
            $data = json_decode(file_get_contents($fichier), true);
            return is_array($data) ? $data : [];
        }
        return [];
    }
}
