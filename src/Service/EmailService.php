<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\Contact;
use App\Entity\Review;
use App\Service\Interface\IEmailService;
use App\Util\CalendarEventHelper;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailService implements IEmailService
{
	/**
	 * @param MailerInterface $mailer
	 * @param CalendarEventHelper $calendarEventHelper
	 * @param TranslatorInterface $translator
	 */
	public function __construct(private readonly MailerInterface     $mailer,
								private readonly CalendarEventHelper $calendarEventHelper,
								private readonly TranslatorInterface $translator)
	{
	}

	/**
	 * @param Activity $activity
	 * @param string $recipientEmail
	 * @param string $recipientName
	 * @param string $lang
	 * @return void
	 * @throws TransportExceptionInterface
	 * @throws Exception
	 */
	public function sendActivityConfirmationEmail(Activity $activity, string $recipientEmail, string $recipientName,
												  string   $lang): void
	{
		$email = (new TemplatedEmail())
			->from('no-reply@signpost.eu')
			->to($recipientEmail)
			->subject($this->translator->trans('Email.Confirmation.Activity confirmation', [], null, $lang)
				. " -  {$activity->getSubject()} on {$activity->getStart()->format('d-m-Y H:i')}")
			->htmlTemplate('email/activity_confirmation.html.twig')
			->attachPart($this->calendarEventHelper->generateCalendarEventProposal($activity))
			->context([
				'recipient_name' => !empty($recipientName)
					? $recipientName
					: $this->translator->trans('Email.Common.Unknown recipient', [], null, $lang),
				'sender_name' => $activity->getUser()->getFullName(),
				'subject' => $activity->getSubject(),
				'date' => $activity->getStart()->format('d-m-Y H:i'),
				'location' => $activity->getInstitution()->getAddress(),
				'lang' => $lang
			]);

		$this->mailer->send($email);
	}

	/**
	 * @param string $senderName
	 * @param string $subject
	 * @param string $location
	 * @param string $date
	 * @param string $recipientEmail
	 * @param string $recipientName
	 * @param string $lang
	 * @return void
	 * @throws TransportExceptionInterface
	 */
	public function sendActivityCancellationEmail(string $senderName, string $subject, string $location, string $date,
												  string $recipientEmail, string $recipientName, string $lang): void
	{
		$email = (new TemplatedEmail())
			->from('no-reply@signpost.eu')
			->to($recipientEmail)
			->subject($this->translator->trans('Email.Cancellation.Activity cancellation', [], null, $lang) . " - {$subject} on {$date}")
			->htmlTemplate('email/activity_cancellation.html.twig')
			->context([
				'recipient_name' => $recipientName,
				'sender_name' => $senderName,
				'subject' => $subject,
				'date' => $date,
				'location' => $location,
				'lang' => $lang
			]);

		$this->mailer->send($email);
	}

	/**
	 * @param Review $review
	 * @param Contact $contact
	 * @param string $senderName
	 * @param string $lang
	 * @return void
	 * @throws TransportExceptionInterface
	 */
	public function sendReviewEmail(Review $review, Contact $contact, string $senderName, string $lang): void
	{
		$email = (new TemplatedEmail())
			->from('no-reply@signpost.eu')
			->to($contact->getEmail1())
			->subject($this->translator->trans('Email.Review.Activity review', [], null, $lang) . ": {$review->getActivity()->getSubject()}")
			->htmlTemplate('email/activity_review.html.twig')
			->context([
				'recipient_name' => $contact->getFirstName(),
				'sender_name' => $senderName,
				'review_title' => $review->getTitle(),
				'review_content' => $review->getContent(),
				'lang' => $lang
			]);

		foreach ($review->getAttachments() as $attachment) {
			$email->attachFromPath($attachment->getPath());
		}

		$this->mailer->send($email);
	}
}