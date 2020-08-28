
<div class="form-group form-group-<?= $field->name; ?> form-float">
    <div class="form-line <?= $objFromBuild->getClassFieldValidation($data, $fieldData->err) ?>">
        <?php if (!empty($field->data)) : ?>
            <br /><br />
            <select class="form-control" name="<?= $field->name; ?>">
                <?php if (isset($field->defaultValue)) : ?>
                    <?php foreach ($field->data as $val) : ?>
                        <?php if ($val['id'] == $field->defaultValue) : ?>
                            <option value="<?= $val['id']; ?>"><?= $val['name']; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php foreach ($field->data as $val) : ?>
                    <?php if ($field->defaultValue != $val['id']) : ?>
                        <option value="<?= $val['id']; ?>"><?= $val['name']; ?></option>
                    <?php endif; ?>
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