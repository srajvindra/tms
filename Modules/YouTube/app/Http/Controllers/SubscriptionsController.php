<?php

namespace Modules\YouTube\Http\Controllers;

use App\Http\Controllers\Controller;
use Google\Client;
use Google\Service\YouTube;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\YouTube\Models\YoutubeSubscription;
use Modules\YouTube\Models\YoutubeChannelVideo;

class SubscriptionsController extends Controller
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('youtube.client_id'));
        $this->client->setClientSecret(config('youtube.client_secret'));
        $this->client->setRedirectUri(config('youtube.redirect_uri'));
        $this->client->addScope(YouTube::YOUTUBE_READONLY);
        $this->client->refreshToken(config('youtube.refresh_token'));
    }

    /**
     * Fetch all subscribed YouTube channels and save to database.
     */
    public function index(): JsonResponse
    {
        $youtube = new YouTube($this->client);

        $saved = 0;
        $pageToken = null;

        do {
            $params = [
                'mine'       => true,
                'maxResults' => 50,
                'order'      => 'alphabetical',
            ];

            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }

            $response = $youtube->subscriptions->listSubscriptions('snippet', $params);

            foreach ($response->getItems() as $item) {
                $snippet = $item->getSnippet();

                YoutubeSubscription::updateOrCreate(
                    ['subscription_id' => $item->getId()],
                    [
                        'account_id'   => 1,
                        'channel_id'   => $snippet->getResourceId()->getChannelId(),
                        'title'        => $snippet->getTitle(),
                        'description'  => $snippet->getDescription(),
                        'thumbnail'    => $snippet->getThumbnails()->getDefault()->getUrl(),
                        'subscribed_at' => $snippet->getPublishedAt(),
                    ]
                );

                $saved++;
            }

            $pageToken = $response->getNextPageToken();
        } while ($pageToken);

        return response()->json([
            'message' => 'Subscriptions synced successfully.',
            'total'   => $saved,
        ]);
    }

    /**
     * Get videos for a specified YouTube channel.
     */
    public function channelVideos(Request $request, string $channelId): JsonResponse
    {
        $youtube = new YouTube($this->client);

        $videos = [];
        $pageToken = null;
        $maxResults = (int) $request->get('max_results', 50);
        sleep(2);

        do {
            $params = [
                'channelId'  => $channelId,
                'maxResults' => min($maxResults, 50),
                'order'      => $request->get('order', 'date'),
                'type'       => 'video',
            ];

            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }

            $response = $youtube->search->listSearch('snippet', $params);

            foreach ($response->getItems() as $item) {
                $snippet = $item->getSnippet();
                $videos[] = [
                    'video_id'     => $item->getId()->getVideoId(),
                    'title'        => $snippet->getTitle(),
                    'description'  => $snippet->getDescription(),
                    'thumbnail'    => $snippet->getThumbnails()->getDefault()->getUrl(),
                    'published_at' => $snippet->getPublishedAt(),
                ];
            }

            $pageToken = $response->getNextPageToken();
        } while ($pageToken && count($videos) < $maxResults);

        foreach ($videos as $video) {
            YoutubeChannelVideo::updateOrCreate(
                ['video_id' => $video['video_id']],
                [
                    'channel_id'   => $channelId,
                    'title'        => $video['title'],
                    'description'  => $video['description'],
                    'thumbnail'    => $video['thumbnail'],
                    'published_at' => $video['published_at'],
                ]
            );
        }

        return response()->json([
            'channel_id' => $channelId,
            'total'      => count($videos),
            'videos'     => $videos,
        ]);
    }
}
