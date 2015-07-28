<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RG\Event;

/**
 * Base report events class.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
final class ReportEvents
{
    /**
     * report.install event is thrown before a report is installed
     * in the report service.
     *
     * The event listener receives an instance of
     * RG\ReportsBundle\Event\FilterReportEvent.
     *
     * @vat string
     */
    const REPORT_PRE_INSTALL = 'report.pre_install';

    /**
     * report.install event is thrown after a report is installed
     * in the report service.
     *
     * The event listener receives an instance of
     * RG\ReportsBundle\Event\FilterReportEvent.
     *
     * @vat string
     */
    const REPORT_POST_INSTALL = 'report.post_install';

    /**
     * report.install event is thrown before a report is uninstalled
     * from the report service.
     *
     * The event listener receives an instance of
     * RG\ReportsBundle\Event\FilterReportEvent.
     *
     * @vat string
     */
    const REPORT_PRE_UNINSTALL = 'report.pre_uninstall';

    /**
     * report.install event is thrown after a report is uninstalled
     * from the report service.
     *
     * The event listener receives an instance of
     * RG\ReportsBundle\Event\FilterReportEvent.
     *
     * @vat string
     */
    const REPORT_POST_UNINSTALL = 'report.post_uninstall';

    /**
     * report.install event is thrown before a report is rendered.
     *
     * The event listener receives an instance of
     * RG\ReportsBundle\Event\FilterReportEvent.
     *
     * @vat string
     */
    const REPORT_PRE_RENDER = 'report.pre_render';

    /**
     * report.install event is thrown after a report is rendered.
     *
     * The event listener receives an instance of
     * RG\ReportsBundle\Event\FilterReportEvent.
     *
     * @vat string
     */
    const REPORT_POST_RENDER = 'report.post_render';
}
