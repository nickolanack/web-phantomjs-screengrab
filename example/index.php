<?php
include_once __DIR__ . '/vendor/nickolanack/scaffolds/scaffolds/defines.php';

HTML('document', 
    array(
        'title' => 'Site Monitor - Phantom',
        'header' => function () {
            
            ?>
<style type="text/css">
.thumb-cntnr {
	float: left;
	height: 220px;
	margin: 10px;
	box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
	border-radius: 4px;
	overflow: hidden;
	width: 256px;
}
</style>

<?php
        },
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
                                
                                $url = $protocol . '://' . $site;
                                
                                $folder = $protocol . '.' . $site;
                                if (file_exists(__DIR__ . '/' . $folder)) {
                                    
                                    if (file_exists(__DIR__ . '/' . $folder . '/page256x192.png')) {
                                        
                                        ?><div class="thumb-cntnr">
	<a href="<?php echo $url?>" target="_blank"> <img
		src="<?php echo $folder.'/page256x192.png'; ?>" />
	</a>
</div><?php
                                    }
                                    
                                    /*
                                     * array_walk(array_unique(scandir(__DIR__ . '/' . $folder)),
                                     * function ($file) use($folder) {
                                     *
                                     * if (substr($file, - 4) === '.png') {
                                     *
                                     * ?><img
                                     * src="<?php echo $folder.'/'.$file; ?>" /><?php
                                     * }
                                     * });
                                     *
                                     */
                                } else {
                                    
                                    $pcmd = 'phantomjs ' . __DIR__ .
                                         '/vendor/nickolanack/web-phantomjs-screengrab/screengrab.js ' .
                                         escapeshellarg($url) . ' >/dev/null 2>&1 &';
                                    
                                    // echo $pcmd . '<br/>';
                                    shell_exec($pcmd);
                                }
                                
                                return $url;
                            }, array_values($lines));
                        
                        // echo implode("<br/>", $sites) . '<br/>';
                        array_walk($sites, 
                            function ($site) {
                                
                                $pcmd = 'phantomjs ' . __DIR__ .
                                     '/vendor/nickolanack/web-phantomjs-screengrab/screengrab.js ' .
                                     escapeshellarg($site) . ' >/dev/null 2>&1 &';
                                
                                // echo $pcmd . '<br/>';
                                
                                shell_exec($pcmd);
                            });
                    }
                ));
        }
    ));
