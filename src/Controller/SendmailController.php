<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\CvRepository;
use App\Service\CvPdfGenerator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Text_LanguageDetect;

class SendmailController extends AbstractController
{
    /**
     * @Route("/sendmail", name="app_sendmail", methods={"POST"})
     * @throws TransportExceptionInterface
     */
    public function index(Request $request, MailerInterface $mailer, CvRepository $cvRepository, CvPdfGenerator $cvPdfGenerator): Response
    {
        $cv = $cvRepository->findOneBy(['IsActive'=>1]);
        $baseurl = $request->getSchemeAndHttpHost();
        $cv = $cvRepository->findOneBy(['IsActive' => 1]);
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        $errors = "";
        $success = "";
        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('sujet')->getData() == "cv") {
                $email = (new TemplatedEmail())
                    ->attach($cvPdfGenerator->refresh($cv), $cvPdfGenerator->filename($cv), 'application/pdf')
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

                $ld = new Text_LanguageDetect();
                $detectedLang = $ld->detect($form->get('message')->getData(), 2); // Augmenter le nombre de langues détectées à 2
                $blockedLanguages = ['russian', 'ukrainian','slovene','croatian', 'polish']; // Bloque le russe et l’ukrainien
                foreach ($detectedLang as $lang => $score) {
                    if (in_array($lang, $blockedLanguages, true)) {
                        // Si la langue est bloquée, on renvoie une réponse
                        return $this->json(["success" => "Votre message a bien été envoyé !", "error" => ""]);
                    }
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
