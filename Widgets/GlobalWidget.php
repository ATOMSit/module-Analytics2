<?php

namespace Modules\Analytics\Widgets;

use App\Helpers\AnalyticsHelper;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Analytics\Period;
use Analytics;

class GlobalWidget extends AbstractWidget
{
    private $helper;

    public function __construct(AnalyticsHelper $helper, array $config = [])
    {
        $this->addConfigDefaults([
            'view_id' => '1'
        ]);
        $this->helper = $helper;
        parent::__construct($config);
    }

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'view_id' => '1'
    ];

    /**
     * Returns the list of visitors and the number of page views.
     *
     * @param Period $period
     * @return Collection
     */
    protected function visitorsAndPageViews(Period $period): Collection
    {
        $response = $this->helper->getView($this->config['view_id'])::performQuery(
            $period,
            'ga:users,ga:pageviews',
            ['dimensions' => 'ga:date']
        );
        return collect($response['rows'] ?? [])->map(function (array $dateRow) {
            return [
                'date' => Carbon::createFromFormat('Ymd', $dateRow[0])->format('d/m/Y'),
                'visitors' => (int)$dateRow[1],
                'pageViews' => (int)$dateRow[2],
            ];
        });
    }

    /**
     * Returns the average time spend on the website for a visitor.
     *
     * @param Period $period
     * @return float
     */
    protected function avgSessionDuration(Period $period): float
    {
        $response = $this->helper->getView($this->config['view_id'])::performQuery(
            $period,
            'ga:avgSessionDuration'
        );
        $time = collect($response['rows'] ?? [])->map(function (array $dateRow) {
            return $dateRow[0] / 60;
        });
        return number_format((float)$time[0], 2, '.', '');
    }

    /**
     * Returns the rebound rate of the website.
     *
     * @param Period $period
     * @return float
     */
    protected function bounceRate(Period $period): float
    {
        $response = $this->helper->getView($this->config['view_id'])::performQuery(
            $period,
            'ga:sessions',
            [
                'metrics' => 'ga:bounceRate',
            ]
        );
        $rate = collect($response['rows'] ?? [])->map(function (array $dateRow) {
            return $dateRow[0];
        });
        return number_format((float)$rate[0], 2, '.', '');
    }

    /**
     * Returns the percentage of direct visitor to the site.
     *
     * @param Period $period
     * @return float
     */
    protected function directRate(Period $period): float
    {
        $response = $this->helper->getView($this->config['view_id'])::performQuery(
            $period,
            'ga:sessions',
            [
                'dimensions' => 'ga:medium'
            ]
        );
        $total = (int)0;
        $direct = (int)0;

        foreach ($response['rows'] as $row) {
            if ($row[0] === '(none)') {
                $direct = (int)$row[1];
                $total = $total + (int)$row[1];
            } elseif ($row[0] !== '(none)') {
                $total = $total + (int)$row[1];

            }
        }
        return number_format((float)($direct / $total) * 100, 2, '.', '');
    }

    /**
     * Returns the number of impressions for the website.
     *
     * @param Period $period
     * @return int
     */
    protected function pageViews(Period $period): int
    {
        $response = $this->helper->getView($this->config['view_id'])::performQuery(
            $period,
            'ga:pageviews'
        );
        return (int)$response['rows'][0][0];
    }


    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $analytics = $this->helper->getView($this->config['view_id']);
        $period = Period::create(Carbon::today()->subWeek(), Carbon::today());
        $direct_rate = $this->directRate($period);
        $pageviews = $this->pageViews($period);
        $bounce_rate = $this->bounceRate($period);
        $stats = $this->visitorsAndPageViews($period);
        $avgSessionDuration = $this->avgSessionDuration($period);

        return view('analytics::application.google.widgets.global_widget', [
            'bounce_rate' => $bounce_rate,
            'direct_rate' => $direct_rate,
            'page_views' => $pageviews,
            'stats' => $stats,
            'avgSessionDuration' => $avgSessionDuration,
            'config' => $this->config,
        ]);
    }
}