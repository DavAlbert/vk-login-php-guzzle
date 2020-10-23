<?php require 'partials/HeaderPartial.php' ?>
<div class="container">
    <div class="row">
        <div class="col text-center column align-items-center">
            <h1>Hello <?= /** @var string $firstName */ $firstName ?> <?= /** @var string $lastName */ $lastName ?></h1>
            <img class="m-2 img-responsive center-block" src="<?= /** @var string $photo */ $photo ?>"  alt="vk-photo"/>
            <a class="btn btn-outline-danger mt-3" href="/logout">Logout from VK</a>
        </div>
    </div>
</div>
<?php require 'partials/ScriptsPartial.php' ?>
