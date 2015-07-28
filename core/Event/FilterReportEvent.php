<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RG\Event;

use Symfony\Component\EventDispatcher\Event;
use RAM\BaseReport;

/**
 * ReportRenderEvent describes the events of a report.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class FilterReportEvent extends Event
{
    protected $report;

    public function __construct(BaseReport $report)
    {
        $this->report = $report;
    }

    /**
     * @return BaseReport
     */
    public function getReport()
    {
        return $this->report;
    }
}
