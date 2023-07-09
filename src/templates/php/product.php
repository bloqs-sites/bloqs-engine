<?php /** @var \Bloqs\Models\Product $bloq */

use function TorresDeveloper\MVC\baseurl;

?>
<div class="bloq template<?=0 ?? rand(0, 11)?>">
  <img loading="lazy" decoding="async" class="image" src="<?= ("https://picsum.photos/254.webp?random=" . rand()) ?? $bloq->getImage() ?? "https://picsum.photos/254" ?>" alt="bloq" />
  <span class="name"><?=$bloq->getName()?></span>
  <span class="price">34</span>
  <span class="category"><span class="c"><?= $bloq->getPreference()->getName() ?></span> <?php if($tags = count($bloq->getKeywords())): ?><span class="untitle" title="tags">[<?=$tags?>]</span><?php endif; ?> <?php if($bloq->getHasAdultConsideration()): ?><span class="nsfw">NSFW</span><?php endif; ?></span>
  <span class="author">Created by: <a class="a" href="<?=baseurl("profile/" . $bloq->getCreator()->getId())?>"><?=$bloq->getCreator()->getName()?></a></span>
  <a href="<?=baseurl("bloq/index/" . $bloq->getId())?>#reviews" class="aggregaterating">
  <div class="ratings">
  <span class="raemoji"><?= "&#x1F446" ?: "&#x1F447" ?>;</span><span class="racount">+14.5k</span>
  </div>
  <div class="reviews">
  <span>&#x1F4AC;</span><span class="recount">5.7k</span>
  </div>
  </a>
  <ul class="actions">
    <li><a href="<?=baseurl("bloq/index/" . $bloq->getId())?>" class="visit">Visit</a></li>
    <li><a href="#" class="buy">Buy</a></li>
    <li><a href="#" class="more">V</a></li>
  </ul>
  <ul class="related">
    <li>O</li>
    <li>O</li>
    <li>O</li>
  </ul>
  <img loading="lazy" decoding="async" class="related-image" src="https://picsum.photos/144.webp?random=<?=rand()?>" alt="bloq" />
</div>
