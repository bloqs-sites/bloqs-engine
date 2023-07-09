<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<?php

use function TorresDeveloper\MVC\debug;

 $this->render("head") ?>
</head>

<body class="no-js">

<main id="sign">

<h1><?=$title?></h1>

<?php if (($methods["basic"] ?? null) === true): ?>
<section id="basic">
<form action="<?=$action?>/basic?redirect=<?=$redirect?>" method="POST">
<?php if(($type == 1) && isset($client)): ?>
<input value="<?=$client?>" name="email" type="hidden" autocomplete="email" inputmode="email" />
<p>Email: <samp><?=$client?></samp></p>
<?php else: ?>
<p><label>Email: <input name="email" type="email" autocomplete="email" inputmode="email" /></label></p>
<?php endif; ?>
<p><label>Password: <input name="pass" type="password" autocomplete="new-password" /></label></p>
<p class="flex"><button class="button wide" type="submit"><?=$title?></button></p>
</form>
</section>
<?php endif; ?>

<?php if ((($methods["basic"] ?? null) === true) && (($methods["sso"] ?? null) === true)): ?>
<hr />
<?php endif; ?>

<?php if (($methods["sso"] ?? null) === true): ?>
<section id="sso">
<ul>
<li><a href="#"><img loading="lazy" decoding="async" class="sso-icon" src="TODO" alt="TODO" /></a></li>
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

<?php if ($title === "Sign In"): ?>
<p>Already have credentials? <a href="/credentials/log">Log In</a>.</p>
<?php elseif ($title === "Log In"): ?>
<p>Did not create your credentials yet? <a href="/credentials/sign">Sign In</a>.</p>
<?php endif; ?>

<aside>Want to know a little bit more about the Bloqs Auth system. Visit the <a href="#">wiki</a>.</aside>

</main>
</body>

</html>
