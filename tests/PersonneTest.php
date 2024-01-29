<?php

namespace Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use App\Entity\Person;
use App\Entity\Wallet;
use App\Entity\Product;

class PersonneTest extends TestCase
{
    private Person $person;
    private Person $person2;

    private Person $person3;
    private Product $product;

    private Product $product2;

    private Person $person4;

    protected function setUp(): void
    {
        $this->person = new Person('Marie', 'USD');
        $this->person2 = new Person('JohnArbuckle', 'USD');
        $this->person3 = new Person('Navia', 'USD');
        $this->person4 = new Person('Lynette', 'EUR');
        $this->product = new Product('Parapluie', ['EUR' => 800.0], 'tech');
        $this->product2 = new Product('Ordinateur', ['USD' => 1000.0], 'tech');
        $this->person2->setWallet(new Wallet('USD'));
        $this->person3->setWallet(new Wallet('USD'));
        $this->person->setWallet(new Wallet('USD'));
        $this->person4->setWallet(new Wallet('EUR'));
    }

    public function testGetName()
    {
        $this->assertEquals('Marie', $this->person->getName());
    }

    public function testSetWallet()
    {
        $wallet = new Wallet('USD');
        $this->person->setWallet($wallet);
        $this->assertSame($wallet, $this->person->getWallet());
    }

    /**
     * @throws Exception
     */
    public function testTransferFund()
    {
        $this->person->getWallet()->addFund(100.0);

        $this->person->transfertFund(50.0, $this->person2);
        $this->assertEquals(50.0, $this->person->getWallet()->getBalance());
        $this->assertEquals(50.0, $this->person2->getWallet()->getBalance());
    }

    public function testTransferFundWithDifferentCurrencies()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Can't give money with different currencies");
        $this->person->transfertFund(50.0, $this->person4);
    }

    /**
     * @throws Exception
     */
    public function testBuyProduct()
    {
        $this->person->getWallet()->addFund(1500.0);
        $this->person->buyProduct($this->product2);
        $this->assertEquals(500.0, $this->person->getWallet()->getBalance());
    }


    /**
     * @throws Exception
     */
    public function testBuyProductWithInvalidCurrency()
    {
        $this->person->getWallet()->addFund(1000.0);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Can't buy product with this wallet currency");
        $this->person->buyProduct($this->product);
    }

    /**
     * @throws Exception
     */
    public function testHasFund()
    {
        $this->assertEquals(0, $this->person->getWallet()->getBalance());
        $this->person->getWallet()->addFund(100.0);
        $this->assertTrue($this->person->hasFund());
    }


    /**
     * @throws Exception
     */
    public function testDivideWallet()
    {
        $this->person->getWallet()->addFund(300);
        $this->person->divideWallet([$this->person,$this->person2, $this->person3]);
        $this->assertEquals(100, $this->person->getWallet()->getBalance());
        $this->assertEquals(100, $this->person2->getWallet()->getBalance());
        $this->assertEquals(100, $this->person3->getWallet()->getBalance());
    }

    /**
     * @throws Exception
     */
    public function testDivideWalletFloat()
    {
        $this->person->getWallet()->addFund(100);
        $this->person->divideWallet([$this->person,$this->person2, $this->person3]);
        $this->assertEquals(33.34, $this->person->getWallet()->getBalance());
        $this->assertEquals(33.33, $this->person2->getWallet()->getBalance());
        $this->assertEquals(33.33, $this->person3->getWallet()->getBalance());
    }
    public function testDivideWalletNegatif()
    {
        $this->person->divideWallet([$this->person,$this->person2, $this->person3]);
        $this->assertEquals(0, $this->person->getWallet()->getBalance());
        $this->assertEquals(0, $this->person2->getWallet()->getBalance());
        $this->assertEquals(0, $this->person3->getWallet()->getBalance());
    }

    /**
     * @throws Exception
     */
    public function testDivideWalletWithNoPersons()
    {
        $this->person->getWallet()->addFund(300);
        $this->person->divideWallet([$this->person,$this->person]);
        $this->assertEquals(300.0, $this->person->getWallet()->getBalance());
    }
}
