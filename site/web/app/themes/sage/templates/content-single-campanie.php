<?php
  global $wp_query;

  use Roots\Sage\Assets;

  TemplateEngine::get()->render(
    'jumbotron',
    array(
      'show_header' => false,
      'extra_class' => 'small-jumbotron',
      'algolia_search' => get_search_form($echo = false)
    )
  );

  $campaign = \RepoManager::getCampaignRepository()
    ->getByPost($wp_query->post, true);

  $attachments = array();
  foreach ($campaign->getAttachments() as $attachment) {
    $attachments[] = array(
      'url' => $attachment,
      'name' => basename($attachment),
    );
  }

  TemplateEngine::get()->render(
    'campaign',
    array(
      'title' => $campaign->getTitle(),
      'image' => $campaign->getImage(),
      'content' => $campaign->getContent(),
      'has_attachments' => (bool) $campaign->getAttachments(),
      'attachments' => $attachments,
    )
  );

  $guides = $campaign->getSimilarGuides();

  if (!$guides) {
    $guides = \RepoManager::getGuideRepository()
      ->getList(4);
  }

  $guideProps = array();
  foreach ($guides as $guide) {
    $guideProps[] = array(
      'icon' => $guide->getPictograma()->getUrl(),
      'title' => $guide->getTitle(),
      'permalink' => $guide->getPermalink()
    );
  }

  TemplateEngine::get()->render(
    'guide_listing',
    array(
      'guides' => $guideProps,
      'see_more' => true,
      'center' => true,
    )
  );

  TemplateEngine::get()->render(
    'app_promo',
    array(
      'mobile_app_dsu' => Assets\asset_path('images/app_mobil_dsu.jpg'),
      'ios_badge' => Assets\asset_path('images/ios-badge.svg'),
      'playstore_badge' => Assets\asset_path('images/playstore-badge.svg'),
    )
  );
?>
