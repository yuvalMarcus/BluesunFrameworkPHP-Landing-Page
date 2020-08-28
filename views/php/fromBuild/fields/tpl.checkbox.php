<div class="form-group form-group-<?= $field->name; ?> form-float">
    <input type="<?= $field->type; ?>" id="md_checkbox_<?= $field->name; ?>" class="filled-in chk-col-light-green" name="<?= $field->name; ?>" <?php if (!Post::get($data, 'submit') && $field->checkbox) : ?>checked=""<?php endif; ?> <?php if (Post::get($data, 'submit') && $field->checkbox) : ?>checked=""<?php endif; ?>>
    <label for="md_checkbox_<?= $field->name; ?>"><?= $field->label; ?></label>
</div>