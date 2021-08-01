<?php


class Utils {

    public static function capitalizeFirst($text) {
        return strtoupper(substr($text, 0, 1)) . strtolower(substr($text, 1));
    }


}