<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <?php $this->render("head") ?>
</head>

<body class="no-js">

    <main id="account-creation">
        <hgroup>
            <h1>Welcome to Bloqs!</h1>
            <p>Create an Account</p>
        </hgroup>

        <form method="POST">
            <?php if (is_iterable($preferences)) : ?>
                <p>Select what are you interested in:</p>
                <div id="preferences-list">
                    <?php foreach ($preferences as $p) : ?>
                        <input class="visually-hidden" type="checkbox" id="<?= preg_replace("/\s+/", "-", $p) ?>-preference" name="preferences[<?= $p ?>]" />
                        <label for="<?= preg_replace("/\s+/", "-", $p) ?>-preference"><?= $p ?></label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div id="text-inputs">
                <p><label for="form-name">Username</label></p>
                <p><input name="name" id="form-name" /></p>

                <p><label for="form-email">E-mail</label></p>
                <p><input type="email" name="email" id="form-email" /></p>

                <p><label for="form-passwd">Password</label></p>
                <p><input type="password" name="passwd" id="form-passwd" /></p>
            </div>

            <?php if ($adultAllowed === true) : ?>
                <p id="age-consideration"><label><input type="checkbox" />Do you want to possibly be exposed to adult consideration content?</label></p>
            <?php endif; ?>

            <p><button type="submit">Submit!</button></p>
        </form>
    </main>

</body>

</html>
