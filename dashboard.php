<!DOCTYPE html>
<html lang="en" >

<head>
    <meta charset="UTF-8">
    <title>An Anonymous Pen on CodePen</title>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
    <style class="cp-pen-styles">@import url("https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700&subset=latin-ext");
</style>

<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>



<script>
"use strict";

var Dashboard = function () {
	var global = {
		tooltipOptions: {
			placement: "right"
		},
		menuClass: ".c-menu"
	};

	var menuChangeActive = function menuChangeActive(el) {
		var hasSubmenu = $(el).hasClass("has-submenu");
		$(global.menuClass + " .is-active").removeClass("is-active");
		$(el).addClass("is-active");

		// if (hasSubmenu) {
		// 	$(el).find("ul").slideDown();
		// }
	};

	var sidebarChangeWidth = function sidebarChangeWidth() {
		var $menuItemsTitle = $("li .menu-item__title");

		$("body").toggleClass("sidebar-is-reduced sidebar-is-expanded");
		$(".hamburger-toggle").toggleClass("is-opened");

		if ($("body").hasClass("sidebar-is-expanded")) {
			$('[data-toggle="tooltip"]').tooltip("destroy");
		} else {
			$('[data-toggle="tooltip"]').tooltip(global.tooltipOptions);
		}
	};

	return {
		init: function init() {
			$(".js-hamburger").on("click", sidebarChangeWidth);

			$(".js-menu li").on("click", function (e) {
				menuChangeActive(e.currentTarget);
			});

			$('[data-toggle="tooltip"]').tooltip(global.tooltipOptions);
		}
	};
}();

Dashboard.init();
//# sourceURL=pen.js
</script>
<style>

body {
    font-family: 'Roboto', sans-serif;
    font-weight: 400;
    background-color: #f0f3f5;
    margin-top:40px;
}
/*==============================*/
/*====== Recently connected  heading =====*/
/*==============================*/
.memberblock {
	width: 100%;
	float: left;
	clear: both;
	margin-bottom: 15px
}
.member {
	width: 24%;
	float: left;
	margin: 2px 1% 2px 0;
	background: #ffffff;
	border: 1px solid #d8d0c3;
	padding: 3px;
	position: relative;
	overflow: hidden
}
.memmbername {
	position: absolute;
	bottom: -30px;
	background: rgba(0, 0, 0, 0.8);
	color: #ffffff;
	line-height: 30px;
	padding: 0 5px;
	white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden;
	width: 100%;
	font-size: 11px;
	transition: 0.5s ease all;
}
.member:hover .memmbername {
	bottom: 0
}
.member img {
	width: 100%;
	transition: 0.5s ease all;
}
.member:hover img {
	opacity: 0.8;
	transform: scale(1.2)
}

.panel-default>.panel-heading {
    color: #607D8B;
    background-color: #ffffff;
    font-weight: 400;
    font-size: 15px;
    border-radius: 0;
    border-color: #e1eaef;
}

.page-header.small {
    position: relative;
    line-height: 22px;
    font-weight: 400;
    font-size: 20px;
}

.favorite i {
    color: #eb3147;
}

.btn i {
    font-size: 17px;
}

.panel {
    box-shadow: 0px 2px 10px 0 rgba(0, 0, 0, 0.05);
    -moz-box-shadow: 0px 2px 10px 0 rgba(0, 0, 0, 0.05);
    -webkit-box-shadow: 0px 2px 10px 0 rgba(0, 0, 0, 0.05);
    -ms-box-shadow: 0px 2px 10px 0 rgba(0, 0, 0, 0.05);
    transition: all ease 0.5s;
    -moz-transition: all ease 0.5s;
    -webkit-transition: all ease 0.5s;
    -ms-transition: all ease 0.5s;
    margin-bottom: 35px;
    border-radius: 0px;
    position: relative;
    border: 0;
    display: inline-block;
    width: 100%;
}
.pad { 
    padding-top:10px;
    padding-bottom:20px;
    padding-left:10px;
    padding-right:10px;
}


html, body {
  height: 100%;
  width: 100%;
}

body {
  margin: 0;
  padding: 0;
  font-family: "Open Sans";
  font-size: 14px;
  font-weight: 400;
  background-color: #ececec;
  color: #102c58;
}

* {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

::-webkit-input-placeholder {
  color: #c3c3c3;
}

h1 {
  font-size: 24px;
}

h2 {
  font-size: 20px;
}

h3 {
  font-size: 18px;
}

.u-list {
  margin: 0;
  padding: 0;
  list-style: none;
}

.u-input {
  outline: 0;
  border: 1px solid #d0d0d0;
  padding: 5px 10px;
  height: 35px;
  font-size: 12px;
  -webkit-border-radius: 10px;
  border-radius: 10px;
  background-clip: padding-box;
}

.c-badge {
  font-size: 10px;
  font-weight: 700;
  min-width: 17px;
  padding: 5px 4px;
  border-radius: 100px;
  display: block;
  line-height: 0.7;
  color: #fff;
  text-align: center;
  white-space: nowrap;
  background-color: #f91605;
}
.c-badge--header-icon {
  position: absolute;
  bottom: -9px;
}

.tooltip {
  width: 120px;
}
.tooltip-inner {
  padding: 8px 10px;
  color: #fff;
  text-align: center;
  background-color: #051835;
  font-size: 12px;
  border-radius: 3px;
}
.tooltip-arrow {
  border-right-color: #051835 !important;
}

.hamburger-toggle {
  position: relative;
  padding: 0;
  background: transparent;
  border: 1px solid transparent;
  cursor: pointer;
  order: 1;
}
.hamburger-toggle [class*='bar-'] {
  display: block;
  background: #102c58;
  -webkit-transform: rotate(0deg);
  transform: rotate(0deg);
  -webkit-transition: .2s ease all;
  transition: .2s ease all;
  border-radius: 2px;
  height: 2px;
  width: 24px;
  margin-bottom: 4px;
}
.hamburger-toggle [class*='bar-']:nth-child(2) {
  width: 18px;
}
.hamburger-toggle [class*='bar-']:last-child {
  margin-bottom: 0;
  width: 12px;
}
.hamburger-toggle.is-opened {
  left: 3px;
}
.hamburger-toggle.is-opened [class*='bar-'] {
  background: #102c58;
}
.hamburger-toggle.is-opened .bar-top {
  -webkit-transform: rotate(45deg);
  transform: rotate(45deg);
  -webkit-transform-origin: 15% 15%;
  transform-origin: 15% 15%;
}
.hamburger-toggle.is-opened .bar-mid {
  opacity: 0;
}
.hamburger-toggle.is-opened .bar-bot {
  -webkit-transform: rotate(45deg);
  transform: rotate(-45deg);
  -webkit-transform-origin: 15% 95%;
  transform-origin: 15% 95%;
  width: 24px;
}
.hamburger-toggle:focus {
  outline-width: 0;
}
.hamburger-toggle:hover [class*='bar-'] {
  background: #f5642d;
}

.header-icons-group {
  display: flex;
  order: 3;
  margin-left: auto;
  height: 100%;
  border-left: 1px solid #cccccc;
}
.header-icons-group .c-header-icon:last-child {
  border-right: 0;
}

.c-header-icon {
  position: relative;
  display: flex;
  float: left;
  width: 70px;
  height: 100%;
  align-items: center;
  justify-content: center;
  line-height: 1;
  cursor: pointer;
  border-right: 1px solid #cccccc;
}
.c-header-icon i {
  font-size: 18px;
  line-height: 40px;
}
.c-header-icon--in-circle {
  border: 1px solid #d0d0d0;
  border-radius: 100%;
}
.c-header-icon:hover i {
  color: #f5642d;
}

.l-header {
  padding-left: 70px;
  position: fixed;
  top: 0;
  right: 0;
  z-index: 10;
  width: 100%;
  background: #ffffff;
  -webkit-transition: padding 0.5s ease-in-out;
  -moz-transition: padding 0.5s ease-in-out;
  -ms-transition: padding 0.5s ease-in-out;
  -o-transition: padding 0.5s ease-in-out;
  transition: padding 0.5s ease-in-out;
}
.l-header__inner {
  height: 100%;
  width: 100%;
  display: flex;
  height: 70px;
  align-items: center;
  justify-content: stretch;
  border-bottom: 1px solid;
  border-color: #cccccc;
}
.sidebar-is-expanded .l-header {
  padding-left: 220px;
}

.c-search {
  display: flex;
  height: 100%;
  width: 350px;
}
.c-search__input {
  border-top-right-radius: 0px;
  border-bottom-right-radius: 0px;
  border-right: 0;
  flex-basis: 100%;
  height: 100%;
  border: 0;
  font-size: 14px;
  padding: 0 20px;
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -ms-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}

.c-dropdown {
  opacity: 0;
  text-align: left;
  position: absolute;
  flex-direction: column;
  display: none;
  width: 300px;
  top: 30px;
  right: -40px;
  background-color: #fff;
  overflow: hidden;
  min-height: 300px;
  border: 1px solid #d0d0d0;
  -webkit-border-radius: 10px;
  border-radius: 10px;
  background-clip: padding-box;
  -webkit-box-shadow: 0px 5px 14px -1px #cecece;
  -moz-box-shadow: 0px 5px 14px -1px #cecece;
  box-shadow: 0px 5px 14px -1px #cecece;
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -ms-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}

.l-sidebar {
  width: 70px;
  position: absolute;
  z-index: 10;
  left: 0;
  top: 0;
  bottom: 0;
  background: #102c58;
  -webkit-transition: width 0.5s ease-in-out;
  -moz-transition: width 0.5s ease-in-out;
  -ms-transition: width 0.5s ease-in-out;
  -o-transition: width 0.5s ease-in-out;
  transition: width 0.5s ease-in-out;
}
.l-sidebar .logo {
  width: 100%;
  height: 70px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #051835;
}
.l-sidebar .logo .logo__txt {
  font-size: 26px;
  line-height: 1;
  color: #fff;
  text-align: center;
  font-weight: 700;
}
.l-sidebar__content {
  height: 100%;
  position: relative;
}
.sidebar-is-expanded .l-sidebar {
  width: 220px;
}

.c-menu > ul {
  display: flex;
  flex-direction: column;
}
.c-menu > ul .c-menu__item {
  color: #fff;
  max-width: 100%;
  overflow: hidden;
}
.c-menu > ul .c-menu__item__inner {
  display: flex;
  flex-direction: row;
  align-items: center;
  min-height: 60px;
  position: relative;
  cursor: pointer;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
}
.c-menu > ul .c-menu__item__inner:before {
  position: absolute;
  content: " ";
  height: 0;
  width: 2px;
  left: 0;
  top: 50%;
  margin-top: -18px;
  background-color: #5f9cfd;
  opacity: 0;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
}
.c-menu > ul .c-menu__item.is-active .c-menu__item__inner {
  border-left-color: #5f9cfd;
  background-color: #1e3e6f;
}
.c-menu > ul .c-menu__item.is-active .c-menu__item__inner i {
  color: #5f9cfd;
}
.c-menu > ul .c-menu__item.is-active .c-menu__item__inner .c-menu-item__title span {
  color: #5f9cfd;
}
.c-menu > ul .c-menu__item.is-active .c-menu__item__inner:before {
  height: 36px;
  opacity: 1;
}
.c-menu > ul .c-menu__item:not(.is-active):hover .c-menu__item__inner {
  background-color: #f5642d;
  border-left-color: #f5642d;
}
.c-menu > ul .c-menu__item i {
  flex: 0 0 70px;
  font-size: 18px;
  font-weight: normal;
  text-align: center;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
}
.c-menu > ul .c-menu__item .c-menu-item__expand {
  position: relative;
  left: 100px;
  padding-right: 20px;
  margin-left: auto;
  -webkit-transition: all 1s ease-in-out;
  -moz-transition: all 1s ease-in-out;
  -ms-transition: all 1s ease-in-out;
  -o-transition: all 1s ease-in-out;
  transition: all 1s ease-in-out;
}
.sidebar-is-expanded .c-menu > ul .c-menu__item .c-menu-item__expand {
  left: 0px;
}
.c-menu > ul .c-menu__item .c-menu-item__title {
  flex-basis: 100%;
  padding-right: 10px;
  position: relative;
  left: 220px;
  opacity: 0;
  -webkit-transition: all 0.7s ease-in-out;
  -moz-transition: all 0.7s ease-in-out;
  -ms-transition: all 0.7s ease-in-out;
  -o-transition: all 0.7s ease-in-out;
  transition: all 0.7s ease-in-out;
}
.c-menu > ul .c-menu__item .c-menu-item__title span {
  font-weight: 400;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
}
.sidebar-is-expanded .c-menu > ul .c-menu__item .c-menu-item__title {
  left: 0px;
  opacity: 1;
}
.c-menu > ul .c-menu__item .c-menu__submenu {
  background-color: #051835;
  padding: 15px;
  font-size: 12px;
  display: none;
}
.c-menu > ul .c-menu__item .c-menu__submenu li {
  padding-bottom: 15px;
  margin-bottom: 15px;
  border-bottom: 1px solid;
  border-color: #072048;
  color: #5f9cfd;
}
.c-menu > ul .c-menu__item .c-menu__submenu li:last-child {
  margin: 0;
  padding: 0;
  border: 0;
}

main.l-main {
  width: 100%;
  height: 100%;
  padding: 70px 0 0 70px;
  -webkit-transition: padding 0.5s ease-in-out;
  -moz-transition: padding 0.5s ease-in-out;
  -ms-transition: padding 0.5s ease-in-out;
  -o-transition: padding 0.5s ease-in-out;
  transition: padding 0.5s ease-in-out;
}
main.l-main .content-wrapper {
  padding: 25px;
  height: 100%;
}
main.l-main .content-wrapper .page-content {
  border-top: 1px solid #d0d0d0;
  padding-top: 25px;
}
main.l-main .content-wrapper--with-bg .page-content {
  background: #fff;
  border-radius: 3px;
  border: 1px solid #d0d0d0;
  padding: 25px;
}
main.l-main .page-title {
  font-weight: 400;
  margin-top: 0;
  margin-bottom: 25px;
}
.sidebar-is-expanded main.l-main {
  padding-left: 220px;
}

a.list-group-item {
    height:auto;
    min-height:120px;
}
a.list-group-item.active small {
    color:#fff;
}
</style>




</head>



<body class="sidebar-is-reduced">
  <header class="l-header">
    <div class="l-header__inner clearfix">
      <div class="c-header-icon js-hamburger">
        <div class="hamburger-toggle"><span class="bar-top"></span><span class="bar-mid"></span><span class="bar-bot"></span></div>
      </div>
      <div class="c-header-icon has-dropdown"><span class="c-badge c-badge--header-icon animated shake">12</span><i class="fa fa-bell"></i>
        <div class="c-dropdown c-dropdown--notifications">
          <div class="c-dropdown__header"></div>
          <div class="c-dropdown__content"></div>
        </div>
      </div>
      <div class="c-search">
        <input class="c-search__input u-input" placeholder="Search..." type="text"/>
      </div>
      <div class="header-icons-group">
        <div class="c-header-icon logout"><i class="fa fa-power-off"></i></div>
      </div>
    </div>
  </header>
  <div class="l-sidebar">
    <div class="logo">
      <div class="logo__txt">O</div>
    </div>
    <div class="l-sidebar__content">
      <nav class="c-menu js-menu">
        <ul class="u-list">
          <li class="c-menu__item is-active" data-toggle="tooltip" title="Proposals">
            <div class="c-menu__item__inner"><i class="fa fa-file-text-o"></i>
              <div class="c-menu-item__title"><span>Proposals</span></div>
            </div>
          </li>
          <li class="c-menu__item has-submenu" data-toggle="tooltip" title="History">
            <div class="c-menu__item__inner"><i class="fa fa-history"></i>
              <div class="c-menu-item__title"><span>History</span></div>
            </div>
          </li>
          <li class="c-menu__item has-submenu" data-toggle="tooltip" title="Accounts">
            <div class="c-menu__item__inner"><i class="fa fa-address-book-o"></i>
              <div class="c-menu-item__title"><span>Accounts</span></div>
            </div>
          </li>
          <li class="c-menu__item has-submenu" data-toggle="tooltip" title="Settings">
            <div class="c-menu__item__inner"><i class="fa fa-cogs"></i>
              <div class="c-menu-item__title"><span>Settings</span></div>
            </div>
          </li>
        </ul>
      </nav>
    </div>
  </div>
<main class="l-main">
  <div class="content-wrapper content-wrapper--with-bg">
    
        <div class="list-group">
        <a href="#" class="list-group-item">
            <div class="media col-md-3">
                <figure class="pull-left">
                    <img class="media-object img-rounded img-responsive" src="http://placehold.it/140x100" alt="placehold.it/140x100" >
                </figure>
            </div>
            <div class="col-md-5">
                <h4 class="list-group-item-heading pb-3"> Olympic </h4>
                <p class="list-group-item-text"> Proposal to implement best in class enterprise permissioning system to manage signature accumulation and hierarchy management</p>
            </div>
            <div class="col-md-3 pull-left">
                <div class = "container col-md-12">
                    <div class = "row">
                        <div class="col-md-12 pull-left"><i class="fa fa-check-square"></i> General Manager</div>
                    </div>
                    <div class = "row">
                        <div class="col-md-12 pull-left"><i class="fa fa-check-square"></i> Project Manager</div>
                    </div>
                    <div class = "row">
                        <div class="col-md-12 pull-left"><i class="fa fa-square"></i> Tech Lead</div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 text-center">
                <p> 2 <small> approvals </small></p>
                <button type="button" class="btn btn-primary btn-sm btn-block">Open</button>
            </div>
        </a>
        <a href="#" class="list-group-item">
            <div class="media col-md-3">
                <figure class="pull-left">
                    <img class="media-object img-rounded img-responsive" src="http://placehold.it/140x100" alt="placehold.it/140x100" >
                </figure>
            </div>
            <div class="col-md-5">
                <h4 class="list-group-item-heading pb-3"> Olympic </h4>
                <p class="list-group-item-text"> Proposal to implement best in class enterprise permissioning system to manage signature accumulation and hierarchy management</p>
            </div>
            <div class="col-md-3 pull-left">
                <div class = "container col-md-12">
                    <div class = "row">
                        <div class="col-md-12 pull-left"><i class="fa fa-check-square"></i> General Manager</div>
                    </div>
                    <div class = "row">
                        <div class="col-md-12 pull-left"><i class="fa fa-check-square"></i> Project Manager</div>
                    </div>
                    <div class = "row">
                        <div class="col-md-12 pull-left"><i class="fa fa-square"></i> Tech Lead</div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 text-center">
                <p> 2 <small> approvals </small></p>
                <button type="button" class="btn btn-primary btn-sm btn-block">Open</button>
            </div>
        </a>
        <a href="#" class="list-group-item">
            <div class="media col-md-3">
                <figure class="pull-left">
                    <img class="media-object img-rounded img-responsive" src="http://placehold.it/140x100" alt="placehold.it/140x100" >
                </figure>
            </div>
            <div class="col-md-5">
                <h4 class="list-group-item-heading pb-3"> Olympic </h4>
                <p class="list-group-item-text"> Proposal to implement best in class enterprise permissioning system to manage signature accumulation and hierarchy management</p>
            </div>
            <div class="col-md-3 pull-left">
                <div class = "container col-md-12">
                    <div class = "row">
                        <div class="col-md-12 pull-left"><i class="fa fa-check-square"></i> General Manager</div>
                    </div>
                    <div class = "row">
                        <div class="col-md-12 pull-left"><i class="fa fa-check-square"></i> Project Manager</div>
                    </div>
                    <div class = "row">
                        <div class="col-md-12 pull-left"><i class="fa fa-square"></i> Tech Lead</div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 text-center">
                <p> 2 <small> approvals </small></p>
                <button type="button" class="btn btn-primary btn-sm btn-block">Open</button>
            </div>
        </a>
    </div>
    
  </div>
</main>
<script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script><script src='https://use.fontawesome.com/2188c74ac9.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>

</body>
</html>