# Discovery

## Purpose

The purpose of this extension is to automatically add the discovery
component (Extension:Discovery) to Kol-Zchut articles, by inserting it
before certain headings.

## Please note
- This extension assumes the setting of `$wgExperimentalHtmlIds = true;`
  which makes every section have two IDs, one escaped and one Unicode.
  Starting with MediaWiki 1.30.0 this is deprecated, and Unicode IDs
  should be the default - and this extension will need to be adjusted.
- This extension must be loaded **after** extension:HideMetadataSection.

## Configuration
None.

## Todo
- Make the headings configurable?
