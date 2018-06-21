<?php


class DiscoveryIntegrationHooks {

	static function addBeforeSection(
		Parser $parser, &$sectionContent, $targetSectionName, $fallbackSectionName = null
	) {
		static $added = false;
		$isRightSection = self::isCorrectSection( $targetSectionName, $sectionContent );
		$isFallbackSection = self::isCorrectSection( $fallbackSectionName, $sectionContent );

		// If this is the correct section, OR we didn't find the correct section yet and reached
		// The fallback section, or we ran into the final section
		if ( $isRightSection
		     || ( $isFallbackSection && $added === false )
		     || $sectionContent === HideMetadataSectionHooks::METADATA_CONTENT
		) {
			// Directly insert the <discovery> tag
			$sectionContent = DiscoveryHooks::renderTagDiscovery( '', [], $parser ) . $sectionContent;
		}
	}

	static function isCorrectSection( $sectionName, $sectionContent ) {
		return $sectionName && stripos( $sectionContent, 'id="' . $sectionName . '"' ) !== false;
	}

	public static function onParserSectionCreate(
		Parser $parser, $section, &$sectionContent, $showEditLinks
	) {
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
				self::addBeforeSection( $parser, $sectionContent, 'פסקי_דין' );
				break;
			case 'service':
				self::addBeforeSection( $parser, $sectionContent, 'הרחבות_ופרסומים' );
				break;
			case 'health':
				self::addBeforeSection( $parser, $sectionContent, 'מידע_נוסף' );
				break;
			case 'ruling':
			case 'law':
				self::addBeforeSection( $parser, $sectionContent, 'חקיקה_ונהלים' );
				break;
		}

		return true;
	}

}
