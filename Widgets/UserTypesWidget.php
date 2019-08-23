<?php

namespace Modules\Analytics\Widgets;

use App\Helpers\AnalyticsHelper;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;
use Spatie\Analytics\Period;
use Analytics;
use Illuminate\Support\Collection;

class UserTypesWidget extends AbstractWidget
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
     * Returns the type of users visiting the website.
     *
     * @param Period $period
     * @return Collection
     */
    protected function userTypes(Period $period): Collection
    {
        $responses = Analytics::performQuery(
            $period,
            'ga:sessions',
            [
                'dimensions' => 'ga:userType',
            ]
        );
        $results = array('new_visitor' => 0, 'returning_visitor' => 0);
        foreach ($responses as $respons) {
            if ($respons[0] === 'New Visitor') {
                $results['new_visitor'] = $respons[1];
            } elseif ($respons[0] === 'Returning Visitor') {
                $results['returning_visitor'] = $respons[1];
            }
        }
        $final = array($results['new_visitor'], $results['returning_visitor']);
        return Collection::make($final);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $analytics =
        $period = Period::create(Carbon::today()->subWeek(), Carbon::today());
        $results = $this->userTypes($period);
        $labels = [trans('analytics::google_translation.widgets.user_types.views.new_visitor'), trans('analytics::google_translation.widgets.user_types.views.returning_visitor')];
        $colors = ['#4286f4', '#59f442'];
        return view('analytics::application.google.widgets.chart_pie', [
            'widget' => (string)'user_types',
            'id' => (int)random_int(100, 999),
            'datas' => $results,
            'labels' => $labels,
            'colors' => $colors,
            'config' => $this->config,
        ]);
    }
}
