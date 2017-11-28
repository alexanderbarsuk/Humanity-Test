<div class="jumbotron mt-3">
    <h1><?=$exception->getMessage()?></h1>
    <p><?=nl2br($exception->getTraceAsString())?></p>

</div>