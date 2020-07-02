/*
    XOOPCube Theme : XCL Admin Flex Grid
    Distribution : XCL 2.3 Alpha
    Version : 0.7.0
    Author : Nuno Luciano aka Gigamaster
    Date : 2020-06-11
    URL : https://github.com/xoopscube/xcl/

    -------------------- -------------------- -------------------- ADMIN DASHBOARD */

    /** ---------- ---------- ---------- ---------- ---------- Highligth Worker */


    /** ---------- ---------- ---------- ---------- ---------- Document Ready
     *
     * UI Navigation elements
     * Block & Module Management switch status
     * Alert user of Unsaved Changes before unload
     */

    $(document).ready(() => {

        sideNavControl();
        mobileNavControl();

        /** Management switch status
         *
         * Block and Module Management
         * Switch card block color on change
         */

        // Sidebar - Theme Options
        // .theme-options .theme-options-toogle
        // $(".right-side-toggle").click(function() {
        $(".theme-options").click(function() {
            $(".right-sidebar").slideDown(50),
            $(".right-sidebar").toggleClass("right-panel-show");
        });

        /** Display Template file name
         *
         * Edit settings of --ui-root.css line 150
         * --ui-dev-mode : block; // or none
         */
        $("div.ui-dev-mode").attr('title', 'The template file name. Edit display --ui-root.css liine 150.');

    });
        // addEventListener('load', () => {
        //     const code = querySelector('pre code');
        //     const worker = new Worker('worker.js');
        //     console.log('load worker: ', worker);

        //     worker.onmessage = (event) => { code.innerHTML = event.data; }
        //     worker.postMessage(code.textContent);
        //   });
    /** ---------- ---------- ---------- ---------- ---------- Reusable Toogle
     * Hoow To Use
     * - Call it from any clickcable element with the className to display:
     * onclick="slideToggle('.className', this)"
     * - Set the className display to :none
     * <div class="className" style="display:none">
     * </div>
     * Customize : ( time, "effect", )
     */
    function slideToggle(className, obj) {
        $(className).slideToggle(500,"easeInOutCubic", obj.checked );
    }



    /** ---------- ---------- ---------- ---------- ---------- Navigation elements
     *
     * Layout Navigation
     * sideNavControl();
     * userDropdownMenu();
     * mobileNavControl();
     */

    const MAIN_LAYOUT       = $('.grid');
    const MAIN_SCROLL       = 'scrollbar';

    const MOBILE_ACTIVE     = '.nav-mobile';

    const SIDE_NAV          = $('.nav-aside');
    const SIDE_ACTIVE       = 'nav-aside-active';

    const userMenu          = $('.nav-user-control');

    const sideNavBlock      = $('.nav-block'); // console.log('sideNavBlock: ', sideNavBlock);

    const SIDE_MENU_BLOCK_OPEN  = 'nav-block-open';
    const SIDE_MENU_BLOCK_CLOSE = 'nav-block-close';


    // Function Toggle Class
    function toggleClass(el, className) {

    if (el.hasClass(className)) {
        el.removeClass(className);
        } else {
            el.addClass(className);
        }
    }

    // Function Side Nav Control
    function sideNavControl() {

        sideNavBlock.each((i, sideMenuTitleToggle) => {

            $(sideMenuTitleToggle).on('click', (e) => {

            const SIDE_MENU_LINK = $(sideMenuTitleToggle).siblings();

            // Toggle class of side menu title
            if (sideMenuTitleToggle) {
                toggleClass($(sideMenuTitleToggle), SIDE_MENU_BLOCK_OPEN);
            }

            // Switch view of side menu
            if (SIDE_MENU_LINK && SIDE_MENU_LINK.length === 1) {
                toggleClass($(SIDE_MENU_LINK), SIDE_MENU_BLOCK_CLOSE);
            }

            });

        });

    }

    // Function toggle nav-aside
    function mobileNavControl() {

        $(MOBILE_ACTIVE).on('click', function(e) {

            // console.log('clicked mobile control'); // Check

            toggleClass($(MOBILE_ACTIVE),'nav-mobile-close');
            toggleClass(SIDE_NAV, SIDE_ACTIVE);
            toggleClass(MAIN_LAYOUT, MAIN_SCROLL);

        });

    }


    /** ---------- ---------- ---------- ---------- ---------- Toggle Card SVG on Change
     *
     *
     */
    $('input[name^=uninstall]').on('change', function() {

        $(this).parent('.ui-checkbox').next('a').toggleClass('ui-update-change');

        // Switch only the svg background !
        // $(this).closest(".ui-card-block").find('.ui-card-block-image').toggleClass('ui-update-change');

        // Switch color of elements: <a> (affects border bottom), svg icon and text !
        $(this).closest(".ui-card-block").find('.ui-block-type').toggleClass('ui-update-change');

        // alert('Clik <{$smarty.const._AD_LEGACY_LANG_UPDATE}> to apply changes!');

        });


        // !TODO merge block and module switch

        // Module Management State Switch
        $('input[name^=isactive]').on('change', function() {

            $(this).parent('.ui-checkbox').next('a').toggleClass('ui-update-change');
            $(this).closest(".ui-card-block").find('.ui-card-block-image,.ui-module-state').toggleClass('ui-update-change');

            // alert('Clik <{$smarty.const._AD_LEGACY_LANG_UPDATE}> to apply changes!');

        });


    /** ---------- ---------- ---------- ---------- ---------- Alert Unsaved Changes
     *
     * Alert user of unsaved changes to form before unload !
     */

    var unsaved = false;

    // Alert to bind the event before unload page
    /* $(window).bind('beforeunload', function() {
        if(unsaved){
        // Most modern browsers (Chrome 51+, Safari 9.1+ etc) ignore custom messages and only display a generic message
        return "You have unsaved changes on this page. Do you want to <{$smarty.const._AD_LEGACY_LANG_UPDATE}> or discard your changes and leave this page?";
        }
    }); */

    // Triggers change in all input fields including text type
    $(document).on('change', ':input', function(){

        unsaved = true;

    });

    // Submit button exception
    $('input[type="submit"]').click(function() {

        unsaved = false;

    });

    function unloadPage(){

        // Most modern browsers (Chrome 51+, Safari 9.1+ etc) ignore custom messages and only display a generic message
        if(unsaved){
            return "You have unsaved changes on this page. Do you want to <{$smarty.const._AD_LEGACY_LANG_UPDATE}> or discard your changes and leave this page?";
        }

    }

    window.onbeforeunload = unloadPage;
