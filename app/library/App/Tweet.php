<?php

class Tweet {
    public function __get($name) {
        if (isset($this->{$name}) === false) {
            $this->{$name} = null;
        }
        
        return $this->{$name};
    }
}