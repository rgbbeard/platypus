<?php
function replace($chars, $target) {
    foreach($chars as $char => $replacement) {
        $target = str_replace($char, $replacement, $target);
    }
    
    return $target;
}

function dump(...$items) {
    $debug = "<pre style='background-color:#f003;padding:10px;'>";

    foreach($items as $item) {
        $value = print_r($item, true);

        if(is_bool($item)) {
            $debug .= "bool(" . (intval($value) ? "true" : "false") . ")";
        } elseif(is_numeric($item)) {
            $debug .= "number(" . $value . ")";
        } elseif(is_array($item)) {
            $debug .=  "array(" . replace(
                [
                    "{" => "{\n",
                    "}" => "\n}",
                    ",\"" => ",\n\""
                ],
                json_encode($item)
            ) . ")";
        } elseif(is_null($item)) {
            $debug .= "null\n";
        } elseif(is_string($item)) {
            $debug .= "string(\"" . $value . "\")\n";
        } else {
            $debug .= $value . "\n";
        }
    }

    $debug .= "</pre>";

    echo $debug;

    $backtrace_array = array_reverse(debug_backtrace());
    $backtrace = "<pre style='background-color:#f0f3;padding:10px;'>";
    foreach($backtrace_array as $stack => $trace) {
        $file = @$trace["file"];
        $function = @$trace["function"];
        $line = @$trace["line"];
        $class = @$trace["class"];

        $tmp = "";

        if($file || $class) {
            $tmp .= "<i>";

            if($file) {
                $tmp .= "$file";
            }

            if($file && $class) {
                $tmp .= "/$class";
            } elseif($class) {
                $tmp .= "$class";
            }

            $tmp .= "</i>->";
        }

        if($function) {
            $tmp .= "<b>$function</b>";
        }

        if($line) {
            $tmp .= " at line $line";
        }

        $backtrace .= "<p style='margin:0;padding:0;font-size:13px;'>[$stack] $tmp</p>\n";
    }
    $backtrace .= "<pre>";
    echo $backtrace;
}

function dd(...$items) {
    dump(...$items);
    die();
}

function array_clear(array $target): array {
    $temp = [];
    foreach($target as $item) {
        if(!empty($item)) {
            $temp[] = $item;
        }
    }
    return $temp;
}

function array_exclude(array $target, $element): array {
    $temp = [];
    for ($x = 0; $x < count($target); $x++) {
        if($x == $element) {
            continue;
        }
        $temp[] = $target[$x];
    }
    return $temp;
}