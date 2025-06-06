<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Item extends Model
{
    /**
     * The id of the item.
     *
     * @var int|null
     */
    protected $id;

    /**
     * The cat_id of the item.
     *
     * @var int
     */
    protected $catId = 0;

    /**
     * The name of the item.
     *
     * @var string
     */
    protected $name = '';

    /**
    * The code of the item.
    *
    * @var string
    */
    protected $code = '';

    /**
    * The itemnumber of the item.
    *
    * @var string
    */
    protected $itemnumber = '';

    /**
    * The stock of the item.
    *
    * @var int
    */
    protected $stock = 0;

    /**
    * The unitName of the item.
    *
    * @var string
    */
    protected $unitName = '';

    /**
    * The cordon of the item.
    *
    * @var int
    */
    protected $cordon = 0;

    /**
    * The cordonText of the item.
    *
    * @var string
    */
    protected $cordonText = '';

    /**
    * The cordonColor of the item.
    *
    * @var string|null
    */
    protected $cordonColor;

    /**
    * The price of the item.
    *
    * @var string
    */
    protected $price = '';

    /**
    * The tax of the item.
    *
    * @var int
    */
    protected $tax = 0;

    /**
    * The shippingCosts of the item.
    *
    * @var string
    */
    protected $shippingCosts = '';

    /**
    * The shippingTime of the item.
    *
    * @var int
    */
    protected $shippingTime = 0;

    /**
    * The image of the item.
    *
    * @var string
    */
    protected $image = '';

    /**
    * The image1 of the item.
    *
    * @var string
    */
    protected $image1 = '';

    /**
    * The image2 of the item.
    *
    * @var string
    */
    protected $image2 = '';

    /**
    * The image3 of the item.
    *
    * @var string
    */
    protected $image3 = '';

    /**
    * The info of the item.
    *
    * @var string
    */
    protected $info = '';

    /**
    * The desc of the item.
    *
    * @var string
    */
    protected $desc = '';

    /**
    * The status of the item.
    *
    * @var int
    */
    protected $status = 0;

    /**
     * Holds if this item is a variant.
     *
     * @var bool
     * @since 1.4.0
     */
    protected bool $isVariant = false;

    /**
     * Holds if this item has variants.
     *
     * @var bool
     * @since 1.4.0
     */
    protected bool $hasVariants = false;

    /**
     * Gets the id of the item.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the item.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Item
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the catId of the item.
     *
     * @return int
     */
    public function getCatId(): int
    {
        return $this->catId;
    }

    /**
     * Sets the catId of the item.
     *
     * @param int $catId
     * @return $this
     */
    public function setCatId(int $catId): Item
    {
        $this->catId = $catId;

        return $this;
    }

    /**
     * Gets the name of the item.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the item.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Item
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the itemnumber of the item.
     *
     * @return string
     */
    public function getItemnumber(): string
    {
        return $this->itemnumber;
    }

    /**
     * Sets the itemnumber of the item.
     *
     * @param string $itemnumber
     * @return $this
     */
    public function setItemnumber(string $itemnumber): Item
    {
        $this->itemnumber = $itemnumber;

        return $this;
    }

    /**
     * Gets the code of the item.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Sets the code of the item.
     *
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): Item
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Gets the stock of the item.
     *
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * Sets the stock of the item.
     *
     * @param int $stock
     * @return $this
     */
    public function setStock(int $stock): Item
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Gets the unitName of the item.
     *
     * @return string
     */
    public function getUnitName(): string
    {
        return $this->unitName;
    }

    /**
     * Sets the unitName of the item.
     *
     * @param string $unitName
     * @return $this
     */
    public function setUnitName(string $unitName): Item
    {
        $this->unitName = $unitName;

        return $this;
    }

    /**
     * Gets the cordon of the item.
     *
     * @return int
     */
    public function getCordon(): int
    {
        return $this->cordon;
    }

    /**
     * Sets the cordon of the item.
     *
     * @param int $cordon
     * @return $this
     */
    public function setCordon(int $cordon): Item
    {
        $this->cordon = $cordon;

        return $this;
    }

    /**
     * Gets the cordonText of the item.
     *
     * @return string
     */
    public function getCordonText(): string
    {
        return $this->cordonText;
    }

    /**
     * Sets the cordonText of the item.
     *
     * @param string $cordonText
     * @return $this
     */
    public function setCordonText(string $cordonText): Item
    {
        $this->cordonText = $cordonText;

        return $this;
    }

    /**
     * Gets the cordonColor of the item.
     *
     * @return string|null
     */
    public function getCordonColor(): ?string
    {
        return $this->cordonColor;
    }

    /**
     * Sets the cordonColor of the item.
     *
     * @param string|null $cordonColor
     * @return $this
     */
    public function setCordonColor(?string $cordonColor): Item
    {
        $this->cordonColor = $cordonColor;

        return $this;
    }

    /**
     * Gets the price of the item.
     *
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * Sets the price of the item.
     *
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): Item
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Gets the tax of the item.
     *
     * @return int
     */
    public function getTax(): int
    {
        return $this->tax;
    }

    /**
     * Sets the tax of the item.
     *
     * @param int $tax
     * @return $this
     */
    public function setTax(int $tax): Item
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Gets the shippingCosts of the item.
     *
     * @return string
     */
    public function getShippingCosts(): string
    {
        return $this->shippingCosts;
    }

    /**
     * Sets the shippingCosts of the item.
     *
     * @param string $shippingCosts
     * @return $this
     */
    public function setShippingCosts(string $shippingCosts): Item
    {
        $this->shippingCosts = $shippingCosts;

        return $this;
    }

    /**
     * Gets the shippingTime of the item.
     *
     * @return int
     */
    public function getShippingTime(): int
    {
        return $this->shippingTime;
    }

    /**
     * Sets the shippingTime of the item.
     *
     * @param string $shippingTime
     * @return $this
     */
    public function setShippingTime(string $shippingTime): Item
    {
        $this->shippingTime = (int)$shippingTime;

        return $this;
    }

    /**
     * Gets the preview image of the item.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Sets the preview image of the item.
     *
     * @param string $image
     * @return $this
     */
    public function setImage(string $image): Item
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Gets the image1 of the item.
     *
     * @return string
     */
    public function getImage1(): string
    {
        return $this->image1;
    }

    /**
     * Sets the image1 of the item.
     *
     * @param string $image1
     * @return $this
     */
    public function setImage1(string $image1): Item
    {
        $this->image1 = $image1;

        return $this;
    }

    /**
     * Gets the image2 of the item.
     *
     * @return string
     */
    public function getImage2(): string
    {
        return $this->image2;
    }

    /**
     * Sets the image2 of the item.
     *
     * @param string $image2
     * @return $this
     */
    public function setImage2(string $image2): Item
    {
        $this->image2 = $image2;

        return $this;
    }

    /**
     * Gets the image3 of the item.
     *
     * @return string
     */
    public function getImage3(): string
    {
        return $this->image3;
    }

    /**
     * Sets the image3 of the item.
     *
     * @param string $image3
     * @return $this
     */
    public function setImage3(string $image3): Item
    {
        $this->image3 = $image3;

        return $this;
    }

    /**
     * Gets the short info of the item.
     *
     * @return string
     */
    public function getInfo(): string
    {
        return $this->info;
    }

    /**
     * Sets the short info of the item.
     *
     * @param string $info
     * @return $this
     */
    public function setInfo(string $info): Item
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Gets the description of the item.
     *
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * Sets the description of the item.
     *
     * @param string $desc
     * @return $this
     */
    public function setDesc(string $desc): Item
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Gets the status of the item.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Sets the description of the item.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): Item
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get if this item is a variant.
     *
     * @return bool
     * @since 1.4.0
     */
    public function isVariant(): bool
    {
        return $this->isVariant;
    }

    /**
     * Set if this item is a variant.
     *
     * @param bool $isVariant
     * @return $this
     * @since 1.4.0
     */
    public function setIsVariant(bool $isVariant): Item
    {
        $this->isVariant = $isVariant;
        return $this;
    }

    /**
     * Get if this item has variants.
     *
     * @return bool
     * @since 1.4.0
     */
    public function hasVariants(): bool
    {
        return $this->hasVariants;
    }

    /**
     * Set if this item has variants.
     *
     * @param bool $hasVariants
     * @return $this
     * @since 1.4.0
     */
    public function setHasVariants(bool $hasVariants): Item
    {
        $this->hasVariants = $hasVariants;
        return $this;
    }
}
