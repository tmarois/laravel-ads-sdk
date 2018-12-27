<?php namespace LaravelAds\GoogleAds;


class Reports
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * $dateFrom
     *
     */
    protected $dateRange = [];

    /**
     * __construct()
     *
     *
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * setDateRange()
     *
     *
     * @return self
     */
    public function setDateRange($dateFrom, $dateTo)
    {
        $this->dateRange[] = $dateFrom;
        $this->dateRange[] = $dateTo;

        return $this;
    }

}
