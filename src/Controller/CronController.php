<?php

namespace App\Controller;

use App\Repository\CvRepository;
use App\Repository\DemarchageRepository;
use App\Repository\MessageRepository;
use App\Service\CvPdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CronController extends AbstractController
{
    /**
     * @Route("/cron",name="app_cron",methods={"GET"})
     */
    public function sendmail(DemarchageRepository $demarchageRepository, MessageRepository $messageRepository,Request $request,
                             MailerInterface $mailer,EntityManagerInterface $entityManager,CvPdfGenerator $cvPdfGenerator,CvRepository $cvRepository): JsonResponse
    {
        $cv = $cvRepository->findOneBy(['IsActive'=>1]);

       $demarche =  $demarchageRepository->findOneBy(['status'=>0,'unsubscribe'=>0],['id'=>'desc']);
       if($demarche){
           $message = $messageRepository->findOneBy([],['id'=>'desc']);

        $baseurl = $request->getSchemeAndHttpHost();
        $email = (new TemplatedEmail())

            ->from('contact@lefebvreharold.fr')
            ->to($demarche->getEmail())
            ->attach($cvPdfGenerator->refresh($cv), $cvPdfGenerator->filename($cv), 'application/pdf')
            ->subject($message->getTitle())
            ->context([
                'mail'=>$message->getUsers()->getEmail(),
                'nom'=>$message->getUsers()->getNom(),
                'prenom'=>$message->getUsers()->getPrenom(),
                'telephone'=>$message->getUsers()->getTelephone(),
                'adresse'=>$message->getUsers()->getAdresse(),
                'codepostal'=>$message->getUsers()->getCodepostal(),
                'contact'=>$message->getUsers()->getContact(),
                'ville'=>$message->getUsers()->getVille(),
                'sujet' => $message->getTitle(),
                'message' => $message->getContent(),
                'linkedin' => $message->getUsers()->getLinkedin(),
                'github' => $message->getUsers()->getGithub(),
                'base' => $baseurl
            ])
            ->htmlTemplate('sendmail/campagneemail.html.twig');
        $email->getHeaders()->addHeader('List-Unsubscribe', '<mailto:'.$message->getUsers()->getEmail().'>,<'.$baseurl.'/unsubscribe/'.$demarche->getEmail().'>');

        $mailer->send($email);
        $demarche->setStatus(1);
        $entityManager->flush();
        return $this->json(['mail'=>'envoyé']);
       }else{
           return $this->json(['mail'=>'terminé']);
       }
    }

}
