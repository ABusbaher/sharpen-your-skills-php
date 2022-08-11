<?php
use App\Classes\Product as Product;
use App\Classes\Tax as Tax;
use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\ToHighValueException;

$tax = new Tax();
$product = new Product("The Little Prince", 12345, 20.25, $tax);
?>
    <h2>TAX</h2>

    <h4>Product price reported as $<?php echo $product->getPrice() ?>
        before tax and $<?php echo $product->getPriceWithTax() ?>
        after <?php echo $tax->getRate() ?>% tax.</h4>
<?php
try {
    $tax->setRate(21);
} catch (NotFloatException | ToHighValueException | NotPositiveFloatException $ex) {
    echo $ex->getMessage();
    die();
}
?>
<h4>Product price reported as $<?php echo $product->getPrice() ?>
    before tax and $<?php echo $product->getPriceWithTax() ?>
    after <?php echo $tax->getRate() ?>% tax.</h4>

