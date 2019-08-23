<?php

namespace Modules\Analytics\Widgets;

use App\Helpers\AnalyticsHelper;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Analytics\Period;
use Analytics;

class TopSocialsMediasWidget extends AbstractWidget
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
     * Returns the list of social networks bringing visitors.
     *
     * @param Period $period
     * @param int $maxResults
     * @return Collection
     */
    protected function fetchTopSocialsMedias(Period $period, int $maxResults = 10): Collection
    {
        $response = $this->helper->getView($this->config['view_id'])::performQuery(
            $period,
            'ga:sessions',
            [
                'dimensions' => 'ga:socialNetwork',
                'sort' => '-ga:sessions',
            ]
        );
        $topSocialsMedias = collect($response['rows'] ?? [])->map(function (array $browserRow) {
            return [
                'name' => $browserRow[0],
                'sessions' => (int)$browserRow[1],
            ];
        });
        $i = 0;
        foreach ($topSocialsMedias as $socialsMedia) {
            if ($socialsMedia['name'] === '(not set)') {
                unset($topSocialsMedias[$i]);
                break;
            }
            $i++;
        }
        if ($topSocialsMedias->count() <= $maxResults) {
            return $topSocialsMedias;
        }
        return $this->summarizeTopSocialsMedias($topSocialsMedias, $maxResults);
    }

    /**
     * Add "Other" to the list if too much result.
     *
     * @param Collection $topSocialsMedias
     * @param int $maxResults
     * @return Collection
     */
    protected function summarizeTopSocialsMedias(Collection $topSocialsMedias, int $maxResults): Collection
    {
        return $topSocialsMedias
            ->take($maxResults - 1)
            ->push([
                'name' => 'Others',
                'sessions' => $topSocialsMedias->splice($maxResults - 1)->sum('sessions'),
            ]);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $analytics = $this->helper->getView($this->config['view_id']);

        $period = Period::create(Carbon::today()->subWeek(), Carbon::today());
        $results = $this->fetchTopSocialsMedias($period, 4);
        return view('analytics::application.google.widgets.table_widget', [
            'widget' => (string)'socials_medias',
            'datas' => $results,
            'config' => $this->config,
        ]);
    }
}
