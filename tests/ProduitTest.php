<?php

namespace Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use App\Entity\Product;

class ProduitTest extends TestCase
{
    protected Product $product;
    protected Product $product2;

    protected Product $product3;

    protected Product $product4;
    public function setUp(): void
    {
        $this->product = new Product('Graine de Tournesol', ['USD' => 2.0], 'food');
        $this->product2 = new Product('Ordinateur', [], 'tech');
        $this->product3 = new Product('Parapluie', ['EUR' => 1000.0], 'tech');
        $this->product4 = new Product('Smartphone', ['USD' => 800.0, 'EUR' => 700.0], 'tech');
    }

    public function testConstructor()
    {
        $name = 'Ordinateur qui fais le café';
        $prices = ['USD' => 1000.0, 'EUR' => 900.0];
        $type = 'tech';

        $product = new Product($name, $prices, $type);

        $this->assertEquals($name, $product->getName());
        $this->assertEquals($prices, $product->getPrices());
        $this->assertEquals($type, $product->getType());
    }

    public function testInvalidType()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid type');

        new Product('Graine de tournesol', ['USD' => 1000.0], 'graines');
    }

    public function testSetPricesWithInvalidCurrency()
    {
        try {
            $this->product2->setPrices(['XYZ' => 500.0]);
            $this->fail('Invalid currency');
        } catch (Exception $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
            $this->assertEquals('Invalid currency', $exception->getMessage());
        }
    }

    public function testSetPricesWithNegativePrice()
    {

        try {
            $this->product->setPrices(['USD' => -100.0]);
            $this->fail('Invalid price');
        } catch (Exception $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
            $this->assertEquals('Invalid price', $exception->getMessage());
        }
    }

    public function testGetTVAForFoodProduct()
    {
        $this->assertEquals(0.1, $this->product->getTVA());
    }

    public function testGetTVAForNonFoodProduct()
    {
        $this->assertEquals(0.2, $this->product3->getTVA());
    }

    public function testListCurrencies()
    {

        $this->assertEquals(['USD', 'EUR'], $this->product4->listCurrencies());
    }

    /**
     * @throws Exception
     */
    public function testGetPrice()
    {


        $this->assertEquals(800.0, $this->product4->getPrice('USD'));
        $this->assertEquals(700.0, $this->product4->getPrice('EUR'));
    }

    public function testGetPriceWithInvalidCurrency()
    {


        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid currency');

        $this->product->getPrice('XYZ');
    }

    public function testGetPriceWithUnavailableCurrency()
    {

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Currency not available for this product');

        $this->product->getPrice('EUR');
    }
    public function testSetName()
    {
        $this->product->setName('Montre');

        $this->assertEquals('Montre', $this->product->getName());
    }

    /**
     * @throws Exception
     */
    public function testSetType()
    {
        $this->product->setType('food');
        $this->assertEquals('food', $this->product->getType());
    }
}
