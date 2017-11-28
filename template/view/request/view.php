<div class="row">
    <div class="col-sm">
        <div class="card">
            <div class="card-header">
                Requester: <?=$request->getUserEntity()->getName()?>
            </div>
            <div class="card-body">
                <h4 class="card-title">Details:</h4>

                <p class="card-text"><strong></strong></p>

                <p class="card-text"><i>Id:</i> <strong><?=$request->getId()?></strong></p>
                <p class="card-text"><i>User name:</i> <strong><?=$request->getUserEntity()->getName()?></strong></p>
                <p class="card-text"><i>Start date:</i> <strong><?=$request->getStartDate()->format('Y-m-d')?></strong></p>
                <p class="card-text"><i>End date:</i> <strong><?=$request->getEndDate()->format('Y-m-d')?></strong></p>
                <p class="card-text"><i>Type:</i> <strong><?=$request->getType()?></strong></p>
                <p class="card-text"><i>Days:</i> <strong><?=$request->getDuration()?></strong></p>
                <p class="card-text"><i>Available:</i> <strong><?=$usersList[$request->getUserEntity()->getId()]->getDaysLeft($request->getType())?></strong></p>
                <p class="card-text"><i>Status:</i> <strong><?=$request->getStatus()?></strong></p>
            </div>
            <div class="card-footer">
                <?php if($request->getStatus() == \Entity\RequestEntity::STATUS['NEW'] or $user->getRole() == \Entity\UserEntity::USER_ROLES['MANAGER']):?>
                <div class="btn-group btn-group-sm" role="group">
                    <a class="btn btn-warning" href="/request/edit/<?=$request->getId()?>" role="button">Edit</a>
                    <a class="btn btn-danger" href="/request/delete/<?=$request->getId()?>" role="button">Delete</a>
                </div>
                <?php endif;?>
                <?php if($user->getRole() == \Entity\UserEntity::USER_ROLES['MANAGER']):?>
                    <div class="btn-group btn-group-sm" role="group">
                        <a class="btn btn-success <?=($request->getStatus() != \Entity\RequestEntity::STATUS['NEW'] ? 'disabled' : '')?>" href="/request/approve/<?=$request->getId()?>" role="button" <?=($request->getStatus() != \Entity\RequestEntity::STATUS['NEW'] ? 'aria-disabled="true"' : '')?>>Approve</a>
                        <a class="btn btn-dark <?=($request->getStatus() != \Entity\RequestEntity::STATUS['NEW'] ? 'disabled' : '')?>" href="/request/reject/<?=$request->getId()?>" role="button" <?=($request->getStatus() != \Entity\RequestEntity::STATUS['NEW'] ? 'aria-disabled="true"' : '')?>>Reject</a>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>

</div>