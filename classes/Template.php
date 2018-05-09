<?php

require_once(dirname(__FILE__).'/Parser.php');

    class Template {

        public static function render($tplName, $params) {

            if (!file_exists(dirname(__FILE__).'/../templates/'.$tplName.'.isml')) {
                return 'Template '.$tplName.' not found !';
            }

            $content = file_get_contents(dirname(__FILE__).'/../templates/'.$tplName.'.isml');


            return self::parseContent($content, $params);
        }


        static private function parseContent($content, $params) {

            //COMMENTAIRE
            $content = self::comment($content);

            //ISINCLUDE
            $content = self::includeTpl($content, $params);

            //LOOP
            $content = self::loop($content, $params);

            //VARIABLE SIMPLE
            $content = self::parseSimpleVar($content, $params);

            //VARIABLE OBJECT
            $content = self::parseObjectVar($content, $params);

            //CONDITION
            $content = self::condition($content, $params);

            //DECORATE
            $content = self::decorate($content, $params);

            return $content;
        }

        static private function comment($content) {
            //Allow only one decorator
            if (preg_match_all('#(?s)<iscomment(.(?:(?!<iscomment).)*?)</iscomment>#', $content, $matches)) {
                $content = str_replace($matches[0],'',$content);
            }
            return $content;
        }

        static private function decorate($content, $params) {
            //Allow only one decorator
            if (preg_match('#<isdecorate template=\'([a-z_]+)\'>(.*)?</isdecorate>#s', $content, $matches)) {

                $decorator = self::render($matches[1], $params);
                $isreplace = self::parseContent($matches[2], $params);

                return str_replace('<isreplace />', $isreplace, $decorator);
            }

            return $content;
        }


        static private function loop($content, $params) {
            if (preg_match('#(?s)<isloop.*</isloop>#', $content)) {
                $tagParser =  new Parser();
                $element = $tagParser->TagParser($content);
                $isloop =[];
                $recurse='off';
                foreach($element as $array){
                    $switch1 ='1';
                    while ($switch1=='1') {
                    if(preg_match('#(?s).*<isloop.*</isloop>#',$array['node'])) {
                    if ($array['tagname'] == 'isloop') {
                        $isloop[] = $array;
                        $switch1 ='0';
                    } else {
                        $elements = $tagParser->Tagparser($array['inner_html']);
                        foreach($elements as $array){
                            if(preg_match('#(?s).*<isloop.*</isloop>#',$array['inner_html'])){
                                $array = $array;
                                
                            }
                        }
                    } 
                    } else {
                        $switch1 ='0';
                    }
                    }
                }
                for($i=0;$i<count($isloop);$i++){
                    preg_match('#(?s)<isloop.*?items="\${(.*?)}".*?alias="(.*?)".*?>(.*)</isloop>#', $isloop[$i]['node'], $matches);
                        if (isset($params[$matches[1]]) && is_array($params[$matches[1]])){
                            $inFor='';
                            $inFor1='';
                            $inFor2='';
                            $inFor3='';
                            foreach($params[$matches[1]] as $item) {
                                $paramsLoop = array($matches[2] => $item);
                                $params2 = array_merge($params, $paramsLoop);
                                if(preg_match('#(?s)(<isloop.*?>)(.(?:(?!<isloop).)*?)(</isloop>.*)#',$isloop[$i]['inner_html'])) {
                                    $elements = $tagParser->Tagparser($isloop[$i]['inner_html']);
                                    foreach($elements as $subarray){
                                        $switch2 ='1';
                                        while ($switch2=='1') {
                                            if(preg_match('#(?s).*<isloop.*</isloop>#',$subarray['node'])) {
                                            if ($subarray['tagname'] == 'isloop') {
                                                $match = $subarray['node'];
                                                $switch2 ='0';
                                            } else {
                                                $elements = $tagParser->Tagparser($subarray['inner_html']);
                                                for($j=0;$j<count($elements);$j++){
                                                    if($elements[$j]['tagname'] == 'isloop'){
                                                        $subarray = $elements[$j];  
                                                    }
                                                }
                                            }
                                            } else {
                                                $switch2 ='0';
                                            }
                                        }
                                    }
                                    $specialCharacter   = array('$','/','.');
                                    $replace  = array('\\$','\\/','\\.');
                                    $match     = str_replace($specialCharacter, $replace, $match);
                                    $recurse ='on';
                                    preg_match('#(?s)(.*)('.$match.')(.*)#',$isloop[$i]['inner_html'],$matchLoop);
                                        $out1 = self::condition($matchLoop[1], $params2);
                                        $out2 = self::condition($matchLoop[2], $params2);
                                        $out3 = self::condition($matchLoop[3], $params2);
                                } else {
                                    $out = self::condition($isloop[$i]['inner_html'], $params2);
                                }
                                if (is_object($item)) {
                                    if(isset($out)){
                                        $inFor .= self::parseObjectVar($out, $params2);
                                    }
                                    if(isset($out1)){
                                        $inFor1 .= self::parseObjectVar($out1, $params2);
                                    }
                                    if(isset($out2)){
                                        $inFor2 .= $out2;
                                    }
                                    if(isset($out3)){
                                        $inFor3 .= self::parseObjectVar($out3, $params2);
                                    }
                                } else {
                                    if(isset($out)){
                                        $inFor .= self::parseSimpleVar($out, $params2);
                                    }
                                    if(isset($out1)){
                                        $inFor1 .= self::parseSimpleVar($out1, $params2);
                                    }
                                    if(isset($out2)){
                                        $inFor2 .= $out2;
                                    }
                                    if(isset($out3)){
                                        $inFor3 .= self::parseSimpleVar($out3, $params2);
                                    }
                                }
                            }
                            if(isset($inFor)){
                                $outFor = self::parseContent($inFor, $params);
                            }
                            if(isset($inFor1)){
                                $outFor1 = self::parseContent($inFor1, $params);
                            }
                            if(isset($inFor2)){
                                $outFor2 = $inFor2;
                            }
                            if(isset($inFor3)){
                                $outFor3 = self::parseContent($inFor3, $params);
                            }
                            if($recurse == 'on'){
                                $content = str_replace($isloop[$i]['node'],$outFor1.$outFor2.$outFor3, $content);
                            } else {
                                $content = str_replace($isloop[$i]['node'],$outFor, $content);
                            }
                        } else {
                            die('ERREUR DE PARAMETRE, TABLEAU D OBJET ATTENDU');
                        }
                        return self::loop($content, $params);
                }
            } 
            return $content;
        }

        static private function condition($content, $params) {
            $recursivity = 'off';
            if (preg_match('#(?s)<isif(.(?:(?!<isif).)*?)</isif>#', $content, $matches)) {
                $total = $matches[0];
                $recursivity = 'on';
                preg_match('#(?s).*condition="\${(.*?)}".*?>(.*)#', $matches[1], $matches);
                $condition = $matches[1];
                foreach ($params as $key => $param) {
                    $condition = str_replace($key, '$'.$key, $condition);
                }
                $variable = '';
                foreach ($params as $key => $value) {
                    if (is_object($value) || is_array($value)) {
                        $variable .= '$'.$key.' = unserialize(\''.serialize($value).'\');';
                    } else {
                        $variable .= '$'.$key.' = "'.$value.'";';
                    }
                }
                $condition = 'return ('.$condition.');';
                $return = eval($variable.$condition);
                if (!$return) {
                    $content = str_replace($total,'',$content);
                }
                if ($return) {
                    $content = str_replace($total, $matches[2], $content);
                }
            }
            if($recursivity == 'on') {
                return self::condition($content, $params);
            }
            return $content;
        }

        static private function includeTpl($content, $params) {

            if (preg_match_all('#<isinclude.*template.*="(.+)".*/>#', $content, $matches)) {
                foreach ($matches[1] as $match) {
                    $tplRequire = $match;
                    $requireContent = self::render($tplRequire,$params);
                    $content = preg_replace('#<isinclude.*template.*="'.$tplRequire.'".*/>#',$requireContent, $content);
                }
            }

            if (preg_match_all('#<isinclude.*remote.*="(.+)".*/>#', $content, $matches)) {
                foreach ($matches[1] as $match) {

                    $tmp            = explode('-', $match);
                    $controller     = $tmp[0];
                    $action         = $tmp[1];

                    $requireContent = Router::callController($controller, $action);
                    $content = preg_replace('#<isinclude.*remote.*="'.$match.'".*/>#',$requireContent, $content);
                }
            }

            return $content;
        }

        static private function parseSimpleVar($content, $params) {
            if (preg_match_all('#\${([a-zA-Z]+)}#', $content, $matches)) {
                foreach ($matches[1] as $match) {
                    if (isset($params[$match])) {
                        $content = str_replace('${'.$match.'}', $params[$match], $content);
                    }
                }
            }

            return $content;
        }

        static private function parseObjectVar($content, $params) {
            if (preg_match_all('#\${([a-zA-Z.]+)}#', $content, $matches)) {

                foreach ($matches[1] as $match) {

                    $attributes = explode('.', $match);
                    $mainObject = $params[$attributes[0]];

                    for($i = 1; $i < count($attributes); $i++) {

                        $getter = 'get'.ucfirst($attributes[$i]);
                        if (method_exists($mainObject, $getter)) {
                            $mainObject = $mainObject->{$getter}();
                        } else if (property_exists($mainObject, $attributes[$i])) {
                            $mainObject = $mainObject->{$attributes[$i]};
                        } else {
                            $mainObject = '';
                            break;
                        }
                    }
                    if (!is_object($mainObject)) {
                        $content = str_replace('${'.$match.'}', $mainObject, $content);
                    } else {
                        $content = str_replace('${'.$match.'}', '[Object]', $content);
                    }
                }
            }

            return $content;
        }


    }
