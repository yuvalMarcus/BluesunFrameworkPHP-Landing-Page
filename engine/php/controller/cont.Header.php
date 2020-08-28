<?php

class Header {

    public static function Location(string $location) {

        header("Location: $location");
    }

}
