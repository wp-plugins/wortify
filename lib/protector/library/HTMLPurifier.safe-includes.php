<?php

/**
 * @file
 * This file was auto-generated by generate-includes.php and includes all of
 * the core files included by HTML Purifier. This is a convenience stub that
 * includes all files using dirname(__FILE__) and include_once. PLEASE DO NOT
 * EDIT THIS FILE, changes will be overwritten the next time the script is run.
 *
 * Changes to include_path are not necessary.
 */

$__dir = dirname(__FILE__);

include_once $__dir . '/HTMLPurifier.php';
include_once $__dir . '/HTMLPurifier/AttrCollections.php';
include_once $__dir . '/HTMLPurifier/AttrDef.php';
include_once $__dir . '/HTMLPurifier/AttrTransform.php';
include_once $__dir . '/HTMLPurifier/AttrTypes.php';
include_once $__dir . '/HTMLPurifier/AttrValidator.php';
include_once $__dir . '/HTMLPurifier/Bootstrap.php';
include_once $__dir . '/HTMLPurifier/Definition.php';
include_once $__dir . '/HTMLPurifier/CSSDefinition.php';
include_once $__dir . '/HTMLPurifier/ChildDef.php';
include_once $__dir . '/HTMLPurifier/Config.php';
include_once $__dir . '/HTMLPurifier/ConfigSchema.php';
include_once $__dir . '/HTMLPurifier/ContentSets.php';
include_once $__dir . '/HTMLPurifier/Context.php';
include_once $__dir . '/HTMLPurifier/DefinitionCache.php';
include_once $__dir . '/HTMLPurifier/DefinitionCacheFactory.php';
include_once $__dir . '/HTMLPurifier/Doctype.php';
include_once $__dir . '/HTMLPurifier/DoctypeRegistry.php';
include_once $__dir . '/HTMLPurifier/ElementDef.php';
include_once $__dir . '/HTMLPurifier/Encoder.php';
include_once $__dir . '/HTMLPurifier/EntityLookup.php';
include_once $__dir . '/HTMLPurifier/EntityParser.php';
include_once $__dir . '/HTMLPurifier/ErrorCollector.php';
include_once $__dir . '/HTMLPurifier/ErrorStruct.php';
include_once $__dir . '/HTMLPurifier/Exception.php';
include_once $__dir . '/HTMLPurifier/Filter.php';
include_once $__dir . '/HTMLPurifier/Generator.php';
include_once $__dir . '/HTMLPurifier/HTMLDefinition.php';
include_once $__dir . '/HTMLPurifier/HTMLModule.php';
include_once $__dir . '/HTMLPurifier/HTMLModuleManager.php';
include_once $__dir . '/HTMLPurifier/IDAccumulator.php';
include_once $__dir . '/HTMLPurifier/Injector.php';
include_once $__dir . '/HTMLPurifier/Language.php';
include_once $__dir . '/HTMLPurifier/LanguageFactory.php';
include_once $__dir . '/HTMLPurifier/Length.php';
include_once $__dir . '/HTMLPurifier/Lexer.php';
include_once $__dir . '/HTMLPurifier/PercentEncoder.php';
include_once $__dir . '/HTMLPurifier/PropertyList.php';
include_once $__dir . '/HTMLPurifier/PropertyListIterator.php';
include_once $__dir . '/HTMLPurifier/Strategy.php';
include_once $__dir . '/HTMLPurifier/StringHash.php';
include_once $__dir . '/HTMLPurifier/StringHashParser.php';
include_once $__dir . '/HTMLPurifier/TagTransform.php';
include_once $__dir . '/HTMLPurifier/Token.php';
include_once $__dir . '/HTMLPurifier/TokenFactory.php';
include_once $__dir . '/HTMLPurifier/URI.php';
include_once $__dir . '/HTMLPurifier/URIDefinition.php';
include_once $__dir . '/HTMLPurifier/URIFilter.php';
include_once $__dir . '/HTMLPurifier/URIParser.php';
include_once $__dir . '/HTMLPurifier/URIScheme.php';
include_once $__dir . '/HTMLPurifier/URISchemeRegistry.php';
include_once $__dir . '/HTMLPurifier/UnitConverter.php';
include_once $__dir . '/HTMLPurifier/VarParser.php';
include_once $__dir . '/HTMLPurifier/VarParserException.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS.php';
include_once $__dir . '/HTMLPurifier/AttrDef/Clone.php';
include_once $__dir . '/HTMLPurifier/AttrDef/Enum.php';
include_once $__dir . '/HTMLPurifier/AttrDef/Integer.php';
include_once $__dir . '/HTMLPurifier/AttrDef/Lang.php';
include_once $__dir . '/HTMLPurifier/AttrDef/Switch.php';
include_once $__dir . '/HTMLPurifier/AttrDef/Text.php';
include_once $__dir . '/HTMLPurifier/AttrDef/URI.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Number.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/AlphaValue.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Background.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/BackgroundPosition.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Border.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Color.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Composite.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/DenyElementDecorator.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Filter.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Font.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/FontFamily.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Ident.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/ImportantDecorator.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Length.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/ListStyle.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Multiple.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/Percentage.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/TextDecoration.php';
include_once $__dir . '/HTMLPurifier/AttrDef/CSS/URI.php';
include_once $__dir . '/HTMLPurifier/AttrDef/HTML/Bool.php';
include_once $__dir . '/HTMLPurifier/AttrDef/HTML/Nmtokens.php';
include_once $__dir . '/HTMLPurifier/AttrDef/HTML/Class.php';
include_once $__dir . '/HTMLPurifier/AttrDef/HTML/Color.php';
include_once $__dir . '/HTMLPurifier/AttrDef/HTML/FrameTarget.php';
include_once $__dir . '/HTMLPurifier/AttrDef/HTML/ID.php';
include_once $__dir . '/HTMLPurifier/AttrDef/HTML/Pixels.php';
include_once $__dir . '/HTMLPurifier/AttrDef/HTML/Length.php';
include_once $__dir . '/HTMLPurifier/AttrDef/HTML/LinkTypes.php';
include_once $__dir . '/HTMLPurifier/AttrDef/HTML/MultiLength.php';
include_once $__dir . '/HTMLPurifier/AttrDef/URI/Email.php';
include_once $__dir . '/HTMLPurifier/AttrDef/URI/Host.php';
include_once $__dir . '/HTMLPurifier/AttrDef/URI/IPv4.php';
include_once $__dir . '/HTMLPurifier/AttrDef/URI/IPv6.php';
include_once $__dir . '/HTMLPurifier/AttrDef/URI/Email/SimpleCheck.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/Background.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/BdoDir.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/BgColor.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/BoolToCSS.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/Border.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/EnumToCSS.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/ImgRequired.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/ImgSpace.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/Input.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/Lang.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/Length.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/Name.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/NameSync.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/Nofollow.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/SafeEmbed.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/SafeObject.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/SafeParam.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/ScriptRequired.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/TargetBlank.php';
include_once $__dir . '/HTMLPurifier/AttrTransform/Textarea.php';
include_once $__dir . '/HTMLPurifier/ChildDef/Chameleon.php';
include_once $__dir . '/HTMLPurifier/ChildDef/Custom.php';
include_once $__dir . '/HTMLPurifier/ChildDef/Empty.php';
include_once $__dir . '/HTMLPurifier/ChildDef/List.php';
include_once $__dir . '/HTMLPurifier/ChildDef/Required.php';
include_once $__dir . '/HTMLPurifier/ChildDef/Optional.php';
include_once $__dir . '/HTMLPurifier/ChildDef/StrictBlockquote.php';
include_once $__dir . '/HTMLPurifier/ChildDef/Table.php';
include_once $__dir . '/HTMLPurifier/DefinitionCache/Decorator.php';
include_once $__dir . '/HTMLPurifier/DefinitionCache/Null.php';
include_once $__dir . '/HTMLPurifier/DefinitionCache/Serializer.php';
include_once $__dir . '/HTMLPurifier/DefinitionCache/Decorator/Cleanup.php';
include_once $__dir . '/HTMLPurifier/DefinitionCache/Decorator/Memory.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Bdo.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/CommonAttributes.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Edit.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Forms.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Hypertext.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Iframe.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Image.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Legacy.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/List.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Name.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Nofollow.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/NonXMLCommonAttributes.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Object.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Presentation.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Proprietary.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Ruby.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/SafeEmbed.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/SafeObject.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Scripting.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/StyleAttribute.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Tables.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Target.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/TargetBlank.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Text.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Tidy.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/XMLCommonAttributes.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Tidy/Name.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Tidy/Proprietary.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Tidy/XHTMLAndHTML4.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Tidy/Strict.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Tidy/Transitional.php';
include_once $__dir . '/HTMLPurifier/HTMLModule/Tidy/XHTML.php';
include_once $__dir . '/HTMLPurifier/Injector/AutoParagraph.php';
include_once $__dir . '/HTMLPurifier/Injector/DisplayLinkURI.php';
include_once $__dir . '/HTMLPurifier/Injector/Linkify.php';
include_once $__dir . '/HTMLPurifier/Injector/PurifierLinkify.php';
include_once $__dir . '/HTMLPurifier/Injector/RemoveEmpty.php';
include_once $__dir . '/HTMLPurifier/Injector/RemoveSpansWithoutAttributes.php';
include_once $__dir . '/HTMLPurifier/Injector/SafeObject.php';
include_once $__dir . '/HTMLPurifier/Lexer/DOMLex.php';
include_once $__dir . '/HTMLPurifier/Lexer/DirectLex.php';
include_once $__dir . '/HTMLPurifier/Strategy/Composite.php';
include_once $__dir . '/HTMLPurifier/Strategy/Core.php';
include_once $__dir . '/HTMLPurifier/Strategy/FixNesting.php';
include_once $__dir . '/HTMLPurifier/Strategy/MakeWellFormed.php';
include_once $__dir . '/HTMLPurifier/Strategy/RemoveForeignElements.php';
include_once $__dir . '/HTMLPurifier/Strategy/ValidateAttributes.php';
include_once $__dir . '/HTMLPurifier/TagTransform/Font.php';
include_once $__dir . '/HTMLPurifier/TagTransform/Simple.php';
include_once $__dir . '/HTMLPurifier/Token/Comment.php';
include_once $__dir . '/HTMLPurifier/Token/Tag.php';
include_once $__dir . '/HTMLPurifier/Token/Empty.php';
include_once $__dir . '/HTMLPurifier/Token/End.php';
include_once $__dir . '/HTMLPurifier/Token/Start.php';
include_once $__dir . '/HTMLPurifier/Token/Text.php';
include_once $__dir . '/HTMLPurifier/URIFilter/DisableExternal.php';
include_once $__dir . '/HTMLPurifier/URIFilter/DisableExternalResources.php';
include_once $__dir . '/HTMLPurifier/URIFilter/DisableResources.php';
include_once $__dir . '/HTMLPurifier/URIFilter/HostBlacklist.php';
include_once $__dir . '/HTMLPurifier/URIFilter/MakeAbsolute.php';
include_once $__dir . '/HTMLPurifier/URIFilter/Munge.php';
include_once $__dir . '/HTMLPurifier/URIFilter/SafeIframe.php';
include_once $__dir . '/HTMLPurifier/URIScheme/data.php';
include_once $__dir . '/HTMLPurifier/URIScheme/file.php';
include_once $__dir . '/HTMLPurifier/URIScheme/ftp.php';
include_once $__dir . '/HTMLPurifier/URIScheme/http.php';
include_once $__dir . '/HTMLPurifier/URIScheme/https.php';
include_once $__dir . '/HTMLPurifier/URIScheme/mailto.php';
include_once $__dir . '/HTMLPurifier/URIScheme/news.php';
include_once $__dir . '/HTMLPurifier/URIScheme/nntp.php';
include_once $__dir . '/HTMLPurifier/VarParser/Flexible.php';
include_once $__dir . '/HTMLPurifier/VarParser/Native.php';
