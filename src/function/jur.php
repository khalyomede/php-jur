<?php
    use Khalyomede\Jur;

    if( function_exists('jur') === false ) {
        function jur() {
            return new Jur;
        }
    }
?>