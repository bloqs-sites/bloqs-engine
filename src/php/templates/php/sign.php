<?php HOMEPAGE or exit(0) ?>

<?php $this->render("head") ?>

<body>

<form method="POST">
<p><label>Username: <input name="name" /></label></p>
<?php if ($preferences) : ?>
<p>Select what are you interested in:</p>
<div id="preferences-list">
    <?php foreach ($preferences as $p) : ?>
<input class="visually-hidden" type="checkbox" id="<?=$p?>-preference" name="preferences[<?=$p?>]" />
<label for="<?=$p?>-preference"><?=$p?></label>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<p><button type="submit">Create acc</button></p>
</form>

</body>

</html>