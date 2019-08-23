<?php

namespace Modules\Analytics\Http\Controllers;

use App\Helpers\AnalyticsHelper;
use App\Website;
use Carbon\Carbon;
use Exception;
use Google_Client;
use Google_Service_Analytics;
use Google_Service_Analytics_Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Modules\Analytics\Entities\Google;
use Spatie\Analytics\Period;
use Analytics;

class AnalyticsController extends Controller
{
    private $view_id;

    private $helper;
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(AnalyticsHelper $helper)
    {
        $this->helper = $helper;
        if (\Illuminate\Support\Facades\Request::getHttpHost() === 'admin.atomsit.com') {
            $website = Website::query()
                ->where('uuid', \Illuminate\Support\Facades\Request::segment(1))
                ->firstOrFail();
            if ($website->analytics_google === null) {
                $client = new Google_Client();
                $client->setAuthConfig(Config::get('analytics.service_account_credentials_json'));
                $client->setScopes(
                    [
                        'https://www.googleapis.com/auth/analytics.edit',
                    ]
                );
                $analytics = new Google_Service_Analytics($client);
                $profile = new Google_Service_Analytics_Profile();
                $profile->setName($website->uuid);
                $profile->setECommerceTracking(false);
                try {
                    $final = $analytics->management_profiles->insert('146244802', 'UA-146244802-1', $profile);

                    $google = new Google([
                        'view_id' => $final['id']
                    ]);
                    $website->analytics_google()->save($google);
                    $this->view_id = $website->analytics_google->view_id;
                } catch (Exception $e) {
                    print 'There was an Analytics API service error '
                        . $e->getCode() . ':' . $e->getMessage();

                }
            } else {
                $this->view_id = $website->analytics_google->view_id;
            }
        }
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('analytics::application.google')
            ->with('view_id', $this->view_id);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('analytics::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('analytics::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('analytics::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
