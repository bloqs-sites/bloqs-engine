<?php //defined(HOMEPAGE) OR exit(0) ?>

<?php $this->render("head") ?>

<body>

<form action="POST">
<label for="preferences">Select what are you interested in:</label>
<select id="preferences" multiple name="preferences" required>
<?php foreach ($preferences as $p): ?>
<option value="<?=$p?>" label="<?=$p?>">
<?php endforeach; ?>
</select>
</form>

</body>

</html>
