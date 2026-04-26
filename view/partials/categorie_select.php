<?php
/**
 * Affiche un <select> de catégories groupé hiérarchiquement.
 * Variables attendues :
 *   $categories  : tableau plat depuis getCategoriesFlat()
 *   $selected_id : (optionnel) id de la catégorie sélectionnée
 *   $select_name : (optionnel) nom du champ, défaut "id_categorie"
 */
$select_name = $select_name ?? 'id_categorie';
$selected_id = $selected_id ?? null;

// Construire l'arbre
$roots = [];
$map   = [];
foreach ($categories as $cat) {
    $cat['children'] = [];
    $map[$cat['id']] = $cat;
}
foreach ($map as $id => $cat) {
    if (is_null($cat['parent_id'])) {
        $roots[] = &$map[$id];
    } elseif (isset($map[$cat['parent_id']])) {
        $map[$cat['parent_id']]['children'][] = &$map[$id];
    }
}
?>
<select name="<?= htmlspecialchars($select_name) ?>" class="form-select">
    <option value="">-- Aucune --</option>
    <?php foreach ($roots as $grande): ?>
        <?php if (!empty($grande['children'])): ?>
            <optgroup label="<?= htmlspecialchars($grande['nom']) ?>">
                <?php foreach ($grande['children'] as $sous): ?>
                    <?php if (!empty($sous['children'])): ?>
                        <?php foreach ($sous['children'] as $enfant): ?>
                            <option value="<?= $enfant['id'] ?>"
                                <?= $selected_id == $enfant['id'] ? 'selected' : '' ?>>
                                ➤ <?= htmlspecialchars($sous['nom']) ?> — <?= htmlspecialchars($enfant['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="<?= $sous['id'] ?>"
                            <?= $selected_id == $sous['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sous['nom']) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </optgroup>
        <?php else: ?>
            <option value="<?= $grande['id'] ?>"
                <?= $selected_id == $grande['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($grande['nom']) ?>
            </option>
        <?php endif; ?>
    <?php endforeach; ?>
</select>



