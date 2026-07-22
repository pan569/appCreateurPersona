<?php
/**
 * Vue formulaire création / modification — appCreateurPersona
 * Variables : $model (Persona), $options (tableaux des listes déroulantes)
 */
use systeme\vue\form;

$form = form::getInstance('form-control');

$nom = $model['nom'] ?? '';
$estNouveau = ($nom === '' || $nom === null);

// Helper pour récupérer le style_reponse déjà sélectionné
$stylesActuels = json_decode($model['style_reponse'] ?? '[]', true);
if (!is_array($stylesActuels)) {
    $stylesActuels = [];
}
?>
<h1><?= $estNouveau ? 'Nouveau personnage' : 'Modifier « ' . e($nom) . ' »' ?></h1>

<form method="post"
      action="<?= $this->routeur->getRoute('editer')->generateUri(['id' => $nom]); ?>"
      enctype="multipart/form-data">

    <?= $this->champCsrf(); ?>

    <!-- ==================== IDENTITÉ ==================== -->
    <fieldset style="margin-bottom: 1.5rem; border: 1px solid #ccc; padding: 1rem;">
        <legend><strong>Identité</strong></legend>

        <?= $form->ConstructeurChamp('Nom du personnage *', 'nom', $model['nom'] ?? '', 'text'); ?>

        <label>Genre</label>
        <select name="genre" class="form-control">
            <option value="">-- Choisir --</option>
            <?php foreach (($options['genre'] ?? []) as $opt): ?>
                <option value="<?= e($opt) ?>" <?= (($model['genre'] ?? '') === $opt) ? 'selected' : '' ?>><?= e($opt) ?></option>
            <?php endforeach; ?>
        </select>

        <?= $form->ConstructeurChamp('Âge', 'age', $model['age'] ?? '', 'text'); ?>
        <?= $form->ConstructeurChamp('Date de naissance (AAAA-MM-JJ)', 'date_de_naissance', $model['date_de_naissance'] ?? '', 'text'); ?>
        <?= $form->ConstructeurChamp('Heure de naissance (HH:MM)', 'heure_de_naissance', $model['heure_de_naissance'] ?? '', 'text'); ?>
        <?= $form->ConstructeurChamp('Lieu de naissance', 'lieu_de_naissance', $model['lieu_de_naissance'] ?? '', 'text'); ?>
        <?= $form->ConstructeurChamp('Description courte', 'description_courte', $model['description_courte'] ?? '', 'textarea', [60, 3]); ?>
    </fieldset>

    <!-- ==================== APPARENCE ==================== -->
    <fieldset style="margin-bottom: 1.5rem; border: 1px solid #ccc; padding: 1rem;">
        <legend><strong>Apparence</strong></legend>

        <?= $form->ConstructeurChamp('Description physique', 'description_physique', $model['description_physique'] ?? '', 'textarea', [60, 4]); ?>

        <h3 style="margin-top:1rem;">Mensuration / Corpulence</h3>

        <label>Corpulence</label>
        <select name="corpulence" class="form-control">
            <option value="">-- Choisir --</option>
            <?php foreach (($options['corpulence'] ?? []) as $opt): ?>
                <option value="<?= e($opt) ?>" <?= (($model['corpulence'] ?? '') === $opt) ? 'selected' : '' ?>><?= e($opt) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Silhouette</label>
        <select name="Silhouette" class="form-control">
            <option value="">-- Choisir --</option>
            <?php foreach (($options['Silhouette'] ?? []) as $opt): ?>
                <option value="<?= e($opt) ?>" <?= (($model['Silhouette'] ?? '') === $opt) ? 'selected' : '' ?>><?= e($opt) ?></option>
            <?php endforeach; ?>
        </select>

        <?= $form->ConstructeurChamp('Poids (kg)', 'poids', $model['poids'] ?? '', 'text'); ?>
        <?= $form->ConstructeurChamp('Taille (cm)', 'taille', $model['taille'] ?? '', 'text'); ?>
        <?= $form->ConstructeurChamp('Poitrine', 'Poitrine', $model['Poitrine'] ?? '', 'text'); ?>

        <label>Bonnet</label>
        <select name="Bonnet" class="form-control">
            <option value="">-- Choisir --</option>
            <?php foreach (($options['Bonnet'] ?? []) as $opt): ?>
                <option value="<?= e($opt) ?>" <?= (($model['Bonnet'] ?? '') === $opt) ? 'selected' : '' ?>><?= e($opt) ?></option>
            <?php endforeach; ?>
        </select>

        <?= $form->ConstructeurChamp('Taille (mensuration)', 'Taille_mensuration', $model['Taille_mensuration'] ?? '', 'text'); ?>
        <?= $form->ConstructeurChamp('Hanche', 'Hanche', $model['Hanche'] ?? '', 'text'); ?>

        <label>Couleur des cheveux</label>
        <select name="couleur_cheveux" class="form-control">
            <option value="">-- Choisir --</option>
            <?php foreach (($options['couleur_cheveux'] ?? []) as $opt): ?>
                <option value="<?= e($opt) ?>" <?= (($model['couleur_cheveux'] ?? '') === $opt) ? 'selected' : '' ?>><?= e($opt) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Style des cheveux</label>
        <select name="cheveux" class="form-control">
            <option value="">-- Choisir --</option>
            <?php foreach (($options['cheveux'] ?? []) as $opt): ?>
                <option value="<?= e($opt) ?>" <?= (($model['cheveux'] ?? '') === $opt) ? 'selected' : '' ?>><?= e($opt) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Couleur des yeux</label>
        <select name="yeux" class="form-control">
            <option value="">-- Choisir --</option>
            <?php foreach (($options['yeux'] ?? []) as $opt): ?>
                <option value="<?= e($opt) ?>" <?= (($model['yeux'] ?? '') === $opt) ? 'selected' : '' ?>><?= e($opt) ?></option>
            <?php endforeach; ?>
        </select>

        <?= $form->ConstructeurChamp('Taille (sexe / érection) en cm', 'taille_sexe', $model['taille_sexe'] ?? '', 'text'); ?>
        <?= $form->ConstructeurChamp('Diamètre (sexe / érection) en cm', 'diametre_sexe', $model['diametre_sexe'] ?? '', 'text'); ?>
        <?= $form->ConstructeurChamp('Traits distinctifs', 'traits_distinctifs', $model['traits_distinctifs'] ?? '', 'textarea', [60, 2]); ?>
        <?= $form->ConstructeurChamp('Style vestimentaire', 'style_vestimentaire', $model['style_vestimentaire'] ?? '', 'text'); ?>
    </fieldset>

    <!-- ==================== PERSONNALITÉ ==================== -->
    <fieldset style="margin-bottom: 1.5rem; border: 1px solid #ccc; padding: 1rem;">
        <legend><strong>Personnalité</strong></legend>

        <?= $form->ConstructeurChamp('Traits principaux (séparés par des virgules)', 'traits', $model['traits'] ?? '', 'text'); ?>

        <label>Ton</label>
        <select name="ton" class="form-control">
            <option value="">-- Choisir --</option>
            <?php foreach (($options['ton'] ?? []) as $opt): ?>
                <option value="<?= e($opt) ?>" <?= (($model['ton'] ?? '') === $opt) ? 'selected' : '' ?>><?= e($opt) ?></option>
            <?php endforeach; ?>
        </select>

        <label style="display:block;margin-top:0.8rem;">Style de réponse (plusieurs choix possibles)</label>
        <?php foreach (($options['style_reponse'] ?? []) as $opt): ?>
            <label style="display:inline-block;margin-right:1.2rem;font-weight:normal;">
                <input type="checkbox"
                       name="style_reponse[]"
                       value="<?= e($opt) ?>"
                       <?= in_array($opt, $stylesActuels, true) ? 'checked' : '' ?>>
                <?= e($opt) ?>
            </label>
        <?php endforeach; ?>
    </fieldset>

    <!-- ==================== BACKGROUND ==================== -->
    <fieldset style="margin-bottom: 1.5rem; border: 1px solid #ccc; padding: 1rem;">
        <legend><strong>Background</strong></legend>

        <?= $form->ConstructeurChamp('Backstory', 'backstory', $model['backstory'] ?? '', 'textarea', [60, 6]); ?>
        <?= $form->ConstructeurChamp('Événements clés / Secrets', 'evenements_cles_secrets', $model['evenements_cles_secrets'] ?? '', 'textarea', [60, 4]); ?>
        <?= $form->ConstructeurChamp('Objectifs', 'objectifs', $model['objectifs'] ?? '', 'textarea', [60, 3]); ?>
    </fieldset>

    <?= $form->ConstructeurChamp('', 'form', 'true', 'hidden'); ?>

    <p>
        <button type="submit" class="btn">
            <?= $estNouveau ? 'Créer le personnage' : 'Enregistrer les modifications' ?>
        </button>
        <a class="btn btn-secondaire"
           href="<?= $this->routeur->getRoute('index')->generateUri(); ?>">
            Annuler
        </a>
    </p>
</form>
