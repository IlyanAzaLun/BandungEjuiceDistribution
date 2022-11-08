<form name="coaform" id="coaform" action="#" method="post" enctype="multipart/form-data" onSubmit=" return validate('nameLabel');">
    <input type="hidden" name="txtPHeadCode" id="txtPHeadCode"  value="<?=$role->PHeadCode?>"/>
    <input type="hidden" name="cnodeelem" id="cnodeelem"  value=""/>
    <input type="hidden" name="clevel" id="clevel"  value=""/>                
    <table class="coaTable" width="100%" cellspacing="0" cellpadding="5">
        <input type="hidden" name="csrf_test_name" id="CSRF_TOKEN" value="<?=$this->security->get_csrf_hash()?>"/>
        <input type="hidden" name="txtHeadLevel" id="txtHeadLevel" class="form_input"  value="<?=$role->HeadLevel?>"/>
        <input type="hidden" name="txtHeadType" id="txtHeadType" class="form_input"  value="<?=$role->HeadType?>"/>    
        <tr>
            <td>Head Code</td>
            <td>
                <input type="text" name="txtHeadCode" id="txtHeadCode" class="form_input"  value="<?=$role->HeadCode?>" readonly="readonly"/>
            </td>
        </tr>
        <tr>
            <td>Head Name</td>
            <td>
                <input type="text" name="txtHeadName" id="txtHeadName" class="form_input" value="<?=$role->HeadName?>"  onkeyUp="checkNameField('txtHeadName','nameLabel')"/>
                <input type="hidden" name="HeadName" id="HeadName" class="form_input" value="<?=$role->HeadName?>"/><label id="nameLabel" class="errore"></label>
            </td>
        </tr>
        <tr>
            <td>Parent Head</td>
            <td>
                <input type="text" name="txtPHead" id="txtPHead" class="form_input" readonly="readonly" value="<?=$role->PHeadName?>"/>
            </td>
        </tr>
        <?php if($role->HeadLevel > 3):?>
        <tr>
            <td>Note No</td>
            <td>
                <input type="text" name="noteNo" id="noteNo" class="form_input"  value="<?=$role->noteNo?>"/>
            </td>
        </tr>
        <?php endif;?>
        <tr>
            <td></td>
            <td id="innerCheck">
                <input type="checkbox" name="IsActive" id="IsActive" <?=($role->IsActive == 1)?'checked':''?>>
                <label for="IsActive">Is Active</label>

                <?php if($role->HeadLevel > 3 && ($role->HeadType == 'A' || $role->HeadType == 'L')):?>
                <input type="checkbox" name="isFixedAssetSch" id="isFixedAssetSch" value="1" onchange="isFexedAssetSch_chage('isFizedAssetSch', '<?=$role->HeadType?>')" <?=($role->isFixedAssetSch == 1)?'checked':''?>>
                <label for="isFixedAssetSch">is Fixed Asset</label>
                <?php endif;?>

                <?php if($role->HeadLevel > 3): ## FIRST CONDTION CHECKBOX?>
                <?php if($role->HeadType == "A"): ## SECOND CONDTION?>
                <input type="checkbox" name="isStock" value="1" id="isStock" onchange="isStock_chage()" <?=($role->isStock == 1)?'checked':''?>>
                <label for="isStock">is Stock</label>

                <input type="checkbox" name="isCashNature" value="1" id="isCashNature" onchange="isCashNature_change()" <?=($role->isCashNature == 1)?'checked':''?>>
                <label for="isCashNature">is Cash Nature</label>

                <input type="checkbox" name="isBankNature" value="1" id="isBankNature" onchange="isBankNature_chage()" <?=($role->isBankNature == 1)?'checked':''?>>
                <label for="isBankNature">is Bank Nature</label>
                <?php endif; ## ./SECOND?>
                <input type="checkbox" name="isSubType" value="1" id="isSubType" onchange="isSubType_chage()" <?=($role->isSubType == 1)?'checked':''?>>
                <label for="isSubType">is Bank Nature</label>
                <?php endif; ## ./FIRST?>
            </td>
        </tr>
        <?php if($role->isFixedAssetSch == 1): ## FIRST GROUP CONDITION?>
        <?php if($role->HeadLevel > 3 && $role->HeadType == 'A'): ## SECOND GROUP CONDITION?>
        <tr id="fixedassetCode">
            <td>Fixed Asset Code</td>
            <td>
                <input type="text" name="assetCode" id="assetCode" class="form_input" value="<?=$role->assetCode?>"/>
            </td>
        </tr>
        <tr id="fixedassetRate">
            <td>Depreciation Rate %</td>
            <td>
                <input type="text" name="DepreciationRate" id="DepreciationRate" class="form_input" value="<?=$role->DepreciationRate?>"/>
            </td>
        </tr>
        <?php elseif($role->HeadLevel > 3 && $role->HeadType == "L"): ## SECOND GROUP CONDITION?>
        <tr id="depreciationCode">
            <td>Depraciation Code</td><td><input type="text" name="depCode" id="depCode" class="form_input" value="<?=$role->depCode?>"/></td>
        </tr>
        <?php endif; ## ./SECOND?>
        <tr id="fixedassetCode"></tr>
        <tr id="depreciationCode"></tr>
        <?php endif; ## ./FIRST?>
        <?php if($role->HeadLevel >= 2): ## FIRST GROUP CONDITION ?>
        <tr id="">
            <input type="btnUndo" class="btn btn-success" value="Undo">
        </tr>
        <?php endif;?>
    </table>
</form>