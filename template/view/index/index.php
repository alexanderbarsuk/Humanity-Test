<div class="jumbotron">
    <?php if($user->getId()):?>
    <h1 class="display-3">Hello, <?=$user->getName()?>!</h1>
    <?php else:?>
    <h1 class="display-3">Hello, man! You are not signed in!</h1>
    <?php endif;?>



    <?php if($user->getRole() != \Entity\UserEntity::USER_ROLES['GUEST']):?>
    <hr class="my-4">
    <p class="lead">You have <?=$user->getDaysLeft()?> days of vacations in current year.</p>
    <p class="lead"><?=\Entity\RequestEntity::TYPE['PAID']?>: <strong><?=$user->getDaysLeft(\Entity\RequestEntity::TYPE['PAID'])?></strong></p>
    <p class="lead"><?=\Entity\RequestEntity::TYPE['MEDICAL']?>: <strong><?=$user->getDaysLeft(\Entity\RequestEntity::TYPE['MEDICAL'])?></strong></p>
    <?php endif;?>
</div>
