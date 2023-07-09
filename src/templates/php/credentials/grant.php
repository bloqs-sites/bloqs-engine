<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<?php $this->render("head") ?>
</head>

<body class="no-js">

<h1>Grant Permissions</h1>

<p>Your current session does not have the following permissions:</p>
<ul>
<?php foreach ($permissions as $p): ?>
<li><?=$p?></li>
<?php endforeach; ?>
</ul>

<main>

<h2>Confirmation</h2>

<p>Insert your secret to be sure you want to grant the above permissions to this session!</p>

<?php if (($methods["basic"] ?? null) === true): ?>
<section id="basic">
<form action="<?=$query?>" method="POST">
<p><label>Password: <input name="pass" type="password" autocomplete="new-password" /></label></p>
<p class="flex"><button class="button wide" type="submit">Confirm grant</button></p>
</form>
</section>
<?php endif; ?>

</main>

<aside>Those granted permissions will be lost passed a certain period of time and this processes will repeat if needed.</aside>

</body>

</html>
