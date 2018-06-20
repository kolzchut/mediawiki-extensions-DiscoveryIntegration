<?php


class DiscoveryIntegrationHooks {

	static function addBeforeSection( $sectionName, &$sectionContent, Parser $parser ) {
		if ( stripos( $sectionContent, 'id="' . $sectionName . '"' ) !== false ) {
			// Directly insert the <discovery> tag
			$sectionContent = DiscoveryHooks::renderTagDiscovery( '', [], $parser ) . $sectionContent;
		}
	}

	public static function onParserSectionCreate( Parser $parser, $section, &$sectionContent, $showEditLinks ) {
		if ( $section === 0 || $parser->getTitle()->isSpecialPage() === true ) {
			return true;
		};

		$articleType = $parser->getOutput()->getExtensionData( 'ArticleType' );
		if ( empty( $articleType ) ) {
			return true;
		};

		switch ( $articleType ) {
			case 'portal':
			case 'term':
			case 'right':
			case 'proceeding':
				self::addBeforeSection( 'פסקי_דין', $sectionContent, $parser );
				break;
			case 'service':
				self::addBeforeSection( 'הרחבות_ופרסומים', $sectionContent, $parser );
				break;
			case 'health':
				self::addBeforeSection( 'מידע_נוסף', $sectionContent, $parser );
				break;
			case 'ruling':
			case 'law':
				self::addBeforeSection( 'חקיקה_ונהלים', $sectionContent, $parser );
				break;
		}

		return true;
	}

}
