<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;
use App\Entity\File;

final class ResolveFilePathSubscriber implements EventSubscriberInterface
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function onPreSerialize(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        if (!($attributes = RequestAttributesExtractor::extractAttributes($request)) || !\is_a($attributes['resource_class'], File::class, true)) {
            return;
        }

        $imageFiles = $controllerResult;

        if (!is_iterable($imageFiles)) {
            $imageFiles = [$imageFiles];
        }

        foreach ($imageFiles as $imageFile) {
            if (!$imageFile instanceof File) {
                continue;
            }

            $imageFile->path = $this->storage->resolveUri($imageFile, 'file');
        }
    }
}