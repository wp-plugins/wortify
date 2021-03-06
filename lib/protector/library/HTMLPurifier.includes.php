<?php

/**
 * @file
 * This file was auto-generated by generate-includes.php and includes all of
 * the core files included by HTML Purifier. Use this if performance is a
 * primary concern and you are using an opcode cache. PLEASE DO NOT EDIT THIS
 * FILE, changes will be overwritten the next time the script is run.
 *
 * @version 4.4.0
 *
 * @warning
 *      You must *not* include any other HTML Purifier files before this file,
 *      because 'include' not 'include_once' is used.
 *
 * @warning
 *      This file includes that the include path contains the HTML Purifier
 *      library directory; this is not auto-set.
 */

include 'HTMLPurifier.php';
include 'HTMLPurifier/AttrCollections.php';
include 'HTMLPurifier/AttrDef.php';
include 'HTMLPurifier/AttrTransform.php';
include 'HTMLPurifier/AttrTypes.php';
include 'HTMLPurifier/AttrValidator.php';
include 'HTMLPurifier/Bootstrap.php';
include 'HTMLPurifier/Definition.php';
include 'HTMLPurifier/CSSDefinition.php';
include 'HTMLPurifier/ChildDef.php';
include 'HTMLPurifier/Config.php';
include 'HTMLPurifier/ConfigSchema.php';
include 'HTMLPurifier/ContentSets.php';
include 'HTMLPurifier/Context.php';
include 'HTMLPurifier/DefinitionCache.php';
include 'HTMLPurifier/DefinitionCacheFactory.php';
include 'HTMLPurifier/Doctype.php';
include 'HTMLPurifier/DoctypeRegistry.php';
include 'HTMLPurifier/ElementDef.php';
include 'HTMLPurifier/Encoder.php';
include 'HTMLPurifier/EntityLookup.php';
include 'HTMLPurifier/EntityParser.php';
include 'HTMLPurifier/ErrorCollector.php';
include 'HTMLPurifier/ErrorStruct.php';
include 'HTMLPurifier/Exception.php';
include 'HTMLPurifier/Filter.php';
include 'HTMLPurifier/Generator.php';
include 'HTMLPurifier/HTMLDefinition.php';
include 'HTMLPurifier/HTMLModule.php';
include 'HTMLPurifier/HTMLModuleManager.php';
include 'HTMLPurifier/IDAccumulator.php';
include 'HTMLPurifier/Injector.php';
include 'HTMLPurifier/Language.php';
include 'HTMLPurifier/LanguageFactory.php';
include 'HTMLPurifier/Length.php';
include 'HTMLPurifier/Lexer.php';
include 'HTMLPurifier/PercentEncoder.php';
include 'HTMLPurifier/PropertyList.php';
include 'HTMLPurifier/PropertyListIterator.php';
include 'HTMLPurifier/Strategy.php';
include 'HTMLPurifier/StringHash.php';
include 'HTMLPurifier/StringHashParser.php';
include 'HTMLPurifier/TagTransform.php';
include 'HTMLPurifier/Token.php';
include 'HTMLPurifier/TokenFactory.php';
include 'HTMLPurifier/URI.php';
include 'HTMLPurifier/URIDefinition.php';
include 'HTMLPurifier/URIFilter.php';
include 'HTMLPurifier/URIParser.php';
include 'HTMLPurifier/URIScheme.php';
include 'HTMLPurifier/URISchemeRegistry.php';
include 'HTMLPurifier/UnitConverter.php';
include 'HTMLPurifier/VarParser.php';
include 'HTMLPurifier/VarParserException.php';
include 'HTMLPurifier/AttrDef/CSS.php';
include 'HTMLPurifier/AttrDef/Clone.php';
include 'HTMLPurifier/AttrDef/Enum.php';
include 'HTMLPurifier/AttrDef/Integer.php';
include 'HTMLPurifier/AttrDef/Lang.php';
include 'HTMLPurifier/AttrDef/Switch.php';
include 'HTMLPurifier/AttrDef/Text.php';
include 'HTMLPurifier/AttrDef/URI.php';
include 'HTMLPurifier/AttrDef/CSS/Number.php';
include 'HTMLPurifier/AttrDef/CSS/AlphaValue.php';
include 'HTMLPurifier/AttrDef/CSS/Background.php';
include 'HTMLPurifier/AttrDef/CSS/BackgroundPosition.php';
include 'HTMLPurifier/AttrDef/CSS/Border.php';
include 'HTMLPurifier/AttrDef/CSS/Color.php';
include 'HTMLPurifier/AttrDef/CSS/Composite.php';
include 'HTMLPurifier/AttrDef/CSS/DenyElementDecorator.php';
include 'HTMLPurifier/AttrDef/CSS/Filter.php';
include 'HTMLPurifier/AttrDef/CSS/Font.php';
include 'HTMLPurifier/AttrDef/CSS/FontFamily.php';
include 'HTMLPurifier/AttrDef/CSS/Ident.php';
include 'HTMLPurifier/AttrDef/CSS/ImportantDecorator.php';
include 'HTMLPurifier/AttrDef/CSS/Length.php';
include 'HTMLPurifier/AttrDef/CSS/ListStyle.php';
include 'HTMLPurifier/AttrDef/CSS/Multiple.php';
include 'HTMLPurifier/AttrDef/CSS/Percentage.php';
include 'HTMLPurifier/AttrDef/CSS/TextDecoration.php';
include 'HTMLPurifier/AttrDef/CSS/URI.php';
include 'HTMLPurifier/AttrDef/HTML/Bool.php';
include 'HTMLPurifier/AttrDef/HTML/Nmtokens.php';
include 'HTMLPurifier/AttrDef/HTML/Class.php';
include 'HTMLPurifier/AttrDef/HTML/Color.php';
include 'HTMLPurifier/AttrDef/HTML/FrameTarget.php';
include 'HTMLPurifier/AttrDef/HTML/ID.php';
include 'HTMLPurifier/AttrDef/HTML/Pixels.php';
include 'HTMLPurifier/AttrDef/HTML/Length.php';
include 'HTMLPurifier/AttrDef/HTML/LinkTypes.php';
include 'HTMLPurifier/AttrDef/HTML/MultiLength.php';
include 'HTMLPurifier/AttrDef/URI/Email.php';
include 'HTMLPurifier/AttrDef/URI/Host.php';
include 'HTMLPurifier/AttrDef/URI/IPv4.php';
include 'HTMLPurifier/AttrDef/URI/IPv6.php';
include 'HTMLPurifier/AttrDef/URI/Email/SimpleCheck.php';
include 'HTMLPurifier/AttrTransform/Background.php';
include 'HTMLPurifier/AttrTransform/BdoDir.php';
include 'HTMLPurifier/AttrTransform/BgColor.php';
include 'HTMLPurifier/AttrTransform/BoolToCSS.php';
include 'HTMLPurifier/AttrTransform/Border.php';
include 'HTMLPurifier/AttrTransform/EnumToCSS.php';
include 'HTMLPurifier/AttrTransform/ImgRequired.php';
include 'HTMLPurifier/AttrTransform/ImgSpace.php';
include 'HTMLPurifier/AttrTransform/Input.php';
include 'HTMLPurifier/AttrTransform/Lang.php';
include 'HTMLPurifier/AttrTransform/Length.php';
include 'HTMLPurifier/AttrTransform/Name.php';
include 'HTMLPurifier/AttrTransform/NameSync.php';
include 'HTMLPurifier/AttrTransform/Nofollow.php';
include 'HTMLPurifier/AttrTransform/SafeEmbed.php';
include 'HTMLPurifier/AttrTransform/SafeObject.php';
include 'HTMLPurifier/AttrTransform/SafeParam.php';
include 'HTMLPurifier/AttrTransform/ScriptRequired.php';
include 'HTMLPurifier/AttrTransform/TargetBlank.php';
include 'HTMLPurifier/AttrTransform/Textarea.php';
include 'HTMLPurifier/ChildDef/Chameleon.php';
include 'HTMLPurifier/ChildDef/Custom.php';
include 'HTMLPurifier/ChildDef/Empty.php';
include 'HTMLPurifier/ChildDef/List.php';
include 'HTMLPurifier/ChildDef/Required.php';
include 'HTMLPurifier/ChildDef/Optional.php';
include 'HTMLPurifier/ChildDef/StrictBlockquote.php';
include 'HTMLPurifier/ChildDef/Table.php';
include 'HTMLPurifier/DefinitionCache/Decorator.php';
include 'HTMLPurifier/DefinitionCache/Null.php';
include 'HTMLPurifier/DefinitionCache/Serializer.php';
include 'HTMLPurifier/DefinitionCache/Decorator/Cleanup.php';
include 'HTMLPurifier/DefinitionCache/Decorator/Memory.php';
include 'HTMLPurifier/HTMLModule/Bdo.php';
include 'HTMLPurifier/HTMLModule/CommonAttributes.php';
include 'HTMLPurifier/HTMLModule/Edit.php';
include 'HTMLPurifier/HTMLModule/Forms.php';
include 'HTMLPurifier/HTMLModule/Hypertext.php';
include 'HTMLPurifier/HTMLModule/Iframe.php';
include 'HTMLPurifier/HTMLModule/Image.php';
include 'HTMLPurifier/HTMLModule/Legacy.php';
include 'HTMLPurifier/HTMLModule/List.php';
include 'HTMLPurifier/HTMLModule/Name.php';
include 'HTMLPurifier/HTMLModule/Nofollow.php';
include 'HTMLPurifier/HTMLModule/NonXMLCommonAttributes.php';
include 'HTMLPurifier/HTMLModule/Object.php';
include 'HTMLPurifier/HTMLModule/Presentation.php';
include 'HTMLPurifier/HTMLModule/Proprietary.php';
include 'HTMLPurifier/HTMLModule/Ruby.php';
include 'HTMLPurifier/HTMLModule/SafeEmbed.php';
include 'HTMLPurifier/HTMLModule/SafeObject.php';
include 'HTMLPurifier/HTMLModule/Scripting.php';
include 'HTMLPurifier/HTMLModule/StyleAttribute.php';
include 'HTMLPurifier/HTMLModule/Tables.php';
include 'HTMLPurifier/HTMLModule/Target.php';
include 'HTMLPurifier/HTMLModule/TargetBlank.php';
include 'HTMLPurifier/HTMLModule/Text.php';
include 'HTMLPurifier/HTMLModule/Tidy.php';
include 'HTMLPurifier/HTMLModule/XMLCommonAttributes.php';
include 'HTMLPurifier/HTMLModule/Tidy/Name.php';
include 'HTMLPurifier/HTMLModule/Tidy/Proprietary.php';
include 'HTMLPurifier/HTMLModule/Tidy/XHTMLAndHTML4.php';
include 'HTMLPurifier/HTMLModule/Tidy/Strict.php';
include 'HTMLPurifier/HTMLModule/Tidy/Transitional.php';
include 'HTMLPurifier/HTMLModule/Tidy/XHTML.php';
include 'HTMLPurifier/Injector/AutoParagraph.php';
include 'HTMLPurifier/Injector/DisplayLinkURI.php';
include 'HTMLPurifier/Injector/Linkify.php';
include 'HTMLPurifier/Injector/PurifierLinkify.php';
include 'HTMLPurifier/Injector/RemoveEmpty.php';
include 'HTMLPurifier/Injector/RemoveSpansWithoutAttributes.php';
include 'HTMLPurifier/Injector/SafeObject.php';
include 'HTMLPurifier/Lexer/DOMLex.php';
include 'HTMLPurifier/Lexer/DirectLex.php';
include 'HTMLPurifier/Strategy/Composite.php';
include 'HTMLPurifier/Strategy/Core.php';
include 'HTMLPurifier/Strategy/FixNesting.php';
include 'HTMLPurifier/Strategy/MakeWellFormed.php';
include 'HTMLPurifier/Strategy/RemoveForeignElements.php';
include 'HTMLPurifier/Strategy/ValidateAttributes.php';
include 'HTMLPurifier/TagTransform/Font.php';
include 'HTMLPurifier/TagTransform/Simple.php';
include 'HTMLPurifier/Token/Comment.php';
include 'HTMLPurifier/Token/Tag.php';
include 'HTMLPurifier/Token/Empty.php';
include 'HTMLPurifier/Token/End.php';
include 'HTMLPurifier/Token/Start.php';
include 'HTMLPurifier/Token/Text.php';
include 'HTMLPurifier/URIFilter/DisableExternal.php';
include 'HTMLPurifier/URIFilter/DisableExternalResources.php';
include 'HTMLPurifier/URIFilter/DisableResources.php';
include 'HTMLPurifier/URIFilter/HostBlacklist.php';
include 'HTMLPurifier/URIFilter/MakeAbsolute.php';
include 'HTMLPurifier/URIFilter/Munge.php';
include 'HTMLPurifier/URIFilter/SafeIframe.php';
include 'HTMLPurifier/URIScheme/data.php';
include 'HTMLPurifier/URIScheme/file.php';
include 'HTMLPurifier/URIScheme/ftp.php';
include 'HTMLPurifier/URIScheme/http.php';
include 'HTMLPurifier/URIScheme/https.php';
include 'HTMLPurifier/URIScheme/mailto.php';
include 'HTMLPurifier/URIScheme/news.php';
include 'HTMLPurifier/URIScheme/nntp.php';
include 'HTMLPurifier/VarParser/Flexible.php';
include 'HTMLPurifier/VarParser/Native.php';
