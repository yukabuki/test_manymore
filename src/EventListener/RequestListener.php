<?php

namespace App\EventListener;

use App\Entity\Request;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Request::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Request::class)]
class RequestListener
{
	private Security $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}

	public function prePersist(Request $request, LifecycleEventArgs $event): void
	{
		$request->setCreatedAt(new DateTimeImmutable());
		$request->setCreatedBy($this->security->getUser());

		$request->setUpdatedAt(new DateTimeImmutable());
		$request->setUpdatedBy($this->security->getUser());
	}

	public function preUpdate(Request $request, LifecycleEventArgs $event): void
	{
		$request->setUpdatedAt(new DateTimeImmutable());
		$request->setUpdatedBy($this->security->getUser());
	}
}