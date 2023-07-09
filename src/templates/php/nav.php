<?php

use function Bloqs\Config\cnf;
use function Bloqs\Config\hist;
use function Bloqs\Config\provider;
use function Bloqs\Core\getClient;
use function Bloqs\Core\issetToken;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\t;
?>

<?php if (!provider()): ?>

<dialog id="instance-asker" aria-labelledby="instance-asker-label" aria-describedby="instance-asker-description" open>

<h1 id="instance-asker-label" class="display-2">Provide a <span class="clr-30 underline-hover thick">Bloqs Instance</span></h1>

<p>An <strong>instance</strong> is what brings <span class="clr-10-hover">life</span> to this website.</p>
<p id="instance-asker-description">There are <a href="#">tons of instances to discover.</a><br />
After you choose one, put it on the form input and start browsing!</p>

<aside>For more information about instances, visit the <a href="#">Bloqs Wiki</a>.</aside>

<form action="<?=baseurl("market/provide")?>" autocomplete="on" method="POST" name="instance-asker">
<p><label>Instance configuration: <input autofocus required name="bloq-instance" type="url" autocomplete="url" inputmode="url" placeholder="http://example.org/bloqs-cnf.json" list="instances-history" /></label></p>
<datalist id="instances-history">
<label>
Instances from your history:
<select name="bloq-instance">
<?php if (is_iterable($fav_instances = null)): ?>
<optgroup label="Favourite instances">
<?php foreach ($favorite_instances as $i): ?>
<option label="<?=$i["name"]?>" value="<?=$i["uri"]?>" />
<?php endforeach; ?>
</optgroup>
<?php endif; ?>
<?php if (is_iterable($instances_hist = hist())): ?>
<?php foreach ($instances_hist as $i): ?>
<option label="<?=$i["name"]?>" value="<?=$i["uri"]?>" />
<?php endforeach; ?>
<?php endif; ?>
</select>
</label>
</datalist>

<button class="button wide inverted a11y-margin-top" type="submit">Browse instance</button>
</form>

</dialog>

<?php endif; ?>

<nav id="nav">

<div class="hamburger">

<label for="toggle">+</label>
<input type="checkbox" id="toggle">

<div class="drop">

<section>

<h2>Provider</h2>

<p>Provider: <a href="<?=provider()?>"><samp><?=provider()?></samp></a></p>
<a>Change</a>

</section>

<section class="anchors">

<?php if (issetToken()) : ?>
<div>
<h2>Bloqs</h2>
<ul>
<?php if (getClient()["is_super"] ?? false): ?>
<li><a href="/preferences">Manage Preference</a></li>
<?php endif; ?>
<li><a href="/bloq/make">Create Product</a></li>
<li><a href="/offer/create">Create Offer</a></li>
<li><a href="#">Create Organization Account</a></li>
</ul>
</div>
<?php endif; ?>

<div>
<h2>Links</h2>
<ul>
<li><a href="#">Example</a></li>
</ul>
</div>

</section>

</div>

</div>

<a href="<?=baseurl()?>"><img loading="lazy" decoding="async" id="logo" src="https://picsum.photos/200" alt="<?=cnf("logo", "alt") ?? "Bloqs instance Logo"?>" /></a>

<ul id="acc-anchors">
<?php if (issetToken()) : ?>
  <li><a class="button inverted" href="/credentials/revoke/">Log out</a></li>
<?php else : ?>
<li><a class="button inverted" href="/credentials/sign/"><?= t("Create Account"); ?></a></li>
  <li><a class="button inverted" href="/credentials/log/"><?= t("Log In"); ?></a></li>
<?php endif; ?>
</ul>

</nav>
