<?php if(count($requests) > 0):?>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">User Name</th>
            <th scope="col">Start Date</th>
            <th scope="col">End Date</th>
            <th scope="col">Type</th>
            <th scope="col">Duration</th>
            <th scope="col">Available</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($requests as $request):?>
        <tr>
            <th scope="row"><?=$request->getId()?></th>
            <td><?=$request->getUserEntity()->getName()?></td>
            <td><?=$request->getStartDate()->format('Y-m-d')?></td>
            <td><?=$request->getEndDate()->format('Y-m-d')?></td>
            <td><?=$request->getType()?></td>
            <td><?=$request->getDuration()?></td>
            <td><?=$usersList[$request->getUserEntity()->getId()]->getDaysLeft($request->getType())?></td>
            <td><?=$request->getStatus()?></td>
            <td>
                <div class="btn-group btn-group-sm" role="group">
                    <a class="btn btn-info" href="/request/view/<?=$request->getId()?>" role="button">Details</a>
                    <?php if($request->getStatus() == \Entity\RequestEntity::STATUS['NEW'] or $user->getRole() == \Entity\UserEntity::USER_ROLES['MANAGER']):?>
                    <a class="btn btn-warning" href="/request/edit/<?=$request->getId()?>" role="button">Edit</a>
                    <a class="btn btn-danger" href="/request/delete/<?=$request->getId()?>" role="button">Delete</a>
                    <?php endif;?>
                </div>
                <?php if($user->getRole() == \Entity\UserEntity::USER_ROLES['MANAGER']):?>
                <div class="btn-group btn-group-sm" role="group">
                    <a class="btn btn-success <?=($request->getStatus() != \Entity\RequestEntity::STATUS['NEW'] ? 'disabled' : '')?>" href="/request/approve/<?=$request->getId()?>" role="button" <?=($request->getStatus() != \Entity\RequestEntity::STATUS['NEW'] ? 'aria-disabled="true"' : '')?>>Approve</a>
                    <a class="btn btn-dark <?=($request->getStatus() != \Entity\RequestEntity::STATUS['NEW'] ? 'disabled' : '')?>" href="/request/reject/<?=$request->getId()?>" role="button" <?=($request->getStatus() != \Entity\RequestEntity::STATUS['NEW'] ? 'aria-disabled="true"' : '')?>>Reject</a>
                </div>
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php else:?>
    <p>No requests</p>
<?php endif;?>
