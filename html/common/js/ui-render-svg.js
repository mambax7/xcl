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

      $('img.svg').each((i, e) => {

        const $img = $(e);

        const imgID = $img.attr('id');

        const imgClass = $img.attr('class');

        const imgURL = $img.attr('src');

        $.get(imgURL, (data) => {
            // Find image with class"svg"
            let $svg = $(data).find('svg');

            // Add image's ID
            if (typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add classes to the new inline SVG
            if (typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', `${imgClass} -ui-icon`);
            }

            // Remove XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
            if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                $svg.attr(`viewBox 0 0  ${$svg.attr('height')} ${$svg.attr('width')}`);
            }

            // Replace with inline SVG
            $img.replaceWith($svg);
        }, 'xml');
    });
