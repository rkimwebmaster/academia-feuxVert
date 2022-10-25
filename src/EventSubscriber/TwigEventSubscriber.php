<?php

namespace App\EventSubscriber;

use App\Repository\EntrepriseRepository;
use App\Repository\PromotionRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $entrepriseRepository;
    private $promotionRepository;

    public function __construct(Environment $twig, EntrepriseRepository $entrepriseRepository, PromotionRepository $promotionRepository)
    {
        $this->twig = $twig;
        $this->entrepriseRepository = $entrepriseRepository;
        $this->promotionRepository = $promotionRepository;
    }
    public function onKernelController(ControllerEvent $event)
    {
        $this->twig->addGlobal('entreprise', $this->entrepriseRepository->findOneBy([]));
        $this->twig->addGlobal('promotions', $this->promotionRepository->findAll([]));
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
