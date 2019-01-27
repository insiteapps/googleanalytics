<?php

namespace InsiteApps\GoogleAnalytics {
    
    use SilverStripe\Control\Controller;
    use SilverStripe\Core\Extension;
    use SilverStripe\Dev\DevBuildController;
    use SilverStripe\Dev\DevelopmentAdmin;
    use SilverStripe\ORM\DatabaseAdmin;
    use SilverStripe\SiteConfig\SiteConfig;
    use SilverStripe\View\ArrayData;
    use SilverStripe\View\Requirements;
    
    class GoogleLiteLogger extends Extension
    {
        // the Google Analytics code to be used in the JS snippet or
        public static $google_analytics_code;
        
        public static function activate( $code = null )
        {
            
            switch ( $code ) {
                case null:
                    self::$google_analytics_code = null;
                    break;
                case 'SiteConfig':
                    SiteConfig::add_extension( GoogleAnalyticsLiteConfig::class );
                    break;
                default:
                    self::$google_analytics_code = $code;
            }
            
            Controller::add_extension( GoogleLiteLogger::class );
            
        }
        
        public function onAfterInit()
        {
            if ( $this->owner instanceof DevelopmentAdmin || $this->owner instanceof DatabaseAdmin || ( class_exists( DevBuildController::class ) && $this->owner instanceof DevBuildController ) ) {
                return;
            }
            
            // include the JS snippet into the frontend page markup
            if ( GoogleAnalyticsLiteConfig::get_google_config( 'code' ) ) {
                $code             = GoogleAnalyticsLiteConfig::get_google_config( 'code' );
                $SnippetPlacement = GoogleAnalyticsLiteConfig::get_google_config( 'placement' );
                $snippet          = new ArrayData( array(
                    'GoogleAnalyticsCode' => $code,
                ) );
                $snippetHtml      = $snippet->renderWith( 'GoogleAnalyticsLiteJSSnippet' );
                
                if ( $SnippetPlacement === 'Head' ) {
                    Requirements::insertHeadTags( sprintf( "<script type=\"text/javascript\">%s</script>", $snippetHtml->Value ) );
                } else {
                    Requirements::customScript( $snippetHtml );
                }
                
                
            }
            
        }
    }
    
}