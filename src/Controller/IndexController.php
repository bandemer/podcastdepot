<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class IndexController extends Controller
{
    /**
     * @Route("/", name="startseite")
     */
    public function index()
    {
        $session = new Session();

        $notices = $session->getFlashBag()->get('notice', array());
        $errors = $session->getFlashBag()->get('error', array());

        $timestamp = filemtime('assets/podcastdepot.min.css');

        return $this->render(
            'index.html.twig',
            array(
                'csstime' => date('YmdHis', $timestamp),
                'jahr' => date('Y'),
                'notices' => $notices,
                'errors' => $errors
            ));
    }

    /**
     * @Route("/kalender/")
     */
    public function kalender()
    {
        $session = new Session();

        $notices = $session->getFlashBag()->get('notice', array());
        $errors = $session->getFlashBag()->get('error', array());

        $timestamp = filemtime('assets/podcastdepot.min.css');

        return $this->render(
            'kalender.html.twig',
            array(
                'csstime' => date('YmdHis', $timestamp),
                'jahr' => date('Y'),
                'notices' => $notices,
                'errors' => $errors
            ));
    }

    /**
     * @Route("/impressum/")
     */
    public function impressum()
    {
        return $this->render('impressum.html.twig');
    }


    /**
     * @Route("/datenschutz/")
     */
    public function datenschutz()
    {
        return $this->render('datenschutz.html.twig');
    }

    /**
     * @Route("/kontakt/")
     */
    public function kontakt()
    {
        $token = md5(uniqid(rand(), true));

        $session = new Session();
        $session->set('token', $token);

        return $this->render('kontakt.html.twig',
            array('token' => $token));
    }

    /**
     * @Route("/feed/")
     */
    public function feed()
    {
        if ($this->container->has('profiler')) {
            $this->container->get('profiler')->disable();
        }

        $feed = new \DOMDocument();
        $feed->load('assets/podcastdepot-feed.rss');

        foreach ($feed->getElementsByTagName('lastBuildDate') AS $t) {
            $t->nodeValue = date('r');
        }


        $feeds = [
            'https://kleinesgespraech.de/feed/feed-mp3/',
            'https://thematischfrisch.de/feed/mp3/',
            'https://kleinermonolog.de/feed/mp3/',
        ];



        foreach ($feeds AS $f) {
            $xml = file_get_contents($f);
            $temp = new \DOMDocument();
            $temp->loadXML($xml);

            foreach ($temp->getElementsByTagName('item') AS $i) {
                foreach ($feed->getElementsByTagName('channel') AS $c) {
                    $i = $feed->importNode($i, true);
                    $c->appendChild($i);
                }
            }
        }

        $response = new Response(
            $feed->saveXML(),
            Response::HTTP_OK,
            ['content-type' => 'application/rss+xml']
        );

        $response->setCharset('UTF-8');

        return($response);

    }

    /**
     * @Route("/senden/")
     */
    public function senden(Request $request)
    {
        //Konfiguration
        $config = array();
        $config['recipients'] = array('pascal@podcastdepot.de');
        $config['subject'] = 'Nachricht über Kontaktformular auf '.
            'podcastdepot.de';

        if ($request->request->has('nachricht') AND
            $request->request->has('email') AND
            $request->request->has('token')) {
            $error = array();

            $email = filter_var($request->request->get('email'),
                FILTER_VALIDATE_EMAIL);
            $nachricht = htmlentities(trim(
                $request->request->get('nachricht')));

            $session = new Session();

            //Token überprüfen
            if (!$session->has('token') OR
                $request->request->get('token') != $session->get('token')) {
                $error[] = 'Ein Fehler ist aufgetreten. '.
                    'Bitte versuche es erneut.';
            }

            //E-Mail-Adresse überprüfen
            if ($email == false) {
                $error[] = 'Die angegebene E-Mail-Adresse ist nicht gültig.';
            }

            //Fehlermeldung ausgeben
            if (count($error) > 0) {

                foreach ($error AS $e) {
                    $session->getFlashBag()->add('error', $e);
                }

            //Wenn alles in Ordnung ist, Nachricht versenden
            } else {

                $message =
                    "Folgende Nachricht ist über das Kontaktformular ".
                        "eingegangen:\n\n".
                    "Absender: ".$email."\n\n".
                    "Nachricht:\n".$nachricht;

                $headers = "From: ".$email."\nReply-To: ".$email;
                foreach ($config['recipients'] AS $r) {
                    mail($r, $config['subject'], $message, $headers);
                }
                $session->getFlashBag()->add('notice',
                    'Danke! Deine Nachricht wurde erfolgreich versendet.');
                $session->set('token', '');
            }
        }

        return $this->redirectToRoute('startseite');
    }


}