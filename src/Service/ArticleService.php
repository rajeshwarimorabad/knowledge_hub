<?php

namespace Drupal\knowledge_hub\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\node\Entity\Node;

/**
 * ArticleService
 */
class ArticleService {
  protected $entityTypeManager;
  protected $cacheBackend;
  
  /**
   * Method __construct
   *
   * @param EntityTypeManagerInterface $entityTypeManager [explicite description]
   * @param CacheBackendInterface $cacheBackend [explicite description]
   *
   * @return void
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, CacheBackendInterface $cacheBackend) {
    $this->entityTypeManager = $entityTypeManager;
    $this->cacheBackend = $cacheBackend;
  }

  public function getLatestArticles($limit = 5) {
    $cid = 'knowledge_hub:latest_articles';
    
    if ($cache = $this->cacheBackend->get($cid)) {
      return $cache->data;
    }

    $storage = $this->entityTypeManager->getStorage('node');
    $query = $storage->getQuery()
      ->condition('status', 1)
      ->condition('type', 'article')
      ->accessCheck(true)
      ->sort('created', 'DESC')
      ->range(0, $limit);

    $nids = $query->execute();
    $articles = $storage->loadMultiple($nids);

    $this->cacheBackend->set($cid, $articles, CacheBackendInterface::CACHE_PERMANENT, ['node_list:article']);

    return $articles;
  }
}
