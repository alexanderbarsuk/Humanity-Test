<!doctype html>
<html lang="en">
<head>
    <title><?=$title?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="http://php.net/favicon.ico">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="/"><?= $appName ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <?php if ($user->getRole() !== \Entity\UserEntity::USER_ROLES['GUEST']): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/leave">Request leave</a>
                    </li>
                <?php endif; ?>
                <?php if ($user->getRole() !== \Entity\UserEntity::USER_ROLES['GUEST']): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/requests">Manage requests</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">Login as</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">
                        <?php foreach ($usersList as $u): ?>
                            <a class="dropdown-item"
                               href="/login/<?= $u->getId() ?>"><?= $u->getName() . " - " . $u->getRole() ?></a>
                        <?php endforeach; ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/logout">Logout</a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item active text-white">
                    <?= $user->getName() ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main role="main" class="container">
    <?php if($message):?>
        <?php foreach ($message as $m):?>
        <div class="alert alert-<?=$m['type']?>" role="alert">
            <?=$m['text']?>
        </div>
        <?php endforeach;?>
    <?php endif;?>

<?=$content?>
</main>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>