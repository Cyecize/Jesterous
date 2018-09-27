Jesterous.net - A Blog website
========================

Visit
--------------
https://jesterous.net/
--------------
https://jesterous.net/?lang=en

Features
--------------

The main functionalities are:

  * Multi language support

  * Users can view articles, quotes, rate them, share, subscribe to authors, leave comments and replies;

  * Authors can post article, monitor views, send messages to subscribers and more;
  
  * Enhanced text editor for creating beautiful blog posts


Technologies used
---------------------
	
Front end
	
	* HTML, CSS, Js, jQuery, Bootstrap, OwlCarousel, fontAwesome, Quill Text editor

Framework
	
	* Symfony framework 3.4 with php7.2
	

Want to run the app?
---------------------
	
Steps: 
	
	* Git pull
	
	* run: php (7.1.9 at least) composer.phar install
	
	* set up proper smtp settings
	
	* doctrine:schema:update --force
	
	* When you open the website, the db will be completely empty. 
	
	* Register the first account and that will trigger the first run service, which will 
	        create the initial roles, categories, languages and such..
	
	* Ready to go!