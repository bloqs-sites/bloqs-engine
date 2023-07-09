<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<?php
use function Bloqs\Config\cnf;

$this->render("head")
?>
</head>

<body class="no-js">

<main id="bloq-creation">
<h1 class="display-2">Create a <span class="clr-60 underline thick">Bloq</span></h1>

<?php if (is_iterable($preferences)) : ?>

<form action="#" name="bloqs-create" method="POST" enctype="multipart/form-data" autocomplete="on">

<p><label>Name: <input name="name" type="text" autocomplete="name" maxlength="80" minlength="1" required /></label></p>
<p><label>Description: <textarea name="description" minlength="0" maxlength="140" rows="4" required placeholder="My very cool product..."></textarea></p>

<fieldset>
<legend>Keywords</legend>
<p><label>Tags: <textarea name="keywords" placeholder="tag1;tag2;...;tagn" ></textarea></label></p>
</fieldset>

<div class="selects">
<label>Category:
<select name="preference" required>
<?php
/** @var \Bloqs\Models\Category $p */
foreach ($preferences as $p):
?>
<option value="<?=$p->getId()?>"><?=$p->getName()?></option>
<?php endforeach; ?>
</select>
</label>

<label>Creator:
<select name="creator" required>
<?php
/** @var ?\Bloqs\Models\Person $cur */
if (isset($cur)):
?>
<option value="<?=$cur->getId()?>"><?=$cur->getName()?></option>
<?php endif; ?>
<optgroup label="Profiles">
<?php
if (is_iterable($profiles)):
/** @var \Bloqs\Models\Person $p */
foreach ($profiles as $p):
?>
<option value="<?=$p->getId()?>"><?=$p->getName()?></option>
<?php
endforeach;
endif;
?>
</optgroup>
<optgroup label="Organizations">
</optgroup>
</select>
</label>
</div>

<p><label>Image: <input type="file" accept="image/*" multiple name="image" /></label></p>

<?php if ((false ?? cnf("REST", "NSFW") ?: false) === true): ?>
<p><label><input type="checkbox" name="adult" />This item has content Consideration? (+18)</label></p>
<?php endif; ?>

<p><button class="button wide inverted" type="submit">Create Bloq!</button></p>

</form>

<?php else: ?>

<p>An error on the server side occured :(</p>

<?php endif; ?>

</main>

</body>

</html>
