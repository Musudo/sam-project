<?php

namespace App\Util;

use App\Entity\Activity;
use DateTimeImmutable;
use Eluceo\iCal\Domain\Entity\Attendee;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Exception;
use Symfony\Component\Mime\Part\DataPart;

class CalendarEventHelper
{
	/**
	 * Generates iCal event with activity data
	 * @param Activity $activity
	 * @return DataPart iCal event wrapped in DataPart object
	 * @throws Exception
	 */
	public function generateCalendarEventProposal(Activity $activity): DataPart
	{
		// Set begin and end times
		$occurrence = new TimeSpan(
			new DateTime(DateTimeImmutable::createFromInterface($activity->getStart()), 'Europe/Brussels'),
			new DateTime(DateTimeImmutable::createFromInterface($activity->getEnd()), 'Europe/Brussels')
		);

		// Set organizer
		$organizer = new Organizer(new EmailAddress($activity->getUser()->getEmail()), $activity->getUser()->getFullName());

		// Set attendees
		$attendees = [];
		foreach ($activity->getContacts() as $contact) {
			$attendee = new Attendee(new EmailAddress($contact->getEmail1()));
			$attendee->setDisplayName($contact->getFullName());
			$attendees[] = $attendee;
		}

		foreach ($activity->getExternalParticipants() as $ep) {
			$attendee = new Attendee(new EmailAddress($ep->getEmail()));
			$attendee->setDisplayName($ep->getEmail());
			$attendees[] = $attendee;
		}

		// Create an event
		$event = new Event();
		$event
			->setOccurrence($occurrence)
			->setLocation(new Location($activity->getInstitution()->getAddress()))
			->setOrganizer($organizer)
			->setSummary($activity->getSubject())
			->setDescription($activity->getSubject())
			->setAttendees($attendees);

		// Add the event to the calendar
		$calendar = new Calendar([$event]);

		// Transform domain entity into an iCalendar component
		$componentFactory = new CalendarFactory();
		$calendarComponent = $componentFactory->createCalendar($calendar);

		// Create attachable data part from calendar event
		$icalPart = new DataPart((string)$calendarComponent, $activity->getSubject() . ".ics", 'text/calendar');
		$icalPart
			->asInline()
			->getHeaders()
			->addParameterizedHeader('Content-Type', 'text/calendar', ['charset' => 'utf-8'])
			->addParameterizedHeader('Content-Disposition', 'attachment');

		return $icalPart;
	}
}