define([], function() {
    var init = function() {
        const darkThemeMq = window.matchMedia("(prefers-color-scheme: dark)");
        const darkThemeToggle = document.querySelectorAll(".darktheme-checkbox");

        const setTheme = function(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
        };

        const updateCheckboxes = function(checked) {
            darkThemeToggle.forEach(function(checkbox) {
                checkbox.checked = checked;
            });
        };

        const initializeTheme = function() {
            const storedDarkThemeSetting = getCookie("darkThemeEnabled");

            if (storedDarkThemeSetting === null) {
                setCookie("darkThemeEnabled", darkThemeMq.matches ? "1" : "0");
                updateCheckboxes(darkThemeMq.matches);
                setTheme(darkThemeMq.matches ? 'dark' : 'light');
            } else {
                const isDarkTheme = storedDarkThemeSetting === "1";
                updateCheckboxes(isDarkTheme);
                setTheme(isDarkTheme ? 'dark' : 'light');
            }
        };

        const handleCheckboxChange = function(e) {
            const isDarkThemeEnabled = e.target.checked;

            setTheme(isDarkThemeEnabled ? 'dark' : 'light');
            setCookie("darkThemeEnabled", isDarkThemeEnabled ? "1" : "0");
            updateCheckboxes(isDarkThemeEnabled);
        };

        darkThemeToggle.forEach(function(checkbox) {
            checkbox.addEventListener("change", handleCheckboxChange);
        });

        initializeTheme();

        /**
         * Retrieves the value of a cookie by name.
         * @param {string} name - The name of the cookie.
         * @returns {string|null} - The value of the cookie or null if not found.
         */
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            return null;
        }

        /**
         * Sets a cookie with the given name and value.
         * @param {string} name - The name of the cookie.
         * @param {string} value - The value to set for the cookie.
         */
        function setCookie(name, value) {
            document.cookie = `${name}=${value}; path=/; SameSite=None; Secure`;
        }
    };

    return {
        init: init
    };
});