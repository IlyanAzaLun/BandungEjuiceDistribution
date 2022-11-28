<?php echo form_open_multipart('master_information/account_bank', ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
    <input type="hidden" name="txtPHeadCode" id="txtPHeadCode"  value="<?=$role->PHeadCode?>"/>
    <input type="hidden" name="cnodeelem" id="cnodeelem"  value=""/>
    <input type="hidden" name="clevel" id="clevel"  value=""/>                
    <div class="row">
        <!-- end First col -->
        <div class="col-12">
            <input type="hidden" name="csrf_test_name" id="CSRF_TOKEN" value="<?=$this->security->get_csrf_hash()?>"/>
            <input type="hidden" name="txtHeadLevel" id="txtHeadLevel" class="form-control form-control-sm"  value="<?=$role->HeadLevel?>"/>
            <input type="hidden" name="txtHeadType" id="txtHeadType" class="form-control form-control-sm"  value="<?=$role->HeadType?>"/>    
            <div class="form-group row">
                <label class="col-12 col-lg-2 col-form-label" for="txtHeadCode">Head Code</label>
                <div class="col-12 col-lg-10">
                    <input type="text" name="txtHeadCode" id="txtHeadCode" class="form-control form-control-sm" value="<?=$role->HeadCode?>" readonly="readonly">
                </div> 
            </div>    
            <div class="form-group row">
                <label class="col-12 col-lg-2 col-form-label" for="txtHeadName">Head Name</label>
                <div class="col-12 col-lg-10">
                    <input type="text" name="txtHeadName" id="txtHeadName" class="form-control form-control-sm" value="<?=$role->HeadName?>">
                </div> 
                
                <label class="col-12 col-lg-2 col-form-label errore" id="nameLabel"></label>
                <div class="col-12 col-lg-10">
                    <input type="hidden" name="HeadName" id="HeadName" class="form-control form-control-sm" value="<?=$role->HeadName?>"/>
                </div> 
            </div>    
            <div class="form-group row">
                <label class="col-12 col-lg-2 col-form-label" for="txtPHead">Parent Head</label>
                <div class="col-12 col-lg-10">
                    <input type="text" name="txtPHead" id="txtPHead" class="form-control form-control-sm" readonly="readonly" value="<?=$role->PHeadName?>"/>
                </div> 
            </div>
        <!-- / IF CHILD -->
            <?php if($role->HeadLevel >= 3):?>
            <div class="form-group row">
                <label class="col-12 col-lg-2 col-form-label" for="noteNo">Note No</label>
                <div class="col-12 col-lg-10">
                    <input type="text" name="noteNo" id="noteNo" class="form-control form-control-sm"  value="<?=$role->noteNo?>"/>
                </div> 
            </div>
            <?php endif;?>
        <!-- ./END IF CHILD -->
            <div class="form-group row">
                <div class="col-12 col-lg-10 offset-lg-2">
                    <input type="hidden" name="IsActive" value=0>
                    <input type="checkbox" name="IsActive" id="IsActive" value=1 <?=($role->IsActive == 1)?'checked':''?>>
                    <label for="IsActive" class="mr-2" >Is Active</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12 col-lg-10 offset-2">
                    <?php if($role->HeadLevel >= 3 && ($role->HeadType == 'A' || $role->HeadType == 'B')):?>
                        
                    <input type="hidden" name="isFixedAssetSch" value=0>
                    <input type="checkbox" name="isFixedAssetSch" id="isFixedAssetSch" value="1" onchange="isFexedAssetSch_chage('isFizedAssetSch', '<?=$role->HeadType?>')" <?=($role->isFixedAssetSch == 1)?'checked':''?>>
                    <label for="isFixedAssetSch" class="mr-2" >is Fixed Asset</label>
                    <?php endif;?>

                    <?php if($role->HeadLevel >= 3): ## FIRST CONDTION CHECKBOX?>
                    <?php if($role->HeadType == "A"): ## SECOND CONDTION?>

                    <input type="hidden" name="isStock" value=0>
                    <input type="checkbox" name="isStock" value="1" id="isStock" onchange="isStock_chage()" <?=($role->isStock == 1)?'checked':''?>>
                    <label for="isStock" class="mr-2" >is Stock</label>

                    <input type="hidden" name="isCashNature" value=0>
                    <input type="checkbox" name="isCashNature" value="1" id="isCashNature" onchange="isCashNature_change()" <?=($role->isCashNature == 1)?'checked':''?>>
                    <label for="isCashNature" class="mr-2" >is Cash Nature</label>

                    <input type="hidden" name="isBankNature" value=0>
                    <input type="checkbox" name="isBankNature" value="1" id="isBankNature" onchange="isBankNature_chage()" <?=($role->isBankNature == 1)?'checked':''?>>
                    <label for="isBankNature" class="mr-2" >is Bank Nature</label>

                    <?php endif; ## ./SECOND?>

                    <input type="hidden" name="isSubType" value=0>
                    <input type="checkbox" name="isSubType" value="1" id="isSubType" onchange="isSubType_chage()" <?=($role->isSubType == 1)?'checked':''?>>
                    <label for="isSubType" class="mr-2" >is Bank Nature</label>

                    <?php endif; ## ./FIRST?>
                </div>
            </div>
            
            <?php if($role->isFixedAssetSch == 1): ## FIRST GROUP CONDITION?>
            <?php if($role->HeadLevel >= 3 && $role->HeadType == 'A'): ## SECOND GROUP CONDITION?>
            
            <div class="form-group row">
                <label class="col-12 col-lg-2 col-form-label" for="assetCode">Fixed Asset Code</label>
                <div class="col-12 col-lg-10">
                    <input type="text" name="assetCode" id="assetCode" class="form-control form-control-sm" value="<?=$role->assetCode?>"/>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-12 col-lg-2 col-form-label" for="DepreciationRate">Depreciation Rate</label>
                <div class="input-group col-12 col-lg-10">
                    <input type="text" name="DepreciationRate" id="DepreciationRate" class="form-control text-right" value="<?=$role->DepreciationRate?>"/>
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>
            
            <?php elseif($role->HeadLevel >= 3 && $role->HeadType == "B"): ## SECOND GROUP CONDITION?>
            <div class="form-group row">
                <label class="col-12 col-lg-2 col-form-label" for="depCode">Depraciation Code</label>
                <div class="col-12 col-lg-10">
                    <input type="text" name="depCode" id="depCode" class="form-control form-control-sm" value="<?=$role->depCode?>"/>
                </div>
            </div>
            <?php endif; ## ./SECOND?>
            <?php endif; ## ./FIRST?>
            
            <div class="float-right">
                <?php if($role->HeadLevel < 3): ## FIRST GROUP CONDITION ?>
                <input type="submit" name='add_new' class="btn btn-info" value="Create New">
                <?php endif; ## ./FIRST?>
                <?php if($role->HeadLevel >= 2): ## FIRST GROUP CONDITION ?>
                <input type="submit" name='submit' class="btn btn-success" value="Submit">
                <?php endif;?>
            </div>

        </div>
    </div>
<?php echo form_close(); ?>
<script>
    $('input#txtHeadName').focus();
</script>