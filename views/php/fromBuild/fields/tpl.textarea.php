
                                <div class="form-group form-group-<?= $field->name; ?> form-float">
                                    <div class="form-line <?=$objFromBuild->getClassFieldValidation($data,$fieldData->err)?>">
                                        <textarea name="<?= $field->name; ?>" cols="30" rows="5" id="<?= $field->id; ?>" class="form-control no-resize"><?= $fieldData->value; ?></textarea>
                                        <label class="form-label"><?=$field->label?></label>
                                    </div>
                                    <?php if(\Post::get($data,'submit') && $fieldData->err && !empty($fieldData->message_err)) : ?>
                                    <label id="name-error" class="error" for="name"><?=$fieldData->message_err?>.</label>
                                    <?php endif; ?>
                                    <em><?=$field->em?></em>
                                </div>