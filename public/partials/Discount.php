<?php

use App\Classes\Discount;
use App\Classes\Product as Product;
use App\Classes\Tax as Tax;
use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\ToHighValueException;
?>

<h2>DISCOUNT</h2>

<?php
$tax = new Tax();
$discount = new Discount();
try {
    $discount->setDiscount(15);
} catch (NotFloatException | ToHighValueException | NotPositiveFloatException $ex) {
    echo $ex->getMessage();
    die();
}
$productWithDiscount = new Product("The Little Prince", 12345, 20.25, $tax, $discount);
?>
<h4>Tax=<?php echo $tax->getRate() ?>%, discount=<?php echo $discount->getDiscount() ?>%</h4>
<h4>Tax amount = $<?php echo $productWithDiscount->taxCost() ?>;
    Discount amount = $<?php echo $productWithDiscount->universalDiscount() ?></h4>
<h4>Price before = $<?php echo $productWithDiscount->getPrice() ?>,
    price after = $<?php echo $productWithDiscount->getPriceWithTaxAndDiscounts() ?></h4>