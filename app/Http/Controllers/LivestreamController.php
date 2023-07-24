<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Google\Client as Google_Client;
use Google\Service\YouTube as Google_Service_YouTube;
use Google\Service\YouTube\LiveBroadcast as Google_Service_YouTube_LiveBroadcast;
use Google\Service\YouTube\LiveBroadcastSnippet as Google_Service_YouTube_LiveBroadcastSnippet;
use Google\Service\YouTube\LiveBroadcastStatus as Google_Service_YouTube_LiveBroadcastStatus;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LivestreamController extends Controller
{
    public function authorizeYouTube()
    {
        $client = new Google_Client();
        $client->setDeveloperKey(config('services.youtube.key'));
        $client->setClientId('560651328457-17rjjeg47vhiea91uai10pdqu6oeerv1.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost:8000/livestream/create');
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube',
            'https://www.googleapis.com/auth/youtube.force-ssl',
            'https://www.googleapis.com/auth/youtube.readonly',
            'https://www.googleapis.com/auth/youtube.upload',
            'https://www.googleapis.com/auth/youtubepartner',
            'https://www.googleapis.com/auth/youtubepartner-channel-audit',
        ]);
        return redirect()->away($client->createAuthUrl());
    }

    public function createYouTubeLivestream(Request $request)
    {
        $client = new Google_Client();
        $client->setDeveloperKey(config('services.youtube.key'));
        $client->setClientId('560651328457-17rjjeg47vhiea91uai10pdqu6oeerv1.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost:8000/livestream/create');
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube',
            'https://www.googleapis.com/auth/youtube.force-ssl',
            'https://www.googleapis.com/auth/youtube.readonly',
            'https://www.googleapis.com/auth/youtube.upload',
            'https://www.googleapis.com/auth/youtubepartner',
            'https://www.googleapis.com/auth/youtubepartner-channel-audit',
        ]);

        $certPath = base_path('cacert.pem');

        $httpClient = new Client([
            'verify' => $certPath,
        ]);
        $client->setHttpClient($httpClient);

        $code = $request->input('code');
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);
        $client->setAccessToken($accessToken);

        session()->put('youtube_access_token', $accessToken);

        $liveStreamData = session('liveStreamData', []);

        $youtube = new Google_Service_YouTube($client);

        $broadcast = new Google_Service_YouTube_LiveBroadcast();

        $snippet = new Google_Service_YouTube_LiveBroadcastSnippet();
        $snippet->setTitle($liveStreamData['title']);
        $snippet->setDescription($liveStreamData['description']);
        $snippet->setScheduledStartTime($liveStreamData['start_date_time']);
        $snippet->setScheduledEndTime($liveStreamData['end_date_time']);

        $broadcast->setSnippet($snippet);

        $status = new Google_Service_YouTube_LiveBroadcastStatus();
        $status->setPrivacyStatus('private'); // Set the privacy status as per your requirement

        $broadcast->setStatus($status);

        $response = $youtube->liveBroadcasts->insert('snippet,status', $broadcast);
        $url = 'https://www.youtube.com/watch?v=' . $response->getId();

        $service = Service::find($liveStreamData['service_id']);
        $service->live_stream_url = $url;
        $service->save();

        session()->forget('liveStreamData');

        return redirect()->route('formation')->with('success', 'Service created successfully. Livestream url : ' . $url);

    }
}
