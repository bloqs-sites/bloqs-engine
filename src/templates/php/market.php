<!DOCTYPE html>

<html lang="en"
    xmlns="http://www.w3.org/1999/xhtml"
    xml:lang="en">

<head>
<?php

use function TorresDeveloper\MVC\baseurl;

 $this->render("head") ?>
</head>

<body class="no-js">

<?php if (isset($provider)): ?>

<nav id="nav">

<!-- <img id="logo" src="https://source.unsplash.com/random/144x144" /> -->
<form>
<p><label>Bloqs Provider: <input name="provider" type="url" value="<?=$provider?>" /></label></p>
</form>

<?php if ($logged) : ?>
<div id="bloqs-hamburger">

<label for="bloqs-a-toggle">+</label>
<input type="checkbox" id="bloqs-a-toggle">
<ul id="bloqs-anchors">
  <li><a href="/bloq/make">Create Bloq</a></li>
  <li><a href="#">Create Organization Account</a></li>
</ul>

</div>

<p><span id="user-id"><?=$logged["id"]?></span></p>
<p><a href="/client/deauth">Log out</a></p>
<?php else : ?>
<ul id="acc-anchors">
  <li><a href="/credentials/">Create Account</a></li>
  <li><a href="/client/auth">Log In</a></li>
</ul>
<?php endif; ?>

</nav>

<main>

<header>
<h1><?=$name?></h1>

<search>
<form action="#items">
<p id="search-bar">
<label><span class="visually-hidden">Search: </span><input type="search" name="s" id="search" placeholder="Search..."/></label>
<button type="submit" title="submit search" id="search-btn">&#x1F50D;</button>
</p>
</form>
</search>

<form action="#items">
<p id="trends-text">I want to see the items</p>
<p id="trends-goto"><button type="submit" name="trends" title="submit trends search" id="trends-btn">&#x1F447;</button></p>
</form>
</header>

<div id="items">
<div class="bottom right"></div>
<div class="left right"></div>
<div class="left"></div>
<div class=""></div>
<div class=""></div>
<div class="top"></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
<div class=""></div>
</div>

</main>

<?php else: ?>

<script type="module" async src="/assets/js/provide-instance-dialog.js"></script>
<dialog id="instance-asker" open>

<h1>Provide an Bloqs Instance</h1>
<p>An <strong>instance</strong> is what brings life to this website.</p>
<p>There are <a href="#">tons of instances</a> to discover.<br />
After you choose one, put it on the form input and start browsing!</p>
<p>For more information about instances, visit the <a href="#">Bloqs Wiki</a>.</p>

<form action="<?=baseurl("market/provide")?>" autocomplete="on" method="POST" name="instance-asker">
<p><label>Instance configuration: <input autofocus required name="instance" type="url" autocomplete="url" inputmode="url" placeholder="https://example.org/bloqs-config.json" list="instances-history" /></label></p>
<datalist id="instances-history">
<label>
Instances from your history:
<select name="instance">
<?php if (is_iterable($fav_instances)): ?>
<optgroup label="Favourite instances">
<?php foreach ($favorite_instances as $i): ?>
<option label="<?=$i["name"]?>" value="<?=$i["uri"]?>" />
<?php endforeach; ?>
</optgroup>
<?php endif; ?>
<?php if (is_iterable($instances_hist)): ?>
<?php foreach ($instances_hist as $i): ?>
<option label="<?=$i["name"]?>" value="<?=$i["uri"]?>" />
<?php endforeach; ?>
<?php endif; ?>
</select>
</label>
</datalist>
<button type="submit">Browse instance</button>
</form>

</dialog>

<?php endif; ?>

</body>

</html>
