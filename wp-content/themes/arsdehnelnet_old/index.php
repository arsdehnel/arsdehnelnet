<?php get_header(); ?>
	    <section class="blog col-2-3">
			{% block content %}
				<p>If you see me, you haven’t set your <code>{% verbatim %}{% block content %}…{% endblock %}{% endverbatim %}</code> yet.</p>
				<article class="entry">
					<h1>Developing My First Responsive Site</h1>
					<p>Below is the story of me developing my first fully-responsive site (this one).  All the learnings, trials and general thoughts regarding it.</p>
					<h2>Starting From Scratch</h4>
					<p>I started from scratch. Not just where you have some guides but no colors or something &mdash; completely from scratch.</p> 
				</article>
				<nav class="nav-blog cf">
					<a href="#">&larr; Previous Entry<span>(Becoming a Front-End Developer)</span></a>
					<a href="#">Blog Archives</a>
					<a href="#">Next Entry &rarr;</a>
				</nav>
			{% endblock %}
	    </section>
	    <aside class="col-1-3 social-media social-media-github">
	      <header>
	        <a href="#" class="cf">
	          <h2 class="social-site">www.twitter.com</h2>
	          <img src="https://g.twimg.com/Twitter_logo_blue.png" />
	          <span class="social-username">@arsdehnel_wdwrk</span>
	        </a>
	      </header>
	      <article class="entry">
	        <h3 class="entry-label">12/01/13 12:17PM</h3>
	        <div class="entry-content">apple dumplings, biscuits and homemade gravy.  all before 9 am. 
	  #winning</div>
	      </article>
	      <article class="entry">
	        <h3 class="entry-label">11/29/13 4:45AM</h3>
	        <div class="entry-content">apple dumplings, biscuits and homemade gravy.  all before 9 am. 
	  #winning</div>
	      </article>
	      <article class="entry entry-more">
	        <a href="#">more&rarr;</a>
	      </article>
	      <header>
	        <a href="#" class="cf">
	          <h2 class="social-site">www.github.com</h2>
	          <img src="http://www.iconsdb.com/icons/preview/caribbean-blue/github-11-xxl.png" />
	          <span class="social-username">arsdehnel</span>
	        </a>
	      </header>
	      <article class="entry">
	        <h3 class="entry-label">arsdehnel.net</h3>
	        <div class="entry-content">the repo for this site</div>
	      </article>
	    </aside>
<?php get_footer(); ?>