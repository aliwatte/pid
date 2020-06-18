<?php

namespace App\DataPersister;

use App\Entity\Show;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
class PostPersister implements DataPersisterInterface
{
	protected $entityManager;
	
	public function _construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
	public function supports($data): bool
	{
		return $data instanceof Show;
	}
	
	public function persist($data)
	{
		var_dump($data);
		die();
		$entityManager->persist($data);
		$entityManager->flush();
	}
	
	public function remove($data)
	{
		$entityManager->remove($data);
		$entityManager->flush();
	}
}