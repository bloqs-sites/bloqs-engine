<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <?php

use Bloqs\Models\Category;

 $this->render("head") ?>
</head>

<body class="no-js">

<?php if (is_iterable($preferences)) : ?>

<form action="#" method="POST" enctype="multipart/form-data">

<p><label>Name: <input name="name" required /></label></p>
<p><label>Description: <input name="description" required /></label></p>
<select name="preference" required>
<?php foreach ($preferences as $p) : ?>
<?php if ($p instanceof Category): ?>
<option value="<?=$p->getId()?>"><?=$p->getName()?></option>
<?php endif; ?>
<?php endforeach; ?>
</select>

<p><label>Image: <input type="file" name="image" /></label></p>

<?php if ($adultAllowed === true) : ?>
<p><label><input type="checkbox" name="adult" />+18</label></p>
<?php endif; ?>

<p><button type="submit">Create Bloq!</button></p>

</form>

<?php else: ?>

<p>An error on the server side occured :(</p>

<?php endif; ?>

</body>

</html>
