<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_Tabs')){
    class IFWP_Tabs {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static proteced
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static protected $meta_boxes = [], $settings_pages = [], $tabs = [];

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static protected function get_settings_page_id($settings_page = ''){
            $settings_page_id = '';
            if(is_string($settings_page)){
                $settings_page_id = 'ifwp';
                if($settings_page != 'General'){
                    $settings_page_id .= '-' . sanitize_title(wp_strip_all_tags($settings_page));
                }
            } elseif(is_array($settings_page)){
                if(!empty($settings_page['id'])){
                    $settings_page_id = $settings_page['id'];
                }
            }
            return $settings_page_id;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static protected function get_tab_id($tab = ''){
            return sanitize_title(self::get_tab_title($tab));
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static protected function get_tab_title($tab = ''){
            $title = '';
            if(is_array($tab)){
                if(!empty($tab['label'])){
                    $tab = $tab['label'];
                } else {
                    $tab = '';
                }
            }
            if(is_string($tab)){
                $title = wp_strip_all_tags($tab);
            }
            return $title;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static protected function maybe_add_settings_page($settings_page = ''){
            $settings_page_id = self::get_settings_page_id($settings_page);
            if($settings_page_id){
                if(!array_key_exists($settings_page_id, self::$settings_pages)){
                    if(is_string($settings_page)){
                        if($settings_page_id == 'ifwp'){
                            self::$settings_pages[$settings_page_id] = [
                                'capability' => 'manage_options',
								'columns' => 1,
                                'icon_url' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDI0LjMuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCA5My4xIDg3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA5My4xIDg3OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+Cgkuc3Qwe2ZpbGw6I0ZGRkZGRjt9Cjwvc3R5bGU+CjxnIGlkPSJMYXllcl8yXzFfIj4KCTxnIGlkPSJMYXllcl8xLTIiPgoJCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik02LjQsMjMuN2MwLDAuOS0wLjMsMS43LTAuOSwyLjNjLTAuNiwwLjYtMS40LDAuOS0yLjMsMC45Yy0wLjksMC0xLjctMC4zLTIuMy0wLjlDMCwyNS0wLjMsMjMuNiwwLjMsMjIuNAoJCQljMC4yLTAuNCwwLjQtMC43LDAuNy0xYzAuMy0wLjMsMC42LTAuNSwxLTAuN2MwLjQtMC4yLDAuOC0wLjMsMS4yLTAuMmMwLjQsMCwwLjksMC4xLDEuMiwwLjJjMC40LDAuMiwwLjcsMC40LDEsMC43CgkJCWMwLjMsMC4zLDAuNSwwLjYsMC43LDFDNi4zLDIyLjgsNi40LDIzLjIsNi40LDIzLjd6IE0wLjcsNDQuM2MwLTMuNCwwLjItNi43LDAuNi0xMC4xYzAtMC4zLDAuMi0wLjYsMC40LTAuOQoJCQljMC4zLTAuMywwLjYtMC40LDEtMC40YzAuMywwLDAuNywwLjEsMC45LDAuNEMzLjksMzMuNiw0LDMzLjksNCwzNC4zYzAsMC4xLDAsMC42LTAuMSwxLjNjMCwwLjctMC4xLDEuNS0wLjIsMi41cy0wLjIsMi0wLjIsMy4xCgkJCXMtMC4xLDIuMS0wLjEsM2MwLDEsMC4xLDIsMC40LDNjMC4yLDAuNywwLjUsMS4zLDEsMS44YzAuMywwLjQsMC44LDAuNywxLjIsMC44QzYuNCw0OS45LDYuOCw1MCw3LjIsNTBjMC43LDAsMS40LTAuMywyLTAuNwoJCQljMC44LTAuNiwxLjUtMS4yLDIuMi0yYzAuOC0wLjksMS42LTEuOSwyLjItM2MwLjgtMS4zLDEuNS0yLjYsMi4yLTRjMC4xLTAuMiwwLjMtMC40LDAuNS0wLjVjMC4yLTAuMSwwLjUtMC4yLDAuNy0wLjIKCQkJYzAuNCwwLDAuOCwwLjEsMSwwLjRjMC4yLDAuMiwwLjQsMC42LDAuNCwwLjljMCwwLjIsMCwwLjQtMC4xLDAuNmwtMS4xLDJjLTAuNCwwLjgtMC45LDEuNi0xLjQsMi40cy0xLjEsMS42LTEuOCwyLjQKCQkJYy0wLjYsMC44LTEuMywxLjUtMi4xLDIuMmMtMC43LDAuNi0xLjUsMS4yLTIuMywxLjZjLTAuOCwwLjQtMS42LDAuNi0yLjQsMC42Yy0xLDAtMi0wLjItMi44LTAuN2MtMC44LTAuNC0xLjUtMS4xLTItMS44CgkJCWMtMC42LTAuOC0xLTEuNy0xLjItMi43QzAuOCw0Ni41LDAuNyw0NS40LDAuNyw0NC4zeiIvPgoJCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0xOC41LDUzYzEuNy0wLjcsMy4yLTEuNiw0LjctMi43YzEuMy0xLDIuNi0yLDMuOC0zLjJjMS4xLTEsMi0yLjIsMi45LTMuNGMwLjctMS4xLDEuNC0yLjIsMS45LTMuMwoJCQljMC4yLTAuNSwwLjctMC44LDEuMi0wLjhjMC40LDAsMC43LDAuMSwxLDAuNGMwLjIsMC4yLDAuNCwwLjYsMC40LDAuOWMwLDAuMiwwLDAuNC0wLjEsMC41Yy0wLjYsMS4yLTEuMiwyLjQtMiwzLjYKCQkJYy0wLjksMS4zLTEuOCwyLjUtMi45LDMuNmMtMS4yLDEuMy0yLjYsMi40LTQsMy40Yy0xLjYsMS4yLTMuNCwyLjItNS4yLDNjMS4zLDEuMywyLjQsMi44LDMuMyw0LjRjMC45LDEuNiwxLjcsMy4zLDIuMyw1CgkJCWMwLjYsMS43LDEuMSwzLjUsMS40LDUuMmMwLjMsMS43LDAuNSwzLjQsMC41LDUuMWMwLDEuNC0wLjEsMi44LTAuNCw0LjJjLTAuMiwxLjItMC43LDIuMy0xLjIsMy40Yy0wLjUsMC45LTEuMSwxLjctMS45LDIuMwoJCQljLTAuNywwLjUtMS42LDAuOC0yLjYsMC44Yy0xLjMsMC4xLTIuNS0wLjUtMy4zLTEuNmMtMC45LTEuMy0xLjUtMi44LTEuNy00LjNjLTAuNC0yLjEtMC42LTQuMy0wLjYtNi41Yy0wLjEtMi41LTAuMS01LjItMC4xLTguMQoJCQl2LTUuMWMwLTEuOSwwLTQsMC02LjJjMC0yLjIsMC00LjUsMC03czAtNC45LDAuMS03LjNzMC4xLTQuOCwwLjEtNy4yczAuMS00LjYsMC4xLTYuN2MwLjEtNCwwLjItNy42LDAuNC0xMC44CgkJCWMwLjEtMi43LDAuNS01LjQsMS04YzAuMy0xLjgsMS0zLjUsMS45LTVDMjAuMSwwLjYsMjEuMywwLDIyLjYsMGMwLjksMCwxLjgsMC4zLDIuNSwwLjhjMC43LDAuNiwxLjMsMS4zLDEuNiwyLjIKCQkJYzAuNCwxLDAuOCwyLjEsMC45LDMuMmMwLjIsMS4zLDAuMywyLjYsMC4zLDMuOWMwLDIuOC0wLjIsNS42LTAuNyw4LjNjLTAuNSwyLjYtMS4yLDUuMi0yLDcuOGMtMC44LDIuNS0xLjksNS0zLDcuMwoJCQljLTEuMSwyLjQtMi40LDQuOC0zLjYsNy4xdjYuNWMwLDEuMSwwLDIuMiwwLDMuMlMxOC41LDUyLjIsMTguNSw1M3ogTTE4LjUsNTcuM2MwLDEuNSwwLDIuOSwwLDQuMXMwLDIuNCwwLDMuNWMwLDIuMiwwLDQuMywwLDYuNQoJCQljMCwxLjksMC4xLDMuOCwwLjMsNS44YzAuMSwxLjQsMC40LDIuOCwwLjksNC4xYzAuNCwxLDEsMS42LDEuOCwxLjZjMC43LDAsMS40LTAuNCwxLjctMWMwLjUtMC43LDAuOC0xLjQsMS0yLjIKCQkJYzAuMy0wLjgsMC40LTEuNywwLjUtMi42YzAuMS0wLjksMC4xLTEuNiwwLjEtMi4yYzAtMS41LTAuMS0zLTAuNC00LjVjLTAuMy0xLjYtMC43LTMuMS0xLjItNC43Yy0wLjUtMS41LTEuMi0zLTItNC41CgkJCUMyMC42LDU5LjgsMTkuNiw1OC41LDE4LjUsNTcuM0wxOC41LDU3LjN6IE0xOC42LDM1LjNjMS0xLjksMS45LTMuOCwyLjctNS43YzAuOC0yLDEuNS00LjEsMi4xLTYuMmMwLjYtMi4yLDEtNC4zLDEuNC02LjUKCQkJYzAuMy0yLjIsMC41LTQuNSwwLjUtNi43YzAtMS4xLDAtMi4xLTAuMi0zLjJjLTAuMS0wLjgtMC4yLTEuNi0wLjUtMi4zYy0wLjItMC42LTAuNS0xLjEtMC44LTEuNWMtMC4zLTAuMy0wLjctMC41LTEuMi0wLjUKCQkJYy0wLjUsMC0xLDAuNC0xLjIsMC44Yy0wLjQsMC43LTAuNywxLjUtMC45LDIuMmMtMC4zLDEuMS0wLjUsMi4yLTAuNiwzLjNjLTAuMiwxLjItMC4zLDIuNS0wLjQsMy44cy0wLjIsMi42LTAuMyw0CgkJCXMtMC4xLDIuNi0wLjEsMy43cy0wLjEsMi4yLTAuMSwzLjFzMCwxLjYsMCwyYzAsMC45LDAsMS44LDAsMi41czAsMS41LTAuMSwyLjFzMCwxLjUsMCwyLjJTMTguNiwzNC4yLDE4LjYsMzUuM0wxOC42LDM1LjN6Ii8+CgkJPHBhdGggY2xhc3M9InN0MCIgZD0iTTU2LjQsNDEuNWMtMC4xLDEuMi0wLjIsMi41LTAuNSwzLjdjLTAuMiwxLjItMC42LDIuMy0xLjIsMy4zYy0wLjUsMS0xLjMsMS44LTIuMiwyLjRjLTEsMC42LTIuMSwxLTMuMywwLjkKCQkJYy0wLjcsMC0xLjUtMC4xLTIuMi0wLjRjLTAuNi0wLjItMS4xLTAuNi0xLjYtMUM0NSw1MCw0NC42LDQ5LjYsNDQuMyw0OWMtMC4zLTAuNS0wLjYtMS4xLTAuOC0xLjdjLTAuNywxLjMtMS43LDIuMy0yLjksMy4yCgkJCWMtMSwwLjctMi4xLDEuMS0zLjMsMS4xYy0wLjgsMC0xLjctMC4yLTIuNC0wLjVjLTAuNy0wLjMtMS4zLTAuOC0xLjgtMS40Yy0wLjUtMC42LTAuOS0xLjQtMS4yLTIuMmMtMC4zLTAuOS0wLjQtMS45LTAuNC0yLjgKCQkJYzAtMS41LDAuMi0zLDAuNS00LjVjMC4yLTEuMSwwLjYtMi4xLDEtMy4yYzAuNC0wLjksMC45LTEuNywxLjYtMi41YzAuMS0wLjEsMC4zLTAuMiwwLjQtMC4zYzAuMi0wLjEsMC40LTAuMSwwLjUtMC4xCgkJCWMwLjQsMCwwLjcsMC4xLDAuOSwwLjRjMC4yLDAuMiwwLjQsMC42LDAuNCwwLjljMCwwLjMtMC4xLDAuNi0wLjMsMC44TDM2LDM3LjJjLTAuMywwLjUtMC42LDEuMS0wLjgsMS42CgkJCWMtMC4zLDAuOC0wLjYsMS42LTAuOCwyLjVjLTAuMiwxLjEtMC4zLDIuMy0wLjMsMy41QzM0LDQ1LjksMzQuNCw0NywzNSw0Ny45YzAuNiwwLjcsMS40LDEuMSwyLjMsMS4xYzAuNiwwLDEuMS0wLjIsMS42LTAuNAoJCQljMC42LTAuMywxLjItMC43LDEuNy0xLjJjMC42LTAuNiwxLTEuNCwxLjMtMi4xYzAuNC0xLDAuNi0yLDAuNy0zLjFsMC41LTZjMC0wLjMsMC4yLTAuNywwLjQtMC45YzAuMy0wLjIsMC42LTAuNCwwLjktMC4zCgkJCWMwLjQsMCwwLjgsMC4xLDEsMC40YzAuMiwwLjMsMC40LDAuNiwwLjMsMWwtMC40LDUuOGMtMC4xLDEuMSwwLDIuMSwwLjEsMy4yYzAuMSwwLjgsMC40LDEuNSwwLjgsMi4yYzAuMywwLjUsMC44LDEsMS4zLDEuMwoJCQljMC41LDAuMywxLjEsMC40LDEuNywwLjRjMC44LDAsMS41LTAuMiwyLjEtMC43YzAuNi0wLjUsMS4xLTEuMiwxLjQtMmMwLjQtMC45LDAuNi0xLjksMC44LTIuOWMwLjItMS4yLDAuMi0yLjMsMC4yLTMuNQoJCQljMC0xLjItMC4xLTIuNS0wLjItMy43Yy0wLjEtMC43LTAuMi0xLjMtMC4yLTJjMC0wLjMsMC4xLTAuNywwLjQtMC45YzAuMy0wLjMsMC42LTAuNCwxLTAuNGMwLjMsMCwwLjYsMC4xLDAuOCwwLjMKCQkJYzAuMiwwLjIsMC4zLDAuNCwwLjQsMC43YzAsMC4xLDAuMSwwLjQsMC4yLDAuOHMwLjMsMSwwLjUsMS42YzAuMiwwLjcsMC41LDEuNCwwLjgsMmMwLjMsMC43LDAuNywxLjMsMS4yLDEuOQoJCQljMC41LDAuNiwxLDEuMSwxLjcsMS41YzAuNiwwLjQsMS40LDAuNiwyLjEsMC42YzAuNCwwLDAuOC0wLjEsMS4xLTAuM2MwLjMtMC4yLDAuNi0wLjQsMC44LTAuNmMwLjItMC4yLDAuMy0wLjQsMC41LTAuN2wwLjItMC40CgkJCWMwLjEtMC4yLDAuMy0wLjUsMC41LTAuNmMwLjItMC4xLDAuNS0wLjIsMC43LTAuMmMwLjQsMCwwLjgsMC4xLDEsMC40YzAuMiwwLjMsMC40LDAuNiwwLjQsMC45YzAsMC40LTAuMiwwLjgtMC40LDEuMQoJCQljLTAuMywwLjUtMC42LDEtMSwxLjRjLTAuNSwwLjUtMSwwLjgtMS42LDEuMWMtMC43LDAuMy0xLjQsMC41LTIuMSwwLjVjLTAuNiwwLTEuMy0wLjEtMS45LTAuM2MtMC42LTAuMi0xLjItMC40LTEuNy0wLjcKCQkJYy0wLjUtMC4zLTEtMC43LTEuNC0xLjFDNTcuMiw0Mi40LDU2LjgsNDIsNTYuNCw0MS41eiIvPgoJCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik04My4yLDQxLjVjMC4xLDEuOS0wLjQsMy44LTEuMiw1LjVjLTAuNywxLjQtMS42LDIuNi0yLjgsMy41aDAuMWMxLjMsMCwyLjYtMC4zLDMuOC0wLjkKCQkJYzEuMS0wLjYsMi4yLTEuNCwzLjEtMi40YzAuOS0xLDEuNy0yLjEsMi41LTMuMmMwLjctMS4yLDEuNC0yLjQsMS45LTMuNWMwLjEtMC4yLDAuMy0wLjQsMC41LTAuNmMwLjItMC4xLDAuNS0wLjIsMC43LTAuMgoJCQljMC40LDAsMC43LDAuMSwwLjksMC40YzAuMiwwLjIsMC40LDAuNiwwLjMsMC45YzAsMC4yLDAsMC40LTAuMSwwLjVjLTAuNiwxLjQtMS40LDIuOC0yLjIsNC4xYy0wLjgsMS40LTEuOSwyLjYtMywzLjcKCQkJYy0xLjEsMS4xLTIuNCwyLTMuOCwyLjdjLTEuNSwwLjctMy4xLDEtNC43LDFjLTEuMiwwLTIuNS0wLjItMy42LTAuNmMtMS4xLTAuNC0yLjItMS0zLjEtMS44Yy0wLjMtMC4yLTAuNi0wLjUtMC44LTAuOAoJCQljLTAuMi0wLjMtMC40LTAuNS0wLjYtMC44YzAsMC0wLjEtMC4xLTAuMS0wLjFjLTAuMS0wLjItMC4yLTAuNC0wLjItMC43YzAtMC4zLDAuMS0wLjYsMC4zLTAuOGMwLDAsMC0wLjEsMC4xLTAuMWwwLjEtMC4xCgkJCWMwLjItMC4yLDAuNS0wLjMsMC44LTAuM2gwLjFjMC4xLDAsMC4yLDAsMC4yLDBjMCwwLDAsMCwwLjEsMGwwLDBjMC4xLDAsMC4yLDAsMC4yLDAuMWMwLDAsMCwwLDAuMSwwYzAuMiwwLjEsMC40LDAuMywwLjUsMC41CgkJCWMwLjIsMC4zLDAuMywwLjUsMC42LDAuOGMwLjIsMC4yLDAuNCwwLjQsMC43LDAuNWMwLjMsMC4yLDAuNiwwLjIsMC45LDAuMmMwLjYsMCwxLjItMC4yLDEuOC0wLjVjMC43LTAuMywxLjMtMC44LDEuNy0xLjQKCQkJYzAuNS0wLjcsMC45LTEuNSwxLjEtMi40YzAuMy0xLDAuNS0yLjEsMC40LTMuMWMwLTAuNy0wLjEtMS41LTAuMy0yLjJjLTAuMS0wLjUtMC40LTEtMC43LTEuNWMtMC4yLTAuMy0wLjUtMC42LTAuOS0wLjgKCQkJYy0wLjMtMC4yLTAuNi0wLjMtMC45LTAuM2MtMC43LDAuMS0xLjQsMC40LTEuOSwxYy0wLjgsMC43LTEuNCwxLjYtMiwyLjVjLTAuNiwxLTEuMywyLjEtMS45LDMuM3MtMS4yLDIuNC0xLjksMy41CgkJCWMwLDMuOC0wLjEsNy40LTAuMiwxMC45cy0wLjIsNi43LTAuNCw5LjZzLTAuNSw1LjYtMC44LDhjLTAuMywyLjEtMC44LDQuMS0xLjQsNi4xYy0wLjUsMS41LTEuMiwyLjgtMi4yLDMuOQoJCQljLTAuOCwwLjktMiwxLjQtMy4yLDEuNGMtMC41LDAtMC45LTAuMS0xLjQtMC4yYy0wLjYtMC4yLTEuMS0wLjUtMS40LTFjLTAuNS0wLjYtMC45LTEuNC0xLjEtMi4yYy0wLjMtMS4yLTAuNS0yLjUtMC41LTMuOAoJCQljMC0yLjMsMC4zLTQuNiwwLjctNi45YzAuNS0yLjgsMS4yLTUuNiwyLjEtOC4zYzAuOS0yLjksMi02LDMuMi05YzEuMi0zLjEsMi42LTYuMSw0LjEtOWMwLTAuOCwwLTEuNiwwLTIuNHMwLTEuNiwwLTIuMwoJCQljLTAuMywwLjMtMC43LDAuNS0xLjEsMC41Yy0wLjQsMC0wLjgtMC4xLTEtMC40Yy0wLjItMC4zLTAuMy0wLjYtMC4zLTAuOWMwLTAuMiwwLTAuNCwwLjEtMC41YzAuNS0xLjIsMS0yLjQsMS4zLTMuNwoJCQljMC4zLTEuMiwwLjUtMi4yLDAuNy0zLjFjMC4xLTAuNywwLjItMS40LDAuMy0yLjFjMC0wLjUsMC0wLjgsMC0wLjhjMC0wLjMsMC4xLTAuNywwLjQtMC45YzAuMy0wLjMsMC42LTAuNCwxLTAuNAoJCQljMC4zLDAsMC43LDAuMSwwLjksMC40YzAuMiwwLjIsMC40LDAuNiwwLjQsMC45djEwLjhjMC41LTAuOSwxLjEtMS44LDEuNi0yLjdjMC41LTAuOCwxLjEtMS42LDEuNy0yLjRjMC42LTAuNywxLjItMS4yLDEuOS0xLjcKCQkJYzAuNy0wLjQsMS41LTAuNywyLjMtMC43YzAuOCwwLDEuNSwwLjIsMi4yLDAuNmMwLjcsMC40LDEuMiwwLjksMS43LDEuNWMwLjUsMC43LDAuOSwxLjUsMS4xLDIuM0M4MywzOS41LDgzLjIsNDAuNSw4My4yLDQxLjV6CgkJCSBNNjcuNiw1Mi43Yy0xLjEsMi42LTIuMiw1LjItMy4xLDcuOGMtMC45LDIuNi0xLjcsNS4xLTIuNCw3LjVzLTEuMSw0LjctMS41LDYuOGMtMC4zLDEuOC0wLjUsMy43LTAuNSw1LjZjMCwwLjQsMCwwLjgsMC4xLDEuMwoJCQljMCwwLjQsMC4xLDAuOSwwLjMsMS4zYzAuMSwwLjQsMC4zLDAuNywwLjYsMWMwLjMsMC4zLDAuNiwwLjQsMSwwLjRjMC45LTAuMSwxLjctMC42LDItMS41YzAuNy0xLjIsMS4yLTIuNiwxLjUtMy45CgkJCWMwLjQtMS45LDAuOC0zLjgsMS01LjdjMC4yLTIuMSwwLjUtNC40LDAuNi02LjdzMC4zLTQuNywwLjMtNy4xUzY3LjUsNTQuOCw2Ny42LDUyLjd6Ii8+Cgk8L2c+CjwvZz4KPC9zdmc+Cg==',
                                'id' => $settings_page_id,
                                'menu_title' => 'IFWP',
                                'option_name' => str_replace('-', '_', $settings_page_id),
                                'page_title' => 'General Settings',
                                'revision' => true,
                                'style' => 'no-boxes',
                                'submenu_title' => 'General',
                                'submit_button' => 'Save General Settings',
                                'tabs' => [],
                                'tab_style' => 'left',
                            ];
                        } else {
                            self::$settings_pages[$settings_page_id] = [
								'capability' => 'manage_options',
                                'columns' => 1,
                                'id' => $settings_page_id,
                                'menu_title' => $settings_page,
                                'option_name' => str_replace('-', '_', $settings_page_id),
                                'page_title' => $settings_page . ' Settings',
                                'parent' => 'ifwp',
                                'revision' => true,
                                'style' => 'no-boxes',
                                'submit_button' => 'Save ' . $settings_page . ' Settings',
                                'tabs' => [],
                                'tab_style' => 'left',
                            ];
                        }
                    } elseif(is_array($settings_page)){
                        self::$settings_pages[$settings_page_id] = $settings_page;
                    }
                    ksort(self::$settings_pages);
                }
            }
            return $settings_page_id;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static protected function maybe_add_tab($settings_page_id = '', $tab = ''){
            if(!array_key_exists($settings_page_id, self::$tabs)){
                self::$tabs[$settings_page_id] = [];
            }
            if(!array_key_exists($settings_page_id, self::$meta_boxes)){
                self::$meta_boxes[$settings_page_id] = [];
            }
            $tab_id = self::get_tab_id($tab);
            if($tab_id){
                if(!array_key_exists($tab_id, self::$tabs[$settings_page_id])){
                    self::$tabs[$settings_page_id][$tab_id] = $tab;
                    ksort(self::$tabs[$settings_page_id]);
                }
                if(!array_key_exists($tab_id, self::$meta_boxes[$settings_page_id])){
                    self::$meta_boxes[$settings_page_id][$tab_id] = [
                        'fields' => [],
                        'id' => $settings_page_id . '-' . $tab_id,
                        'settings_pages' => $settings_page_id,
                        'tab' => $tab_id,
                        'title' => self::get_tab_title($tab),
                    ];
                }
            }
            return $tab_id;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static protected function maybe_add_field($settings_page_id = '', $tab_id = '', $args = []){
            if(empty($args['columns'])){
                $args['columns'] = 12;
            }
            if(array_key_exists($settings_page_id, self::$meta_boxes)){
               if(array_key_exists($tab_id, self::$meta_boxes[$settings_page_id])){
                    self::$meta_boxes[$settings_page_id][$tab_id]['fields'][] = $args;
                }
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function load(){
            add_filter('mb_settings_pages', [__CLASS__, 'mb_settings_pages']);
            add_filter('rwmb_meta_boxes', [__CLASS__, 'rwmb_meta_boxes']);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function mb_settings_pages($settings_pages){
            if(self::$settings_pages){
                $general_id = 'ifwp';
                if(array_key_exists($general_id, self::$settings_pages)){
                    $general = self::$settings_pages[$general_id];
                    unset(self::$settings_pages[$general_id]);
                    self::$settings_pages = array_merge([
                        $general_id => $general,
                    ], self::$settings_pages);
                }
                foreach(self::$settings_pages as $settings_page_id => $settings_page){
                    $empty = true;
                    if(array_key_exists($settings_page_id, self::$meta_boxes)){
                        foreach(self::$meta_boxes[$settings_page_id] as $meta_box){
                            if(!empty($meta_box['fields'])){
                                $empty = false;
                                break;
                            }
                        }
                    }
                    if(!$empty){
                        $tabs = self::$tabs[$settings_page_id];
                        $general_id = sanitize_title('General');
                        if(!empty($tabs[$general_id])){
                            $general = $tabs[$general_id];
                            unset($tabs[$general_id]);
                            $tabs = array_merge([
                                $general_id => $general,
                            ], $tabs);
                        }
                        foreach($tabs as $tab_id => $tab){
                            if(empty(self::$meta_boxes[$settings_page_id][$tab_id]['fields'])){
                                unset($tabs[$tab_id]);
                            }
                        }
                        $settings_page['tabs'] = $tabs;
                        $settings_pages[] = $settings_page;
                    }
                }
            }
            return $settings_pages;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function rwmb_meta_boxes($meta_boxes){
            if(is_admin()){
                if(self::$meta_boxes){
                    foreach(self::$meta_boxes as $tmp){
                        if($tmp){
                            foreach($tmp as $meta_box){
                                if(!empty($meta_box['fields'])){
                                    $meta_boxes[] = $meta_box;
                                }
                            }
                        }
                    }
                }
            }
            return $meta_boxes;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // dynamic public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public $debug_backtrace = [], $settings_page_id = '', $tab_id = '';

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function __construct($settings_page = 'General', $tab = 'General'){
            if(!$settings_page){
                $settings_page = 'General';
            }
            if(!$tab){
                $tab = 'General';
            }
			if(!is_array($tab)){
				$tab = [
					'label' => $tab,
					'icon' => 'dashicons-minus',
				];
			}
            $this->debug_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            $this->settings_page_id = self::maybe_add_settings_page($settings_page);
            $this->tab_id = self::maybe_add_tab($this->settings_page_id, $tab);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function __get($name = ''){
            return $this->get_option($name);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function add_field(){
            switch(func_num_args()){
                case 1:
                    $args = func_get_arg(0);
                    $id = !empty($args['id']) ? $args['id'] : '';
                    break;
                case 2:
                    $id = func_get_arg(0);
                    $args = func_get_arg(1);
                    break;
                default:
                    return;
            }
            if(!$id){
                $id = uniqid();
            }
            if(empty($args['name'])){
                $args['name'] = '';
            }
            $args['id'] = $this->tab_id . '_' . $id;
            return self::maybe_add_field($this->settings_page_id, $this->tab_id, $args);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function admin_url(){
            return admin_url('admin.php?page=' . $this->settings_page_id . '#tab-' . $this->tab_id);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function current_dir_path(){
            if(!empty($this->debug_backtrace[0]['file'])){
                return plugin_dir_path($this->debug_backtrace[0]['file']);
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function get_option($option = '', $default = false){
            $option = $this->tab_id . '_' . $option;
            $options = get_option(str_replace('-', '_', $this->settings_page_id));
            if(isset($options[$option])){
                return $options[$option];
            }
            return $default;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function is_current_screen(){
            if(is_admin()){
                $current_screen = get_current_screen();
                if($current_screen){
                    if($this->settings_page_id == 'ifwp'){
                        if($current_screen->id == 'toplevel_page_ifwp'){
                            return true;
                        }
                    } else {
                        if($current_screen->id == 'ifwp_page_' . $this->settings_page_id){
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// functions
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_tab')){
    function ifwp_tab($settings_page = '', $tab = ''){
        return new IFWP_Tabs($settings_page, $tab);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_tab_option')){
    function ifwp_tab_option($settings_page = '', $tab = '', $option = '', $default = false){
        $settings_page_id = IFWP_Tabs::get_settings_page_id($settings_page);
        $tab_id = IFWP_Tabs::get_tab_id($tab);
		$option = $tab_id . '_' . $option;
		$options = get_option(str_replace('-', '_', $settings_page_id));
        if(isset($options[$option])){
            return $options[$option];
        }
        return $default;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// loader
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IFWP_Tabs::load();
