<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <?php $this->render("head") ?>
</head>

<body class="no-js">

<form method="POST">

<p><label>Username: <input name="name" /></label></p>
<p><label>E-mail: <input type="email" name="email" /></label></p>
<p><label>Password: <input type="password" name="passwd" /></label></p>

<?php if (is_iterable($preferences)) : ?>
    <p>Select what are you interested in:</p>
    <div id="preferences-list">
        <?php foreach ($preferences as $p) : ?>
            <input class="visually-hidden" type="checkbox" id="<?= $p ?>-preference" name="preferences[<?= $p ?>]" />
            <label for="<?= $p ?>-preference"><?= $p ?></label>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($adultAllowed === true) : ?>
<p><label>Do you want to possibly be exposed to adult consideration content? <input type="checkbox" name="adult" /></label></p>
<?php endif; ?>

<p><button type="submit">Create acc</button></p>

</form>

</body>

</html>
