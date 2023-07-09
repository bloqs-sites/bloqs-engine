<?php
/** @var \TorresDeveloper\MVC\View\ViewLoader $this */
/** @var \Bloqs\Models\Product $bloq */

use function Bloqs\Core\getLang;
use function TorresDeveloper\MVC\baseurl;

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

</header>

<section id="product" itemscope itemtype="https://schema.org/Product">

<div class="main">

<div class="id">

<div class="identifier">
<h1 itemprop="name"><?=$bloq->getName()?></h1>

<code itemprop="identifier"><?=$bloq->getId()?></code>
</div>

<div class="category">
<span itemprop="category" style="--clr: <?=$bloq->getPreference()->getColor()?>;"><?=$bloq->getPreference()->getName()?></span>
<span itemprop="isFamilyFriendly" class="visually-hidden"><?=$bloq->getHasAdultConsideration() ? "True" : "False" ?></span>
<?php if($bloq->getHasAdultConsideration()): ?>
<span class="nsfw">NSFW</span>
<?php endif; ?>
</div>

</div>

<img loading="lazy" decoding="async" itemprop="image" src="<?= "https://picsum.photos/512" ?? $bloq->getImage() ?? "https://picsum.photos/512" ?>" alt="bloq" />

<p itemprop="description"><?=$bloq->getDescription()?></p>

<time itemprop="releaseDate" datetime="<?=$bloq->getReleaseDate()->format(\DateTimeInterface::ATOM)?>"><?=$bloq->getReleaseDate()->format("Y-m-d")?></time>

</div>

<hr />

<div class="keywords">
<h2>Tags</h2>

<?php if ($keywords = $bloq->getKeywords()): ?>
<ul>
<?php
/** @var string $keyword */
foreach($keywords as $keyword):
?>
<li itemprop="keywords"><?=$keyword?></li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<p>(none)</p>
<?php endif; ?>

</div>

<hr />

<div class="top-notes">

<div itemprop="review" itemscope itemtype="http://schema.org/Rating" >

<h2>Top Positive note</h2>

<div class="content">

<div itemprop="author" itemscope itemtype="http://schema.org/Person">
<img loading="lazy" decoding="async" itemprop="image" src="<?= ("https://picsum.photos/144.webp?random=" . rand()) ?? $bloq->getImage() ?>" alt="bloq" />
<p itemprop="name">UsernameVaryPag</p>
</div>

<meter itemprop="ratingValue" min="-5" value="3" max="5" low="0" high="1" optimum="4">Rated 3/5</meter>

<p itemprop="ratingExplanation">Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat.</p>

<a href="<?=baseurl("bloq/index/" . $bloq->getId() . "/reviews/123")?>">
<?= "&#x1F446" ?: "&#x1F447" ?>; +14.5k
&#x1F4AC; 5.7k
</a>

</div>

</div>

<div itemprop="review" itemscope itemtype="http://schema.org/Rating" >

<h2>Top Negative note</h2>

<div class="content">

<div itemprop="author" itemscope itemtype="http://schema.org/Person">
<img loading="lazy" decoding="async" itemprop="image" src="<?= ("https://picsum.photos/144.webp?random=" . rand()) ?? $bloq->getImage() ?>" alt="bloq" />
<p itemprop="name">UsernameVaryPag</p>
</div>

<meter itemprop="ratingValue" min="-5" value="-2" max="5" low="0" high="1" optimum="4">Rated -2/5</meter>

<p itemprop="ratingExplanation">Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat.</p>

<a href="<?=baseurl("bloq/index/" . $bloq->getId() . "/reviews/123")?>">
<?= "&#x1F446" ?: "&#x1F447" ?>; +14.5k
&#x1F4AC; 5.7k
</a>

</div>

</div>

</div>

<hr />

<?= $this->load("php/product/reviews", [
    "bloq" => $bloq,
])->getContents() ?>

<hr />

<section class="related">
<h2>Related</h2>
</section>

</section>

</main>

</body>

</html>
