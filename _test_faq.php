<?php

    function get_hundred_values() {
    for ($i = 1; $i <= 100; $i++) {
        yield $i;
    }
}
foreach (get_hundred_values() as $value) {
    print($value);
}

echo $this->filename;
$this->fullFileName1();
echo '<br>';
echo $this->filename;

echo $Loader->fullFileName1();