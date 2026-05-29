<?php
class Utils {

  public function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = $this->utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        // Fix invalid UTF-8 by re-encoding
        $mixed = mb_convert_encoding($mixed, 'UTF-8', 'UTF-8');
    }
    return $mixed;
  }
}
?> 