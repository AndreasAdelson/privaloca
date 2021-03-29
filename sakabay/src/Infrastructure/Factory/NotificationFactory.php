<?php

namespace App\Infrastructure\Factory;

use App\Domain\Model\Answer;
use App\Domain\Model\Company;
use App\Domain\Notification\Model\Notification;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationFactory
{
    private $manager;
    private $translator;

    public function __construct(
        \Mgilet\NotificationBundle\Manager\NotificationManager $manager,
        TranslatorInterface $translator
    ) {
        $this->manager = $manager;
        $this->translator = $translator;
    }

    public function validationCompanyNotificationAdmin(array $destinataires, string $redirect, Company $company)
    {
        $subject = $this->translator->trans('validatecompany_subject_admin');
        $message = sprintf(
            $this->translator->trans('validatecompany_message_admin'),
            $company->getName()
        );

        $this->addNotification($destinataires, $subject, $message, $redirect);
    }

    public function validationCompanyNotificationUser(array $destinataires, string $redirect, Company $company)
    {
        $subject = $this->translator->trans('validatecompany_subject');
        $message = sprintf(
            $this->translator->trans('validatecompany_message'),
            $company->getName()
        );

        $this->addNotification($destinataires, $subject, $message, $redirect);
    }

    public function createCompanyNotificationAdmin(array $destinataires, string $redirect, Company $company)
    {
        $subject = $this->translator->trans('createcompany_subject_admin');
        $creatorName =  $company->getUtilisateur()->getFirstName() . ' ' . $company->getUtilisateur()->getLastName();
        $message = sprintf(
            $this->translator->trans('createcompany_message_admin'),
            $creatorName,
            $company->getName()
        );

        $this->addNotification($destinataires, $subject, $message, $redirect);
    }

    public function createCompanyNotificationUser(array $destinataires, string $redirect, Company $company)
    {
        $subject = $this->translator->trans('createcompany_subject');
        $message = sprintf(
            $this->translator->trans('createcompany_message'),
            $company->getName()
        );

        $this->addNotification($destinataires, $subject, $message, $redirect);
    }

    public function updatePassword(array $destinataires)
    {
        $subject = $this->translator->trans('updatepassword_subject');

        $message = $this->translator->trans('updatepassword_message');

        $this->addNotification($destinataires, $subject, $message, $redirect = null);
    }

    public function createService(array $destinataires, string $redirect, $besoin)
    {
        $subject = $this->translator->trans('createbesoin_subject');
        $message = sprintf(
            $this->translator->trans('createbesoin_message'),
            $besoin->getTitle()
        );

        $this->addNotification($destinataires, $subject, $message, $redirect);
    }

    public function createAnswerNotification(array $destinataires, string $redirect, Answer $answer, Company $company)
    {
        $subject = $this->translator->trans('createanswer_subject');
        $message = sprintf(
            $this->translator->trans('createanswer_message'),
            $answer->getCompany()->getName(),
            $answer->getBesoin()->getTitle()
        );

        $this->addNotification($destinataires, $subject, $message, $redirect);
    }


    private function addNotification(
        $users,
        $subject,
        $message,
        $redirect
    ) {
        $notification = new Notification();
        $notification->setSubject($subject);
        $notification->setMessage($message);
        if ($redirect) {
            $notification->setLink(parse_url($redirect, PHP_URL_PATH));
        }

        $this->manager->addNotification($users, $notification, true);
    }
}