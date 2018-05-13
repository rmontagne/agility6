<?php

class Template
{

    public static function render($tplName, $params)
    {
        if (! file_exists(dirname(__FILE__) . '/../templates/' . $tplName . '.isml')) {
            return 'Template ' . $tplName . ' not found !';
        }
        
        $content = file_get_contents(dirname(__FILE__) . '/../templates/' . $tplName . '.isml');
        
        return self::parseContent($content, $params);
    }

    static private function parseContent($content, $params)
    {
        
        // COMMENTAIRE
        $content = self::comment($content);
        
        // ISINCLUDE
        $content = self::includeTpl($content, $params);
        
        // LOOP
        $content = self::loop($content, $params);
        
        // VARIABLE SIMPLE
        $content = self::parseSimpleVar($content, $params);
        
        // VARIABLE OBJECT
        $content = self::parseObjectVar($content, $params);
        
        // CONDITION
        $content = self::condition($content, $params);
        
        // DECORATE
        $content = self::decorate($content, $params);
        
        return $content;
    }

    static private function comment($content)
    {
        // Allow only one decorator
        if (preg_match_all('#(?s)<iscomment(.(?:(?!<iscomment).)*?)</iscomment>#', $content, $matches)) {
            $content = str_replace($matches[0], '', $content);
        }
        return $content;
    }

    static private function decorate($content, $params)
    {
        // Allow only one decorator
        if (preg_match('#<isdecorate template=\'([a-z_]+)\'>(.*)?</isdecorate>#s', $content, $matches)) {
            
            $decorator = self::render($matches[1], $params);
            $isreplace = self::parseContent($matches[2], $params);
            
            return str_replace('<isreplace />', $isreplace, $decorator);
        }
        
        return $content;
    }

    static private function loop($content, $params)
    {
        if (preg_match_all('#<isloop\s+items\s*=\s*"\s*\$\{\s*(\w+)\s*\}\s*"\s+alias\s*=\s*"(\w+)".*>(.*)<\/isloop>#sU', $content, $matches, PREG_SET_ORDER)) {
            
            foreach ($matches as $match) {
                if (isset($params[$match[1]]) && is_array($params[$match[1]])) {
                    $inFor = '';
                    foreach ($params[$match[1]] as $item) {
                        $paramsLoop = array(
                            $match[2] => $item
                        );
                        $params2 = array_merge($params, $paramsLoop);
                        
                        $out = self::condition($match[3], $params2);
                        if (is_object($item)) {
                            $inFor .= self::parseObjectVar($out, $params2);
                        } else {
                            $inFor .= self::parseSimpleVar($out, $params2);
                        }
                    }
                    
                    $outFor = self::parseContent($inFor, $params);
                    $content = str_replace($match[0], $outFor, $content);
                } else {
                    die('ERREUR DE PARAMETRE, TABLEAU D OBJET ATTENDU');
                }
            }
        }
        return $content;
    }

    static private function condition($content, $params)
    {
        $recursivity = 'off';
        if (preg_match('#(?s)<isif(.(?:(?!<isif).)*?)</isif>#', $content, $matches)) {
            $total = $matches[0];
            $recursivity = 'on';
            preg_match('#(?s).*condition="\${(.*?)}".*?>(.*)#', $matches[1], $matches);
            $condition = $matches[1];
            foreach ($params as $key => $param) {
                $condition = str_replace($key, '$' . $key, $condition);
            }
            $variable = '';
            foreach ($params as $key => $value) {
                if (is_object($value) || is_array($value)) {
                    $variable .= '$' . $key . ' = unserialize(\'' . serialize($value) . '\');';
                } else {
                    $variable .= '$' . $key . ' = "' . $value . '";';
                }
            }
            $condition = 'return (' . $condition . ');';
            $return = eval($variable . $condition);
            if (! $return) {
                $content = str_replace($total, '', $content);
            }
            if ($return) {
                $content = str_replace($total, $matches[2], $content);
            }
        }
        if ($recursivity == 'on') {
            return self::condition($content, $params);
        }
        return $content;
    }

    static private function includeTpl($content, $params)
    {
        if (preg_match_all('#<isinclude\s+template\s*=\s*"(.+)"\s*/>#', $content, $matches)) {
            foreach ($matches[1] as $match) {
                $tplRequire = $match;
                $requireContent = self::render($tplRequire, $params);
                $content = preg_replace('#<isinclude.*template.*="' . $tplRequire . '".*/>#', $requireContent, $content);
            }
        }
        
        if (preg_match_all('#<isinclude.*remote.*="(.+)".*/>#', $content, $matches)) {
            foreach ($matches[1] as $match) {
                
                $tmp = explode('-', $match);
                $controller = $tmp[0];
                $action = $tmp[1];
                
                $requireContent = Router::callController($controller, $action);
                $content = preg_replace('#<isinclude.*remote.*="' . $match . '".*/>#', $requireContent, $content);
            }
        }
        
        if (preg_match_all('#<isinclude\s+templateSpec\s*=\s*"(.+)"\s*/>#', $content, $matches,PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $tplRequire = $match[1];
                if (! file_exists(dirname(__FILE__) . '/../templates/' . $tplRequire . '.isml')) {
                    die( 'Template ' . $tplRequire . ' not found !');
                }                
                $requireContent = file_get_contents(dirname(__FILE__) . '/../templates/' . $tplRequire . '.isml');
                $content = str_replace($match[0], $requireContent, $content);
            }
        }
        
        return $content;
    }

    static private function parseSimpleVar($content, $params)
    {
        if (preg_match_all('#\${(\w+)}#', $content, $matches)) {
            foreach ($matches[1] as $match) {
                if (isset($params[$match])) {
                    $content = str_replace('${' . $match . '}', $params[$match], $content);
                }
            }
        }
        
        return $content;
    }

    static private function parseObjectVar($content, $params)
    {
        if (preg_match_all('#\${([a-zA-Z0-9.]+)}#', $content, $matches)) {
            
            foreach ($matches[1] as $match) {
                
                $attributes = explode('.', $match);
                $mainObject = $params[$attributes[0]];
                
                for ($i = 1; $i < count($attributes); $i ++) {
                    
                    $getter = 'get' . ucfirst($attributes[$i]);
                    if (method_exists($mainObject, $getter)) {
                        $mainObject = $mainObject->{$getter}();
                    } else if (property_exists($mainObject, $attributes[$i])) {
                        $mainObject = $mainObject->{$attributes[$i]};
                    } else {
                        $mainObject = '';
                        break;
                    }
                }
                if (! is_object($mainObject)) {
                    $content = str_replace('${' . $match . '}', $mainObject, $content);
                } else {
                    $content = str_replace('${' . $match . '}', '[Object]', $content);
                }
            }
        }
        return $content;
    }
}
