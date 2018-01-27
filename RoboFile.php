<?php

require_once 'vendor/autoload.php';

/**
 * Tasks für robo.li
 *
 */
class RoboFile extends \Robo\Tasks
{

    /**
     * Deploy auf podcastdepot.de Live-Server
     */
    public function deploy()
    {
        $this->say('Git pull');
        $this->_exec('GIT_SSH_COMMAND="ssh -i /var/www/clients/client1/podcastdepot.de/.ssh/podcastdepot-timmeserver" git pull');

        $this->build();

        $this->say('Cache löschen');
        $this->_exec('php bin/console cache:clear --no-warmup -e prod');

    }

    /**
     * Build auf Dev-Server
     */
    public function build()
    {
        $this->say('SCSS kompilieren');
        $this->taskScss([
            'scss/bootstrap-reboot.scss' => 'public/assets/bootstrap-reboot.css',
            'scss/bootstrap-grid.scss' => 'public/assets/bootstrap-grid.css',
            'scss/bootstrap.scss' => 'public/assets/bootstrap.css'])
            ->importDir('scss')
            ->run();

        $this->say('podcastdepot.css erzeugen');
        $this->taskConcat(array(
            'public/assets/bootstrap-reboot.css',
            'public/assets/bootstrap-grid.css',
            'public/assets/bootstrap.css'))
            ->to('public/assets/podcastdepot.css')
            ->run();

        $this->say('CSS minifizieren');
        $this->taskMinify('public/assets/podcastdepot.css')
            ->to('public/assets/podcastdepot.min.css')
            ->run();
    }

    /**
     * Watch auf scss Verzeichnis
     */
    public function watch()
    {
        $this->taskWatch()
            ->monitor('scss', function() {
                $this->build();
            })->run();
    }
}