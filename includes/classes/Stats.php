<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Stats {

    private static $stats = ['downloads' => 0, 'last_updated' => ''];
    private static $statsPath = __DIR__ . '/../data/stats.json';

    public static function load() {
        if (file_exists(Stats::$statsPath)) {
            $contents = file_get_contents(Stats::$statsPath);
            if ($contents !== false) {
                $parsed = json_decode($contents, true);
                if ($parsed !== null) {
                    Stats::$stats = $parsed;
                }
            }
        }
    }

    public static function set($name, $value) {
        Stats::$stats[$name] = $value;
        self::save();
    }

    public static function downloads() {
        return Stats::$stats['downloads'];
    }

    public static function updateTime() {
        self::load();
        Stats::$stats['last_updated'] = time();
        self::save();
    }

    public static function incDownloads() {
        self::load();
        if (isset(Stats::$stats) && Stats::$stats['downloads'] > 0) {
            Stats::$stats['downloads'] += 1;
            self::save();
        }
    }

    public static function updated() {
        $timestamp = time();
        $lastUpdated = (int)(Stats::$stats['last_updated']);
        $difference = $timestamp - $lastUpdated;
        $day = 60 * 60 * 24;
        if ($difference < $day) {
            return 'Today at ' . date('g:ia', $lastUpdated);
        } else {
            return date('\O\n D \a\t g:ia', $lastUpdated);
        }
    }

    public static function save() {
        if (file_exists(Stats::$statsPath)) {
            if(self::downloads() != 0) {
                file_put_contents(Stats::$statsPath, json_encode(Stats::$stats));
            }
        }
    }

}