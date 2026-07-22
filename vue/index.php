<?php
/**
 * Vue liste des personnages — appCreateurPersona
 * Variables disponibles : $model (tableau d'objets Persona)
 */
?>
<h1>Créateur de Personnages</h1>
<p class="text-muted">
    Créez, modifiez et gérez vos personnages pour le jeu de rôle / IA.
    Les fichiers <code>character.json</code>, <code>memory.json</code> et <code>rules.md</code> sont générés automatiquement.
</p>

<p>
    <a class="btn" href="<?= $this->routeur->getRoute('editer')->generateUri(['id' => '']); ?>">
        + Nouveau personnage
    </a>
</p>

<?php if (empty($model)): ?>
    <p>Aucun personnage pour le moment.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Genre</th>
                <th>Âge</th>
                <th>Ton</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($model as $item): ?>
            <tr>
                <td><strong><?= e($item['nom'] ?? '') ?></strong></td>
                <td><?= e($item['genre'] ?? '') ?></td>
                <td><?= e($item['age'] ?? '') ?></td>
                <td><?= e($item['ton'] ?? '') ?></td>
                <td style="white-space: nowrap;">
                    <a class="btn btn-secondaire"
                       style="font-size:0.8rem;padding:0.25rem 0.6rem;"
                       href="<?= $this->routeur->getRoute('editer')->generateUri(['id' => $item['nom'] ?? '']); ?>">
                        Modifier
                    </a>
                    <a class="btn btn-secondaire"
                       style="font-size:0.8rem;padding:0.25rem 0.6rem;color:#c0392b;"
                       href="<?= $this->routeur->getRoute('supprimer')->generateUri(['id' => $item['nom'] ?? '']); ?>"
                       onclick="return confirm('Supprimer définitivement « <?= e($item['nom'] ?? '') ?> » ?');">
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
