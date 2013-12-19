<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
	<meta charset="utf-8" />
	<title>{% if title is defined %}{{ title }} - {% endif %}{{ siteName }}</title>
	<link rel="home" href="{{ siteUrl }}" />
	<link rel="stylesheet" type="text/css" href="/wp-content/themes/arsdehnelnet/styles/css/style.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<?php wp_head(); ?>
</head>

<body>
	<header id="site-header">
	  <h1>Adam Dehnel</h1>
	  <div>father, developer, cook and woodworker</div>
	</header>
	<?php 
		echo wp_arsdehnelnet_menu( 'main' );
	?> 
	<!--	
	<nav class="nav-main">
	  <a href="#home" class="home" data-section-id="home">
	    Home
	  </a>
	  <a href="#web" class="web active" data-section-id="web">
	    Web
	  </a>
	  <a href="#food" class="food" data-section-id="food">
	    Food 
	  </a> 
	  <a href="#wood" class="wood" data-section-id="wood">
	    Wood
	  </a>
	  <a href="#about" class="about" data-section-id="about"> 
	    About 
	  </a>
	  <a href="#secured" class="secured" data-section-id="secured">
	    Secured
	  </a>
	</nav>
	-->
	<main class="web nav-sub-exists" role="main">
	  <nav class="nav-sub">
	    <a href="#">Something else</a><a href="#">Something  else</a><a href="#">Something else</a><a href="#">Something else</a><a href="#" class="nav-sub-pull">&#9655;</a>
	  </nav>
	  <div class="content grid">
