<?php

namespace SwitchEshop;

use DateTime;

/**
 * Class GameUS
 * @package SwitchEshop
 */
class GameUS
{

    /** @var string */
    public $game_code;

    /** @var bool */
    public $buyonline;

    /** @var string */
    public $front_box_art;

    /** @var float */
    public $eshop_price;

    /** @var string */
    public $nsuid;

    /** @var string */
    public $video_link;

    /** @var string */
    public $number_of_players;

    /** @var float */
    public $ca_price;

    /** @var string */
    public $id;

    /** @var string */
    public $title;

    /** @var bool */
    public $free_to_start;

    /** @var bool */
    public $digitaldownload;

    /** @var DateTime */
    public $release_date;

    /** @var array */
    public $categories;

    /** @var string */
    public $slug;

    /** @var bool */
    public $buyitnow;

    /**
     * GameUS constructor.
     * @param \stdClass $obj
     */
    public function __construct(\stdClass $obj)
    {
        foreach ($obj as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }
            switch ($key) {
                case 'buyonline':
                case 'free_to_start':
                case 'digitaldownload':
                case 'buyitnow':
                    $this->$key = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'eshop_price':
                case 'ca_price':
                    $this->$key = filter_var($value, FILTER_VALIDATE_FLOAT);
                    break;
                case 'categories':
                    if (isset($value->category)) {
                        foreach ($value->category as $category) {
                            $this->$key[] = $category;
                        }
                    }
                    break;
                case 'release_date':
                    $this->$key = new DateTime($value);
                    break;
                default:
                    $this->$key = $value;
                    break;
            }
        }
    }
}
