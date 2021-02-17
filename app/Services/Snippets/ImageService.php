<?php

namespace App\Services\Snippets;

/*
    This class show an alignable and zoomable image to place in the posts.

    Example of snippet:
    {# image id=[1] alignment=[left] width=[300] show_caption=[true] zoom=[true] #}
*/

use App\Models\Post;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageService
{
    private int $count = 1;

    /**
     * Substitute accordion snippets with the related HTML
     *
     * @param string $postBody
     *
     * @return string
     */
    public function snippetsToHTML(string $postBody): string
    {
        // Find snippet occurrences
        //$ptn = '/{# +gallery +(name|width)=\[(.*)\] +(name|width)=\[(.*)\] +(name|width)=\[(.*)\] +#}/';
        $ptn = '/{# +image +(id|alignment|width|show_caption|zoom)=\[(.*)\] +(id|alignment|width|show_caption|zoom)=\[(.*)\] +(id|alignment|width|show_caption|zoom)=\[(.*)\] +(id|alignment|width|show_caption|zoom)=\[(.*)\] +(id|alignment|width|show_caption|zoom)=\[(.*)\] +#}/';

        if (preg_match_all($ptn, $postBody, $matches)) {
            // Transform the matches array in a way that can be used
            $matches = $this->turnArray($matches);

            foreach ($matches as $key => $singleImageMatches) {
                $parameters = $this->getParameters($singleImageMatches);

                $image = Media::find($parameters['image_id']);

                if (is_null($image)) {
                    $imageHtml = "<div class='alert alert-warning' role='alert'>A image with this id has not been found.</div>";
                } else {
                    $imageHtml = self::prepareImageHtml($image, $parameters);
                }

                ray($image);

                //$imageHtml = "Image HTML";

                // Replace the TOKEN found in the article with the generatd gallery HTML
                $postBody = str_replace($parameters['token'], $imageHtml, $postBody);
            }
        } else {
            $postBody = $postBody;
        }

        return $postBody;
    }

    /**
     *  Turn array of the matches after preg_match_all function.
     *  https://secure.php.net/manual/en/function.preg-match-all.php
     *
     * @param array $m
     * @return array $ret
     */
    public function turnArray(array $m): array
    {
        $ret = [];

        for ($z = 0; $z < count($m); $z++) {
            for ($x = 0; $x < count($m[$z]); $x++) {
                $ret[$x][$z] = $m[$z][$x];
            }
        }
        return $ret;
    }

    /**
     *  Returns the plugin parameters.
     *
     * @param array $matches
     *
     * @return array $ret
     */
    public function getParameters(array $matches): array
    {
        $ret = [];
        //ray($matches);

        // Get activation string parameters (from article)
        $ret['token'] = $matches[0];

        $ret['image_id'] = $matches[2];
        $ret['alignment'] = $matches[4];
        $ret['width'] = $matches[6];
        $ret['show_caption'] = filter_var($matches[8], FILTER_VALIDATE_BOOLEAN);
        $ret['zoom'] = filter_var($matches[10], FILTER_VALIDATE_BOOLEAN);



        //ray($ret);
        return $ret;
    }

    /**
     *  Returns HTML with the image, caption, zoom functionality, etc.
     *
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media $image
     * @param array $parameters
     *
     * @return string $ret
     */
    public function prepareImageHtml(Media $image, array $parameters): string
    {
        $alt = $image->getCustomProperty('image_description');
        $width = "w-100 sm:" . $parameters['width']; // 100% width mobile, then for bigger devices the one specified
        $margin = "mb-6 sm:mb-0 ";

        switch ($parameters['alignment']) {
            case 'right':
                $alignment = "float-right";
                $margin .= 'ml-0 sm:ml-3';
                break;

            default:
                $alignment = "float-left";
                $margin .= 'mr-0 sm:mr-3';
                break;
        }

        $imageHtml = "";
        $imageHtml .= "<div class='imageSnippet relative {$width} {$margin} {$alignment}'>";
            $imageHtml .= "<a href='" . $image->getUrl() . "' data-fancybox='images' data-caption='" . $alt . "' alt='" . $alt . "'>";
                $imageHtml .= "<img class='my-0' src='" . $image->getUrl('thumb') . "' />";
            $imageHtml .= "</a>";
            $imageHtml .= "<div class='absolute bottom-0 right-0 p-2 bg-gray-100 opacity-80'>";
                $imageHtml .= "<svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7'></path></svg>";
            $imageHtml .= "</div>";
        $imageHtml .= "</div>";

        return $imageHtml;
    }


}





/*
 *  ACCORDION EXAMPLE
 *
 *

<div class="accordion flex flex-col items-center justify-center h-screen">
  <!--  Panel 1  -->
  <div class="w-1/2">
    <input type="checkbox" name="panel" id="panel-1" class="hidden">
    <label for="panel-1" class="relative block bg-black text-white p-4 shadow border-b border-grey">Panel 1</label>
    <div class="accordion__content overflow-hidden bg-grey-lighter">
      <h2 class="accordion__header pt-4 pl-4">Header</h2>
      <p class="accordion__body p-4" id="panel1">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto possimus at a cum saepe molestias modi illo facere ducimus voluptatibus praesentium deleniti fugiat ab error quia sit perspiciatis velit necessitatibus.Lorem ipsum dolor sit amet, consectetur
        adipisicing elit. Lorem ipsum dolor sit amet.</p>
    </div>
  </div>
  <!-- Panel 2 -->
  <div class="w-1/2">
    <input type="checkbox" name="panel" id="panel-2" class="hidden">
    <label for="panel-2" class="relative block bg-black text-white p-4 shadow border-b border-grey">Panel 2</label>
    <div class="accordion__content overflow-hidden bg-grey-lighter">
      <h2 class="accordion__header pt-4 pl-4">Header</h2>
      <p class="accordion__body p-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto possimus at a cum saepe molestias modi illo facere ducimus voluptatibus praesentium deleniti fugiat ab error quia sit perspiciatis velit necessitatibus.Lorem ipsum dolor sit amet, consectetur
        adipisicing elit. Lorem ipsum dolor sit amet.</p>
    </div>
  </div>
  <!--  Panel 3  -->
  <div class="w-1/2">
    <input type="checkbox" name="panel" id="panel-3" class="hidden">
    <label for="panel-3" class="relative block bg-black text-white p-4 shadow border-b border-grey">Panel 3</label>
    <div class="accordion__content overflow-hidden bg-grey-lighter">
      <h2 class="accordion__header pt-4 pl-4">Header</h2>
      <p class="accordion__body p-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto possimus at a cum saepe molestias modi illo facere ducimus voluptatibus praesentium deleniti fugiat ab error quia sit perspiciatis velit necessitatibus.Lorem ipsum dolor sit amet, consectetur
        adipisicing elit. Lorem ipsum dolor sit amet.</p>
    </div>
  </div>
</div>



 *
 */