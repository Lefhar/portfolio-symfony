<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\CvRepository;
use Knp\Snappy\Pdf;
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
    public function index(Request $request, MailerInterface $mailer, CvRepository $cvRepository,Pdf $knpSnappyPdf): Response
    {
        $cv = $cvRepository->findOneBy(['IsActive'=>1]);
        if(!file_exists(getcwd().'/assets/file/'.$cv->getTitleFile() . '.pdf'))
        {
            $html = $this->renderView('download/index.html.twig', array(
                'cv' => $cv
            ));
            $knpSnappyPdf->setTimeout(120);
            $knpSnappyPdf->setOption("enable-local-file-access",true); // added this
            $pdf = $knpSnappyPdf->getOutputFromHtml($html, array(

                    'orientation' => 'portrait',

                    'page-height' => 297,

                    'page-width'  => 210,

                    'encoding' => 'utf-8',

                    'images' => true,

                    'dpi' => 72,

                    'enable-external-links' => true,

                    'enable-internal-links' => true,
                    'margin-top'=>0,
                    'margin-bottom'=>0,
                    'margin-left'=>0,
                    'margin-right'=>0,
                    'no-background'=>false,
                    'background'=>true

                )
            );
            file_put_contents(getcwd().'/assets/file/'.$cv->getTitleFile() . '.pdf', $pdf);
        }
        $baseurl = $request->getSchemeAndHttpHost();
        $cv = $cvRepository->findOneBy(['IsActive' => 1]);
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        $errors = "";
        $success = "";
        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('sujet')->getData() == "cv") {
                $email = (new TemplatedEmail())
                    ->attachFromPath(getcwd() . '/assets/file/' . $cv->getTitleFile() . '.pdf', $cv->getTitleFile() . '.pdf')
                    ->from('contact@lefebvreharold.fr')
                    ->to($form->get('email')->getData())
                    ->subject("demande de cv")
                    ->context([
                        'sujet' => $form->get('sujet')->getData(),
                        'nom'=> $cv->getUsers()->getNom(),
                        'prenom'=>$cv->getUsers()->getPrenom(),
                        'telephone'=>$cv->getUsers()->getTelephone(),
                        'adresse'=>$cv->getUsers()->getAdresse(),
                        'codepostal'=>$cv->getUsers()->getCodepostal(),
                        'ville'=>$cv->getUsers()->getVille(),
                        'contact'=>$cv->getUsers()->getContact(),
                        'mail' => $form->get('email')->getData(),
                        'message' => $form->get('message')->getData(),
                        'linkedin' => $cv->getUsers()->getLinkedin(),
                        'github' => $cv->getUsers()->getGithub(),
                        'base' => $baseurl
                    ])
                    ->htmlTemplate('sendmail/cvemail.html.twig');

            } else {
                if(empty($form->get('message')) or $form->get('message')=="" or $form->get('sujet')=="" or empty($form->get('sujet')) or empty($form->get('email')) )
                {
                    return $this->json(["success" => "", "error" => "Veuillez remplir tous les champs"]);
                }
                $email = (new TemplatedEmail())
                    ->to('contact@lefebvreharold.fr')
                    ->from($form->get('email')->getData())
                    ->subject($form->get('sujet')->getData())
                    ->context([
                        'sujet' => $form->get('sujet')->getData(),
                        'mail' => $form->get('email')->getData(),
                        'message' => $form->get('message')->getData(),
                        'linkedin' => $cv->getUsers()->getLinkedin(),
                        'github' => $cv->getUsers()->getGithub(),
                        'base' => $baseurl
                    ])
                    ->htmlTemplate('sendmail/email.html.twig');
            }
            $mailer->send($email);
            $success = "Votre message a bien été envoyé";
        } else {
            foreach ($form->getErrors(true) as $formError) {
                $errors = $formError->getMessage();
            }

        }

        return $this->json(["success" => $success, "error" => $errors]);
    }
}
