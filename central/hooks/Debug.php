<?php

class Debug {
    function profiler() {
        if($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
            $this->ci =& get_instance();
            $this->ci->output->enable_profiler();
        }
    }
}
