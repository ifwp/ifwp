<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP')){
    class IFWP {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function after_setup_theme(){
            $src = get_stylesheet_directory() . '/ifwp-functions.php';
            if(file_exists($src)){
                require_once($src);
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
            ifwp_build_update_checker('https://github.com/ifwp/ifwp', IFWP, 'ifwp');
            $general = ifwp_tab();
            $general->add_field([
            	'std' => '<p><img style="max-width: 100px; height: auto;" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxNTAgMTQwLjIiPjxnIGlkPSJMYXllcl8yIiBkYXRhLW5hbWU9IkxheWVyIDIiPjxnIGlkPSJMYXllcl8xLTIiIGRhdGEtbmFtZT0iTGF5ZXIgMSI+PHBhdGggZD0iTTEwLjMxLDM4LjEyYTUsNSwwLDAsMS0xLjUsMy42Niw1LDUsMCwwLDEtMy42NiwxLjUsNSw1LDAsMCwxLTMuNjUtMS41QTUsNSwwLDAsMSwwLDM4LjEyYTUuMTQsNS4xNCwwLDAsMSwxLjUtMy42NSw1LjI4LDUuMjgsMCwwLDEsMS42NC0xLjExLDUuMjIsNS4yMiwwLDAsMSwyLS4zOSw1LjIzLDUuMjMsMCwwLDEsMiwuMzksNS4yOCw1LjI4LDAsMCwxLDEuNjQsMS4xMSw1LjE0LDUuMTQsMCwwLDEsMS41LDMuNjVaTTEuMTMsNzEuMzJhMTMwLDEzMCwwLDAsMSwxLTE2LjI2LDIuNDIsMi40MiwwLDAsMSwuNzEtMS4zOSwyLjE0LDIuMTQsMCwwLDEsMS41Ni0uNTksMS44LDEuOCwwLDAsMSwxLjQxLjY1LDIuMiwyLjIsMCwwLDEsLjU3LDEuNWMwLC4yMy0uMDUuOTEtLjE0LDJzLS4yMSwyLjQ4LS4zNCw0LS4yNSwzLjIzLS4zNCw1LS4xNCwzLjM4LS4xNCw0Ljg1QTE2LjM5LDE2LjM5LDAsMCwwLDYsNzZhNy43Niw3Ljc2LDAsMCwwLDEuNTMsMi44Niw0LjgxLDQuODEsMCwwLDAsMiwxLjM2LDYuNDQsNi40NCwwLDAsMCwyLC4zNCw2LDYsMCwwLDAsMy4yOC0xLjEzLDE4LjMzLDE4LjMzLDAsMCwwLDMuNDktMy4xNyw0MS42Myw0MS42MywwLDAsMCwzLjU5LTQuOUE3MS4yOCw3MS4yOCwwLDAsMCwyNS40OSw2NWEyLjA4LDIuMDgsMCwwLDEsLjc5LS44NywyLjEzLDIuMTMsMCwwLDEsMS4xNC0uMzIsMS45MywxLjkzLDAsMCwxLDEuNS42OCwyLjI1LDIuMjUsMCwwLDEsLjU5LDEuNDgsMS45MiwxLjkyLDAsMCwxLS4yMiwxYy0uNDYuODctMSwxLjkzLTEuNywzLjE3cy0xLjQ2LDIuNTMtMi4zMywzLjg1LTEuODIsMi42NC0yLjg2LDMuOTRhMjguNDcsMjguNDcsMCwwLDEtMy4zMSwzLjUxLDE3LDE3LDAsMCwxLTMuNjgsMi41Miw4LjU5LDguNTksMCwwLDEtMy45MSwxQTksOSwwLDAsMSw3LDgzLjc4YTEwLjA5LDEwLjA5LDAsMCwxLTMuMjUtMi44OSwxMi42MSwxMi42MSwwLDAsMS0yLTQuMjhBMjAuNzgsMjAuNzgsMCwwLDEsMS4xMyw3MS4zMloiLz48cGF0aCBkPSJNMjkuODUsODUuMzdhNDQuMjgsNDQuMjgsMCwwLDAsNy42Mi00LjMxLDQ2LjM1LDQ2LjM1LDAsMCwwLDYuMTItNS4xMywzOCwzOCwwLDAsMCw0LjU5LTUuNDksMzUuODUsMzUuODUsMCwwLDAsMy01LjM1LDEuOSwxLjksMCwwLDEsMS45Mi0xLjI1LDIsMiwwLDAsMSwxLjU2LjY1LDIuMTEsMi4xMSwwLDAsMSwuNiwxLjQ1LDIuMjIsMi4yMiwwLDAsMS0uMTcuODUsNDMsNDMsMCwwLDEtMy4xOCw1Ljc1LDM5LjM4LDM5LjM4LDAsMCwxLTQuNjcsNS44Myw0Ni4zOCw0Ni4zOCwwLDAsMS02LjQzLDUuNTIsNTEuMTQsNTEuMTQsMCwwLDEtOC40NCw0Ljg4LDM2LjY5LDM2LjY5LDAsMCwxLDUuMzMsNyw0OC4wNiw0OC4wNiwwLDAsMSwzLjc2LDguMDcsNTIuNTIsNTIuNTIsMCwwLDEsMi4yNyw4LjQ3LDQ2LjQ3LDQ2LjQ3LDAsMCwxLC43Niw4LjE4LDMyLjc1LDMyLjc1LDAsMCwxLS42NSw2LjcyQTE5LjQ3LDE5LjQ3LDAsMCwxLDQyLDEzMi43MmExMSwxMSwwLDAsMS0zLjA5LDMuNjYsNi44Nyw2Ljg3LDAsMCwxLTQuMTQsMS4zMyw2LjE2LDYuMTYsMCwwLDEtNS4yNi0yLjUyLDE3LjE4LDE3LjE4LDAsMCwxLTIuNzUtNyw2MS40Niw2MS40NiwwLDAsMS0xLTEwLjQ4cS0uMTUtNi0uMTQtMTMuMTFWOTYuNDRjMC0zLjExLDAtNi40NiwwLTEwczAtNy4zLjA1LTExLjIxLjA1LTcuODMuMDktMTEuNzYuMDctNy43OC4xMS0xMS41OC4wOS03LjM5LjE3LTEwLjc5cS4xNi05Ljc1LjY4LTE3LjM5YTkyLjU1LDkyLjU1LDAsMCwxLDEuNjQtMTIuOTIsMjIuMDYsMjIuMDYsMCwwLDEsMy4wOS04LDUuODcsNS44NywwLDAsMSw1LTIuNzUsNi4xOCw2LjE4LDAsMCwxLDQsMS4zM0E5LjczLDkuNzMsMCwwLDEsNDMuMDUsNC45YTE5LjY0LDE5LjY0LDAsMCwxLDEuNDcsNS4yNEE0MS4wOSw0MS4wOSwwLDAsMSw0NSwxNi40Myw3NCw3NCwwLDAsMSw0My43OSwyOS44YTkxLjc1LDkxLjc1LDAsMCwxLTMuMjMsMTIuNTEsMTA2LjU3LDEwNi41NywwLDAsMS00Ljc5LDExLjg0UTMzLDU5Ljk0LDI5LjkxLDY1LjU0Vjc1LjkzcTAsMi43LDAsNS4xNkMyOS44Niw4Mi43MywyOS44NSw4NC4xNiwyOS44NSw4NS4zN1ptMCw3cTAsMy41NywwLDYuNjZjMCwyLjA2LDAsMy45NCwwLDUuNjQsMCwzLjUxLDAsNywuMDUsMTAuNDJhODkuMzMsODkuMzMsMCwwLDAsLjU0LDkuMjYsMjcuMTgsMjcuMTgsMCwwLDAsMS40Nyw2LjYzYy42NiwxLjY4LDEuNjIsMi41MiwyLjg2LDIuNTJhMy4yNywzLjI3LDAsMCwwLDIuNzgtMS41MywxMS45LDExLjksMCwwLDAsMS42Ny0zLjYzQTI0LjU4LDI0LjU4LDAsMCwwLDQwLDEyNGMuMTUtMS40Ni4yMy0yLjY0LjIzLTMuNTRhNDMuMjcsNDMuMjcsMCwwLDAtLjY1LTcuMjUsNDQuNzYsNDQuNzYsMCwwLDAtMi03LjUxLDQ4LjA4LDQ4LjA4LDAsMCwwLTMuMjUtNy4xN0EzMy4wNiwzMy4wNiwwLDAsMCwyOS44NSw5Mi4zM1pNMzAsNTYuODJhOTUuMTQsOTUuMTQsMCwwLDAsNC4yOC05LjIxLDkxLDkxLDAsMCwwLDMuMzctMTAsODksODksMCwwLDAsMi4yMS0xMC41Myw3NCw3NCwwLDAsMCwuNzktMTAuODIsNDMuMjQsNDMuMjQsMCwwLDAtLjI1LTQuOTMsMTgsMTgsMCwwLDAtLjgtMy43Nyw2LjY1LDYuNjUsMCwwLDAtMS4zNi0yLjQzLDIuNSwyLjUsMCwwLDAtMS44OS0uODgsMi4zOSwyLjM5LDAsMCwwLTIsMS4zNiwxNC4xLDE0LjEsMCwwLDAtMS40NywzLjYyLDQ1LjQ3LDQ1LjQ3LDAsMCwwLTEsNS4yMWMtLjI4LDItLjUyLDQtLjcsNi4xNXMtLjM0LDQuMjctLjQ2LDYuNC0uMiw0LjE0LS4yNSw2LS4xLDMuNTItLjEyLDUsMCwyLjUyLDAsMy4yM3EtLjA2LDIuMjYtLjA5LDRjMCwxLjE3LDAsMi4zMy0uMDgsMy40NnMtLjA3LDIuMzQtLjA5LDMuNjJTMzAsNTUuMDgsMzAsNTYuODJaIi8+PHBhdGggZD0iTTkwLjkyLDY2Ljg0YTQwLjI1LDQwLjI1LDAsMCwxLS44LDUuOTIsMTguNTQsMTguNTQsMCwwLDEtMiw1LjM2QTExLjYyLDExLjYyLDAsMCwxLDg0LjY5LDgyYTkuMSw5LjEsMCwwLDEtNS4zMywxLjUsOS4zMyw5LjMzLDAsMCwxLTMuNDgtLjYsOC41NSw4LjU1LDAsMCwxLTIuNjEtMS41OCw5LjM2LDkuMzYsMCwwLDEtMS44NC0yLjI3LDE2LDE2LDAsMCwxLTEuMjQtMi42OSwxNS43NywxNS43NywwLDAsMS00LjY4LDUuMDcsOS42Miw5LjYyLDAsMCwxLTUuMzUsMS43OCw5LjE1LDkuMTUsMCwwLDEtMy44OC0uNzksOC4zMyw4LjMzLDAsMCwxLTMtMi4yNCwxMC4zOSwxMC4zOSwwLDAsMS0xLjg5LTMuNDgsMTQuMjgsMTQuMjgsMCwwLDEtLjY4LTQuNTksMzAuNzUsMzAuNzUsMCwwLDEsLjc2LTcuMzMsMjcuODksMjcuODksMCwwLDEsMS43LTUuMDcsMTkuNiwxOS42LDAsMCwxLDIuNTItNCwxLjczLDEuNzMsMCwwLDEsLjcxLS41MSwyLjM4LDIuMzgsMCwwLDEsLjg4LS4xNywyLDIsMCwwLDEsMS41LjY1LDIuMiwyLjIsMCwwLDEsLjU5LDEuNUEyLjYzLDIuNjMsMCwwLDEsNTksNTguNTdsLS44OCwxLjMxYTE1LjA2LDE1LjA2LDAsMCwwLTEuMzMsMi42LDI2LDI2LDAsMCwwLTEuMjIsNEEyNS42NSwyNS42NSwwLDAsMCw1NSw3Mi4xMWE4LjExLDguMTEsMCwwLDAsMS4zNiw1LjA3LDQuNTcsNC41NywwLDAsMCwzLjgsMS43Myw2LjMzLDYuMzMsMCwwLDAsMi42LS42NSw4LjU0LDguNTQsMCwwLDAsMi42Ny0yLDEyLjIzLDEyLjIzLDAsMCwwLDIuMTUtMy40NiwxNS42MSwxNS42MSwwLDAsMCwxLjEzLTQuOTNsLjc0LTkuNzRhMiwyLDAsMCwxLC43MS0xLjQyLDIuMjMsMi4yMywwLDAsMSwxLjUtLjU2LDEuODYsMS44NiwwLDAsMSwxLjUzLjcsMi41OSwyLjU5LDAsMCwxLC41NiwxLjYybC0uNjIsOS4zNWEyMy4xMSwyMy4xMSwwLDAsMCwuMiw1LjE1LDkuNzQsOS43NCwwLDAsMCwxLjIyLDMuNTQsNS4zLDUuMywwLDAsMCwyLjA5LDIsNiw2LDAsMCwwLDIuNzguNjVBNS4xMyw1LjEzLDAsMCwwLDgyLjg3LDc4YTguMjQsOC4yNCwwLDAsMCwyLjI3LTMuMjNBMTksMTksMCwwLDAsODYuMzYsNzBhNDIuODQsNDIuODQsMCwwLDAsLjM3LTUuNjksMzcuNjUsMzcuNjUsMCwwLDAtLjQtNS45NSwyMy4zOCwyMy4zOCwwLDAsMS0uNC0zLjE3LDIuMDgsMi4wOCwwLDAsMSwuNjMtMS41LDIsMiwwLDAsMSwxLjUzLS42NSwyLDIsMCwwLDEsMS4zNS40OCwyLjIyLDIuMjIsMCwwLDEsLjc0LDEuMjdjMCwuMTUuMTUuNTkuMzQsMS4zMXMuNDUsMS41Ny43OSwyLjU3YTI3LDI3LDAsMCwwLDEuMzQsMy4xOCwxNS4yNywxNS4yNywwLDAsMCwyLDMuMDgsMTAuNTgsMTAuNTgsMCwwLDAsMi42NiwyLjM1LDYuMjksNi4yOSwwLDAsMCwzLjQzLjk0LDMuNjgsMy42OCwwLDAsMCwxLjg0LS40Myw0Ljc0LDQuNzQsMCwwLDAsMS4yNy0xLDQuODEsNC44MSwwLDAsMCwuNzctMS4wN2wuMzEtLjYzYTIuNDEsMi40MSwwLDAsMSwuODItMSwyLjA3LDIuMDcsMCwwLDEsMS4xNi0uMzcsMiwyLDAsMCwxLDEuNTYuNzFBMi4yNywyLjI3LDAsMCwxLDEwOSw2NmE0LjE4LDQuMTgsMCwwLDEtLjU5LDEuODJBOC44OCw4Ljg4LDAsMCwxLDEwNi43OCw3MGExMC4xNiwxMC4xNiwwLDAsMS0yLjY0LDEuODEsNy42Myw3LjYzLDAsMCwxLTMuNDIuNzYsOS40NCw5LjQ0LDAsMCwxLTMuMDYtLjQ4QTExLjY5LDExLjY5LDAsMCwxLDk1LDcwLjgxLDEzLjksMTMuOSwwLDAsMSw5Mi43OSw2OSwxOSwxOSwwLDAsMSw5MC45Miw2Ni44NFoiLz48cGF0aCBkPSJNMTM0LDY2Ljc5YTE5LjUyLDE5LjUyLDAsMCwxLTEuODcsOC44OSwxNS4wOSwxNS4wOSwwLDAsMS00LjU5LDUuNjFoLjIyYTEyLjQ5LDEyLjQ5LDAsMCwwLDYuMDktMS41LDE5LjU3LDE5LjU3LDAsMCwwLDUtMy44NiwzMS4yMywzMS4yMywwLDAsMCw0LTUuMjRxMS43NS0yLjg4LDMuMTEtNS42YTIuMTgsMi4xOCwwLDAsMSwuODItMSwyLjIxLDIuMjEsMCwwLDEsMS4xNi0uMzQsMS44OCwxLjg4LDAsMCwxLDEuNTMuNjgsMi4yOCwyLjI4LDAsMCwxLC41NywxLjQ4LDIuMjIsMi4yMiwwLDAsMS0uMTcuODUsNTcuMTYsNTcuMTYsMCwwLDEtMy41MSw2LjYsMzEuNDYsMzEuNDYsMCwwLDEtNC43Niw2LDIzLjQ3LDIzLjQ3LDAsMCwxLTYuMTIsNC4zNCwxNi44OCwxNi44OCwwLDAsMS03LjUzLDEuNjcsMTguNjQsMTguNjQsMCwwLDEtNS44NC0uOTQsMTMuMywxMy4zLDAsMCwxLTUtMi45Miw3LjgxLDcuODEsMCwwLDEtMS4zNi0xLjMzLDksOSwwLDAsMS0uOTEtMS4zMy4zMy4zMywwLDAsMS0uMTEtLjIyLDEuODksMS44OSwwLDAsMS0uMzQtMS4wOCwyLjExLDIuMTEsMCwwLDEsLjUxLTEuMzYuMjguMjgsMCwwLDEsLjExLS4xNC40Ny40NywwLDAsMCwuMTItLjA5LDIsMiwwLDAsMSwxLjI0LS41NmguMTdhMS4yMSwxLjIxLDAsMCwxLC40LDAsLjEuMSwwLDAsMSwuMDgsMCwuMTIuMTIsMCwwLDAsLjA5LDAsMSwxLDAsMCwxLC4zOS4xMS4xNy4xNywwLDAsMCwuMTIuMDYsMy4xMywzLjEzLDAsMCwxLC44NS44NSw3LjQ5LDcuNDksMCwwLDAsLjksMS4yNSw1Ljc4LDUuNzgsMCwwLDAsMS4wOC44MiwyLjgxLDIuODEsMCwwLDAsMS40Mi4zNyw1LjU1LDUuNTUsMCwwLDAsMi44My0uODUsOC43NSw4Ljc1LDAsMCwwLDIuNTUtMi40NCwxMy42NCwxMy42NCwwLDAsMCwxLjg0LTMuODUsMTYuOTEsMTYuOTEsMCwwLDAsLjcxLTUsMTIuMjgsMTIuMjgsMCwwLDAtLjQzLTMuNDlBNy45LDcuOSwwLDAsMCwxMjguMjgsNjFhNC42Nyw0LjY3LDAsMCwwLTEuMzktMS4zMywyLjg1LDIuODUsMCwwLDAtMS40Mi0uNDIsNC40Niw0LjQ2LDAsMCwwLTMuMDksMS41OCwyNC40OSwyNC40OSwwLDAsMC0zLjA4LDRjLTEsMS42My0yLDMuNDItMyw1LjM5cy0yLDMuODctMyw1LjcycS0uMTIsOS4xNi0uMzIsMTcuNXQtLjY3LDE1LjQ2cS0uNSw3LjE0LTEuMzQsMTIuOTJhNTEuNDgsNTEuNDgsMCwwLDEtMi4zMiw5Ljg4QTE3LjM2LDE3LjM2LDAsMCwxLDEwNSwxMzhhNy4xMSw3LjExLDAsMCwxLTUuMjcsMi4yMSw3LjI1LDcuMjUsMCwwLDEtMi4yMS0uNCw1LjE5LDUuMTksMCwwLDEtMi4zMi0xLjYxLDkuNzQsOS43NCwwLDAsMS0xLjgyLTMuNDgsMjAuMzYsMjAuMzYsMCwwLDEtLjczLTYuMDcsNjAuODksNjAuODksMCwwLDEsMS4xOS0xMS4xNkExMjIuMzUsMTIyLjM1LDAsMCwxLDk3LjE4LDEwNHEyLjE3LTcuMTEsNS4xOC0xNC41OVQxMDksNzQuODljMC0xLjMzLDAtMi42MywwLTMuOTFzMC0yLjUzLDAtMy43NGEyLjIzLDIuMjMsMCwwLDEtMS43NS44NSwxLjksMS45LDAsMCwxLTEuNTMtLjY4LDIuMjMsMi4yMywwLDAsMS0uNTctMS40NywyLjQ3LDIuNDcsMCwwLDEsLjE3LS44NSw0MC4zOSw0MC4zOSwwLDAsMCwyLjEtNS45MnEuNzQtMi44MSwxLjEtNC45M2EyOC4yOSwyOC4yOSwwLDAsMCwuNDMtMy4zN2MwLS44My4wNS0xLjI3LjA1LTEuM2EyLDIsMCwwLDEsLjYzLTEuNDgsMi4xNiwyLjE2LDAsMCwxLDMsMCwyLDIsMCwwLDEsLjYyLDEuNTFWNjdjLjg3LTEuNDgsMS43NC0yLjkzLDIuNjEtNC4zN2EyOS44OCwyOS44OCwwLDAsMSwyLjc1LTMuODVBMTMuNDEsMTMuNDEsMCwwLDEsMTIxLjc2LDU2YTYuODIsNi44MiwwLDAsMSwzLjcxLTEsNyw3LDAsMCwxLDMuNTEuOSw4LjUxLDguNTEsMCwwLDEsMi43MiwyLjUsMTEuNjMsMTEuNjMsMCwwLDEsMS43MywzLjc2QTE3LjU5LDE3LjU5LDAsMCwxLDEzNCw2Ni43OVpNMTA4Ljg3LDg1cS0yLjc4LDYuMjMtNSwxMi41MnQtMy43OSwxMi4xMnEtMS41Niw1LjgzLTIuNDEsMTAuOTNhNTUsNTUsMCwwLDAtLjg1LDljMCwuNjUsMCwxLjM0LjExLDIuMWE5LjA4LDkuMDgsMCwwLDAsLjQ2LDIuMSw0LjI0LDQuMjQsMCwwLDAsMSwxLjYxQTIuMTcsMi4xNywwLDAsMCwxMDAsMTM2YzEuMjUsMCwyLjMyLS43OCwzLjIzLTIuMzVhMjMuNDMsMjMuNDMsMCwwLDAsMi4yOS02LjM0LDgyLjUzLDgyLjUzLDAsMCwwLDEuNTYtOS4xOGMuNDItMy40Ni43NS03LjA3LDEtMTAuODVzLjQzLTcuNTguNTQtMTEuNDFTMTA4LjgsODguMzcsMTA4Ljg3LDg1WiIvPjwvZz48L2c+PC9zdmc+" alt="IFWP"></p><p>Improvements and Fixes for WordPress.</p><p><a class="button" href="https://ifwp.co" target="_blank">ifwp.co</a></p>',
                'type' => 'custom_html',
            ]);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function load(){
            add_action('after_setup_theme', [__CLASS__, 'after_setup_theme']);
            add_action('ifwp_loaded', [__CLASS__, 'ifwp_loaded']);
            add_action('wp_enqueue_scripts', [__CLASS__, 'wp_enqueue_scripts']);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function wp_enqueue_scripts(){
            $src = plugin_dir_url(__FILE__) . 'functions.js';
            $ver = filemtime(plugin_dir_path(__FILE__) . 'functions.js');
            wp_enqueue_script('ifwp-functions', $src, ['jquery'], $ver, true);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// functions
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_add_admin_notice')){
	function ifwp_add_admin_notice($admin_notice = '', $class = 'error', $is_dismissible = false){
		if(!in_array($class, ['error', 'warning', 'success', 'info'])){
			$class = 'warning';
		}
		if($is_dismissible){
			$class .= ' is-dismissible';
		}
		$admin_notice = '<div class="notice notice-' . $class . '"><p>' . $admin_notice . '</p></div>';
        admin_notices('admin_notices', function() use($admin_notice){
			echo $admin_notice;
		});
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_are_plugins_active')){
	function ifwp_are_plugins_active($plugins = []){
		$r = false;
		if($plugins){
			$r = true;
			foreach($plugins as $plugin){
				if(!ifwp_is_plugin_active($plugin)){
					$r = false;
					break;
				}
			}
		}
		return $r;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_array_keys_exist')){
	function ifwp_array_keys_exist($keys = [], $array = []){
		if(!$keys or !$array or !is_array($keys) or !is_array($array)){
			return false;
		}
		foreach($keys as $key){
			if(!array_key_exists($key, $array)){
				return false;
			}
		}
		return true;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_base64_urldecode')){
	function ifwp_base64_urldecode($data = '', $strict = false){
		return base64_decode(strtr($data, '-_', '+/'), $strict);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_base64_urlencode')){
	function ifwp_base64_urlencode($data = ''){
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_clone_role')){
	function ifwp_clone_role($source_role = '', $destination_role = '', $display_name = ''){
        if($source_role and $destination_role and $display_name){
            $role = get_role($source_role);
            $capabilities = $role->capabilities;
            add_role($destination_role, $display_name, $capabilities);
        }
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_format_function')){
	function ifwp_format_function($function_name = '', $args = []){
		$str = '';
		if($function_name){
			$str .= '<div style="color: #24831d; font-family: monospace; font-weight: 400;">' . $function_name . '(';
			$function_args = [];
			if($args){
				foreach($args as $arg){
					$arg = shortcode_atts([
                        'default' => 'null',
						'name' => '',
						'type' => '',
                    ], $arg);
					if($arg['default'] and $arg['name'] and $arg['type']){
						$function_args[] = '<span style="color: #cd2f23; font-family: monospace; font-style: italic; font-weight: 400;">' . $arg['type'] . '</span> <span style="color: #0f55c8; font-family: monospace; font-weight: 400;">$' . $arg['name'] . '</span> = <span style="color: #000; font-family: monospace; font-weight: 400;">' . $arg['default'] . '</span>';
					}
				}
			}
			if($function_args){
				$str .= ' ' . implode(', ', $function_args) . ' ';
			}
			$str .= ')</div>';
		}
		return $str;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_get_memory_size')){
    function ifwp_get_memory_size(){
        if(!function_exists('exec')){
	        return 0;
	    }
	    exec('free -b', $output);
	    $output = $output[1];
	    $output = preg_replace('/\s+/', ' ', $output);
	    $output = trim($output);
	    $output = explode(' ', $output);
	    $output = $output[1];
	    return (int) $output;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_is_plugin_active')){
    function ifwp_is_plugin_active($plugin = ''){
		if(!function_exists('is_plugin_active')){
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		return is_plugin_active($plugin);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_new')){
    function ifwp_new(...$args){
        if(!$args){
            return null;
        }
        $class_name = array_shift($args);
        if(!class_exists($class_name)){
            return null;
        }
        if($args){
            return new $class_name(...$args);
        } else {
            return new $class_name;
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_off')){
    function ifwp_off($tag = '', $function_to_add = '', $priority = 10, $accepted_args = 1){
        return remove_filter($tag, $function_to_add, $priority, $accepted_args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_on')){
    function ifwp_on($tag = '', $function_to_add = '', $priority = 10, $accepted_args = 1){
        add_filter($tag, $function_to_add, $priority, $accepted_args);
		return _wp_filter_build_unique_id($tag, $function_to_add, $priority);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_prepare')){
    function ifwp_prepare(...$args){
        global $wpdb;
        if(!$args){
            return '';
        }
        if(strpos($args[0], '%') !== false and count($args) > 1){
            return str_replace("'", '', $wpdb->remove_placeholder_escape($wpdb->prepare(...$args)));
        } else {
            return $args[0];
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_seems_json')){
    function ifwp_seems_json($str = ''){
        return (is_string($str) and preg_match('/^\{\".*\"\:.*\}$/', $str));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// loader
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IFWP::load();
