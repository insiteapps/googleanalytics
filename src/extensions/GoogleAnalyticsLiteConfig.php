<?php

/**
 * @package       googleanalytics-lite
 * @author        Patrick Chitovoro
 * @copyright (c) Chito Systems 2015 - 2019
 */

namespace InsiteApps\GoogleAnalytics {
    
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\OptionsetField;
    use SilverStripe\Forms\Tab;
    use SilverStripe\Forms\TextField;
    use SilverStripe\ORM\DataExtension;
    use SilverStripe\SiteConfig\SiteConfig;
    
    class GoogleAnalyticsLiteConfig extends DataExtension
    {
        
        private static $db = array(
            'GoogleAnalyticsLiteCode' => 'Varchar',
            'SnippetPlacement'        => 'Enum("Footer,Head","Footer")',
        );
        
        public function updateCMSFields( FieldList $fields )
        {
            
            //$fields->addFieldToTab( 'Root', new Tab( 'GoogleAnalyticsLite' ) );
            $fields->addFieldsToTab( 'Root.GoogleAnalyticsLite', array(
                TextField::create( 'GoogleAnalyticsLiteCode', 'Google Analytics Code' )
                         ->setRightTitle( '(UA-XXXXXX-X)' ),
                OptionsetField::create( 'SnippetPlacement', 'Google analytics snippet placement' )
                              ->setSource( $this->owner->dbObject( 'SnippetPlacement' )->enumValues() ),
            ) );
            
        }
        
        /**
         * Return various configuration values
         *
         * @param $key
         *
         * @return bool
         */
        public static function get_google_config( $key )
        {
            if ( class_exists( SiteConfig::class ) && SiteConfig::has_extension( 'GoogleAnalyticsLiteConfig' ) ) {
                $config = SiteConfig::current_site_config();
                switch ( $key ) {
                    case 'code':
                        return $config->GoogleAnalyticsLiteCode ? $config->GoogleAnalyticsLiteCode : false;
                    case 'placement':
                        return $config->SnippetPlacement ? $config->SnippetPlacement : false;
                }
                
            }
            
            return false;
            
            
        }
    }
}