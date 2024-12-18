<?php

namespace App\Service;

use App\Security\MicrosoftGraphApiAuthenticator;
use GuzzleHttp\Exception\GuzzleException;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model\Attendee;
use Microsoft\Graph\Model\AttendeeType;
use Microsoft\Graph\Model\DateTimeTimeZone;
use Microsoft\Graph\Model\EmailAddress;
use Microsoft\Graph\Model\Event;
use Microsoft\Graph\Model\ItemBody;

class OutlookEventService
{
	public function __construct
	(
		private readonly MicrosoftGraphApiAuthenticator $microsoftGraphApiAuthenticator,
	)
	{
	}

	/**
	 * @throws GraphException
	 * @throws GuzzleException
	 */
	public function createOutlookEvent(): void
	{
		$accessToken = $this->microsoftGraphApiAuthenticator->getAccessToken();

		$graph = new Graph();
		$graph->setAccessToken($accessToken);

		$emailAddress = new EmailAddress();
		$emailAddress->setAddress('musa.tashtamirov@signpost.eu');
		$emailAddress->setName('Musa Tashtamirov');

		$attendee = new Attendee();
		$attendee->setEmailAddress($emailAddress);
		$attendee->setType(new AttendeeType('required'));

		$event = new Event();
		$event->setSubject('Test event van musa');
		$event->setBody(new ItemBody());
		$event->getBody()->setContent('Event details signpost');
		$event->setStart(new DateTimeTimeZone('2022-01-25T15:00:00'));
		$event->setEnd(new DateTimeTimeZone('2022-01-025T16:00:00'));
		$event->setAttendees([$attendee]);

		$newEvent = $graph->createRequest('post', '/me/calendar/events')
			->attachBody($event)
			->execute();
	}

	public function updateEvent(): void
	{
//		 $eventId = "EVENT_ID";
//		 $event->setSubject("Updated event");
//		 $graph->createRequest("patch", "/me/calendar/events/{$eventId}")
//			 ->attachBody($event)
//			 ->execute();
	}
}