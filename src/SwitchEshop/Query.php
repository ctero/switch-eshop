<?php

namespace SwitchEshop;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Query
 * @package SwitchEshop
 */
class Query
{

    /** @var Client Guzzle Client */
    private $client;

    /** @var array API URLs for various eShops. */
    private $games_url = [
        'americas' => 'http://www.nintendo.com/json/content/get/filter/game'
    ];

    /** @var array Default filter options for eShops. */
    private $options = [
        'americas' => [
            'shop' => 'ncom',
            'system' => 'switch',
            'sort' => 'title',
            'direction' => 'asc',
            'offset' => 0,
            'limit' => '50'
        ]
    ];

    /**
     * Query constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Query the eShop for game information.
     *
     * @param array $request
     * @return bool|ResultSet
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getGames(array $request = [])
    {
        $method = 'GET';
        $request = array_merge($this->options['americas'], $request);

        try {
            $resp = $this->client->request($method, $this->games_url['americas'], ['query' => $request]);
        } catch (RequestException $e) {
            return false;
        }

        if (!$resp) {
            return false;
        }
        $resp = json_decode($resp->getBody()->getContents());

        $rs = new ResultSet();
        $rs->total = $resp->filter->total;
        $rs->offset = $resp->games->offset;
        $rs->limit = $resp->games->limit;

        foreach ($resp->games->game as $game) {
            $rs->games[] = new GameUS($game);
        }

        return $rs;
    }
}