<?php

class AsyncOperation extends Thread {

    public function __construct(array &$data) {

    }

    public function run(array &$data, $callback,int $sleep) {

        $callback();

        if($sleep) {

            sleep(500);
        }
    }
}