<?php

/** @var \DateTimeInterface $availabilityStarts */
/** @var \DateTimeInterface $availabilityEnds */

function convertCamelCaseToSentence($input) {
    $result = '';

    // Loop through each character in the input string
    for ($i = 0; $i < strlen($input); $i++) {
        $char = $input[$i];

        // Check if the character is uppercase
        if (ctype_upper($char)) {
            // Add a space before the uppercase character
            $result .= ' ';
        }

        // Convert the character to lowercase and add it to the result
        $result .= strtolower($char);
    }

    return $result;
}

?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<?php

use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\now;

 $this->render("head") ?>
</head>

<body class="no-js">

<header>
<?=$this->render("php/nav")?>
</header>

<main id="order-creation">

<h1 class="display-2">Create an <span class="clr-60 underline thick">Order</span></h1>

<form method="<?=isset($offers) ? "POST" : "GET"?>" action="<?=baseurl(isset($offers) ? "offer/create" : "offer/create/items-offered")?>" >
<?php
$enum = [
	"BackOrder",
	"Discontinued",
	"InStock",
	"InStoreOnly",
	"LimitedAvailability",
	"OnlineOnly",
	"OutOfStock",
	"PreOrder",
	"PreSale",
    "SoldOute",
];
$min = now()->format("Y-m-d\\TH:i");
?>
<p><label>Availability
<select name="availability" size="<?=count($enum)?>">
<!-- <option value=""> -->
<?php
foreach ($enum as $i):
?>
<option <?php if (($old["availability"] ?? null) === $i): ?> selected <?php endif ?> value="<?=$i?>" label="<?=convertCamelCaseToSentence($i)?>">
<?php endforeach; ?>
</select>
</label></p>

<p><label>Start date: <input name="availabilityStarts" type="datetime-local" min="<?=$min?>" <?= isset($old["availabilityStarts"]) ? ("value=" . $old["availabilityStarts"]) : "" ?>  required /></label></p>
<p><label>End date: <input name="availabilityEnds" type="datetime-local" min="<?=$min?>" <?= isset($old["availabilityEnds"]) ? ("value=" . $old["availabilityEnds"]) : "" ?> required /></label></p>

<p><label>Vendor
<select name="offeredBy" required>
<?php /** @var ?\Bloqs\Models\Person $cur */ ?>
<option <?php if (!($old["offeredBy"] ?? null)): ?> selected <?php endif ?> value="<?=$cur->getId()?>"><?=$cur->getName()?></option>
<optgroup label="Profiles">
<?php
if (is_iterable($profiles)):
/** @var \Bloqs\Models\Person $p */
foreach ($profiles as $p):
?>
<option <?php if (($old["offeredBy"] ?? null) === $i): ?> selected <?php endif ?> value="<?=$p->getId()?>"><?=$p->getName()?></option>
<?php
endforeach;
endif;
?>
</optgroup>
<optgroup label="Organizations">
</optgroup>
</select>
</label></p>

<p><label>Price <input name="price" type="number" min="0.001" step="0.001" <?= isset($old["price"]) ? ("value=" . $old["price"]) : "" ?> required />$</label></p>

<?php if (isset($offers)): ?>

<fieldset>

<legend>Offer</legend>

<p><label>List of Offers made by <?= $cur->getName() ?>
<select name="offers[]" multiple required>
<?php
/** @var \Bloqs\Models\Product $i */
foreach ($offers as $i):
?>
<option value="<?=$i->getId()?>"><?=$i->getName()?></option>
<?php endforeach; ?>
</select>
</label></p>

</fieldset>

<?php endif; ?>

<p><button type="submit"><?= isset($offers) ? "Create" : "Show offers" ?></button></p>

</form>

</main>

</body>

</html>
