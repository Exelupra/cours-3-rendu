<?php

namespace Tests;

use App\Entity\Wallet;
use Exception;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    private Wallet $wallet;
    protected function setUp(): void
    {
        $this->wallet = new Wallet('USD');
    }
    public function testConstructorWithValidCurrency()
    {
        $this->assertInstanceOf(Wallet::class, $this->wallet);
        $this->assertEquals(0.0, $this->wallet->getBalance());
        $this->assertEquals('USD', $this->wallet->getCurrency());
    }

    /**
     * @throws Exception
     */
    public function testSetBalanceWithZeroBalance()
    {
        $this->wallet->setBalance(0.0);
        $this->assertEquals(0.0, $this->wallet->getBalance());
    }

    /**
     * @throws Exception
     */
    public function testSetBalanceWithPositiveBalance()
    {
        $this->wallet->setBalance(100.0);
        $this->assertEquals(100.0, $this->wallet->getBalance());
    }

    public function testSetBalanceWithNegativeBalance()
    {

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid balance');
        $this->wallet->setBalance(-50.0);
    }

    /**
     * @throws Exception
     */
    public function testSetCurrencyWithValidCurrency()
    {
        $this->wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $this->wallet->getCurrency());
    }

    public function testSetCurrencyWithInvalidCurrency()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid currency');
        $this->wallet->setCurrency('XYZ');
    }

    /**
     * @throws Exception
     */
    public function testRemoveFundWithValidAmount()
    {
        $this->wallet->setBalance(100.0);
        $this->wallet->removeFund(50.0);
        $this->assertEquals(50.0, $this->wallet->getBalance());
    }


    /**
     * @throws Exception
     */
    public function testRemoveFundWithInsufficientFunds()
    {

        $this->wallet->setBalance(30.0);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Insufficient funds');
        $this->wallet->removeFund(50.0);
    }

    public function testRemoveFundWithNegative()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid amount');
        $this->wallet->removeFund(-50.0);
    }

    /**
     * @throws Exception
     */
    public function testAddFundWithValidAmount()
    {
        $this->wallet->addFund(50.0);
        $this->assertEquals(50.0, $this->wallet->getBalance());
    }

    public function testAddFundWithNegativeAmount()
    {

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid amount');
        $this->wallet->addFund(-50.0);
    }
}
