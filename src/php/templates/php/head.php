<?php

HOMEPAGE or exit(0);

use function TorresDeveloper\MVC\baseurl;

?>

<meta charset="utf-8" />
<title>PAP</title>

<!-- <base href="" target="" /> -->

<!--
<link rel="alternate" type="application/atom+xml" href="posts.xml" title="Cool Stuff Blog">
<link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.csszengarden.com/zengarden.xml">

<link rel=alternate href="/en/html" hreflang=en type=text/html title="English HTML">
<link rel=alternate href="/fr/html" hreflang=fr type=text/html title="French HTML">
<link rel=alternate href="/en/html/print" hreflang=en type=text/html media=print title="English HTML (for printing)">
<link rel=alternate href="/fr/html/print" hreflang=fr type=text/html media=print title="French HTML (for printing)">
<link rel=alternate href="/en/pdf" hreflang=en type=application/pdf title="English PDF">
<link rel=alternate href="/fr/pdf" hreflang=fr type=application/pdf title="French PDF">

<link rel="dns-prefetch" href="//example.com">
<link rel="preconnect" href="//example.com">
<link rel="preconnect" href="//cdn.example.com" crossorigin>
<link rel="prefetch" href="//example.com/next-page.html" as="document" crossorigin="use-credentials">
<link rel="prefetch" href="/library.js" as="script">
<link rel="prerender" href="//example.com/next-page.html">

<link rel="icon" type="image/x-icon" href="/favicon.ico" />
<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
<link rel="mask-icon" href="favicon.svg" color="red" />
<link rel="icon" type="image/vnd.microsoft.icon" href="https://example.com/image.ico" />
<link rel="shortcut icon" href="https://example.com/myicon.ico" />
<link rel=icon href=favicon.png sizes="16x16" type="image/png">
<link rel=icon href=windows.ico sizes="32x32 48x48" type="image/vnd.microsoft.icon">
<link rel=icon href=mac.icns sizes="128x128 512x512 8192x8192 32768x32768">
<link rel=icon href=iphone.png sizes="57x57" type="image/png">
<link rel=icon href=gnome.svg sizes="any" type="image/svg+xml">
-->

<!-- Make this files be in /
apple-touch-icon-180x180-precomposed.png
apple-touch-icon-152x152-precomposed.png
apple-touch-icon-144x144-precomposed.png
apple-touch-icon-120x120-precomposed.png
apple-touch-icon-114x114-precomposed.png
apple-touch-icon-76x76-precomposed.png
apple-touch-icon-72x72-precomposed.png
apple-touch-icon-precomposed.png
the same ones but without -precomposed
-->

<!--
<link rel="manifest" href="manifest.webmanifest">

<link rel="author" href="/about">
<link rel="license" href="/about">
-->

<!--[if gt IE 8]><!--><link href="<?=baseurl("assets/css/style.css?v=13aug2022")?>" rel="stylesheet" title="Default Style" /><!--<![endif]-->
<!--[if lte IE 8]><link href="<?=baseurl("assets/css/legacy.css?v=13aug2022")?>" rel="stylesheet" title="Default Style Legacy" /><![endif]-->

<link href="<?=baseurl("assets/css/legacy.css?v=13aug2022")?>" rel="alternate stylesheet" title="Default Style Legacy" />
<!-- <link href="fancy.css" rel="stylesheet" title="Fancy" /> -->
<!-- <link href="basic.css" rel="alternate stylesheet" title="Basic" /> -->

<!-- <link rel="modulepreload" href="fog-machine.mjs"> -->

<meta content="width=device-width, initial-scale=1" name="viewport" />
<!-- <meta name="application-name" content="" /> -->
<!-- <meta name="application-name" content="" lang="" /> -->
<meta name="author" content="" />
<meta name="description" content="" />
<!-- <meta name="generator" content="" /> -->
<meta name="keywords" content="" />
<!-- <meta name="referrer" content="" /> -->
<meta name="theme-color" content="" />
<!-- <meta name="theme-color" content="" media="(prefers-color-scheme: dark)" /> -->
<meta name="color-scheme" content="light" />
<!-- <meta http-equiv="default-style" content="" /> -->
<meta http-equiv="x-ua-compatible" content="IE=edge" />
<!-- <meta http-equiv="content-security-policy" content="" /> -->

<meta name="apple-mobile-web-app-title" content="The base HTML for a lot of files" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<!-- https://ogp.me/ -->
<!-- https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/markup -->
<!-- https://microformats.org/wiki/existing-rel-values#HTML5_link_type_extensions -->
<!-- https://wiki.whatwg.org/wiki/MetaExtensions -->

<!--
VIA SERVER Pragma directives - http-equiv

State   Keyword Notes
Content Language    content-language    Non-conforming
Encoding declaration    content-type
Default style   default-style
Refresh refresh
Set-Cookie  set-cookie  Non-conforming
X-UA-Compatible x-ua-compatible
Content security policy content-security-policy
-->

<meta name="date" content="2022-12-20" />

<meta name="robots" content="all" />

<!--[if lt IE 9]>
<script src="script/html5shiv.js"></script>
<![endif]-->

<!--<link rel="preload" as="image" type="image/webp" href="/logo.webp">-->
<script defer type="module" src="<?=baseurl("assets/js/webp-in-css/polyfill.js")?>"></script>
<script defer type="module" src="https://unpkg.com/avif-in-css/polyfill.js"></script>
