<?php
require_once  __DIR__ . '/../app/bootstrap.php';

use App\Classes\Product as Product;
use App\Classes\Tax as Tax;
use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\ToHighTaxException;

$tax = new Tax();
$product = new Product("The Little Prince", 12345, 20.25, $tax);
?>
<h1>Sharpen your skills</h1>
<h3>TAX</h3>

<h5>Product price reported as $<?php echo $product->getPrice() ?>
    before tax and $<?php echo $product->getPriceWithTax() ?>
    after <?php echo $tax->getRate() ?>% tax.</h5>
<?php
try {
    $tax->setRate(21);
} catch (NotFloatException | ToHighTaxException | NotPositiveFloatException $ex) {
    echo $ex->getMessage();
    die();
}
?>
<h5>Product price reported as $<?php echo $product->getPrice() ?>
    before tax and $<?php echo $product->getPriceWithTax() ?>
    after <?php echo $tax->getRate() ?>% tax.</h5>