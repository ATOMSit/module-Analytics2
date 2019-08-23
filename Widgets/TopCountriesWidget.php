<?php


namespace Modules\Analytics\Widgets;


use App\Helpers\AnalyticsHelper;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Collection;
use Spatie\Analytics\Period;
use Analytics;

class TopCountriesWidget extends AbstractWidget
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
     * Returns the list of countries of origin of visitors
     *
     * @param Period $period
     * @param int $maxResults
     * @return Collection
     */
    protected function fetchTopCountries(Period $period, int $maxResults = 10): Collection
    {
        $response = $this->helper->getView($this->config['view_id'])::performQuery(
            $period,
            'ga:sessions',
            [
                'dimensions' => 'ga:country,ga:countryIsoCode',
                'sort' => '-ga:sessions',
            ]
        );
        $topCountries = collect($response['rows'] ?? [])->map(function (array $browserRow) {
            return [
                'name' => $browserRow[0],
                'type' => 'country',
                'iso' => (string)$browserRow[1],
                'sessions' => (int)$browserRow[2],
            ];
        });
        if ($topCountries->count() <= $maxResults) {
            return $topCountries;
        }
        return $this->summarizeTopCountries($topCountries, $maxResults);
    }

    /**
     * Add "Other" to the list if too much result.
     *
     * @param Collection $topCountries
     * @param int $maxResults
     * @return Collection
     */
    protected function summarizeTopCountries(Collection $topCountries, int $maxResults): Collection
    {
        return $topCountries
            ->take($maxResults - 1)
            ->push([
                'name' => 'Others',
                'type' => 'country',
                'iso' => null,
                'sessions' => $topCountries->splice($maxResults - 1)->sum('sessions')
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
        $results = $this->fetchTopCountries($period, 5);
        return view('analytics::application.google.widgets.table_widget', [
            'widget' => (string)'country',
            'datas' => $results,
            'config' => $this->config,
        ]);
    }
}
