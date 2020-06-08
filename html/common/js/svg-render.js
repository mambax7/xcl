    /** ---------- ---------- ---------- ---------- ---------- Render SVG
     *
     * Replace < img > with < svg >
     *
     * Get the classname property of object < image >
     * Get the element SVG
     * Remove the element SVG unused attributes
     * Set element SVG inline
     * Set element with a new className property
     * @param   {Object} img
     * @param   {Id} imgID
     * @param   {ClasseName} img.class='svg'
     * @param   {imgURL} img src='ui-icon-*name*.svg'
     * @returns {SVG} svg class="svg-ui-icon"
     *
     * !TODO
     * Output svg with custom class="svg-ui-icon"
     * and cache all icons or make a single sprite
     */
/*     $('img.svg').each(function(){

        var $img      = $(this);
        var imgID     = $img.attr('id');
        var imgClass  = $img.attr('class');
        var imgURL    = $img.attr('src');

        $.get(imgURL, function(data) {

          // Find image with class"svg"
          var $svg  = $(data).find('svg');

          if(typeof imgID !== 'undefined') {
              $svg  = $svg.attr('id', imgID);
          }

          if(typeof imgClass !== 'undefined') {
              // SVG inline with ClassName='svg-ui-icon'
              $svg  = $svg.attr('class', imgClass+'-ui-icon');
          }

          // Remove the element SVG unused attributes
          $svg = $svg.removeAttr('xmlns:a');
          if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
              $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
          }

          $img.replaceWith($svg);

        }, 'xml');

      }); */



      $('img.svg').each((i, e) => {

        const $img = $(e);

        const imgID = $img.attr('id');

        const imgClass = $img.attr('class');

        const imgURL = $img.attr('src');

        $.get(imgURL, (data) => {
            // Get the SVG tag, ignore the rest
            let $svg = $(data).find('svg');

            // Add replaced image's ID to the new SVG
            if (typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', `${imgClass} -ui-icon`);
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
            if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                $svg.attr(`viewBox 0 0  ${$svg.attr('height')} ${$svg.attr('width')}`);
            }

            // Replace image with new SVG
            $img.replaceWith($svg);
        }, 'xml');
    });
