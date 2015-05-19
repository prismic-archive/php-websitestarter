<?php

class RoboFile extends \Robo\Tasks
{

    private $source = array(
        'serve.sh', 'README.md', 'RoboFile.php', 'app', 'tests', 'composer.json', 'composer.lock',
        'config-sample.php', 'vendor', 'public');

    private $archive = 'blogtemplate-%VER%.zip';

    function dist($version)
    {
        $this->taskFileSystemStack()->mkdir('dist')->run();
        $this->taskFileSystemStack()->mkdir('webkitstarter')->run();
        $this->_exec('cp -r ' . implode(' ', $this->source) . ' webkitstarter');
        $this->_exec('zip -r dist/' . str_replace('%VER%', $version, $this->archive) . ' webkitstarter');
        $this->_deleteDir(['webkitstarter']);
    }

}

