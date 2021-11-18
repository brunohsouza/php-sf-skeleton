<?php

declare(strict_types=1);

namespace App\Application\Command;


class CreateSalesOrderCommand
{
    private readonly string $merchantOrderReference;
    private readonly string $merchantId;
    private readonly \ArrayObject $lines;
    private readonly string $brand;
    private readonly string $country;
    private readonly \ArrayObject $shippingAddress;
    private readonly \ArrayObject $billingAddress;
    private readonly ?\ArrayObject $shippingContacts;

    public function __construct(
        string $merchantOrderReference,
        string $merchantId,
        array $lines,
        string $brand,
        string $country,
        array $shippingAddress,
        array $billingAddress,
        array $shippingContacts = null
    ) {
        $this->merchantOrderReference = $merchantOrderReference;
        $this->merchantId = $merchantId;
        $this->lines = new \ArrayObject($lines);
        $this->brand = $brand;
        $this->country = $country;
        $this->shippingAddress = new \ArrayObject($shippingAddress);
        $this->billingAddress = new \ArrayObject($billingAddress);
        $this->shippingContacts = isset($shippingContacts) ? new \ArrayObject($shippingContacts) : null;
    }

    public function getMerchantOrderReference(): string
    {
        return $this->merchantOrderReference;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getLines(): \ArrayObject
    {
        return $this->lines;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getShippingAddress(): \ArrayObject
    {
        return $this->shippingAddress;
    }

    public function getBillingAddress(): \ArrayObject
    {
        return $this->billingAddress;
    }

    public function getShippingContacts(): ?\ArrayObject
    {
        return $this->shippingContacts;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }
}
