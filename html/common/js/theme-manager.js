/**
 * Theme Changer
 *
 * How To Use
 *
 * 1) Add [data-theme="dark"] after :root { } in theme style.css file
 *
 *      :root[data-theme="dark"] {
            --ui-color: #e7e7e7;
            --ui-link-color: #face74;
        }
 *
 * 2) Add <button id="themeToggle"> to theme.html
 *
        <button id="themeToggle"></button>
        <script type="module" src="<{$xoops_url}>/common/js/ThemeManager.js"></script>
            <script type="module">
            import {ThemeManager} from '<{$xoops_url}>/common/js/ThemeManager.js';
            new ThemeManager(document.getElementById('themeToggle'));
        </script>

    ⚠️ USE CSS.escape if IDs are numbers or special characters
    https://developer.mozilla.org/en-US/docs/Web/API/CSS/escape

        const theId = "1";
        const el = document.querySelector(`#${CSS.escape(theId)}`);
*/
export class ThemeManager {
    'use-strict';
    /**
     * Constructs object of class ThemeManager
     * @param {string} themeToggle - the html element to change the theme mode
     * @param {string} theme - initial theme mode light and vice versa for dark
     */
    constructor(themeToggle, theme = 'light') {
        //get theme toggle DOM node
        if (!themeToggle) {
            console.error(`A valid DOM element must be passed as the themeToggle. You passed ${themeToggle}`);
            return;
        }
        this.themeToggle = document.querySelector('#themeToggle'); /* ⚠️ USE CSS.escape if number or special character */
        this.themeToggle.addEventListener('click', () => this.switchTheme());

        //get initial theme and apply it
        this.theme = theme;
        if (localStorage.getItem('data-theme')) {
            if (localStorage.getItem('data-theme') === (theme === 'light' ? 'dark' : 'light')) {
                this.theme = (theme === 'light' ? 'dark' : 'light');
            }
        }
        else if (window.matchMedia(`(prefers-color-scheme: ${(theme === 'light' ? 'dark' : 'light')})`).matches) {
            this.theme = (theme === 'light' ? 'dark' : 'light');
        }
        this._applyTheme();

        //add listener to change web theme on os theme change
        window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', (e) => {
            this.theme = (e.matches ? 'light' : 'dark');
            this._applyTheme();
        });

    }

    /**
     * Private _applyTheme sets documentElement and localStorage 'data-theme' attribute
     * Icon Moon : &#x1F317
     * Icon Sun  : &#x263C;
     */
    _applyTheme = () => {
        this.themeToggle.innerHTML = (this.theme === 'light' ? '&#x1F317' : '&#x263C;');
        document.documentElement.setAttribute('data-theme', this.theme);
        localStorage.setItem('data-theme', this.theme);
    }

    /**
     * switchTheme toggles the website theme on themeToggle event: 'click'
     */
    switchTheme = () => {
        this.theme = (this.theme === 'light' ? 'dark' : 'light');
        this._applyTheme();
    }
}
