<?php

namespace Drupal\twig_views\Twig;

use Drupal\views\Views;

/**
 * Adds extension to render a view.
 */
class RenderView extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'render_view';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction(
        'render_view',
        [$this, 'render_view'],
        ['is_safe' => ['html']]
      ),
    ];
  }

  /**
   *
   * @param $view
   * @param $display
   *
   * @return array|null
   */
  public static function render_view($view, $display) {
    //- Throw exception if the display doesn't set.
    if (!isset($display) && empty($display)) {
      throw new \InvalidArgumentException(sprintf('You need to specify the view display.'));
    }
    //- Get the view machine id.
    $view = Views::getView($view);
    //- Set the display machine id.
    $view->setDisplay($display);
    //- Get the title.
    $title = $view->getTitle();
    //- Render.
    $render = $view->render();
    //- Prepare Title Render array.
    $the_title_render_array = [
      '#markup'       => t('@title', ['@title' => $title]),
      '#prefix'       => '<h2>',
      '#suffix'       => '</h2>',
      '#allowed_tags' => ['h2'],
    ];
    //- View Content.
    $view_content = [
      'view_title'  => $the_title_render_array,
      'view_output' => $render,
    ];
    //- Return the view render
    return render($view_content);
  }

}
