<?php

namespace Drupal\knowledge_hub\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\knowledge_hub\Service\ArticleService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends ControllerBase {
    protected $article_service;

    public function __construct(ArticleService $article_service) {
        $this->article_service = $article_service;
    }

    public static function  create(ContainerInterface $container) {
        return new static($container->get('knowledge_hub.article_service'));
    }

    public function getArticles() {
        $articles = $this->article_service->getLatestArticles();
        $data = [];
    
        foreach ($articles as $article) {
          $data[] = [
            'id' => $article->id(),
            'title' => $article->getTitle(),
            'created' => $article->getCreatedTime(),
          ];
        }
    
        return new JsonResponse($data);
    }
}