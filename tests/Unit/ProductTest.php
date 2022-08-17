<?php

namespace Tests\Unit;

use App\Classes\Cap;
use App\Classes\Discount;
use App\Classes\Tax;
use App\Classes\Transport;
use App\Classes\UpcDiscount;
use App\Exceptions\NotValidCurrencyException;
use PHPUnit\Framework\TestCase;
use App\Classes\Product;

class ProductTest extends TestCase {

    private Product $product;
    private Tax $tax;
    private Product $productWithDiscount;
    private Discount $discount;
    private UpcDiscount $upcDiscount;

    protected function setUp() : void
    {
        $this->tax = new Tax(20);
        $this->product = new Product("The Little Prince", 12345, 20.25, $this->tax);
        $this->discount = new Discount(10);
        $this->upcDiscount = new UpcDiscount(10, 1244);
        $this->productWithDiscount = new Product("Book", 123, 100, $this->tax, $this->discount);
    }

    /** @test */
    public function can_get_product_name(): void
    {
        $this->assertEquals("The Little Prince", $this->product->getName());
    }

    /** @test */
    public function can_get_product_upc(): void
    {
        $this->assertEquals(12345, $this->product->getUpc());
    }

    /** @test */
    public function can_get_product_price(): void
    {
        $this->assertEquals(20.25, $this->product->getPrice());
    }

    /** @test */
    public function can_calculate_tax_price(): void
    {
        $taxCost = round($this->product->getPrice() * $this->tax->getRate() / 100, 2);
        $this->assertEquals($taxCost, $this->product->taxCost());
    }

    /** @test */
    public function price_with_tax_is_changed_when_tax_is_changed(): void
    {
        $taxCost = round($this->product->getPrice() * $this->tax->getRate() / 100, 2);
        $this->assertEquals($taxCost, $this->product->taxCost());
        $this->tax->setRate(21);
        $changedTaxCost = round($this->product->getPrice() * $this->tax->getRate() / 100, 2);
        $this->assertEquals($changedTaxCost, $this->product->taxCost());
    }

    /** @test */
    public function discount_price_is_0_if_discount_is_not_added(): void
    {
        $this->assertEquals(0, $this->product->universalDiscountAmount());
    }

    /** @test */
    public function can_calculate_discount_price(): void
    {
        $priceDiscount = round($this->productWithDiscount->getPrice() *
            $this->discount->getDiscount() / 100, 2);
        $this->assertEquals($priceDiscount, $this->productWithDiscount->universalDiscountAmount());
    }

    /** @test */
    public function price_upc_discount_is_0_if_upc_discount_is_not_added(): void
    {
        $this->assertEquals(0, $this->product->upcDiscountAmount());
    }

    /** @test */
    public function price_upc_discount_is_0_if_upc_discount_is_not_same_as_product_upc(): void
    {
        $productOtherUpc = new Product("Book", 123, 100,
            $this->tax, null, $this->upcDiscount);
        $this->assertEquals(0, $productOtherUpc->upcDiscountAmount());
    }

    /** @test */
    public function can_calculate_upc_discount(): void
    {
        $productWithUpcDiscount = new Product("Book", 1244, 100,
            $this->tax, null, $this->upcDiscount);
        $priceUpcDiscount = round($productWithUpcDiscount->getPrice() *
            $this->upcDiscount->getUpcDiscount()  / 100, 2);
        $this->assertEquals($priceUpcDiscount, $productWithUpcDiscount->upcDiscountAmount());
    }

    /** @test */
    public function can_calculate_all_discounts(): void
    {
        $productWithDiscounts = new Product("Book", 1244, 100, $this->tax,
            $this->discount, $this->upcDiscount);
        $allDiscounts = round($productWithDiscounts->getPrice() * $this->discount->getDiscount() / 100, 2)
            + round($productWithDiscounts->getPrice() * $this->upcDiscount->getUpcDiscount() / 100, 2);
        $this->assertEquals($allDiscounts, $productWithDiscounts->allDiscounts());
    }

    /** @test */
    public function can_calculate_lower_price_when_upc_discount_before_price(): void
    {
        $upcDiscount = new UpcDiscount(10, 1244, true);
        $product = new Product("Book", 1244, 100, $this->tax,
            $this->discount, $upcDiscount);
        $lowerPrice = $product->getPrice() - $product->upcDiscountAmount();
        $this->assertEquals($lowerPrice, $product->lowerPrice());
    }

    /** @test */
    public function can_calculate_lower_price_when_universal_discount_before_price(): void
    {
        $discount = new Discount(10, true);
        $product = new Product("Book", 1244, 100, $this->tax,
            $discount);
        $lowerPrice = $product->getPrice() - $product->universalDiscountAmount();
        $this->assertEquals($lowerPrice, $product->lowerPrice());
    }

    /** @test */
    public function can_calculate_lower_price_when_universal_discount_and_upc_discount_before_price(): void
    {
        $upcDiscount = new UpcDiscount(10, 1244, true);
        $discount = new Discount(10, true);
        $product = new Product("Book", 1244, 100, $this->tax,
            $discount, $upcDiscount);
        $lowerPrice = $product->getPrice() - $product->universalDiscountAmount() - $product->upcDiscountAmount();
        $this->assertEquals($lowerPrice, $product->lowerPrice());
    }

    /** @test */
    public function lower_price_is_equal_to_price_when_no_discount_before_tax(): void
    {
        $productWithDiscounts = new Product("Book", 123, 100, $this->tax, $this->discount, $this->upcDiscount);
        $lowerPrice = $productWithDiscounts->getPrice();
        $this->assertEquals($lowerPrice, $productWithDiscounts->lowerPrice());
    }

    /** @test */
    public function lower_price_is_equal_to_price_when_no_discounts(): void
    {
        $productWithoutDiscounts = new Product("Book", 123, 100, $this->tax);
        $lowerPrice = $productWithoutDiscounts->getPrice();
        $this->assertEquals($lowerPrice, $productWithoutDiscounts->lowerPrice());
    }

    /** @test */
    public function can_calculate_transport_costs_with_absolute_type_of_amount(): void
    {
        $transport = new Transport(1, 'absolute');
        $productWithTransport = new Product("Book", 1244, 100, $this->tax,
            null, null, $transport);
        $transportCosts = $transport->getAmount();
        $this->assertEquals($transportCosts, $productWithTransport->getTransportCost());
    }

    /** @test */
    public function can_calculate_transport_costs_with_percentage_type_of_amount(): void
    {
        $transport = new Transport(1, 'percentage');
        $productWithTransport = new Product("Book", 1244, 100, $this->tax,
            null, null, $transport);
        $transportCosts = round($productWithTransport->lowerPrice() * $transport->getAmount() / 100, 2);
        $this->assertEquals($transportCosts, $productWithTransport->getTransportCost());
    }

    /** @test */
    public function transport_cost_is_0_if_no_transport(): void
    {
        $this->assertEquals(0, $this->product->getTransportCost());
    }

    /** @test */
    public function can_calculate_universal_multiplicative_discount(): void
    {
        $multiplicativeDiscount = new Discount(15, false, true);
        $productWithMultiplicativeDiscount = new Product("Book", 1244, 100, $this->tax,
            $multiplicativeDiscount, $this->upcDiscount);
        $universalDiscount = round(($productWithMultiplicativeDiscount->getPrice() - $productWithMultiplicativeDiscount->upcDiscountAmount())
            * $multiplicativeDiscount->getDiscount() / 100, 2);
        $this->assertEquals($universalDiscount, $productWithMultiplicativeDiscount->universalDiscountAmount());
    }

    /** @test */
    public function can_calculate_upc_multiplicative_discount(): void
    {
        $multiplicativeUpcDiscount = new UpcDiscount(15, 1244, false, true);
        $productWithMultiplicativeDiscount = new Product("Book", 1244, 100, $this->tax,
            $this->discount, $multiplicativeUpcDiscount);
        $upcDiscount = round(($productWithMultiplicativeDiscount->getPrice() - $productWithMultiplicativeDiscount->universalDiscountAmount())
            * $multiplicativeUpcDiscount->getUpcDiscount() / 100, 2);
        $this->assertEquals($upcDiscount, $productWithMultiplicativeDiscount->upcDiscountAmount());
    }

    /** @test */
    public function no_universal_multiplicative_discount_if_no_upc_discount(): void
    {
        $multiplicativeDiscount = new Discount(15, false, true);
        $productWithMultiplicativeDiscount = new Product("Book", 1244, 100, $this->tax,
            $multiplicativeDiscount);
        $universalDiscount = round($productWithMultiplicativeDiscount->getPrice()
            * $multiplicativeDiscount->getDiscount() / 100, 2);
        $this->assertEquals($universalDiscount, $productWithMultiplicativeDiscount->universalDiscountAmount());
    }

    /** @test */
    public function can_calculate_cap_with_absolute_type_of_amount(): void
    {
        $cap = new Cap(20, 'absolute');
        $productWithAbsoluteCap = new Product("Book", 1244, 100, $this->tax,
            null, null, null, null, $cap);
        $capDiscount = round($productWithAbsoluteCap->getPrice()
            * $cap->getAmount() / 100, 2);
        $this->assertEquals($capDiscount, $productWithAbsoluteCap->calculateCap());
    }

    /** @test */
    public function can_calculate_cap_with_percentage_type_of_amount(): void
    {
        $cap = new Cap(20, 'percentage');
        $productWithPercentageCap = new Product("Book", 1244, 100, $this->tax,
            null, null, null, null, $cap);
        $capDiscount = round($productWithPercentageCap->getPrice()
            * $cap->getAmount() / 100, 2);
        $this->assertEquals($capDiscount, $productWithPercentageCap->calculateCap());
    }

    /** @test */
    public function calculate_cap_is_null_when_no_cap(): void
    {
        $this->assertNull($this->product->calculateCap());
    }

    /** @test */
    public function can_update_currency(): void
    {
        $this->assertEquals('USD', $this->product->getCurrency());
        $this->product->setCurrency('GBP');
        $this->assertEquals('GBP', $this->product->getCurrency());
    }

    /** @test */
    public function exception_when_set_not_valid_currency(): void
    {
        $this->expectException(NotValidCurrencyException::class);
        $this->product->setCurrency('not-valid-currency');
    }
}