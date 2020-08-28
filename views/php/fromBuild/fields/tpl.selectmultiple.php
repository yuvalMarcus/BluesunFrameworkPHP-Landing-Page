
<div class="form-group form-group-<?= $field->name; ?> form-float">
    <div class="form-line <?= $objFromBuild->getClassFieldValidation($data, $fieldData->err) ?>">
        <?php if (!empty($field->data)) : ?>
            <br /><br />
            <select name="<?= $field->name; ?>[]" class="form-control show-tick" multiple data-live-search="true">
                <?php foreach ($field->data as $val) : ?>
                    <?php
                    $text = $val['name'];
                    if (!empty($val['name_english_bluesun_framework_php'])) {
                        $text = $val['name_english_bluesun_framework_php'];
                    }
                    if (!empty($val['name_' . $defaultLanguage . '_bluesun_framework_php'])) {
                        $text = $val['name_' . $defaultLanguage . '_bluesun_framework_php'];
                    }
                    ?>
                    <option value="<?= $val['id']; ?>" <?php if (!isset($_POST[$field->name]) && in_array($val['id'], $field->defaultValue)) : ?> selected="selected" <?php endif; ?> <?php if (isset($_POST[$field->name]) && in_array($val['id'], $_POST[$field->name])) : ?> selected="selected" <?php endif; ?> ><?= $text ?></option>
    <?php endforeach; ?>
            </select>

    <?php endif; ?>
        <label class="form-label"><?= $field->label ?></label>
    </div>
<?php if (\Post::get($data, 'submit') && $fieldData->err && !empty($fieldData->message_err)) : ?>
        <label id="name-error" class="error" for="name"><?= $fieldData->message_err ?>.</label>
<?php endif; ?>
    <em><?= $field->em ?></em>
</div>