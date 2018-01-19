<?php
/**
 * Tasks für robo.li
 *
 */
require_once 'vendor/autoload.php';

class RoboFile extends \Robo\Tasks
{

    public function deploy()
    {
        $this->say("Git pull");

        $this->taskGitStack()
            ->pull()
            ->run();

        $this->say("CSS erzeugen");
        $this->taskScss(['scss/bootstrap.scss' => 'assets/bootstrap.css'])
            ->importDir('scss')
            ->run();
    }

    public function build()
    {
        $this->say("CSS erzeugen");

        $this->taskScss(['scss/bootstrap.scss' => 'assets/bootstrap.css'])
            ->importDir('scss')
            ->run();
    }
}