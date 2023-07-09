<!DOCTYPE html>

<html lang="en"
    xmlns="http://www.w3.org/1999/xhtml"
    xml:lang="en">

<head>
<?php $this->render("head") ?>
</head>

<body class="no-js">

<main id="preferences-manager">

<header>

<?php $this->render("nav") ?>

<h1>Preferences Management</h1>

</header>

<?php if (!empty($resources = [...$resources])): ?>
<table class="list">

<caption>
<strong>Existing Preferences</strong>
<details>
<summary>What's a Preference</summary>
<p>Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat.</p>
</details>
</caption>

<colgroup><col><col><col>
<colgroup><col>

<thead>
    <tr>
        <th>Name
        <th>Description
        <th>Color
        <th>Actions

<tbody>
    <?php
    /** @var \Bloqs\Models\Category $i */
    foreach ($resources as $i):
    ?>
    <tr>
        <td><?=$i->getName()?>
        <td <?=$i->getDescription() ? "" : "class=\"no-desc\""?>><?=$i->getDescription() ?: "(no description)"?>
        <td class="clr"><div style="background-color: <?=$i->getColor()?>;"></div>
        <td><button class="button">Update</button><button class="button">Delete</button></td>
    <?php endforeach; ?>

</table>
<?php else: ?>
<p>No preferences found</p>
<?php endif; ?>

<section class="create">

<h2>Create Preference</h2>

<form autocomplete="on" method="POST" name="preferences">

<p><label>Name: <input name="name" type="text" autocomplete="name" maxlength="80" min="1" required /></label></p>

<div class="flex">
<p><label>Description: <textarea name="description" cols="35" rows="4" maxlength="140" placeholder="Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat."></textarea></label></p>
<p><label>Color: <input name="color" type="color" required /></label></p>
</div>

<div class="flex">
<p><button class="button wide" type="submit">Create</button></p>
<p><button class="button" type="reset">Reset</button></p>
</div>
</form>

<section>

</main>

</body>

</html>
