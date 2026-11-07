<?php
function e($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
function excerpt($text,$n=220){$text=trim(strip_tags($text));return mb_strlen($text)>$n?mb_substr($text,0,$n).'…':$text;}
