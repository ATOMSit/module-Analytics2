<?php

namespace Modules\Analytics\Widgets;

use App\Helpers\AnalyticsHelper;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Analytics\Period;
use Analytics;

class TopPagesWidget extends AbstractWidget
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
     * Returns the most viewed pages with important information.
     *
     * @param Period $period
     * @param int $maxResults
     * @return Collection
     */
    protected function topPagesWithInformations(Period $period, int $maxResults = 10): Collection
    {
        $response = $this->helper->getView($this->config['view_id'])::performQuery(
            $period,
            'ga:users,ga:bounceRate,ga:avgTimeOnPage,ga:newUsers',
            [
                'dimensions' => 'ga:pageTitle,ga:pagePath',
                'sort' => '-ga:users',
                'max-results' => $maxResults
            ]
        );
        return Collection::make($response['rows']);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $analytics = $this->helper->getView($this->config['view_id']);

        $period = Period::create(Carbon::today()->subWeek(), Carbon::today());
        $result = $this->topPagesWithInformations($period, 10);
        return view('analytics::application.google.widgets.top_pages_widget', [
            'top_pages' => $result,
            'config' => $this->config,
        ]);
    }
}
