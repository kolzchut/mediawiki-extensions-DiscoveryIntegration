<?php


class DiscoveryIntegrationHooks {

	static function addBeforeSection( Parser $parser, &$sectionContent, $targetSectionNames ) {
		$sectionID = self::getSectionID( $sectionContent );
		$isLastSection = ( $sectionContent === HideMetadataSectionHooks::METADATA_CONTENT );

		// If this is the correct section, OR we didn't find the correct section yet and reached
		// a fallback section, or we ran into the final section
		if ( $isLastSection || in_array( $sectionID, $targetSectionNames ) ) {
			// Directly insert the <discovery> tag
			$sectionContent = DiscoveryHooks::renderTagDiscovery( '', [], $parser ) . $sectionContent;
			$parser->getOutput()->setExtensionData( 'DiscoveryIntegrationAdded', true );
		}
	}

	static function getSectionID( $sectionContent ) {
		$matches = [];
		$result = preg_match_all( '/id="(.*?)"/i', $sectionContent, $matches );

		if ( $result !== 0 && isset( $matches[1] ) && isset( $matches[1][1] ) ) {
			return $matches[1][1];
		}

		// Not found
		return null;
	}

	static function isCorrectSection( $sectionName, $sectionContent ) {
		return $sectionName && stripos( $sectionContent, 'id="' . $sectionName . '"' ) !== false;
	}

	public static function onParserSectionCreate(
		Parser $parser, $section, &$sectionContent, $showEditLinks
	) {
		$title = $parser->getTitle();
		$articleType = $parser->getOutput()->getExtensionData( 'ArticleType' );
		$alreadyAdded = $parser->getOutput()->getExtensionData( 'DiscoveryIntegrationAdded' );


		if ( $alreadyAdded || empty( $articleType )
			|| $section === 0 || $title->getNamespace() !== NS_MAIN
		) {
			return true;
		};

		switch ( $articleType ) {
			case 'portal':
			case 'term':
			case 'right':
			case 'proceeding':
				self::addBeforeSection( $parser, $sectionContent,
					[ 'פסקי_דין', 'חקיקה_ונהלים', 'הרחבות_ופרסומים', 'תודות' ]
				);
				break;
			case 'service':
				self::addBeforeSection( $parser, $sectionContent,
					[ 'הרחבות_ופרסומים', 'תודות' ]
				);
				break;
			case 'health':
				self::addBeforeSection( $parser, $sectionContent, [ 'מידע_נוסף' ] );
				break;
			case 'ruling':
			case 'law':
				self::addBeforeSection( $parser, $sectionContent,
					[ 'חקיקה_ונהלים', 'פסקי_דין', 'הרחבות_ופרסומים', 'תודות' ]
				);
				break;
		}

		return true;
	}

}
