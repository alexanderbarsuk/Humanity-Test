<form method="post">
    <div class="form-group">
        <label for="startDateInput"><?=$startDate->getTitle()?></label>
        <input type="<?=$startDate->getType()?>" class="form-control <?=($startDate->getError()?'is-invalid':'')?>" id="startDateInput" name="<?=$startDate->getName()?>" value="<?=$startDate->getValue()?>" placeholder="<?=$startDate->getTitle()?>">
        <div class="invalid-feedback"><?=$startDate->getError()?></div>
    </div>
    <div class="form-group">
        <label for="endDateInput"><?=$endDate->getTitle()?></label>
        <input type="<?=$endDate->getType()?>" class="form-control <?=($endDate->getError()?'is-invalid':'')?>" id="endDateInput" name="<?=$endDate->getName()?>" value="<?=$endDate->getValue()?>" placeholder="<?=$startDate->getTitle()?>">
        <div class="invalid-feedback"><?=$endDate->getError()?></div>
    </div>
    <div class="form-group">
        <label for="typeInput"><?=$type->getTitle()?></label>
        <select id="typeInput" class="form-control <?=($type->getError()?'is-invalid':'')?>" name="<?=$type->getName()?>" >
            <?php foreach ($type->getElements() as $t):?>
                <option <?=$type->getValue()==$t?'selected':''?>><?=$t?></option>
            <?php endforeach;?>
        </select>
        <div class="invalid-feedback"><?=$type->getError()?></div>
    </div>
    <button type="submit" class="btn btn-primary is-invalid" name="request">Submit request</button>
    <div class="invalid-feedback"><?=$endDate->getError()?></div>
</form>