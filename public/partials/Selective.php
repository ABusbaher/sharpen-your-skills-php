<?php

use App\Classes\Discount;
use App\Classes\Product as Product;
use App\Classes\Tax as Tax;
use App\Classes\UpcDiscount;

?>
<h2>SELECTIVE</h2>
<?php
$taxForProduct1 = new Tax();
$taxForProduct2 = new Tax(21);
$discount = new Discount(15);
$upcDiscount = new UpcDiscount(7,12345);
$product1 = new Product("The Little Prince", 12345, 20.25, $taxForProduct1, $discount, $upcDiscount);
$product2 = new Product("The Little Prince", 789, 20.25, $taxForProduct2, $discount, $upcDiscount);
?>

<h4><?php echo $product1->reportCosts() ?></h4>
<h4><?php echo $product2->reportCosts() ?></h4>