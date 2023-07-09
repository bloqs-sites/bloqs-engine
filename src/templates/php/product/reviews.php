<?php
/** @var \TorresDeveloper\MVC\View\ViewLoader $this */
/** @var \Bloqs\Models\Product $bloq */

use function TorresDeveloper\MVC\baseurl;

$url = baseurl("bloq/index/" . $bloq->getId());
$goodValue = true;
?>

<section id="reviews">

<h2>Reviews</h2>

<section itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">

<div class="visually-hidden" itemprop="itemReviewed" itemscope itemtype="https://schema.org/Product">
    <a itemprop="url" href="<?=$url?>">Item Reviewed</a>
</div>

<?= $goodValue ? "&#x1F446;" : "&#x1F447;" ?> <span itemprop="ratingValue">+4.5k</span><span itemprop="ratingCount">(3.2k ratings)</span>
&#x1F4AC; <span itemprop="reviewCount">5.7k</span>

</section>

<article itemprop="reviews" itemscope itemtype="https://schema.org/Review">
<div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">

<div>
<div itemprop="author" itemscope itemtype="https://schema.org/Person">
    <img loading="lazy" decoding="async" itemprop="image" src="https://picsum.photos/512.webp?random=<?=rand()?>" />
    <p itemprop="name">torres-developer</p>
</div>
<span itemprop="ratingValue">+5</span>
</div>

<div>
<p itemprop="ratingExplanation">Lorem ipsum dolor sit amet, officia excepteur ex fugiat reprehenderit enim labore culpa sint ad nisi Lorem pariatur mollit ex esse exercitation amet. Nisi anim cupidatat excepteur officia. Reprehenderit nostrud nostrud ipsum Lorem est aliquip amet voluptate voluptate dolor minim nulla est proident. Nostrud officia pariatur ut officia. Sit irure elit esse ea nulla sunt ex occaecat reprehenderit commodo officia dolor Lorem duis laboris cupidatat officia voluptate. Culpa proident adipisicing id nulla nisi laboris ex in Lorem sunt duis officia eiusmod. Aliqua reprehenderit commodo ex non excepteur duis sunt velit enim. Voluptate laboris sint cupidatat ullamco ut ea consectetur et est culpa et culpa duis.</p>
<a href="<?=baseurl("bloq/index/" . $bloq->getId() . "/reviews/123")?>">
<?= "&#x1F446" ?: "&#x1F447" ?>; +14.5k
&#x1F4AC; 5.7k
</a>



<article itemprop="reviews" itemscope itemtype="https://schema.org/Review">
<div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">

<div>
<div itemprop="author" itemscope itemtype="https://schema.org/Person">
    <img loading="lazy" decoding="async" itemprop="image" src="https://picsum.photos/512.webp?random=<?=rand()?>" />
    <p itemprop="name">torres-developer</p>
</div>
<span itemprop="ratingValue">+5</span>
</div>

<div>
<p itemprop="ratingExplanation">Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat.</p>
<a href="<?=baseurl("bloq/index/" . $bloq->getId() . "/reviews/123")?>">
<?= "&#x1F446" ?: "&#x1F447" ?>; +14.5k
&#x1F4AC; 5.7k
</a>
</div>

<p><a href="#">See More...</a></p>

</div>
</article>



</div>

<p><a href="#">See More...</a></p>

</div>
</article>

</section>
