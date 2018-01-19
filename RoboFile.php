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

        $this->_exec('GIT_SSH_COMMAND="ssh -i /var/www/clients/client1/podcastdepot.de/.ssh/podcastdepot-timmeserver" git pull');

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