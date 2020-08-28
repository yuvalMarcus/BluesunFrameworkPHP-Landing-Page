
                                <div class="input-group form-group form-group-<?= $field->name; ?> spinner" data-trigger="spinner">
                                    <div class="form-line <?=$objFromBuild->getClassFieldValidation($data,$fieldData->err)?>">
                                        <input id='<?= $field->id; ?>' type='<?= $field->type; ?>' name='<?= $field->name; ?>' class='form-control text-center' value='<?= $fieldData->value; ?>' placeholder="" data-rule="quantity">
                                        <label class="form-label" style="top: -20px"><?=$field->label?></label>
                                    </div>
                                     <span class="input-group-addon">
                                         <a href="javascript:;" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
                                         <a href="javascript:;" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
                                     </span>
                                    <?php if(\Post::get($data,'submit') && $fieldData->err && !empty($fieldData->message_err)) : ?>
                                    <label id="name-error" class="error" for="name"><?=$fieldData->message_err?>.</label>
                                    <?php endif; ?>
                                </div>