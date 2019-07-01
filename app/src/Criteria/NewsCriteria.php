<?php

namespace App\Criteria;

/**
 * Class NewsCriteria
 * @package App\Criteria
 */
class NewsCriteria
{
    /**
     * @var int|null
     */
    private $count;

    /**
     * @var int|null
     */
    private $offset;

    /**
     * @var int|null
     */
    private $category;

    /**
     * @var \DateTime|null
     */
    private $dateTo;

    /**
     * @var \DateTime|null
     */
    private $dateFrom;

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @param int|null $count
     * @return NewsCriteria
     */
    public function setCount(?int $count): self
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     * @return NewsCriteria
     */
    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCategory(): ?int
    {
        return $this->category;
    }

    /**
     * @param int|null $category
     * @return NewsCriteria
     */
    public function setCategory(?int $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateTo(): ?\DateTime
    {
        return $this->dateTo;
    }

    /**
     * @param \DateTime|null $dateTo
     * @return NewsCriteria
     */
    public function setDateTo(?\DateTime $dateTo): self
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateFrom(): ?\DateTime
    {
        return $this->dateFrom;
    }

    /**
     * @param \DateTime|null $dateFrom
     * @return NewsCriteria
     */
    public function setDateFrom(?\DateTime $dateFrom): self
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }
}