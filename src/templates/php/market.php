<!DOCTYPE html>

<html lang="en"
    xmlns="http://www.w3.org/1999/xhtml"
    xml:lang="en">

<head>
<?php $this->render("head") ?>
</head>

<body class="no-js">

<nav id="nav">

<img id="logo" src="https://source.unsplash.com/random/144x144" />

<?php if ($logged): ?>
<div id="bloqs-hamburger">

<label for="bloqs-a-toggle">+</label>
<input type="checkbox" id="bloqs-a-toggle">
<ul id="bloqs-anchors">
  <li><a href="#">Create Bloq</a></li>
  <li><a href="#">Create Organization Account</a></li>
</ul>

</div>

<p><span><?=$logged["id"]?></span></p>
<?php else: ?>
<ul id="acc-anchors">
  <li><a href="/client/make">Create Account</a></li>
  <li><a href="/client/auth">Log In</a></li>
</ul>
<?php endif; ?>

</nav>

<main>

<header>
<h1>Bloqs</h1>

<form action="#items">
<p id="search-bar">
<label><span class="visually-hidden">Search: </span><input type="search" name="s" id="search" placeholder="Search..."/></label>
<button type="submit" title="submit search" id="search-btn">&#x1F50D;</button>
</p>
</form>

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

</body>

</html>
