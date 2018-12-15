<?php

namespace Drupal\scca_ash\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\Routing\Route;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

/**
 * Our SCCA ASH Theme Negotiator.
 */
class ThemeNegotiator implements ThemeNegotiatorInterface {

  /**
   * Determines when use this ThemeNegotiator.
   *
   * Looks for _custom_theme on route. When set,
   * triggers this negotiator.
   *
   * @return bool
   *  TRUE when _custom_theme is set to scca_ash_2018.
   */
  public function applies(RouteMatchInterface $route_match) {
    $route = $route_match->getRouteObject();
    if (!$route instanceof Route) {
      return FALSE;
    }
    $option = $route->getOption('_custom_theme');
    if (!$option) {
      return FALSE;
    }

    return $option == 'scca_ash_2018';
  }

  /**
   * For triggered routes, use the scca_ash_2018 theme.
   *
   * @return string
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    return 'scca_ash_2018';
  }
}