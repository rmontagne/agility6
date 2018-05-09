<?php

class Parser {
    public function TagParser($html) {
        $pattern = '#<([\w]+)([^>]*?)(([\s]*\/>)|(>((([^<]*?|<\!\-\-.*?\-\->)|(?R))*)<\/\\1[\s]*>))#sm';
        preg_match_all($pattern, $html, $matches, PREG_OFFSET_CAPTURE);
        $elements = array();
        foreach ($matches[0] as $key => $match) {
            $elements[] = array(
                'node' => $match[0],
                'tagname' => $matches[1][$key][0],
                'inner_html' => isset($matches[6][$key][0]) ? $matches[6][$key][0] : ''
            );
        }
        return $elements;
    }
}