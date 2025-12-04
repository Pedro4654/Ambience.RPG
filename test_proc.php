<?php
echo 'SAPI: '.PHP_SAPI."<br>\n";
echo 'php.ini: '.php_ini_loaded_file()."<br>\n";
echo 'PATH: '.nl2br(htmlentities(getenv('PATH')))."<br>\n";

$cmds = [
    'git --version',
    '"C:\\Program Files\\Git\\cmd\\git.exe" --version',
    'where git'
];

foreach ($cmds as $c) {
    echo "<hr>CMD: <b>".htmlentities($c)."</b><br>\n";
    $des=[0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']];
    $p=@proc_open($c,$des,$pipes);
    if(is_resource($p)){
        $out = stream_get_contents($pipes[1]);
        $err = stream_get_contents($pipes[2]);
        echo 'STDOUT: <pre>'.htmlentities($out)."</pre>\n";
        echo 'STDERR: <pre>'.htmlentities($err)."</pre>\n";
        proc_close($p);
    } else {
        echo '<b>proc_open falhou</b><br>';
    }
}
