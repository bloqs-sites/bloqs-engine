<?php use function Bloqs\Core\getLang;

 ?>
<!DOCTYPE html>

<html lang="<?=getLang()->getCode()?>"
    xmlns="http://www.w3.org/1999/xhtml"
    xml:lang="en">

<head>
<?php $this->render("head") ?>
</head>

<body class="no-js">

<main>

<header>

<?php $this->render("nav") ?>

<h1><?=$name ?: "Welcome to Bloqs, no instance provided"?></h1>

</header>

<search>
<form action="#bloqs">
<p id="search-bar">
<label><span class="visually-hidden">Search: </span><input type="search" name="s" id="search" placeholder="Search..."/></label>
<button type="submit" title="submit search" id="search-btn">&#x1F50D;</button>
</p>
</form>
</search>

<form action="#bloqs">
<p id="trends-text">I want to see the items</p>
<p id="trends-goto"><button type="submit" name="trends" title="submit trends search" id="trends-btn">&#x1F447;</button></p>
</form>

<div id="bloqs">
<?php
$this->render("product_template");
/** @var \Bloqs\Models\Product $i */
foreach ($products ?? [] as $i):
?>
<?= $this->load("product", ["bloq" => $i])->getContents(); ?>
<?php endforeach; ?>
</div>

</main>

</body>

</html>
