<?php

namespace App\Controller;

use App\Form\MessageType;
use App\Repository\CvRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class SendmailController extends AbstractController
{
    /**
     * @Route("/sendmail", name="app_sendmail")
     * @throws TransportExceptionInterface
     */
    public function index(Request $request, MailerInterface $mailer,CvRepository $cvRepository): Response
    {

        $baseurl = $request->getSchemeAndHttpHost();
        $cv = $cvRepository->findOneBy(['IsActive'=>1]);
        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {

            if($form->get('sujet')->getData()=="cv")
            {
                $email = (new TemplatedEmail())
                    ->attachFromPath(getcwd().'/assets/file/'.$cv->getTitleFile().'.pdf',$cv->getTitleFile().'.pdf')
                    ->from('contact@lefebvreharold.fr')
                    ->to($form->get('email')->getData())
                    ->subject("demande de cv")
                    ->context([
                        'sujet' => $form->get('sujet')->getData(),
                        'mail' => $form->get('email')->getData(),
                        'message' => $form->get('message')->getData(),
                        'linkedin'=>$cv->getUsers()->getLinkedin(),
                        'github'=>$cv->getUsers()->getGithub(),
                        'base'=>$baseurl
                    ])
                    ->htmlTemplate('sendmail/cvemail.html.twig')
                 ;

                $mailer->send($email);

            }else{
                $email = (new TemplatedEmail())
                    ->to('contact@lefebvreharold.fr')
                    ->from($form->get('email')->getData())
                    ->subject($form->get('sujet')->getData())
                    ->context([
                        'sujet' => $form->get('sujet')->getData(),
                        'mail' => $form->get('email')->getData(),
                        'message' => $form->get('message')->getData(),
                        'linkedin'=>$cv->getUsers()->getLinkedin(),
                        'github'=>$cv->getUsers()->getGithub(),
                        'base'=>$baseurl
                    ])
                    ->htmlTemplate('sendmail/email.html.twig')
                ;
                $mailer->send($email);
            }

        }
        return $this->render('sendmail/index.html.twig', [
            'form' => $form,

        ]);
    }
}
