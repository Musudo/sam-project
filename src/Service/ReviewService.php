<?php

namespace App\Service;

use App\Entity\Review;
use App\Exception\ResourceNotCreatedException;
use App\Exception\ReviewNotFoundException;
use App\Repository\ReviewRepository;
use App\Service\Interface\IReviewService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ReviewService implements IReviewService
{
	private string $guid;

	/**
	 * @param ReviewRepository $reviewRepository
	 * @param UserService $userService
	 * @param Security $security
	 */
	public function __construct(private readonly ReviewRepository $reviewRepository,
								private readonly UserService      $userService,
								private readonly Security         $security)
	{
		$this->guid = $this->security->getUser()->getUserIdentifier();
	}

	/**
	 * @return array
	 */
	public function findAllForAdmin(): array
	{
		try {
			return $this->reviewRepository->findAll();
		} catch (Exception $e) {
			throw new ReviewNotFoundException("Failed to find reviews");
		}
	}

	/**
	 * @return array
	 */
	public function findAllForUser(): array
	{
		try {
			$activities = $this->userService->findByGuid($this->guid)->getActivities();
			$reviews = [];

			foreach ($activities as $activity) {
				foreach ($activity->getReview() as $review) {
					$reviews[] = $review;
				}
			}

			return $reviews;
		} catch (Exception $e) {
			throw new ReviewNotFoundException("Failed to find reviews for user");
		}
	}

	/**
	 * @param string $guid
	 * @return Review|null
	 */
	public function findByGuid(string $guid): ?Review
	{
		try {
			return $this->reviewRepository->findOneBy(['guid' => $this->guid]);
		} catch (Exception $e) {
			throw new ReviewNotFoundException("Failed to find review by guid");
		}
	}

	/**
	 * @param string $id
	 * @return Review|null
	 */
	public function findById(string $id): ?Review
	{
		try {
			return $this->reviewRepository->find($id);
		} catch (Exception $e) {
			throw new ReviewNotFoundException("Failed to find review by id");
		}
	}

	/**
	 * @param Review $review
	 * @return null
	 */
	public function save(Review $review)
	{
		try {
			$this->reviewRepository->save($review, true);
		} catch (Exception $e) {
			throw new ResourceNotCreatedException("Failed to create new review");
		}
	}

	public function send(Request $request, Review $review)
	{
		// TODO: Implement send() method.
	}
}