<?php

namespace App\Service\Interface;

use App\Entity\Review;
use Symfony\Component\HttpFoundation\Request;

interface IReviewService
{
	public function findAllForAdmin();

	public function findAllForUser();

	public function findByGuid(string $guid);

	public function findById(string $id);

	public function save(Review $review);

	public function send(Request $request, Review $review);
}