<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<?php $this->render("head") ?>
</head>

<body class="no-js">

<main id="sign">

<h1>Sign In</h1>

<?php if (($methods["basic"] ?? null) === true): ?>
<section id="basic">
<form action="<?=$action?>?<?=$type?>=basic&<?="$redirect=$location"?>" method="POST">
<p><label>Email: <input name="email" type="email" autocomplete="email" inputmode="email" /></label></p>
<p><label>Password: <input name="pass" type="password" autocomplete="new-password" /></label></p>
<p><button type="submit">Sign In</button></p>
</form>
</section>
<?php endif; ?>

<?php if ((($methods["basic"] ?? null) === true) && (($methods["sso"] ?? null) === true)): ?>
<hr />
<?php endif; ?>

<?php if (($methods["sso"] ?? null) === true): ?>
<section id="sso">
<ul>
<li><a href="#"><img class="sso-icon" src="TODO" alt="TODO" /></a></li>
</ul>
</section>
<?php endif; ?>

<?php if ((($methods["sso"] ?? null) === true) && (((($methods["2fa"] ?? null) === true) || (($methods["blockchain"] ?? null) === true) || (($methods["zkp"] ?? null) === true)))): ?>
<hr />
<?php endif; ?>

<?php if ((($methods["2fa"] ?? null) === true) || (($methods["blockchain"] ?? null) === true) || (($methods["zkp"] ?? null) === true)): ?>
<section>
<ul>
<?php if (($methods["2fa"] ?? null) === true): ?>
<li><a href="./?type=2FA">2FA</a></li>
<?php endif; ?>
<?php if (($methods["blockchain"] ?? null) === true): ?>
<li><a href="./?type=blockchain">Blockchain Based Solution</a></li>
<?php endif; ?>
<?php if (($methods["zkp"] ?? null) === true): ?>
<li><a href="./?type=ZKP">ZKP</a></li>
<?php endif; ?>
</ul>
</section>
<?php endif; ?>

<p>Already have credentials? <a href="#">Log In</a>.</p>

<aside>Want to know a little bit more about the Bloqs Auth system. Visit the <a href="#">wiki</a>.</aside>

</main>
</body>

</html>
