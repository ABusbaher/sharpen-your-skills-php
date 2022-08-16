<?php

use App\Classes\Discount;
use App\Classes\Packaging;
use App\Classes\Product as Product;
use App\Classes\Tax as Tax;
use App\Classes\Transport;
use App\Classes\UpcDiscount;
use App\Exceptions\NotTypeOfAmountException;

?>

<h2>EXPENSES</h2>

<?php
$tax = new Tax(21);
$discount = new Discount(15);
$upcDiscount = new UpcDiscount(7,12345);
$transport = new Transport(2.2);
$packaging = new Packaging(1);
try {
    $packaging->setTypeOfAmount('percentage');
} catch (NotTypeOfAmountException $ex) {
    echo $ex->getMessage();
    die();
}
$productWithAdditionalCost = new Product("The Little Prince", 12345, 20.25,
    $tax, $discount, $upcDiscount, $transport, $packaging);

$productWithoutAdditionalCost = new Product("The Little Prince", 12345, 20.25, $tax);
?>

<h4><?php echo $productWithAdditionalCost->reportCosts() ?></h4>
<h4><?php echo $productWithoutAdditionalCost->reportCosts() ?></h4>