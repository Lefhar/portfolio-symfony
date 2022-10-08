<?php

namespace App\Controller;

use App\Repository\DemarchageRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CronController extends AbstractController
{
    /**
     * @Route("/cron",name="app_cron",methods={"GET"})
     */
    public function sendmail(DemarchageRepository $demarchageRepository, MessageRepository $messageRepository,Request $request,
                             MailerInterface $mailer,EntityManagerInterface $entityManager)
    {
       $demarche =  $demarchageRepository->findOneBy(['status'=>0,'unsubscribe'=>0],['id'=>'desc']);
       $message = $messageRepository->findOneBy([],['id'=>'desc']);
        $baseurl = $request->getSchemeAndHttpHost();
        $email = (new TemplatedEmail())

            ->from('contact@lefebvreharold.fr')
            ->to($demarche->getEmail())
            ->subject($message->getTitle())
            ->context([
                'mail'=>$message->getUsers()->getEmail(),
                'sujet' => $message->getTitle(),
                'message' => $message->getContent(),
                'linkedin' => $message->getUsers()->getLinkedin(),
                'github' => $message->getUsers()->getGithub(),
                'base' => $baseurl
            ])
            ->htmlTemplate('sendmail/campagneemail.html.twig');
        $email->getHeaders()->addHeader('List-Unsubscribe', '<mailto:contact@lefebvreharold.fr>,<'.$baseurl.'/unsubscribe/'.$demarche->getEmail().'>');

        $mailer->send($email);
        $demarche->setStatus(1);
        $entityManager->flush();
        return $this->json(['mail'=>'envoyé']);
    }

}