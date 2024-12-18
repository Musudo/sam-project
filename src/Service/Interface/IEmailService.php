<?php

namespace App\Service\Interface;

use App\Entity\Activity;
use App\Entity\Contact;
use App\Entity\Review;

interface IEmailService
{
	public function sendActivityConfirmationEmail(Activity $activity, string $recipientEmail, string $recipientName,
												  string $lang);
	public function sendActivityCancellationEmail(string $senderName, string $subject, string $location, string $date,
												 string $recipientEmail, string $recipientName, string $lang);
	public function sendReviewEmail(Review $review, Contact $contact, string $senderName, string $lang);
}