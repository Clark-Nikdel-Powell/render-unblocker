#Render Unblocker

A WordPress plugin to optimize stylesheet and script delivery.

* Stops writing of script tags and loads scripts after page render.
* Adds `preload` attribute to stylesheet link tags.
* Inlines critical CSS

##Filters

`no_kill_scripts` • Use this filter to make a script tag output in the normal fashion. Use the script handle.
 
 `noscript_stylesheet_links` • Add additional stylesheet link tags for output in noscript tag in `head`.

`critical_css_path` • Defaults to critical.css in the theme root. Use this filter to override this default.
