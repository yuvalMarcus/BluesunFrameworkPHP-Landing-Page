
                                <div class="form-group form-group-<?= $field->name; ?> form-float">
                                    <div class="form-line <?=$objFromBuild->getClassFieldValidation($data,$fieldData->err)?>">
                                        <input id='<?= $field->id; ?>' type='<?= $field->type; ?>' name='<?= $field->name; ?>' class='form-control' value='<?= $fieldData->value; ?>' placeholder="">
                                        <label class="form-label"><?=$field->label?></label>
                                    </div>
                                    <?php if(\Post::get($data,'submit') && $fieldData->err && !empty($fieldData->message_err)) : ?>
                                    <label id="name-error" class="error" for="name"><?=$fieldData->message_err?>.</label>
                                    <?php endif; ?>
                                    <em><?=$field->em?></em>
                                </div>