

<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                            <?php if(!empty($objFromBuild->back)) {?><a href="<?= $objFromBuild->back; ?>" class="btn btn-default"><i class="fa fa-chevron-right"></i> </a><?php } ?>
                            <?= $objFromBuild->title; ?>
                            </h2>                     
                        </div>
                        <div class="body">
                            <form method="post" action="<?= $objFromBuild->action; ?>">

                                <?php $i = 0; ?>
                                <?php foreach($objFromBuild->fields as $section) : ?>

                                <?php if(!empty($objFromBuild->sectionTitle[$i])) : ?>
                                <div class="section-form-<?=$i?>">
                                <h3><strong><?= $objFromBuild->sectionTitle[$i]; ?></strong></h3>
                                <hr>
                                </div>
                                <?php endif; ?>

                                <?php foreach($section as $val) : ?>
                                <?php $data['fieldData'] = $objFromBuild->getfieldData($data,$val->name,$objFromBuild->getValidationByName($data,$val->name),$objFromBuild->getFieldValueByName($data,$val)); ?>
                                <?php $objFromBuild->fieldBuild($data,$val); ?>
                                <?php endforeach; ?>

                                <?php $i++;?>
                                <?php endforeach; ?>

                                <input type="hidden" name="token" value="<?=$token?>">

                                <button type="submit" name="submit" value="submit" class="btn btn-primary submit_ac"><?= $objFromBuild->submitText; ?></button>

                                <?php if(!empty($objFromBuild->buttons)) : ?>
                                <?php foreach($objFromBuild->buttons as $val) : ?>

                                <button type="button" class="<?=$val->nameClass?>"><?=$val->text?></button>   

                                <?php endforeach; ?>
                                <?php endif; ?>


                            </form>                            
                        </div>
                    </div>
                </div>
            </div>

