<?php

declare(strict_types=1);

namespace App\Application\Query;

class ProductsQuery
{
    private string $brand;
    private string $country;
    private string $language;
    private string $productId;
    private string $status;

    public function __construct(
        string $brand,
        string $country,
        string $language,
        string $productId,
        string $status
    )
    {
        $this->brand = $brand;
        $this->country = $country;
        $this->language = $language;
        $this->productId = $productId;
        $this->status = $status;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getSalesChannel(): string
    {
        return $this->salesChannel;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
