<?php
include_once __DIR__ . '/vendor/nickolanack/scaffolds/scaffolds/defines.php';

HTML('document', 
    array(
        'title' => 'Site Monitor - Phantom',
        'header' => function () {},
        'body' => function () {
            
            HTML('article', 
                array(
                    'title' => 'Latest Screen Grabs',
                    'text' => function () {
                        
                        $lines = explode("\n", trim(shell_exec("httpd -t -D DUMP_VHOSTS")));
                        $lines = array_filter($lines, 
                            function ($l) {
                                return (strpos($l, ' namevhost ') !== false);
                            });
                        
                        $sites = array_map(
                            function ($l) {
                                
                                $parts = explode(' namevhost ', $l);
                                
                                $p = $parts[0];
                                $p = explode('port', $p);
                                $port = (int) trim($p[1]);
                                // echo $port;
                                $protocol = ($port == 80 ? "http" : "https");
                                
                                $s = $parts[1];
                                $s = explode('(', $s);
                                $site = trim($s[0]);
                                return $protocol . '://' . $site;
                            }, $lines);
                        
                        echo implode("<br/>", $sites);
                    }
                ));
        }
    ));
