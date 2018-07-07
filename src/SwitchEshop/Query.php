<?php

namespace SwitchEshop;

use SwitchEshop\GameUS;
use SwitchEshop\ResultSet;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

/**
 * Class Query
 * @package SwitchEshop
 */
class Query
{

    /** @var string */
    const GAMES_US_URL = 'http://www.nintendo.com/json/content/get/filter/game?system=switch&sort=title&direction=asc&shop=ncom';

    /** @var string */
    const PRICE_URL = 'https://api.ec.nintendo.com/v1/price?lang=en';

    /** @var Client */
    protected $client;

    /**
     * Query constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Call the eShop.
     *
     * @param array $request
     * @param string $country
     * @return bool|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function callShop(array $request = [], $country = 'US')
    {
        $method = 'GET';
        $request['offset'] = isset($request['offset']) ? $request['offset'] : 0;
        $request['limit'] = isset($request['limit']) ? $request['limit'] : 200;
        switch ($country) {
            case 'US':
            default:
                $fetchUrl = self::GAMES_US_URL;
                break;
        }
        $url = $fetchUrl . '&' . http_build_query($request);
        try {
            $response = $this->client->request($method, $url);
        } catch (RequestException $e) {
            return false;
        }
        return json_decode($response->getBody()->getContents());
    }

    /**
     * Compiles games information.
     *
     * @param array $request
     * @param string $country
     * @return bool|\SwitchEshop\ResultSet
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getGames(array $request = [], $country = 'US')
    {
        switch ($country) {
            case 'US':
            default:
                $gameClass = GameUS::class;
                break;
        }
        $resp = $this->callShop($request);
        if (!$resp instanceof \stdClass) {
            return false;
        }

        $rs = new ResultSet();
        $rs->total = $resp->filter->total;
        $rs->offset = $resp->games->offset;
        $rs->limit = $resp->games->limit;

        foreach ($resp->games->game as $game) {
            $rs->games[] = new $gameClass($game);
        }

        return $rs;
    }
}