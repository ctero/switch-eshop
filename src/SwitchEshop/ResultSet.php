<?php

namespace SwitchEshop;

/**
 * Class ResultSet
 * @package SwitchEshop
 */
class ResultSet
{

    /** @var int */
    public $offset;

    /** @var int */
    public $limit;

    /** @var int */
    public $total;

    /** @var array */
    public $games = [];
}