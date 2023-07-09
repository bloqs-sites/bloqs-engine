<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<?php

use Bloqs\Models\Category;

 $this->render("head") ?>
</head>

<body class="no-js">

<main id="profile-selector">

<h1>Select Profile</h1>

<section id="list">

<ul id="profiles">
<?php
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\debug;

if (!empty($profiles)):
/** @var \Bloqs\Models\Person $i */
foreach ($profiles as $i) :
?>
<li class="item">
<form action="<?= baseurl("client/select/" . $i->getId()) ?>" method="POST">
<input type="hidden" value="<?=$i->getId()?>" name="id" />
<button type="submit">
<p><?= $i->getName() ?><code>#<?=$i->getId()?></code></p>
<p class="lvl">Level <?=$i->getLevel()?></p>

<?php if ($i->getHasAdultConsideration()): ?>
<p class="nsfw">NSFW</p>
<?php endif; ?>


<?php if(count($i->getLikes()) > 0): ?>

<hr />

<p>Likes:</p>
<ul class="preferences-meter">
<?php foreach($i->getLikes() as $l): ?>
<li title="<?=$l->getName()?>" class="preference-fill" style="--clr: <?=$l->getColor()?>; --weight: <?=$i->getLikeWeight($l) / 1000?>"></li>
<?php endforeach; ?>
</ul>
<ul>
<?php
$sortedLikes = $i->getLikes();
uasort($sortedLikes, static function (Category $x, Category $y) use ($i) : int {
    return $i->getLikeWeight($x) <=> $i->getLikeWeight($y);
});
$c = 0;
foreach ($sortedLikes as $l): ?>
    <li><?=$l->getName()?> (<?=$i->getLikeWeight($l)/10?>%)</li>
<?php
if (++$c >= 3) {
    break;
}
?>
<?php endforeach; ?>
</ul>

<?php endif; ?>

<p><span class="button inverted" type="submit">Select</span></p>

</button>
</form>
</li>
<?php endforeach; ?>
<?php else: ?>
<li>(No profiles were created yet)</li>
<?php endif; ?>
</ul>

<?php if ($canCreate): ?>
<section id="create" class="item">

<h2>Create Profile</h2>

<form method="POST" enctype="multipart/form-data">
    <p><label><span class="required">Name:</span> <input name="name" type="text" autocomplete="username" min="1" max="80" required /></label></p>

    <div class="flex wrap center">
    <p><label>Description: <textarea name="description" cols="35" rows="4" maxlength="140" placeholder="Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat."></textarea></label></p>
    <p><label>Image: <input name="image" type="file" accept="image/*" /></label></p>
    </div>

    <p><label>Url: <input name="url" type="url" autocomplete="url" max="255" /></label></p>

    <?php if ($adultAllowed === true) : ?>
        <p id="age-consideration"><label><input name="adult" type="checkbox" />Do you want to possibly be exposed to adult consideration content?</label></p>
    <?php endif; ?>

    <?php if (is_iterable($preferences) && count($preferences) > 0) : ?>
        <p>Select what are you interested in:</p>
        <div id="preferences-list">
            <?php
            /** @var \Bloqs\Models\Category $i */
            foreach ($preferences as $p) :
            ?>
                <label><input class="visually-hidden" type="checkbox" name="preferences[<?= $p->getId() ?>]" /><?= $p->getName() ?></label>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <p><button type="submit">Submit!</button></p>
</form>

</section>
<?php endif; ?>

</section>

</main>

</body>

</html>
