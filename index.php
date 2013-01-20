<?php

ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', 2);

ob_start ();
session_start ();
setlocale ( LC_CTYPE, "ru_RU.CP1251" );

include_once ("translit.inc");
include ("ewupload.php");
require 'config/connect.php';
require 'config/config.php';
require 'functions/functions.php';
include 'api.watermark.php';

@mysql_connect ( DB_LOCATION, DB_USER, DB_PASSWORD ) or die ( 'Сервер базы данных недоступен' );

@mysql_select_db ( DB_NAME ) or die ( 'В настоящий момент база данных не доступна' );

mysql_query('set character set cp1251');

if(!$_SESSION ['sms_id_region']){

  $str = file_get_contents("http://ipgeo-base.ru/?address=".$_SERVER['REMOTE_ADDR']);
	preg_match("/Регион<\/td>\s*<td>([^<]+)/"
	,$str, $region);
	$query_region = "SELECT ID FROM AUTO_REGION WHERE REGION = '".$region[1]."'";

	$result_region = mysql_query($query_region);
	$myrow_region = mysql_fetch_array($result_region);
	$region_id = $myrow_region['ID'];

	//если не определили регион, то выставляем регион по умолчанию
	if(!$region_id) $region_id = 1;
	$_SESSION ['sms_id_region'] = $region_id;

}

// Содержимое html-тега title

$pageTitle = FORUM_TITLE;

ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', 2);

// Если пользователь не авторизован, но выставлена

// cookie-переменная autologin - входим на форум

if (! isset ( $_SESSION ['user'] ) and isset ( $_COOKIE ['autologin'] ))

	autoLogin ();



// Эта функция выполняется при каждом просмотре страницы зарегистрированным

// пользователем и устанавливает время последнего посещения форума

if (isset ( $_SESSION ['user'] ))

	setTimeVisit ();

if (! isset ( $_GET ['action'] ))

	$_GET ['action'] = 'showMainPage';

//sparesCats

$actions = array ('bannerAdd','bannerAddSubmit','bannerEdit','bannerEditSubmit','bannerList','blogAdd','blogAddSubmit','blogEdit','blogEditSubmit','blogList','rubAdd','rubAddSubmit','rubEdit','rubEditSubmit','rubList','modelAdd','modelAddSubmit','modelEdit','modelEditSubmit','modelList','markAdd','markAddSubmit','markEdit','markEditSubmit','markList','markAdd','colorAdd','colorAddSubmit','colorEdit','colorEditSubmit','colorList','dvigAdd','dvigAddSubmit','dvigEdit','dvigEditSubmit','dvigList','kuzAdd','kuzAddSubmit','kuzEdit','kuzEditSubmit','kuzList','sostAdd','sostAddSubmit','sostEdit','sostEditSubmit','sostList','regionEdit','regionEditSubmit','cityEdit','cityEditSubmit','cityList','cityAdd','cityAddSubmit','regionList','regionAdd','regionAddSubmit','allNews','addAutosaloonAdm','addUserAdm','addAutosaloon','addUser','userList','autosaloonList','PrintSaloon','addNewUser','addNewUserSubmit','addChooseSubmit','addChoose','priceSpares', 'addNews','addNewsSubmit','editNews','editNewsSubmit','myNews','chinfoAct','chinfo','editCarSubmit', 'editSparesSubmit', 'myCar', 'editCar', 'mySpares', 'editSpares','chpassAct','chpass', 'searchSpares', 'PrintSpares', 'showSpares', 'addSparesSubmit', 'addSpares', 'sparesCats', 'searchView', 'showMainPage', 'add', 'quickReply', 'loginForm', 'login', 'logout', 'activateUser', 'newPasswordForm', 'sendNewPassword', 'activatePassword', 'editUserForm', 'updateUser','mark_list','sendMsgForm', 'sendMessage', 'showMsgBox', 'Reklama', 'showInBox', 'showOutBox', 'Print', 'Blog', 'Contact', 'Back', 'sendBack', 'showMsg', 'sendMailForm', 'sendMail', 'redirect', 'searchForm', 'ShowCar', 'searchResult', 'News', 'addCar', 'addCarSubmit', 'SaloonByID', 'saveView', 'SaloonCount', 'price', 'AutoNews', 'showSaloon', 'addNewSaloon', 'addNewSaloonForm', 'SaloonCity', 'showSearch', 'showSearchForm', 'showSearchAdv', 'showSearchFormAdv', 'ModelView', 'MarkView', 'ListView','selectCity' );

if (! in_array ( $_GET ['action'], $actions ))

	$_GET ['action'] = 'showMainPage';

switch ($_GET ['action']) {

	case 'showMainPage' : // главная страница форума

		$content = getMainPage ( $pageTitle );

		break;

	case 'quickReply' : // добавить новую запись в таблицу БД TABLE_POSTS

		$content = quickReply ();

		break;
	case 'mark_list' : // добавить новую запись в таблицу БД TABLE_POSTS

		$content = mark_list ();

		break;


	case 'PrintSpares' :

		$print=1;

		$content = showSpares (1);

		break;

	case 'PrintSaloon' :

		$print=1;

		$content = SaloonByID (1);

		break;

	case 'searchSpares' :

		$content = searchSpares ();

		break;
		//
	case 'mySpares' :

		$content = getMySparesCats ();

		break;

	case 'addNewUser' :

		$content = getAddNewUserForm ();

		break;

	case 'addNewUserSubmit' :

		$content = addNewUser ();

		break;

	case 'addChoose' :

		$content = addChoose ();

		break;

	case 'addChooseSubmit' :

		$content = addChooseSubmit ();

		break;


	case 'editSpares' :

		$content = editSpares ();

		break;

	case 'editSparesSubmit' :

		$content = editSparesSubmit ();

		break;

	case 'myCar' :

		$content = getMyCar ();

		break;

	case 'addUser' :

		$content = getaddUser ();

		break;

	case 'addAutosaloon' :

		$content = getaddAutosaloon ();

		break;

	case 'addUserAdm' :

		$content = addUser ();

		break;

	case 'addAutosaloonAdm' :

		$content = addAutosaloon ();

		break;

	case 'userList' :

		$content = getuserList ();

		break;

	case 'autosaloonList' :

		$content = getautosaloonList ();

		break;

	case 'sostList' :

		$content = sostList ();

		break;

	case 'sostAdd' :

		$content = sostAdd ();

		break;

	case 'sostAddSubmit' :

		$content = sostAddSubmit ();

		break;

	case 'sostEdit' :

		$content = sostEdit ();

		break;

	case 'sostEditSubmit' :

		$content = sostEditSubmit ();

		break;

	case 'colorList' :

		$content = colorList ();

		break;

	case 'colorAdd' :

		$content = colorAdd ();

		break;

	case 'colorAddSubmit' :

		$content = colorAddSubmit ();

		break;

	case 'colorEdit' :

		$content = colorEdit ();

		break;

	case 'colorEditSubmit' :

		$content = colorEditSubmit ();

		break;

	case 'rubList' :

		$content = rubList ();

		break;

	case 'rubAdd' :

		$content = rubAdd ();

		break;

	case 'rubAddSubmit' :

		$content = rubAddSubmit ();

		break;

	case 'rubEdit' :

		$content = rubEdit ();

		break;

	case 'rubEditSubmit' :

		$content = rubEditSubmit ();

		break;

	case 'blogList' :

		$content = blogList ();

		break;

	case 'blogAdd' :

		$content = blogAdd ();

		break;

	case 'blogAddSubmit' :

		$content = blogAddSubmit ();

		break;

	case 'blogEdit' :

		$content = blogEdit ();

		break;

	case 'blogEditSubmit' :

		$content = blogEditSubmit ();

		break;

	case 'bannerList' :

		$content = bannerList ();

		break;

	case 'bannerAdd' :

		$content = bannerAdd ();

		break;

	case 'bannerAddSubmit' :

		$content = bannerAddSubmit ();

		break;

	case 'bannerEdit' :

		$content = bannerEdit ();

		break;

	case 'bannerEditSubmit' :

		$content = bannerEditSubmit ();

		break;

	case 'dvigList' :

		$content = dvigList ();

		break;

	case 'dvigAdd' :

		$content = dvigAdd ();

		break;

	case 'dvigAddSubmit' :

		$content = dvigAddSubmit ();

		break;

	case 'dvigEdit' :

		$content = dvigEdit ();

		break;

	case 'dvigEditSubmit' :

		$content = dvigEditSubmit ();

		break;

	case 'kuzList' :

		$content = kuzList ();

		break;

	case 'kuzAdd' :

		$content = kuzAdd ();

		break;

	case 'kuzAddSubmit' :

		$content = kuzAddSubmit ();

		break;

	case 'kuzEdit' :

		$content = kuzEdit ();

		break;

	case 'kuzEditSubmit' :

		$content = kuzEditSubmit ();

		break;

	case 'modelList' :

		$content = modelList ();

		break;

	case 'modelAdd' :

		$content = modelAdd ();

		break;

	case 'modelAddSubmit' :

		$content = modelAddSubmit ();

		break;

	case 'modelEdit' :

		$content = modelEdit ();

		break;

	case 'modelEditSubmit' :

		$content = modelEditSubmit ();

		break;

	case 'markList' :

		$content = markList ();

		break;

	case 'markAdd' :

		$content = markAdd ();

		break;

	case 'markAddSubmit' :

		$content = markAddSubmit ();

		break;

	case 'markEdit' :

		$content = markEdit ();

		break;

	case 'markEditSubmit' :

		$content = markEditSubmit ();

		break;

	case 'regionList' :

		$content = regionList ();

		break;

	case 'regionAdd' :

		$content = regionAdd ();

		break;

	case 'regionAddSubmit' :

		$content = regionAddSubmit ();

		break;

	case 'regionEdit' :

		$content = regionEdit ();

		break;

	case 'regionEditSubmit' :

		$content = regionEditSubmit ();

		break;

	case 'cityList' :

		$content = cityList ();

		break;

	case 'cityAdd' :

		$content = cityAdd ();

		break;

	case 'cityAddSubmit' :

		$content = cityAddSubmit ();

		break;

	case 'cityEdit' :

		$content = cityEdit ();

		break;

	case 'cityEditSubmit' :

		$content = cityEditSubmit ();

		break;

	case 'editCar' :

		$content = editCar ();

		break;

	case 'editCarSubmit' :

		$content = editCarSubmit ();

		break;

	case 'myNews' :

		$content = getMyNews ();

		break;

	case 'allNews' :

		$content = getAllNews ();

		break;

	case 'editNews' :

		$content = editNews ();

		break;

	case 'editNewsSubmit' :

		$content = editNewsSubmit ();

		break;

	case 'addNews' :

		$content = addNews ();

		break;

	case 'addNewsSubmit' :

		$content = addNewsSubmit ();

		break;

	case 'loginForm' : // форма для входа на форум (авторизация)

		$content = getLoginForm ();

		//$content_login = 1;

		break;

	case 'login' : // вход на форум (авторизация)

		$content = login ();

		$content_login = 1;

		break;

	case 'logout' : // выход

		$content = logout ();

		break;

	case 'sparesCats' : // запчасти

		$content = getSparesCats();

		break;

	case 'addSpares' : // запчасти

		$content = getAddSpares();

		break;

	case 'chpass' : // сменить пароль

		$content = getChangePasswd();

		break;

	case 'chpassAct' : // смена пароля

		$content = changePasswd();

		break;

	case 'chinfo' : // сменить контактную информацию

		$content = getChangeInfo();

		break;

	case 'chinfoAct' : // сменить контактную информацию

		$content = changeInfo();

		break;

	case 'addNewSaloonForm' : // форма для регистрации нового пользователя

		$content = getAddNewSaloonForm ();

		break;

	case 'addNewSaloon' : // добавить нового пользователя

		$content = addNewSaloon ();

		break;

	case 'activateUser' : // активация учетной записи нового пользователя

		$content = activateUser ();

		break;

	case 'newPasswordForm' : // форма для получения нового пароля

		$content = newPasswordForm ();

		break;

	case 'sendNewPassword' : // выслать пользователю новый пароль

		$content = sendNewPassword ();

		break;

	case 'activatePassword' : // активация нового пароля

		$content = activatePassword ();

		break;

	case 'editUserForm' : // форма для редактирования профиля

		$content = getEditUserForm ();

		break;

	case 'updateUser' : // обновить данные о пользователе

		$content = updateUser ();

		break;

	case 'sendMsgForm' : // форма для отправки личного сообщения

		$content = getSendMsgForm ();

		break;

	case 'sendMessage' : // отправить личное сообщение

		$content = sendMessage ();

		break;

	case 'sendMailForm' : // форма для отправки письма пользователю

		$content = getSendMailForm ();

		break;

	case 'sendMail' : // отправка письма

		$content = sendMail ();

		break;

	case 'searchForm' : // форма для поиска по форуму

		$content = searchForm ();

		break;

	case 'searchResult' : // результаты поиска по форуму

		$content = searchResult ();

		break;

	case 'searchView' : // результаты поиска по форуму

		$content = searchView ();

		break;

	case 'selectCity' : // выбор города

		$content = selectCity ();

		break;

	case 'addCar' : // результаты поиска по форуму

		$content = addCar ();

		break;

	case 'saveView' : // результаты поиска по форуму

		$content = saveView ();

		break;

	case 'showSearchForm' : // результаты поиска по форуму

		$content = showSearchForm ();

		break;

	case 'showSearchFormAdv' : // результаты поиска по форуму

		$content = showSearchFormAdv ();

		break;

	case 'ModelView' : // результаты поиска по форуму

		$content = ModelView ();

		break;

	case 'MarkView' : // результаты поиска по форуму

		$content = MarkView ();

		break;

	case 'ListView' : // результаты поиска по форуму

		$content = ListView ();

		break;

	case 'ShowCar' : // результаты поиска по форуму

		$content = ShowCar ();

		break;

	case 'showSpares' : // результаты поиска по форуму

		$content = showSpares ();

		break;

	case 'showSearch' : // результаты поиска по форуму

		$content = showSearch ();

		break;

	case 'showSearchAdv' : // результаты поиска по форуму

		$content = showSearchAdv ();

		break;

	case 'addCarSubmit' : // результаты поиска по форуму

		$content = addCarSubmit ();

		break;

	case 'addSparesSubmit' : // результаты поиска по форуму

		$content = addSparesSubmit ();

		break;

	case 'showSaloon' : // результаты поиска по форуму

		$content = showSaloon ();

		break;

	case 'showSearchSaloon' : // результаты поиска по форуму

		$content = showSearchSaloon ();

		break;

	case 'SaloonCity' : // результаты поиска по форуму

		$content = SaloonCity ();

		break;

	case 'SaloonCount' : // результаты поиска по форуму

		$content = SaloonCount ();

		break;

	case 'SaloonByID' : // результаты поиска по форуму

		$content = SaloonByID ();

		break;

	case 'News' : // результаты поиска по форуму

		$content = News ();

		break;

	case 'price' : // результаты поиска по форуму

		$content = price ();

		break;

	case 'priceSpares' : // результаты поиска по форуму

		$content = priceSpares ();

		break;

	case 'redirect' : // результаты поиска по форуму

		$content = redirect ();

		break;

	case 'Contact' : // результаты поиска по форуму

		$content = getContact ();

		break;

	case 'Back' : // результаты поиска по форуму

		$content = getBack ();

		break;

	case 'sendBack' : // результаты поиска по форуму

		$content = sendBack ();

		break;

	case 'add' : // результаты поиска по форуму

		$content = add ();

		break;



	case 'AutoNews' : // результаты поиска по форуму

		$content = AutoNews ();

		break;

	case 'Blog' : // результаты поиска по форуму

		if (! isset ( $_GET ["id_blog"] ))

			$id_blog = 0;

		else

			$id_blog = intval ( $_GET ["id_blog"] );

		$content = showBlog ( intval ( $_GET ["id"] ), $id_blog );

		break;

	case 'Reklama' : // результаты поиска по форуму

		if (! isset ( $_GET ["id_blog"] ))

			$id_blog = 0;

		else

			$id_blog = intval ( $_GET ["id_blog"] );

		$content = showReklama ( $id_blog );

		break;

	case 'Print' : // результаты поиска по форуму

		$content = ShowCar ( 1 );

		$print = 1;

		break;

	default :

		$content = getMainPage ();

}

if ($print == 1) {

	//$html=ShowCar(1);

	echo $content;

} else {

	$menu = getMainMenu ();

	$html = file_get_contents ( './templates/default.html' );



	$html = str_replace ( '{description}', FORUM_DESCRIPTION, $html );

	$html = str_replace ( '{menu}', $menu, $html );



	$query = "SELECT COUNT(*) FROM " . TABLE_AUTO . " a";

	$query .= ", AUTO_USERS b where a.ID_USER=b.id_author and b.locked=0 and b.lock_admin=0 and a.ACTIVE=1";

	//$query.=($where!="")? " and ".$where:"";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = "<a href=?action=ShowSearchAdv&del=1>Автомобилей (".mysql_result ( $res, 0, 0 ).")</a>";





	$query = "SELECT COUNT(*) FROM " . TABLE_USERS . " b";

	$query .= " where  b.locked=0 and b.lock_admin=0 and b.status='autosaloon'";

	//$query.=($where!="")? " and ".$where:"";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total2 = "<a href=?action=SaloonCity&del=1>Автосалонов (".mysql_result ( $res, 0, 0 ).")</a>";

	$html = str_replace ( '{TOTAL_ALL}', $total."&nbsp&nbsp&nbsp&nbsp".$total2, $html );







	if (isset ( $action )) {

		$html = str_replace ( '{MAIN_2}', "<tr><td><a href=/>На главную</a></td></tr>", $html );

	} else {

		$html = str_replace ( '{MAIN_2}', "", $html );

	}

	$html = str_replace ( '{content}', $content, $html );

	//$html = str_replace( '{MAIN_2}', "<a href=/>На главную</a>", $html );

	//$html = str_replace( '{title}',$pageTitle." > ".$_SESSION['pageTitle'], $html );

	//	$html = str_replace( '{title}',"Автомобильный портал Сибири > ".$_SESSION['pageTitle'], $html );

	$html = str_replace ( '{title}', $_SESSION ['pageTitle'], $html );



	$html = str_replace ( '{META_NAME}', "<META NAME='KEYWORDS' CONTENT='" . $_SESSION ['pageTitle'] . "'", $html );

	$html = str_replace ( "{TOP}", show_banner ( "TOP" ), $html );

	$html = str_replace ( "{BANNER_CENTER}", show_banner ( "BANNER_CENTER" ), $html );

	$html = str_replace ( "{BOTTOM}", show_banner ( "BOTTOM" ), $html );

	$html = str_replace ( "{NEWS}", showNews ( - 1, 6, 0, 'News' ), $html );

	$html = str_replace ( "{SPEC_SALOON}", showSpec ( 0, '' ), $html );


/*
	$query3 = "SELECT * FROM AUTO_BLOG_TYPE where ACTIVE=1  and (ID=1 or ID=3)";

	$action = "<table width=\"100%\" valign=\"top\" cellspacing=\"0\"><tr valign=\"top\">";

	$res3 = @mysql_query ( $query3 );

	while ( $row3 = @mysql_fetch_array ( $res3 ) ) {

		$action .= "<td width=\"33%\" align=\"left\">";

		$action .= "<table width=\"100%\" valign=\"top\" cellspacing=\"2\"><tr><td colspan=\"2\" style=\"border-bottom:1px dotted #999999\" align=\"center\">";

		$action .= "<a href=?action=Blog&id=" . $row3 ["ID"] . "><span class=\"title4\" >" . $row3 ["NAME"] . "</span></a></td></tr>";



		//$query4 = "SELECT * FROM AUTO_BLOG_TYPE where ACTIVE=1 limit 5";

		$query4 = "SELECT UNIX_TIMESTAMP(b.DATE) as DATE,b.ZAGOL, b.SMALL_TEXT, b.PICTURE, b.ID, a.NAME FROM AUTO_BLOG b, AUTO_BLOG_TYPE a where b.ACTIVE=1 and b.TYPE=a.ID and b.TYPE=" . $row3 ["ID"] . " order by b.ID desc, b.DATE desc LIMIT 5";

		$res4 = @mysql_query ( $query4 );

		while ( $row4 = @mysql_fetch_array ( $res4 ) ) {



			$PHOTO = $row4 ["PICTURE"];

			if ((! isset ( $PHOTO )) or ($PHOTO == ""))

				unset($PHOTO);

				//$action.="<tr><td width=\"40\" height=\"40\" align=\"center\" valign=\"top\" ><img src=\"show_image.php?filename=photo/".$PHOTO."&width=40\" border=0/ align=\"center\" ></td><td valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?action=Blog&id=".$row3["ID"]."&id_blog=".$row4["ID"]."><span class=news2>".$row4["ZAGOL"]."</span></a></br></br><span class=date2>".date("d.m.Y",$row4["DATE"])."</span></br></br><span>".$row4["SMALL_TEXT"]."</span></td></tr>";

			//	$action.="<tr><td valign=\"top\"><span class=date2>".date("d.m.Y",$row4["DATE"])."</span>&nbsp<a href=".$_SERVER['PHP_SELF']."?action=Blog&id=".$row3["ID"]."&id_blog=".$row4["ID"]."><span class=news>".$row4["ZAGOL"]."</span></a></br></br><div><div align=\"left\"><img src=\"show_image.php?filename=photo/".$PHOTO."&width=55\" border=0/ style=\"margin-right: 5px\" alt=\"\" align=\"left\" ></div><p><div>".$row4["SMALL_TEXT"]."</div></p></div></td></tr>";

			$action .= "<tr>".($PHOTO ? "<td width=\"60\" height=\"45\" valign=\"middle\" align=\"center\"><img src=\"show_image.php?filename=photo/" . $PHOTO . "&width=60&height=45\" border=0/ style=\"margin-right: 0px\" alt=\"\" ></td>" : "")."<td ".(!$PHOTO ? "colspan=\"2\"" : "")." valign=\"middle\"><span class=date2>" . date ( "d.m.Y", $row4 ["DATE"] ) . "</span>&nbsp<a href=" . $_SERVER ['PHP_SELF'] . "?action=Blog&id=" . $row3 ["ID"] . "&id_blog=" . $row4 ["ID"] . "><span class=news>" . $row4 ["ZAGOL"] . "</span></a></td></tr>";

			//$action.="<tr><td valign=\"top\"><span class=date2>".date("d.m.Y",$row4["DATE"])."</span>&nbsp<a href=".$_SERVER['PHP_SELF']."?action=Blog&id=".$row3["ID"]."&id_blog=".$row4["ID"]."><span class=news>".$row4["ZAGOL"]."</span></a></br></br></td></tr>";





		}



		$action .= "</table></td>";

	}

	$action .= "</tr></table>";
*/
	$html = str_replace ( '{SHOW_BLOCKS}', $action, $html );



	if ((isset ( $_SESSION ['user'] )) && ($_SESSION ['user'] ['status'] == 'user')) {

		$action = "<a href=?action=logout>Выход</a> / <a href=?action=editUserForm>Изменить данные</a>";



	} else {

		$action = "<a href=?action=loginForm>Вход</a> / <a href=?action=loginForm>Регистрация</a>";

	}



	if ((isset ( $_SESSION ['user'] ) && ($_SESSION ['user'] ['status']) == 'autosaloon')) {

		$action = "<b><a href=?action=logout>Выход</a></b> / <a href=/control/>Панель управления</a>";



	} else {

		$action = "<a href=/saloon/><b>Вход</b></a> / <a href=?action=addNewSaloon>Добавить автосалон</a>";

	}



	if (isset ( $_SESSION ['user'] )) {
//ВХОД


		//$html3="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#EEEEEE\"><tr><td >";

		$html3 .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#EEEEEE\" class=\"postTable5\"><tr><td height=\"32\" bgcolor=\"#666666\" style=\"background:url(img/back_right2.gif) no-repeat\" valign=\"middle\" >";

		$html3 .= "<div class=\"head\" >";

		$html3 .= "Панель управления</div></td></tr>";



		if ($_SESSION ['user'] ['status'] === "autosaloon") {

	$query = "SELECT COUNT(*) FROM AUTO_NEWS WHERE ID_SALOON='".$_SESSION['user']['id_author']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=myNews">Новости автосалона</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

		}

		if ($_SESSION ['user'] ['status'] === "autosaloon" || $_SESSION ['user'] ['status'] === "user") {

	$query = "SELECT COUNT(*) FROM AUTO_CAR_BASE WHERE ID_USER='".$_SESSION['user']['id_author']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

		$html3 .= '<tr><td><a href="?action=myCar">Мои автомобили</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

	$query = "SELECT COUNT(*) FROM AUTO_SPARES WHERE ID_USER='".$_SESSION['user']['id_author']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

		$html3 .= '<tr><td><a href="?action=mySpares">Мои товары и запчасти</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

		} else {
		//admin
	$query = "SELECT COUNT(*) FROM AUTO_CAR_BASE";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

		$html3 .= '<tr><td><a href="?action=myCar">Автомобили</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

	$query = "SELECT COUNT(*) FROM AUTO_SPARES";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

		$html3 .= '<tr><td><a href="?action=mySpares">Товары и запчасти</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

		$query = "SELECT COUNT(*) FROM AUTO_NEWS WHERE ID_SALOON='".$_SESSION['user']['id_author']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=myNews">Новости администрации</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

			$query = "SELECT COUNT(*) FROM AUTO_NEWS";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=allNews">Новости автосалонов</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

	$query = "SELECT COUNT(*) FROM AUTO_BLOG_TYPE";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=rubList">Рубрики сообщений</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';


	$query = "SELECT COUNT(*) FROM AUTO_BLOG WHERE TYPE<>'0'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=blogList">Сообщения</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

	$query = "SELECT COUNT(*) FROM AUTO_BANNER";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


	$html3 .= '<tr><td><a href="?action=bannerList">Баннеры</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

	$query = "SELECT COUNT(*) FROM AUTO_USERS WHERE status='user'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

		$html3 .= '<tr><td><a href="?action=userList">Пользователи</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';


	$query = "SELECT COUNT(*) FROM AUTO_USERS WHERE status='autosaloon' OR status='admin'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

		$html3 .= '<tr><td><a href="?action=autosaloonList">Автосалоны</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';
		//



									$query = "SELECT COUNT(*) FROM AUTO_TRADEMARK";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=markList">Марки авто</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

										$query = "SELECT COUNT(*) FROM AUTO_MODEL";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=modelList">Модели авто</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

									$query = "SELECT COUNT(*) FROM AUTO_TYPE_KUZ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=kuzList">Типы кузова</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

					$query = "SELECT COUNT(*) FROM AUTO_TYPE_DVIG";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



			$html3 .= '<tr><td><a href="?action=dvigList">Типы двигателей</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

				$query = "SELECT COUNT(*) FROM AUTO_COLOR";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=colorList">Цвета авто</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

			$query = "SELECT COUNT(*) FROM AUTO_SOST";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=sostList">Состояния авто</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';

			$query = "SELECT COUNT(*) FROM AUTO_REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=regionList">Регионы</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';


			$query = "SELECT COUNT(*) FROM AUTO_CITY";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}


			$html3 .= '<tr><td><a href="?action=cityList">Города</a> ('.mysql_result ( $res, 0, 0 ).')</td></tr>';









		}

		$html3 .= '<tr><td><a href="?action=chinfo">Контактная информация</a></td></tr>';

		$html3 .= '<tr><td><a href="?action=chpass">Сменить пароль</a></td></tr>';

		$html3 .= '<tr><td><b><a href=?action=logout>Выйти</a></b></td></tr>';

		$html3 .= "<tr><td height=\"13\" bgcolor=\"#666666\" style=\"background:url(img/border3.jpg) no-repeat bottom\" colspan=\"2\"></td></tr></table>";



		$html = str_replace ( '{LOGIN}', $html3, $html );



	} elseif ($content_login != 1) {

		unset ( $_SESSION ['action_url'] );

		$html10 = file_get_contents ( './templates/login.html' );

		$html = str_replace ( '{LOGIN}', $html10, $html );

		//$html = str_replace( '{LOGIN}', "<b><a href=?action=loginForm>Вход</a></b><font color=\"#666666\"> :: </font>"."<a href=\"?action=addNewUser\">Регистрация</a>", $html );





	} else {

		$html = str_replace ( '{LOGIN}', "", $html );



	}



	for($i = 1; $i < 6; $i ++) {

		//echo "RIGHT_".$i;

		$html = str_replace ( "{RIGHT_" . $i . "}", show_banner ( "RIGHT_" . $i ), $html );



	}

	$html = str_replace ( "{BLOCK_1}", show_block (), $html );

	////////////////



		//$action .= "<td width=\"33%\" align=\"left\">";
/*
		$action = "<table><tr><td>&nbsp;</td></tr></table><table  bgcolor=\"#eeeeee\" width=\"95%\" valign=\"top\" cellspacing=\"2\"><tr><td colspan=\"2\" style=\ padding-bottom: 7px;\" align=\"center\">";

		$action .= "<a href=?action=sparesCats&id_typeCode=1&del=1><span class=\"title4\" ><a style=\"font-size: 16px; text-decoration: underline;\" href=\"?action=sparesCats&id_typeCode=1&del=1\">Товары и запчасти</a></span></a></td></tr>";
*/



		$action = "<table  bgcolor=\"#eeeeee\" width=\"100%\" valign=\"top\" cellspacing=\"2\"><tr><td valign=\"middle\" height=\"30\" bgcolor=\"#666666\" style=\"background: transparent url(img/back_right2.gif) no-repeat scroll 0% 0%;\"; colspan=\"2\" >";

		$action .= "<div class=\"head\">Товары и запчасти</div></td></tr>";


		//$query4 = "SELECT * FROM AUTO_BLOG_TYPE where ACTIVE=1 limit 5";

		if ((!isset($_REQUEST['action'])) || ($_REQUEST['action']===''))
			$count_sp = 6;
		else
			$count_sp = 3;

		$query4 = "SELECT PHOTO_1, zag, ID FROM AUTO_SPARES order by DATE desc LIMIT ".$count_sp;

		$res4 = @mysql_query ( $query4 );

		while ( $row4 = @mysql_fetch_array ( $res4 ) ) {



			$PHOTO = $row4 ["PHOTO_1"];

			if ((! isset ( $PHOTO )) or ($PHOTO === ""))

				$PHOTO="nofoto.gif";


			$action .= "<tr>".($PHOTO ? "<td width=\"60\" height=\"45\" valign=\"middle\" align=\"center\" style=\"padding-left: 10px;\"><img src=\"show_image.php?filename=photo/" . $PHOTO . "&width=60&height=45\" border=0/ style=\"margin-right: 0px\" alt=\"\" ></td>" : "")."<td ".(!$PHOTO ? "colspan=\"2\" style=\"padding-left: 10px;\" " : "")." valign=\"middle\"><a href=" . $_SERVER ['PHP_SELF'] . "?action=showSpares&id=" . $row4 ["ID"] . "><span class=news style=\"font-weight: normal;\">" . ((strlen($row4 ["zag"]) > 30) ? substr($row4 ["zag"],0,30).'...' : $row4 ["zag"] ) . "</span></a></td></tr>";





		}


		$action .= '<tr><td colspan="2" height="13" bgcolor="#666666" style="background: transparent url(img/border3.jpg) no-repeat scroll center bottom;"/></tr>';
		$action .= "</table>";



	//$action .= "</tr></table>";
	//////
	//if ((!isset($_REQUEST['action'])) || ($_REQUEST['action']===''))
		$auto=$action;
	//else
	//	$auto='';

	//$auto='<table><tr><td>123123</td></tr></table>';

	$html = str_replace ( '{SP}', $auto, $html );

	$query = "SELECT COUNT(*) FROM AUTO_BLOCK where ACTIVE=1";

	$res = @mysql_query ( $query );

	for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	if ($data['COUNT(*)'])
		$html = str_replace ( '{BLK_B}', '<tr><td height="13" bgcolor="#666666" style="background:url(img/border3.jpg) no-repeat bottom"></td></tr>', $html );
	else
		$html = str_replace ( '{BLK_B}', '', $html );

	//
$bilstr = "

<script src=\"js/jquery.scrollable-1.0.2.pack.js\" type=\"text/javascript\"></script>

<script type=\"text/javascript\">
var carousel_index = 0;

var premium_ads = [
";

$query = "SELECT a.ID, a.CAR_TYPE, j.CITY, f.MODEL,i.TRADEMARK,r.REGION, b.CAR_TYPE as TYPE,a.YEAR_VYP,a.PROBEG,a.PRICE, a.CAR_MARK,a.PHOTO_1,a.ID_USER,UNIX_TIMESTAMP(a.DATE_VVOD) as DATE_VVOD, c.* FROM  " . TABLE_AUTO . " a, AUTO_CAR_TYPE b, AUTO_USERS c, AUTO_MODEL f,AUTO_TRADEMARK i,AUTO_CITY j,AUTO_REGION r where ";
$query .= "b.ID=a.CAR_TYPE ";
$query .= " and f.ID=a.CAR_MODEL";
$query .= " and i.ID=a.CAR_MARK";
$query .= " and a.CITY=j.ID";
$query .= " and a.REGION=r.ID";
if($_SESSION ['sms_id_region']) $query.=" and a.REGION=".$_SESSION ['sms_id_region'];
$query .= " and ((".time()." - UNIX_TIMESTAMP(a.BILLING_DATE)) <= (86400 * 10))";
$query .= " and a.BILLING=1";
$query .= " and (a.ID_USER=c.id_author and c.locked=0 and c.lock_admin=0) and a.ACTIVE=1";
$query .= " ORDER BY a.BILLING_DATE DESC, a.DATE_VVOD DESC";

	$res = mysql_query ( $query );

	if (! $res) {
		$msg = 'Ошибка при получении списка моделй';
		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';
		return showErrorMessage ( $msg, $err, true, '' );
	}

for($data=array();$row=mysql_fetch_assoc($res);$data[]=$row);

for ($i=0;$i<count($data);$i++) {
	$photo = $data[$i]['PHOTO_1'] ? $data[$i]['PHOTO_1'] : "none{$data[$i]['CAR_TYPE']}_144x108.jpg";
	$bilstr .= "{id:'".$data[$i]['ID']."',image:'show_image.php?filename=photo/".$photo."&width=144&height=108', price:'".$data[$i]['PRICE']."&nbsp;руб.',location:'".$data[$i]['CITY']."',name:\"".$data[$i]['TRADEMARK'].' '.$data[$i]['MODEL'].' '.$data[$i]['YEAR_VYP']."\"},"."\n";
}

/*
$bilstr .= '
<div>
<a href=?action=ShowCar&id='.$data[$i]['ID'].'>
<img width="144" height="108" alt="'.$data[$i]['CITY'].' '.$data[$i]['TRADEMARK'].' '.$data[$i]['MODEL'].' '.$data[$i]['YEAR_VYP'].'" src="show_image.php?filename=photo/'.$data[$i]['PHOTO_1'].'&width=144&height=108"/>
<span class="p">'.$data[$i]['PRICE'].' руб.</span>
</a>
<span>'.$data[$i]['CITY'].'</span>
<a href="?action=ShowCar&id='.$data[$i]['ID'].'">
<span>'.$data[$i]['TRADEMARK'].' '.$data[$i]['MODEL'].' '.$data[$i]['YEAR_VYP'].'</span>
</a>
</div>
';
*/

//{id:'3063329',image:'http://images.drom.ru/sales/photos/3064/3063329/premium.jpg', price:'325&nbsp;000&nbsp;руб.',location:'Новокузнецк',name:\"Mazda Demio 2006\"},

$bilstr .= "

]

function getImageLink(item) {
    var image_link = '';
    if (typeof item.image == 'undefined') {

    } else {
        image_link = item.image;
    }
    return image_link;
}

function getLink(item) {
    var link = '?action=ShowCar&id='+ item.id;
    if (typeof item.params != 'undefined') {
	link += item.params;
    }
    return link;
}

function wrapHTML(item) {
    var html = '<div><a href=\"' + getLink(item) +'\">';
    html += '<img src=\"' + getImageLink(item);
    html += '\" alt=\"' + item.location + ' ' + item.name;
    html += '\" width=\"144\" height=\"108\"/>';
    html += '<span class=\"p\">' + item.price + '</span></a>';
    html += '<span>' + item.location + '</span>';
    html += '<a href=\"' + getLink(item) +'\">';
    html += '<span>' + item.name + '</span></a></div>';

    return html;
}

function appendItem() {
    if (carousel_index == premium_ads.length) {
      carousel_index = 0
    }
    var item = premium_ads[carousel_index++];

    var api = jQuery(\".tireCat .scrollable\").scrollable();
    api.getItemWrap().append(wrapHTML(item));
}

function prependItem() {
    if (carousel_index == 0) {
      carousel_index = premium_ads.length;
    }
    carousel_index--;
    if (carousel_index - 4 < 0) {
      var item = premium_ads[premium_ads.length + (carousel_index - 4)];
    } else {
      var item = premium_ads[carousel_index - 4];
    }

    var api = jQuery(\".tireCat .scrollable\").scrollable();
    var items = api.getItemWrap();
    items.prepend(wrapHTML(item));
    items.css('left', '-145px');
}

function removeItem(move_right) {
    var api = jQuery(\".tireCat .scrollable\").scrollable();
    items = api.getItemWrap();
    if (move_right == true) {
      items.children(':first').remove();
    } else {
      items.children(':last').remove();
    }
    items.css('left', '0px');

    return true;
}

function updateIndex() {
    this.setIndex(0);
}

//jQuery.noConflict();
jQuery(document).ready(function() {
    var s = jQuery('.tireCat .scrollable').scrollable({
        clickable: false,
        speed: 200,
        size: 3,
        alert: false,
        onSeek: updateIndex
    }).scrollable();

    if (premium_ads.length > 4) {
        jQuery('.tireCat .c1 a').click(function() {
            prependItem();
            s.prev(200, function() { removeItem(false) });
            return false;
        });
        jQuery('.tireCat .c2 a').click(function() {
            appendItem();
            s.next(200, function() { removeItem(true) });
            return false;
        });
    }
});
</script>
";

$bilstr .= '
<div class="tireCat ">

<div class="tireCatC">
<div class="tireCatL">
<div class="tireCatR">
<table cellspacing="3" width="632">
<tbody><tr>
';

//if (count($data) >= 4)
	$bilstr .= '
	<td class="c1">
	<a href="#"> </a>
	</td>
	';

$bilstr .= '
<td style="padding-left: 8px;">
<div class="links">
<span>Спецразмещение</span>
<a href="?action=Reklama&id_blog=53">Как сюда попасть?</a>
</div>
<div class="scrollable">
<div class="items" style="left: 0px;">

';

$query = "SELECT a.ID, a.BILLING ,a.CAR_TYPE, j.CITY, f.MODEL,i.TRADEMARK,r.REGION, b.CAR_TYPE as TYPE,a.YEAR_VYP,a.PROBEG,a.PRICE, a.CAR_MARK,a.PHOTO_1,a.ID_USER,UNIX_TIMESTAMP(a.DATE_VVOD) as DATE_VVOD, c.*, a.REGION= ".$_SESSION ['sms_id_region']." as is_region FROM " . TABLE_AUTO . " a, AUTO_CAR_TYPE b, AUTO_USERS c, AUTO_MODEL f,AUTO_TRADEMARK i,AUTO_CITY j,AUTO_REGION r where ";
$query .= "b.ID=a.CAR_TYPE ";
$query .= " and f.ID=a.CAR_MODEL";
$query .= " and i.ID=a.CAR_MARK";
$query .= " and a.CITY=j.ID";
$query .= " and a.REGION=r.ID";
//if($_SESSION ['sms_id_region']) $query.=" and a.REGION=".$_SESSION ['sms_id_region'];
$query .= " and ((((".time()." - UNIX_TIMESTAMP(a.BILLING_DATE)) <= (86400 * 10)) and a.BILLING=1) OR a.BILLING=0)";
$query .= " and (a.ID_USER=c.id_author and c.locked=0 and c.lock_admin=0) and a.ACTIVE=1";
$query .= " ORDER BY a.BILLING DESC, a.BILLING_DATE DESC, is_region DESC, a.DATE_VVOD DESC LIMIT 4" ;


	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {
		$msg = 'Ошибка при получении списка моделй';
		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';
		return showErrorMessage ( $msg, $err, true, '' );
	}

for($data=array();$row=mysql_fetch_assoc($res);$data[]=$row);
if (count($data) > 0) {
//если есть хотя бы одна объява
for($i=0;$i<count($data);$i++) {

	$photo = $data[$i]['PHOTO_1'] ? $data[$i]['PHOTO_1'] : "none{$data[$i]['CAR_TYPE']}_144x108.jpg";

	$bilstr .= '
<div>
<a href=?action=ShowCar&id='.$data[$i]['ID'].'>
<img width="144" height="108" alt="'.$data[$i]['CITY'].' '.
	$data[$i]['TRADEMARK'].' '.$data[$i]['MODEL'].' '.
	$data[$i]['YEAR_VYP'].'" src="show_image.php?filename=photo/'.
	$photo.'&width=144&height=108"/>
<span class="p">'.$data[$i]['PRICE'].' руб.</span>
</a>
<span>'.$data[$i]['CITY'].'</span>
<a href="?action=ShowCar&id='.$data[$i]['ID'].'">
<span>'.$data[$i]['TRADEMARK'].' '.$data[$i]['MODEL'].' '.$data[$i]['YEAR_VYP'].'</span>
</a>
</div>
';
}
/*
//остальные
$query = "SELECT a.ID, a.CAR_TYPE, j.CITY, f.MODEL,i.TRADEMARK,r.REGION, b.CAR_TYPE as TYPE,a.YEAR_VYP,a.PROBEG,a.PRICE, a.CAR_MARK,a.PHOTO_1,a.ID_USER,UNIX_TIMESTAMP(a.DATE_VVOD) as DATE_VVOD, c.* FROM  " . TABLE_AUTO . " a, AUTO_CAR_TYPE b, AUTO_USERS c, AUTO_MODEL f,AUTO_TRADEMARK i,AUTO_CITY j,AUTO_REGION r where ";
$query .= "b.ID=a.CAR_TYPE ";
$query .= " and f.ID=a.CAR_MODEL";
$query .= " and i.ID=a.CAR_MARK";
$query .= " and a.CITY=j.ID";
$query .= " and a.REGION=r.ID";
if($_SESSION ['sms_id_region']) $query.=" and a.REGION=".$_SESSION ['sms_id_region'];
$query .= " and ((".time()." - UNIX_TIMESTAMP(a.BILLING_DATE)) <= (86400 * 10))";
$query .= " and a.BILLING=1";
$query .= " and (a.ID <> ".$data[0]['ID'].")";
$query .= " and (a.ID_USER=c.id_author and c.locked=0 and c.lock_admin=0) and a.ACTIVE=1";
$query .= " ORDER BY RAND()";
$query .= " LIMIT 0,3 ";


//echo "<hr>".$query;
	$res = mysql_query ( $query );

	if (! $res) {
		$msg = 'Ошибка при получении списка моделй';
		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';
		return showErrorMessage ( $msg, $err, true, '' );
	}

for($data=array();$row=mysql_fetch_assoc($res);$data[]=$row);

if (count($data) > 0) {
for($i=0;$i<count($data);$i++) {
$bilstr .= '
<div>
<a href=?action=ShowCar&id='.$data[$i]['ID'].'>
<img width="144" height="108" alt="'.$data[$i]['CITY'].' '.$data[$i]['TRADEMARK'].' '.$data[$i]['MODEL'].' '.$data[$i]['YEAR_VYP'].'" src="show_image.php?filename=photo/'.$data[$i]['PHOTO_1'].'&width=144&height=108"/>
<span class="p">'.$data[$i]['PRICE'].' руб.</span>
</a>
<span>'.$data[$i]['CITY'].'</span>
<a href="?action=ShowCar&id='.$data[$i]['ID'].'">
<span>'.$data[$i]['TRADEMARK'].' '.$data[$i]['MODEL'].' '.$data[$i]['YEAR_VYP'].'</span>
</a>
</div>
';
}

}
*/
$ost = 4 - count($data);

if ($ost > 0) {
for($i=0;$i<$ost;$i++) {
$bilstr .= '
<div>
<a href="">
<img width="144" height="108" alt="" src="show_image.php?filename=photo/nofoto_144x108.gif&width=144&height=108"/>
</a>
<span></span>

<span></span>

</div>
';
}
}


} else {

for($i=0;$i<4;$i++) {
$bilstr .= '
<div>
<a href="">
<img width="144" height="108" alt="" src="show_image.php?filename=photo/nofoto_144x108.gif&width=144&height=108"/>
</a>
<span></span>

<span></span>

</div>
';
}

}



$bilstr .= '

</div>
</div>
</td>';

//if (count($data) >= 4)
	$bilstr .= '
	<td class="c2">
	<a onclick="return false;" class="nextPage" href="#"> </a>
	</td>';

$bilstr .= '
</tr>
</tbody></table>

</div>
</div>
</div>
</div>
';

$bilstr .= '
<script type="text/javascript">
  carousel_index = 4;
</script>
';
	//

	$html = str_replace ( '{BILLING}', $bilstr, $html );

	echo $html;

}



function mark_list(){

	if ($_GET) {

		if (isset ( $_GET ['id_typeCode'] ))

			$_SESSION ['searchForm'] ['id_typeCode'] = intval ( RemoveXSS ( $_GET ['id_typeCode'] ) );

		if (isset ( $_GET ['id_markCode'] ))

			$_SESSION ['searchForm'] ['id_markCode'] = intval ( RemoveXSS ( $_GET ['id_markCode'] ) );

		if (isset ( $_GET ['id_modelCode'] ))

			$_SESSION ['searchForm'] ['id_modelCode'] = intval ( RemoveXSS ( $_GET ['id_modelCode'] ) );

		if (isset ( $_GET ['id_region'] ))

			$_SESSION ['searchForm'] ['id_region'] = intval ( RemoveXSS ( $_GET ['id_region'] ) );

		if (isset ( $_GET ['cityCode'] ))

			$_SESSION ['searchForm'] ['id_city'] = intval ( RemoveXSS ( $_GET ['cityCode'] ) );

		if (isset ( $_GET ['year1'] ))

			$_SESSION ['searchForm'] ['year1'] = intval ( RemoveXSS ( $_GET ['year1'] ) );

		if (isset ( $_GET ['year2'] ))

			$_SESSION ['searchForm'] ['year2'] = intval ( RemoveXSS ( $_GET ['year2'] ) );

		if (isset ( $_GET ['price1'] ))

			$_SESSION ['searchForm'] ['price1'] = abs ( RemoveXSS ( $_GET ['price1'] ) );

		if (isset ( $_GET ['price2'] ))

			$_SESSION ['searchForm'] ['price2'] = abs ( RemoveXSS ( $_GET ['price2'] ) );

		if (isset ( $_GET ['date_list'] ))

			$_SESSION ['searchForm'] ['date_list'] = intval ( RemoveXSS ( $_GET ['date_list'] ) );


		if (isset ( $_GET ['prav_rul'] ))

			$_SESSION ['searchForm'] ['prav_rul'] = intval ( RemoveXSS ( $_GET ['prav_rul'] ) );


		if ($_GET ['new_model'] > 0) {

			$_SESSION ['searchForm'] ['new'] = intval ( RemoveXSS ( $_GET ['new_model'] ) );

		} else {

			//unset($_SESSION['searchForm']['new']);

		}


		$where = "";

		if (isset ( $_GET ['id_typeCode'] ) and $_GET ['id_typeCode'] > 0)

			$where .= "AND a.CAR_TYPE=" . intval ( mysql_escape_string ( $_GET ['id_typeCode'] ) );

		if (isset ( $_GET ['id_markCode'] ) and $_GET ['id_markCode'] > 0)

			$where .= " and a.CAR_MARK=" . intval ( mysql_escape_string ( $_GET ['id_markCode'] ) ) ;

		if (isset ( $_GET ['id_modelCode'] ) and $_GET ['id_modelCode'] > 0)

			$where .= " and a.CAR_MODEL=" . intval ( mysql_escape_string ( $_GET ['id_modelCode'] ) );

		if (isset ( $_GET ['id_region'] ) and $_GET ['id_region'] > 0)

			$where .= " and a.REGION=" . intval ( mysql_escape_string ( $_GET ['id_region'] ) );

		if (isset ( $_GET ['cityCode'] ) and $_GET ['cityCode'] > 0)

			$where .= " and a.CITY=" . intval ( mysql_escape_string ( $_GET ['cityCode'] ) );

		if (isset ( $_GET ['year1'] ) and $_GET ['year1'] > 0)

			$where .= " and a.YEAR_VYP>=" . intval ( mysql_escape_string ( $_GET ['year1'] ) );

		if (isset ( $_GET ['year2'] ) and $_GET ['year2'] > 0)

			$where.= " and a.YEAR_VYP<=" . intval ( mysql_escape_string ( $_GET ['year2'] ) ) ;

			//$datetoday=mktime(0,0,0,date("m"),date("d"),date("Y"));

		if ($_GET ['date'] == date ( "Y-m-d" ))

			$where .=  " and a.DATE_VVOD='" . date ( "Y-m-d" ) . "'" ;

			//$price1=$_GET['price1'];

		//$price2=$_GET['price2'];


		if (isset ( $_GET ['prav_rul'] ) and $_GET ['prav_rul'] >= 0)

			$where  .= " and a.PRAV_RUL=" . intval ( mysql_escape_string ( $_GET ['prav_rul'] ) ) ;

		if (isset ( $_GET ['new_model'] ) and $_GET ['new_model'] > 0)

			$where .=  " and a.NEW=" . intval ( mysql_escape_string ( $_GET ['new_model'] ) );

	}

	$id_region = ($_GET['id_region']?$_GET['id_region']:$_SESSION ['sms_id_region']);

	$query_region = "SELECT ID, REGION FROM AUTO_REGION WHERE ID=".$id_region;
	$result_region = mysql_query($query_region);
	$myrow_region = mysql_fetch_array($result_region);

	if($_GET['cityCode']){

		$query_city = "SELECT ID, CITY FROM AUTO_CITY WHERE ID=".$_GET['cityCode'];
		$result_city = mysql_query($query_city);
		$myrow_city = mysql_fetch_array($result_city);
	}
	else{
		$query_city = "SELECT ID, CITY FROM AUTO_CITY WHERE ID_REGION=".$id_region.' LIMIT 1';
		$result_city = mysql_query($query_city);
		$myrow_city = mysql_fetch_array($result_city);
	}

	$Gpath = '<table align="left" width="100%"><tr><td align="left" class="title" colspan="3">Поиск по маркам/моделям
   <div class="path"><span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=mark_list">Поиск по маркам/моделям</a><span></div>
   </td></tr>';

	$html = $Gpath;

	$html.= '<tr><td>{main}</td></tr><tr><td>
		<table cellspacing="0" cellpadding="0" border="0" class="top_cars">
            <tbody><tr class="top">
            <td class="img1"></td>
            <td class="img2"><p>Условия отбора:</p></td>
            <td class="img3">&nbsp;&nbsp;</td>
            </tr>
            <tr>
            <td class="imgL">&nbsp;&nbsp;</td>
            <td>

                <table width="100%">
                <tbody><tr><td><input type="checkbox" '.(!isset($_GET['prav_rul'])?' onclick="location.href=\'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'&prav_rul=0\'"':' checked onclick="location.href=\'http://'.$_SERVER['SERVER_NAME'].str_replace('&prav_rul=0','',$_SERVER['REQUEST_URI']).'\'"').' > Только левый руль<br>
<br>&nbsp;Город: '.(isset($_GET['id_region'])?'<a href="http://'.$_SERVER['SERVER_NAME'].str_replace(array("&cityCode=".$_GET['cityCode'],'&id_region='.$_GET['id_region']),'',$_SERVER['REQUEST_URI']).'">Все города</a>':"Все города").'&nbsp; | &nbsp; '.((!isset($_GET['id_region']) || isset($_GET['cityCode']))?'<a href="http://'.$_SERVER['SERVER_NAME'].str_replace(array("&cityCode=".$_GET['cityCode'],'&id_region='.$_GET['id_region']),"",$_SERVER['REQUEST_URI']).'&id_region='.$myrow_region['ID'].'">'.$myrow_region['REGION'].'</a>':$myrow_region['REGION']).'&nbsp; | &nbsp;'.($_GET['cityCode']?$myrow_city['CITY']:'<a href="http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'&cityCode='.$myrow_city['ID'].'">'.$myrow_city['CITY'].'</a>').'&nbsp; | &nbsp;<a href="?action=selectCity">Выбрать город...</a>        </td></tr></tbody></table>
            </td>
            <td class="imgR">&nbsp;&nbsp;</td>
            </tr>
            <tr class="bottom">
            <td class="img1">&nbsp;&nbsp;</td>
            <td class="img2">&nbsp;</td>
            <td class="img3">&nbsp;&nbsp;</td>
            </tr>
            </tbody></table></td></tr>';

	if(!$_GET['mark_id']){
		$html.='
			<tr><td>
			<table class="top_cars" cellspacing="0" cellpadding="0" border="0" >
            <tbody><tr class="top">
            <td class="img1"></td>
            <td class="img2"></td>
            <td class="img3">&nbsp;&nbsp;</td>
            </tr>
            <tr>
            <td class="imgL">&nbsp;&nbsp;</td>
            <td>

                <table width="100%">
                <tbody><tr><td style="padding-top:20px;"><h4> <a href="?action=searchView">Все объявления</a> &nbsp; &nbsp; <a href="?action=showSearchAdv&del=1">Поиск</a> &nbsp; &nbsp; <a href="http://vash_domen.ru/index.php?action=add"><nobr>Добавить объявление (бесплатно!)</nobr></a> </h4>        </td></tr></tbody></table>
            </td>
            <td class="imgR">&nbsp;&nbsp;</td>
            </tr>
            <tr class="bottom">
            <td class="img1">&nbsp;&nbsp;</td>
            <td class="img2">&nbsp;</td>
            <td class="img3">&nbsp;&nbsp;</td>
            </tr>
            </tbody></table></td></tr>';
	}



		$html.='<tr><td><table cellspacing="0" border="0" cellpadfalseding="0" class="top_cars">
				  <tbody><tr class="top">
					<td class="img1"></td>
					<td class="img2"><p>Выберите фирму</p></td>
					<td class="img3">&nbsp;&nbsp;</td>
				  </tr>
				  <tr>
					<td class="imgL">&nbsp;&nbsp;</td>
					<td><table width="100%"><tbody><tr><td style="vertical-align:top;"><ul style="list-style-type:none;">';

		$html = str_replace ( '{main}', file_get_contents ( './templates/searchFormSmall.html' ), $html );

		$action = $_SERVER ['PHP_SELF'] . '?action=searchView';

		$html = str_replace ( '{action}', $action, $html );



	//если марка еще не выбрана
	if(!$_GET['mark_id']){
		$where = "CAR_MARK = AUTO_TRADEMARK.ID ".$where;

		$sql_marks = "SELECT AUTO_TRADEMARK.ID, TRADEMARK, COUNT( a.ID ) as count_ann
			FROM `AUTO_TRADEMARK` , `AUTO_CAR_BASE` a
			WHERE ".$where."
			GROUP BY TRADEMARK";
		//echo $sql_marks;
		$result_marks = mysql_query($sql_marks);

		$count = mysql_num_rows($result_marks);
		$num_in_column = ceil($count/3);


		$i=0;

		while($myrow_marks = mysql_fetch_array($result_marks)){

			if($i && $i%$num_in_column==0) {
				$html.= "</td><td style='vertical-align:top;'><ul style='list-style-type:none;'>";
			}
			$html.= sprintf("<li><h4><b><a href=\"http://".$_SERVER['SERVER_NAME'].str_replace("&mark_id=".$_GET['mark_id'],"",$_SERVER['REQUEST_URI'])."&mark_id=%s\">%s&nbsp;(%s)</a></b></h4></li>", $myrow_marks['ID'],trim($myrow_marks['TRADEMARK']),$myrow_marks['count_ann']);
			$i++;

		}
	}
	//когда уже выбрана марка
	else{

		$where = "CAR_MODEL = AUTO_MODEL.ID AND CAR_MARK=".$_GET['mark_id']." ".$where;

		$sql_marks = "SELECT MODEL, AUTO_MODEL.ID, COUNT( a.ID ) as count_ann
			FROM AUTO_MODEL , `AUTO_CAR_BASE` a
			WHERE ".$where."
			GROUP BY MODEL";
		//echo $sql_marks;
		$result_marks = mysql_query($sql_marks);

		$count = mysql_num_rows($result_marks);
		$num_in_column = ceil(($count+1)/3);

		$i=1;

		$html.='<li><h4><b><a href="http://'.$_SERVER['SERVER_NAME'].str_replace('&mark_id='.$_GET['mark_id'],'',$_SERVER['REQUEST_URI']).'">Все&nbsp;объявления</a></b></h4></li>';

		while($myrow_marks = mysql_fetch_array($result_marks)){

			if($i>0 && $i%$num_in_column==0) $html.= "</td><td style='vertical-align:top;'><ul style='list-style-type:none;'>";

			$html.= sprintf("<li><h4><b><a href=\"http://".$_SERVER['SERVER_NAME'].str_replace(array("action=mark_list","&mark_id=".$_GET['mark_id']),array("action=searchView",""),$_SERVER['REQUEST_URI'])."&idmarkCode=".$_GET['mark_id']."&id_modelCode=%s\">%s&nbsp;(%s)</a></b></h4></li>", $myrow_marks['ID'],$myrow_marks['MODEL'],$myrow_marks['count_ann']);
			$i++;
		}
	}

//////////////////////////////////////////////////

			$html.='</td></tr></tbody></table></td>
						<td class="imgR">&nbsp;&nbsp;</td>
					  </tr>
					  <tr class="bottom">
						<td class="img1">&nbsp;&nbsp;</td>
						<td class="img2">&nbsp;</td>
						<td class="img3">&nbsp;&nbsp;</td>
					  </tr>
					  </tbody></table></td></tr></table>';

///////////////////////////////////////////////////////


			if ($_SESSION ['searchForm'] ['new'] == 1) {

			$html = str_replace ( '{CHECK_NEW}', " checked ", $html );

		}


		if ($_SESSION ['searchForm'] ['foto'] == 1) {

			$html = str_replace ( '{CHECK_PHOTO}', " checked ", $html );

		}



		if (! isset ( $_SESSION ['searchForm'] ['id_typeCode'] )) {

			$_SESSION ['searchForm'] ['id_typeCode'] = 1;

		}

		for($i = 1; $i < 9; $i ++) {

			if ($_SESSION ['searchForm'] ['id_typeCode'] == $i) {

				$html = str_replace ( '{select' . $i . '}', "class='navDiv3'", $html );

				$html = str_replace ( '{id_typeCode}', $i, $html );

			} else {

				$html = str_replace ( '{select' . $i . '}', "class='navDiv2'", $html );

			}



			$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $_SESSION ['searchForm'] ['id_typeCode'].' ORDER BY TRADEMARK';

			$res = mysql_query ( $query );

			if (! $res) {

				die ();

			}

			$mark = "";

			if (mysql_num_rows ( $res ) > 0) {

				while ( $marklist = @mysql_fetch_array ( $res ) ) {

					$selwrk = ($marklist ['ID'] == $_SESSION ['searchForm'] ['id_markCode']) ? " selected" : "";

					$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

				}

			}

			$html = str_replace ( '{MARK}', $mark, $html );

		}

		if (isset ( $_SESSION ['searchForm'] ['id_markCode'] )) {

			$query = "SELECT * FROM AUTO_MODEL where TRADEMARK=" . $_SESSION ['searchForm'] ['id_markCode'].' ORDER BY MODEL';

			//echo $query;

			$res = mysql_query ( $query );

			if (! $res) {

				$msg = 'Ошибка при получении списка моделй';

				$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $err, true, '' );

			}

			$model = "";

			if (mysql_num_rows ( $res ) > 0) {

				while ( $modellist = mysql_fetch_array ( $res ) ) {

					$selwrk = ($modellist ['ID'] == $_SESSION ['searchForm'] ['id_modelCode']) ? " selected" : "";

					$model .= "<option value='" . $modellist ['ID'] . "' " . $selwrk . " >" . $modellist ['MODEL'] . "</option>";

				}

			}

			$html = str_replace ( '{MODEL}', $model, $html );

		}



		$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$region = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $regionlist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($regionlist ['ID'] == $_SESSION ['searchForm'] ['id_region']) ? " selected" : "";

				$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

			}

		}



		$html = str_replace ( '{REGION}', $region, $html );



		if (isset ( $_SESSION ['searchForm'] ['id_region'] )) {



			$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $_SESSION ['searchForm'] ['id_region'].' ORDER BY CITY';

			$res = mysql_query ( $query );

			if (! $res) {

				$msg = 'Ошибка при получении списка марок';

				$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $err, true, '' );

			}

			$mark = "";

			if (mysql_num_rows ( $res ) > 0) {

				while ( $marklist = mysql_fetch_array ( $res ) ) {

					$selwrk = ($marklist ['ID'] == $_SESSION ['searchForm'] ['id_city']) ? " selected" : "";

					$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['CITY'] . "</option>";

				}

			}

			$html = str_replace ( '{CITY}', $mark, $html );

		}



		$year1 = "";

		$year1 .= "<option value='0'>____</option>";

		for($i = date ( 'Y' ); $i > 1949; $i --) {

			$selwrk1 = ($i == $_SESSION ['searchForm'] ['year1']) ? " selected" : "";



			$year1 .= "<option value='" . $i . "' " . $selwrk1 . " >" . $i . "</option>";

		}

		$html = str_replace ( '{YEAR1}', $year1, $html );

		//$html.="</br>".$_SESSION['searchForm']['year1']."</br>";

		$year2 .= "<option value='0'>____</option>";

		if ($_SESSION ['searchForm'] ['year1'] > 0) {

			for($i = date ( 'Y' ); $i >= $_SESSION ['searchForm'] ['year1']; $i --) {

				$selwrk2 = ($i == $_SESSION ['searchForm'] ['year2']) ? " selected" : "";

				$year2 .= "<option value='" . $i . "' " . $selwrk2 . " >" . $i . "</option>";

			}



		}

		$query = "SELECT COUNT(*) FROM " . TABLE_AUTO . " a";

		$query .= ", AUTO_USERS b where a.ID_USER = b.id_author and b.locked=0 and b.lock_admin=0 and a.ACTIVE=1";

		if($_GET['mark_id']) $query.=" and CAR_MARK = ".$_GET['mark_id'];

		//$query.=($where!="")? " and ".$where:"";

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка';

			$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$total = mysql_result ( $res, 0, 0 );

		$html = str_replace ( '{TOTAL}', $total, $html );

		$html = str_replace ( '{YEAR2}', $year2, $html );



		if ($_SESSION ['searchForm'] ['date_list'] > 0) {

			$html = str_replace ( '{DATE_LIST' . $_SESSION ['searchForm'] ['date_list'] . '}', ' selected ', $html );

		} else {

			$html = str_replace ( '{DATE_LIST}', ' selected ', $html );

		}

		$html = str_replace ( '{PRICE1}', $PR = ($_SESSION ['searchForm'] ['price1'] != 0) ? $_SESSION ['searchForm'] ['price1'] : "", $html );

		$html = str_replace ( '{PRICE2}', $PR = ($_SESSION ['searchForm'] ['price2'] != 0) ? $_SESSION ['searchForm'] ['price2'] : "", $html );



	return $html;
}

function selectCity(){

	$Gpath = '<table align="left" width="100%"><tr><td align="left" class="title" colspan="3">Поиск по маркам/моделям
   <div class="path"><span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=mark_list">Поиск по маркам/моделям</a><span></div>
   </td></tr>';


	$html = $Gpath;

	$html.='<table width="100%" style="padding-left:30px;">
	<tbody><tr>
		<td width="45%" align="left"><span style="color: rgb(13, 116, 196); font-size: 22px; font-weight: bold;" id="regionTrigger">Выбрать регион</span></td>
		<td width="10%" align="left"><span style="color: rgb(13, 116, 196); font-size: 16px; font-weight: bold;">или</span></td>
		<td width="45%" align="center"><span style="color: rgb(13, 116, 196); font-size: 22px; font-weight: bold;" id="cityTrigger">Выбрать город</span></td>
	</tr>
	<tr>
		<td width="45%" style="vertical-align: top;" align="left">
			<div style="padding-top: 20px;">';

	$query_regions = "SELECT AUTO_REGION.ID, AUTO_REGION.REGION, COUNT( a.ID ) as count_ann FROM `AUTO_REGION` , `AUTO_CAR_BASE` a WHERE a.REGION = AUTO_REGION.ID GROUP BY AUTO_REGION.REGION";
	$result_regions = mysql_query($query_regions);
	while($myrow_regions = mysql_fetch_array($result_regions)){
		$html .= sprintf('<a href="http://vash_domen.ru/?action=mark_list&id_region=%s">%s&nbsp;(%s)</a><br>', $myrow_regions['ID'], $myrow_regions['REGION'], $myrow_regions['count_ann']);
	}

			$html .= '
</div>
		</td>
		<td></td>
		<td width="45%" style="vertical-align: top;"  align="left">
			<div style="padding-left: 43px; padding-top: 20px;">';

			$query_cities = "SELECT AUTO_CITY.ID, AUTO_CITY.CITY, COUNT( a.ID ) as count_ann, ID_REGION FROM `AUTO_CITY` , `AUTO_CAR_BASE` a WHERE a.CITY= AUTO_CITY.ID GROUP BY AUTO_CITY.CITY";
			$result_cities = mysql_query($query_cities);
			while($myrow_cities = mysql_fetch_array($result_cities)){
				$html .= sprintf('<a href="http://vash_domen.ru/?action=mark_list&id_region=%s&cityCode=%s">%s&nbsp;(%s)</a><br>', $myrow_cities['ID_REGION'], $myrow_cities['ID'], $myrow_cities['CITY'], $myrow_cities['count_ann']);
			}
			$html .= '</div>
		</td>
	</tr>
</tbody></table>';


	return $html;
}


function show_block() {

	$html3 = "<table width=\"194\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#EEEEEE\" align=\"right\"><tr><td >";



	$query = "SELECT * FROM AUTO_BLOCK where ACTIVE=1";

	//echo  $query;





	$res = @mysql_query ( $query );



	//$html3="<table width=\"100%\" bgcolor=\"#F5F5F5\" class=\"postTable2\">";

	$i = 1;

	while ( $row = @mysql_fetch_array ( $res ) ) {

		if ($i != 1)

			$img = "back_middle2.gif";

		else

			$img = "back_right2.gif";



		$html3 .= "<tr><td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#EEEEEE\" class=\"postTable6\" ><tr><td height=\"30\" bgcolor=\"#666666\" style=\"background:url(img/" . $img . ") no-repeat\" valign=\"middle\" >";



		$html3 .= "<div class=\"head\">";

		$html3 .= $row ["NAME"] . "</div></td></tr>";



		$query2 = "SELECT * FROM AUTO_BLOCK_CONT where ACTIVE=1 and ID_BLOCK=" . $row ["ID"];

		$res2 = @mysql_query ( $query2 );

		$i ++;

		while ( $row2 = @mysql_fetch_array ( $res2 ) ) {



			$html3 .= "<tr><td style=\"border-bottom-color:#EEEEEE; border-bottom:solid 0px;\" align=\"center\" valign=\"middle\" >";

			if ($row2 ["TYPE"] == 0) {

				$banner = "<a href=" . $row2 ["URL"] . " target=_blank><img src=\"banner/" . $row2 ["FILE"] . "\" border=\"0\" width=\"180\" /></a>";

			} elseif ($row2 ["TYPE"] == 1) {

				$banner = "<embed src=\"banner/" . $row2 ["FILE"] . "\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"/>";

			} else {

				$banner = "<a href=" . $row2 ["URL"] . " target=_blank>" . $row2 ["TEXT"] . "</a>";



			}

			$html3 .= $banner . "</td></tr>";

		}

		$html3 .= "</table>";



	}

	$html3 .= "</td></tr></table>";




	return $html3;

}



// Функция возвращает html главного меню форума

function getMainMenu() {

	$html = file_get_contents ( './templates/menu.html' );



	// $html = str_replace( '{LOGIN}', $action, $html );





	$action = "<a href=?action=showSearchAdv&del=1>Расширенный поиск</a>";



	$html = str_replace ( '{SEARCH}', $action, $html );



	$action = "<a href=?action=SaloonCity&del=1>Автосалоны</a>";

	$html = str_replace ( '{SEARCH_SALOONS}', $action, $html );



	$action = "<a href=?action=saveView>Мой блокнот</a>";

	$html = str_replace ( '{SAVE}', $action, $html );


	$action = "<a href=?action=sparesCats&id_typeCode=1&del=1><b>Товары и запчасти</b></a>";

	$html = str_replace ( '{SPARES}', $action, $html );



	// if ($_SESSION['user']['status']!="autosaloon")

	// {

	$action = "<tr><td><a href=?action=add><b>Разместить объявление</b></a></td></tr>";

	// }

	// else

	//$action ="";

	$html = str_replace ( '{ADDCAR}', $action, $html );



	$datetoday = mktime ( 0, 0, 0, date ( "m" ), date ( "d" ), date ( "Y" ) );

	$action = "<a href=?action=searchView&del=1&date_list=1>Выставленные " . date ( "d.m.Y" ) . "</a>";

	$html = str_replace ( '{TODAY}', $action, $html );



	$action = "<a href=?action=editCar>Изменить объявление</a>";

	$html = str_replace ( '{EDITCAR}', $action, $html );



	$action = "<a href=?action=MarkView&new=1&type=1>Новые автомобили</a>";

	$html = str_replace ( '{NEW_CAR}', $action, $html );



	$action = "<a href=?action=MarkView&new=0&type=1>Подержанные автомобили</a>";

	$html = str_replace ( '{OLD_CAR}', $action, $html );



	$action = "<a href=?action=MarkView&type=2>Грузовики и спецтехника</a>";

	// $action ="<a href=?action=searchView&id_typeCode=2&del=1>Грузовики и спецтехника</a>";

	$html = str_replace ( '{SPEC_CAR}', $action, $html );



	$action = "<a href=?action=MarkView&type=3>Легкие грузовики</a>";

	// $action ="<a href=?action=searchView&id_typeCode=3&del=1>Легкие грузовики</a>";

	$html = str_replace ( '{LIGHT_CAR}', $action, $html );



	$action = "<a href=?action=MarkView&type=4>Микроавтобусы</a>";

	//$action ="<a href=?action=searchView&id_typeCode=4&del=1>Микроавтобусы</a>";

	$html = str_replace ( '{MICRO_AVTO}', $action, $html );



	$action = "<a href=?action=MarkView&type=5>Автобусы</a>";

	//$action ="<a href=?action=searchView&id_typeCode=5&del=1>Автобусы</a>";

	$html = str_replace ( '{AVTOBUS}', $action, $html );



	$action = "<a href=?action=MarkView&type=6>Мото техника</a>";

	//$action ="<a href=?action=searchView&id_typeCode=6&del=1>Мото техника</a>";

	$html = str_replace ( '{MOTO}', $action, $html );



	$action = "<a href=?action=MarkView&type=7>Водный транспорт</a>";

	//$action ="<a href=?action=searchView&id_typeCode=7>Водный транспорт</a>";

	$html = str_replace ( '{VODNYI}', $action, $html );



	$action = "<a href=?action=MarkView&type=8>Снегоходы</a>";

	//$action ="<a href=?action=searchView&id_typeCode=8>Снегоходы</a>";

	$html = str_replace ( '{SNEGO}', $action, $html );



	$action = "<a href=?action=SaloonCity&del=1>Все автосалоны</a>";

	$html = str_replace ( '{SALOONS}', $action, $html );



	$action = "<a href=?action=showSaloon&id_typeCode=1&del=1>Легковые</a>";

	$html = str_replace ( '{LEGKOVYE_SAL}', $action, $html );



	$action = "<a href=?action=showSaloon&id_typeCode=2&del=1>Грузовые и спецтехника</a>";

	$html = str_replace ( '{GRUS_SAL}', $action, $html );



	$action = "<a href=?action=showSaloon&id_typeCode=3&del=1>Легкие грузовики</a>";

	$html = str_replace ( '{LEG_SAL} ', $action, $html );



	$action = "<a href=?action=showSaloon&id_typeCode=5&del=1>Автобусы</a>";

	$html = str_replace ( '{AVT_SAL}', $action, $html );



	$action = "<a href=?action=showSaloon&id_typeCode=4&del=1>Микроавтобусы</a>";

	$html = str_replace ( '{MICR_SAL}', $action, $html );



	$action = "<a href=?action=addNewSaloon>Добавить автосалон</a>";

	$html = str_replace ( '{ADD_SALOON}', $action, $html );



	$action = "<a href=?action=sendBack>Контакты</a>";

	$html = str_replace ( '{CONTACT}', $action, $html );



	$action = "<a href=?action=Reklama&id_blog=52>Реклама на сайте</a>";

	$html = str_replace ( '{REKLAMA}', $action, $html );



	//$action ="<a href=?action=AutoNews>Автоновости</a>";

	//$html = str_replace( '{AUTO_NEWS}', $action, $html );

	$query3 = "SELECT * FROM AUTO_BLOG_TYPE where ACTIVE=1 ";

	$action = "";

	$res3 = @mysql_query ( $query3 );

	while ( $row3 = @mysql_fetch_array ( $res3 ) ) {

		$action .= "<tr><td><a href=?action=Blog&id=" . $row3 ["ID"] . ">" . $row3 ["NAME"] . "</a></td></tr>";

	}

	$action .= "<tr><td>{sape_link3}</td></tr>";

	$html = str_replace ( '{BLOG}', $action, $html );

	return $html;

}



function addCarSubmit() {
//car


	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}



	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input



	$name = substr ( $_POST ['x_FIO'], 0, 60 );

	$email = substr ( $_POST ['x_mail'], 0, 60 );

	$tel1 = substr ( $_POST ['x_tel1'], 0, 40 );




	$descr = substr ( $_POST ['x_DESCR'], 0, 500 );


	// Обрезаем лишние пробелы

	$name = trim ( $name );

	$email = trim ( $email );

	$tel1 = trim ( $tel1 );


	$name = RemoveXSS ( $name );

	$email = RemoveXSS ( $email );

	$tel1 = RemoveXSS ( $tel1 );

	$price = round ( abs ( RemoveXSS ( $_POST ['x_PRICE'] ) ) );



	$regionCode = intval ( $_POST ['regionCode'] );

	$cityCode = intval ( $_POST ['cityCode'] );



	$id_typeCode = intval ( $_POST ['id_typeCode'] );

	$id_markCode = intval ( $_POST ['id_markCode'] );

	$id_modelCode = intval ( $_POST ['id_modelCode'] );



	$yearCode = (! ( $_POST ['yearCode'] )) ? "" : intval ( $_POST ['yearCode'] );



	$sostoyanie = ($_POST ['x_SOST'] != "") ? abs ( intval ( $_POST ['x_SOST'] ) ) : "";

	$probeg = ($_POST ['x_PROBEG'] != "") ? abs ( intval ( $_POST ['x_PROBEG'] ) ) : "";

	$dvig = ($_POST ['x_DVIG'] != "") ? abs ( intval ( $_POST ['x_DVIG'] ) ) : "";

	$obem = ($_POST ['x_V_DVIG'] != "") ? RemoveXSS ( $_POST ['x_V_DVIG'] ) : "";

	$power = ($_POST ['x_POWER'] != "") ? RemoveXSS ( $_POST ['x_POWER'] ) : "";

	$obem = strtr($obem, ',', '.');

	$privod = ($_POST ['x_TYPE_PRIV'] != "") ? abs ( intval ( $_POST ['x_TYPE_PRIV'] ) ) : "";

	$peredach = ($_POST ['x_KOL_PERED'] != "") ? abs ( intval ( $_POST ['x_KOL_PERED'] ) ) : "";



	$kuzov = ($_POST ['x_TYPE_KUZ'] != "") ? abs ( intval ( $_POST ['x_TYPE_KUZ'] ) ) : "";

	$dverey = ($_POST ['x_DVEREY'] != "") ? abs ( intval ( $_POST ['x_DVEREY'] ) ) : "";



	$color = ($_POST ['x_COLOR'] != "") ? abs ( intval ( $_POST ['x_COLOR'] ) ) : "";



	$metallik = (isset ( $_POST ['x_METALL'] )) ? "1" : "0";

	$prav_rul = (isset ( $_POST ['x_PRAV_RUL'] )) ? "1" : "0";

	$akpp = (isset ( $_POST ['x_AKPP'] )) ? "1" : "0";

	$nerastamog = (isset ( $_POST ['x_NE_RASTAM'] )) ? "1" : "0";

	$bezprobeg = (isset ( $_POST ['x_BEZ_PROB'] )) ? "1" : "0";





	$descr = RemoveXSS ( $_POST ['x_DESCR'] );



	if ($probeg == "0")

		$new = 1;

	else

		$new = 0;

	$new = (isset ( $_POST ['x_NEW'] )) ? "1" : "0";

	// Проверяем, заполнены ли обязательные поля

	$error = '';




	if (! preg_match ( "|^[\d]*[\.,]?[\d]*$|", $obem ))



	{

		$error = $error . '<li>Не верный формат объема двигателя</li>' . "\n";

		$obem = "";

	}





	if (strlen ( $about ) > 500)

		$error = $error . '<li>длина поля "Описание" более 500 символов</li>' . "\n";

	if ($id_typeCode <= 0)

		$error = $error . '<li>Не выбран тип ТС</li>' . "\n";

	if ($id_markCode <= 0)

		$error = $error . '<li>Не выбрана марка</li>' . "\n";

	if ($id_modelCode <= 0)

		$error = $error . '<li>Не выбрана модель</li>' . "\n";

	if (!$yearCode)

		$error = $error . '<li>Не выбран год выпуска</li>' . "\n";


	if ($price <= 0)

		$error = $error . '<li>Не указана стоимость</li>' . "\n";

if ($_SESSION['user']['status'] === 'admin') {

		if (empty ( $email ))

			$error = $error . '<li>не заполнено поле "E-mail"</li>' . "\n";

		if (! empty ( $email ) and ! preg_match ( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ))

			$error = $error . '<li>поле "Адрес e-mail" должно соответствовать формату somebody@somewhere.ru</li>' . "\n";

		if (empty ( $name ))

			$error = $error . '<li>не заполнено поле "ФИО"</li>' . "\n";

		if (! empty ( $name ) and ! preg_match ( "#^[- _0-9a-zА-Яа-я]+$#i", $name ))

			$error = $error . '<li>поле "Имя" содержит недопустимые символы</li>' . "\n";

		if (empty ( $tel1 ))

			$error = $error . '<li>не заполнено поле "Телефон"</li>' . "\n";

	if ($regionCode <= 0)

		$error = $error . '<li>не выбран регион</li>' . "\n";

	if ($cityCode <= 0)

		$error = $error . '<li>не выбран город</li>' . "\n";

}








///////////
if ($_SESSION ['user'] ['status'] === "user") {

	$query = "SELECT COUNT(*) FROM " . TABLE_AUTO . "

		    WHERE ID_USER='" .  $_SESSION['user']['id_author'] . "'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при регистрации нового Объявления';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$c1=mysql_fetch_row($res);



	$COUNT_AD = $c1[0];

	if ($COUNT_AD  >= 3) {



		$msg = '<b><br><center><p>Вы не можете подать более 3 объявлений!</p></center></b></b>';

		return showInfoMessage ( $msg, '' );

	}
	}
	////////////////////

//ктокто


	// Проверяем корректность URL домашней странички

	//  if ( !empty( $url ) and !preg_match( "#^(http:\/\/)?(www.)?[-0-9a-z]+\.[a-z]{2,6}\/?$#i", $url ) )

	//    $error = $error.'<li>поле "Домашняя страничка" должно соответствовать формату http://www.homepage.ru</li>'."\n";



	$IMGCOUNT = 6;

	for($ii = 1; $ii <= $IMGCOUNT; $ii ++) {

		if (! empty ( $_FILES ['x_PHOTO_' . $ii] ['name'] )) {

			//$ext = strrchr ( $_FILES ['x_PHOTO_' . $ii] ['name'], "." );

			unset($imgS);
			$imgS = getimagesize($_FILES ['x_PHOTO_' . $ii]['tmp_name']);

			$extensions = array ("image/jpg", "image/png", "image/jpeg", "image/gif" );

//&&&&&&&&&&&&&&&&//

			if (! in_array ( strtolower($imgS['mime']), $extensions ))

				$error = $error . '<li>Недопустимый формат файла №' . $ii . '</li>' . "\n";



			if ($_FILES ['x_PHOTO_' . $ii] ['size'] > MAX_PHOTO_SIZE)

				$error = $error . '<li>Размер файла №' . $ii . ' больше ' . (MAX_PHOTO_SIZE / 1024) . ' Кб</li>' . "\n";

		}

	}



	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['name'] = $name;

		$_SESSION ['carbase'] ['email'] = $email;

		$_SESSION ['carbase'] ['tel1'] = $tel1;

		$_SESSION ['carbase'] ['price'] = $price;

		$_SESSION ['carbase'] ['region'] = $regionCode;

		$_SESSION ['carbase'] ['city'] = $cityCode;

		$_SESSION ['carbase'] ['descr'] = $descr;

		$_SESSION ['carbase'] ['yearCode'] = $yearCode;

		$_SESSION ['carbase'] ['sostoyanie'] = $sostoyanie;

		$_SESSION ['carbase'] ['probeg'] = $probeg;

		$_SESSION ['carbase'] ['id_typeCode'] = $id_typeCode;

		$_SESSION ['carbase'] ['id_markCode'] = $id_markCode;

		$_SESSION ['carbase'] ['id_modelCode'] = $id_modelCode;

		$_SESSION ['carbase'] ['dvig'] = $dvig;

		$_SESSION ['carbase'] ['obem'] = $obem;

		$_SESSION ['carbase'] ['power'] = $power;

		$_SESSION ['carbase'] ['privod'] = $privod;

		$_SESSION ['carbase'] ['peredach'] = $peredach;

		$_SESSION ['carbase'] ['PRAV_RUL'] = $prav_rul;

		$_SESSION ['carbase'] ['AKPP'] = $akpp;

		$_SESSION ['carbase'] ['kuzov'] = $kuzov;

		$_SESSION ['carbase'] ['dverey'] = $dverey;

		$_SESSION ['carbase'] ['METALL'] = $metallik;

		$_SESSION ['carbase'] ['color'] = $color;

		$_SESSION ['carbase'] ['NE_RASTAM'] = $nerastamog;

		$_SESSION ['carbase'] ['BEZ_PROB'] = $bezprobeg;

		$_SESSION ['carbase'] ['new'] = $new;

$_SESSION ['carbase'] ['photo_1'] = $_FILES ['x_PHOTO_1']['name'];

$_SESSION ['carbase'] ['photo_2'] = $_FILES ['x_PHOTO_2']['name'];

$_SESSION ['carbase'] ['photo_3'] = $_FILES ['x_PHOTO_3']['name'];



	$_SESSION ['carbase'] ['photo_4'] = $_FILES ['x_PHOTO_4']['name'];

	$_SESSION ['carbase'] ['photo_5'] = $_FILES ['x_PHOTO_5']['name'];

	$_SESSION ['carbase'] ['photo_6'] = $_FILES ['x_PHOTO_6']['name'];




		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=addCar' );

		die ();

	}




//car1
	// Формируем SQL-запрос
if (($_SESSION ['user'] ['status'] === "autosaloon") || ($_SESSION ['user'] ['status'] === "user") || ($_SESSION ['user'] ['status'] === "admin")) {
//

//car2





        $img_1="";

        $img_2="";

        $img_3="";

        $img_4="";

        $img_5="";

		$img_6="";

        //echo $_FILES ['x_PHOTO_1']['tmp_name'];

		if (! empty ( $_FILES ['x_PHOTO_1'] ['tmp_name'] )) {

			$img_1 = water ($_FILES ['x_PHOTO_1'] );

		}

		if (! empty ( $_FILES ['x_PHOTO_2'] ['tmp_name'] )) {

			$img_2 = water ( $_FILES ['x_PHOTO_2'] );

		}

		if (! empty ( $_FILES ['x_PHOTO_3'] ['tmp_name'] )) {

			$img_3 = water ( $_FILES ['x_PHOTO_3']  );

		}

		if (! empty ( $_FILES ['x_PHOTO_4'] ['tmp_name'] )) {

			$img_4 = water ( $_FILES ['x_PHOTO_4']  );

		}

		if (! empty ( $_FILES ['x_PHOTO_5'] ['tmp_name'] )) {

			$img_5 = water ( $_FILES ['x_PHOTO_5']  );

		}

		if (! empty ( $_FILES ['x_PHOTO_6'] ['tmp_name'] )) {

			$img_5 = water ( $_FILES ['x_PHOTO_6']  );

		}

if ($_SESSION['user']['status'] === 'admin') {

	$query = "INSERT INTO " . TABLE_USERS . "

		    (

		    name,

		    passw,

		    email,

			puttime,

			last_visit,

			status,locked,lock_admin,

		    tel1,

		    region,

		    city


		    )

		    VALUES

		    (

		    '" . $name . "',

		    '" . mysql_real_escape_string ( md5 ( 'siberia-auto_ru777' ) ) . "',

		    '" . mysql_real_escape_string ( $email ) . "',

			NOW(),

			NOW(),

			'user','0','0',

		    '" . mysql_real_escape_string ( $tel1 ) . "',

		    '" . mysql_real_escape_string ( $regionCode ) . "',

		    '" . mysql_real_escape_string ( $cityCode ) . "'




		    );";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при регистрации нового пользователя';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$id = mysql_insert_id ();
}



		if ($_POST ['a_add'] == "A") {

		if ($_SESSION['user']['status'] !== 'admin') {
			$query = "SELECT * FROM AUTO_USERS

		    WHERE id_author='" .  $_SESSION['user']['id_author'] . "'";

			$res = mysql_query ( $query );
			for($data=array();$row=mysql_fetch_assoc($res);$data=$row);
			$regionCode=$data['region'];
			$cityCode=$data['city'];
		}

			$query = "INSERT INTO AUTO_CAR_BASE

		SET
		`CAR_TYPE`='" . mysql_escape_string ( $id_typeCode ) . "',
		`CAR_MARK`='" . mysql_escape_string ( $id_markCode ) . "',
		`CAR_MODEL`='" . mysql_escape_string ( $id_modelCode ) . "',
		`REGION`=" . mysql_escape_string ( $regionCode ) . ",
		`CITY`=" . mysql_escape_string ( $cityCode ) . ",
		`NEW`='" . mysql_escape_string ( $new ) . "',
		`PRAV_RUL`='" . mysql_escape_string ( $prav_rul ) . "',
		`YEAR_VYP`='" . mysql_escape_string ( $yearCode ) . "',
		`SOST`='" . mysql_escape_string ( $sostoyanie ) . "',
		`PROBEG`='" . mysql_escape_string ( $probeg ) . "',
		`PRICE`='" . mysql_escape_string ( $price ) . "',
		`TYPE_DVIG`='" . mysql_escape_string ( $dvig ) . "',
		`V_DVIG`='" . mysql_escape_string ( $obem ) . "',
		`POWER`='" . mysql_escape_string ( $power ) . "',
		`TYPE_PRIV`='" . mysql_escape_string ( $privod ) . "',
		`KOL_PERED`='" . mysql_escape_string ( $peredach ) . "',
		`AKPP`='" . mysql_escape_string ( $akpp ) . "',
		`TYPE_KUZ`='" . mysql_escape_string ( $kuzov ) . "',
		`DVEREY`='" . mysql_escape_string ( $dverey ) . "',
		`COLOR`='" . mysql_escape_string ( $color ) . "',
		`METALL`='" . mysql_escape_string ( $metallik ) . "',
		`NE_RASTAM`='" . mysql_escape_string ( $nerastamog ) . "',
		`BEZ_PROB`='" . mysql_escape_string ( $bezprobeg ) . "',
		`DATE_VVOD`=NOW(),
		`DESCR`='" . mysql_escape_string ( $descr ) . "',
		".(($_SESSION['user']['status'] !== 'admin') ? "`ID_USER`='" . $_SESSION['user']['id_author'] . "'," : "`ID_USER`='" . $id . "',")."
		`PHOTO_1`='".$img_1."',
		`PHOTO_2`='".$img_2."',
		`PHOTO_3`='".$img_3."',
		`PHOTO_4`='".$img_4."',
		`PHOTO_5`='".$img_5."',
		`PHOTO_6`=  '".$img_6. "'";

			$res = mysql_query ( $query );



			if (! $res) {

				$msg = 'Ошибка при добавлении автомобиля';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addyesSpares.html' );

			$html = str_replace ( '{URL}', '?action=ShowCar&id='.$ID_CAR, $html );

			$html = str_replace ( '{edit}', '?action=editCar&id='.$ID_CAR, $html );

		}




//
}

	return $html;



}



function add() {

	$_SESSION ['pageTitle'] = "Размещение объявления";



	$_SESSION ['url'] = $_SERVER ['PHP_SELF'] . "?action=addCar";

	if (! isset ( $_SESSION ['user'] )) {

		$_SESSION ['action_url'] = "add";

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . "?action=loginForm" );

		die ();



	} else {

		header ( 'Location: http://' . $_SERVER ['SERVER_NAME'] . "/index.php?action=addChoose" );

		die ();

	}



}

function addChoose () {

	$html = file_get_contents ( './templates/addChoose.html' );

	$action = $_SERVER ['PHP_SELF'] . "?action=addChooseSubmit";

	$html = str_replace ( '{action}', $action, $html );

	return $html;
}

function addChooseSubmit () {

	if ($_REQUEST['razd'] === "1") {

		header ( 'Location: http://' . $_SERVER ['SERVER_NAME'] . "/index.php?action=addCar" );

		die ();



	} else

	if ($_REQUEST['razd'] === "2")	{

		header ( 'Location: http://' . $_SERVER ['SERVER_NAME'] . "/index.php?action=addSpares" );

		die ();

	}

}

function water($image) {

	$ext = pathinfo($image['name']);

	$ext = strtolower($ext['extension']);

	$watermark = new watermark ( );
	if (($ext==="jpg") || ($ext==="jpeg"))
		$main_img_obj = @imagecreatefromjpeg ( $image["tmp_name"] );
	else if ($ext==="png")
		$main_img_obj = @imagecreatefrompng ( $image["tmp_name"] );
	else if ($ext==="gif")
		$main_img_obj = @imagecreatefromgif ( $image["tmp_name"] );

	//echo $ext;

	//$main_img_obj = @imagecreatefromjpeg ( $image );

	$watermark_img_obj = @imagecreatefrompng ( 'img/watermark3.png' );

	$return_img_obj = $watermark->create_watermark ( $main_img_obj, $watermark_img_obj, 640, 50 );





	$new_name = uniqid () . '.' . (($ext==="png") ? "jpg" : $ext );


	if (($ext==="jpg") || ($ext==="jpeg"))
		imagejpeg ( $return_img_obj, 'photo/' . $new_name, 65 );
	else if ($ext==="png")
		imagejpeg ( $return_img_obj, 'photo/' . $new_name, 65 );
	else if ($ext==="gif")
		imagegif ( $return_img_obj, 'photo/' . $new_name, 65 );

	//imagejpeg ( $return_img_obj, 'photo/' . $new_name, 65 );

	imagedestroy ( $return_img_obj );



	return $new_name;

}

function water1($image) {

	$ext = pathinfo($image['name']);

	$ext = strtolower($ext['extension']);

	$watermark = new watermark ( );
	if (($ext==="jpg") || ($ext==="jpeg"))
		$main_img_obj = @imagecreatefromjpeg ( $image["tmp_name"] );
	else if ($ext==="png")
		$main_img_obj = @imagecreatefrompng ( $image["tmp_name"] );
	else if ($ext==="gif")
		$main_img_obj = @imagecreatefromgif ( $image["tmp_name"] );



	//$watermark_img_obj = @imagecreatefrompng ( 'img/watermark3.png' );

	//$return_img_obj = $watermark->create_watermark ( $main_img_obj, $watermark_img_obj, 640, 50 );



	//$ext = strtolower ( preg_replace ( "/.+\.(.*)$/", "\\1", $image ) );

	$new_name = uniqid () . '.' . (($ext==="png") ? "jpg" : $ext );


	if (($ext==="jpg") || ($ext==="jpeg"))
		imagejpeg ( $main_img_obj, 'photo_saloon/' . $new_name, 65 );
	else if ($ext==="png")
		imagepng ( $main_img_obj, 'photo_saloon/' . $new_name, 65 );
	else if ($ext==="gif")
		imagegif ( $main_img_obj, 'photo_saloon/' . $new_name, 65 );

	imagedestroy ( $main_img_obj );



	return $new_name;

}

function water2($image) {

	$ext = pathinfo($image['name']);

	$ext = strtolower($ext['extension']);

	$watermark = new watermark ( );
	if (($ext==="jpg") || ($ext==="jpeg"))
		$main_img_obj = @imagecreatefromjpeg ( $image["tmp_name"] );
	else if ($ext==="png")
		$main_img_obj = @imagecreatefrompng ( $image["tmp_name"] );
	else if ($ext==="gif")
		$main_img_obj = @imagecreatefromgif ( $image["tmp_name"] );



	//$watermark_img_obj = @imagecreatefrompng ( 'img/watermark3.png' );

	//$return_img_obj = $watermark->create_watermark ( $main_img_obj, $watermark_img_obj, 640, 50 );



	//$ext = strtolower ( preg_replace ( "/.+\.(.*)$/", "\\1", $image ) );

	$new_name = uniqid () . '.' . (($ext==="png") ? "jpg" : $ext );


	if (($ext==="jpg") || ($ext==="jpeg"))
		imagejpeg ( $main_img_obj, 'photo/' . $new_name, 65 );
	else if ($ext==="png")
		imagepng ( $main_img_obj, 'photo/' . $new_name, 65 );
	else if ($ext==="gif")
		imagegif ( $main_img_obj, 'photo/' . $new_name, 65 );

	imagedestroy ( $main_img_obj );



	return $new_name;

}

function addCar() {



	$_SESSION ['pageTitle'] = "Размещение объявления – Автомобили";




	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}





if (($_SESSION ['user'] ['status'] === "autosaloon") || ($_SESSION ['user'] ['status'] === "user")) {

	$html .= file_get_contents ( './templates/addCarReg.html' );

} else
if (($_SESSION ['user'] ['status'] === "admin")) {

	$html .= file_get_contents ( './templates/addCarAdm.html' );

} else {

	die();

}
//car
	$action = $_SERVER ['PHP_SELF'] . '?action=addCarSubmit';

	$html = str_replace ( '{action}', $action, $html );



	$query = "SELECT * FROM AUTO_CAR_TYPE order by CAR_TYPE ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($typelist ['ID'] == $_SESSION ['carbase'] ['id_typeCode']) ? " selected" : "";

			//$type.="<option value='".$typelist['ID']."' ".$selwrk." >".$typelist['CAR_TYPE']."</option>";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . ">" . $typelist ['CAR_TYPE'] . "</option>";



		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );



	if (isset ( $_SESSION ['carbase'] ['id_typeCode'] )) {

		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $_SESSION ['carbase'] ['id_typeCode'] . "  order by TRADEMARK";



		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок2';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $_SESSION ['carbase'] ['id_markCode']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );



	}



	if (isset ( $_SESSION ['carbase'] ['id_markCode'] )) {

		$query = "SELECT * FROM AUTO_MODEL where TRADEMARK=" . $_SESSION ['carbase'] ['id_markCode'] . " order by MODEL";

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка моделй';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$model = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $modellist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($modellist ['ID'] == $_SESSION ['carbase'] ['id_modelCode']) ? " selected" : "";

				$model .= "<option value='" . $modellist ['ID'] . "' " . $selwrk . " >" . $modellist ['MODEL'] . "</option>";

			}

		}

		$html = str_replace ( '{MODEL}', $model, $html );

	}



	$year1 = "";

	//$year1 .= "<option value='0'>____</option>";

	for($i = date ( 'Y' ); $i > 1949; $i --) {

		$selwrk1 = (intval($i) === intval($_SESSION ['carbase'] ['yearCode'])) ? " selected" : "";

		//$selwrk2 = ($i==$_SESSION['searchForm']['year2']) ? " selected" : "";





		//$year1.="<option value='".$i."' >".$i."</option>";

		$year1 .= "<option value='" . $i . "' " . $selwrk1 . " >" . $i . "</option>";

	}

	$html = str_replace ( '{YEAR1}', $year1, $html );



	$query = "SELECT * FROM AUTO_REGION order by REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка регионов';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] == $_SESSION ['carbase'] ['region']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . ">" . $regionlist ['REGION'] . "</option>";

		}

	}

	$html = str_replace ( '{REGION}', $region, $html );



	if (isset ( $_SESSION ['carbase'] ['region'] )) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $_SESSION ['carbase'] ['region'] . " order by CITY";

		//	$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $_SESSION ['carbase'] ['REGION'];

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка городов';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$city = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $citylist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($citylist ['ID'] == $_SESSION ['carbase'] ['city']) ? " selected" : "";

				$city .= "<option value='" . $citylist ['ID'] . "' " . $selwrk . ">" . $citylist ['CITY'] . "</option>";

			}

		}

		$html = str_replace ( '{CITY}', $city, $html );

	}

	$query = "SELECT * FROM AUTO_SOST";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка состояний';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($sostlist ['ID'] == $_SESSION ['carbase'] ['sostoyanie']) ? " selected" : "";

			$sost .= "<option value='" . $sostlist ['ID'] . "' " . $selwrk . " >" . $sostlist ['SOST'] . "</option>";

		}

	}

	$html = str_replace ( '{SOSTOYANIE}', $sost, $html );



	$query = "SELECT * FROM AUTO_TYPE_DVIG ORDER BY TYPE_DVIG";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка типов двигателей';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($sostlist ['ID'] == $_SESSION ['carbase'] ['dvig']) ? " selected" : "";

			$sost .= "<option value='" . $sostlist ['ID'] . "' " . $selwrk . " >" . $sostlist ['TYPE_DVIG'] . "</option>";

		}

	}

	$html = str_replace ( '{DVIG}', $sost, $html );



	$query = "SELECT * FROM AUTO_TYPE_KUZ ORDER BY TYPE_KUZ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка типов кузовов';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($sostlist ['ID'] == $_SESSION ['carbase'] ['kuzov']) ? " selected" : "";

			$sost .= "<option value='" . $sostlist ['ID'] . "' " . $selwrk . " >" . $sostlist ['TYPE_KUZ'] . "</option>";

		}

	}

	$html = str_replace ( '{KUZOV}', $sost, $html );



	$query = "SELECT * FROM AUTO_COLOR ORDER BY COLOR";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка цветов';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($sostlist ['ID'] == $_SESSION ['carbase'] ['color']) ? " selected" : "";

			$sost .= "<option value='" . $sostlist ['ID'] . "' " . $selwrk . " >" . $sostlist ['COLOR'] . "</option>";

		}

	}

	$html = str_replace ( '{COLOR}', $sost, $html );



	$selwrk1 = ($_SESSION ['carbase'] ['privod'] == 1) ? " selected>" : ">";

	$selwrk2 = ($_SESSION ['carbase'] ['privod'] == 2) ? " selected>" : ">";

	$selwrk3 = ($_SESSION ['carbase'] ['privod'] == 3) ? " selected>" : ">";



	$privod = "<option value=\"0\">- Выберите -</option>";

	$privod .= "<option value=\"1\"" . $selwrk1 . "Заднеприводной</option>";

	$privod .= "<option value=\"2\"" . $selwrk2 . "Переднеприводной</option>";

	$privod .= "<option value=\"3\"" . $selwrk3 . "Полноприводной</option>";



	$html = str_replace ( '{PRIVOD}', $privod, $html );

//	$html = str_replace ( '{PRICE}',$PRICE=(abs(round( $_SESSION ['carbase'] ['price']))>0)?abs(round( $_SESSION ['carbase'] ['price']):"", $html );

	$PRICE=(abs(round( $_SESSION ['carbase'] ['price']))>0)?abs(round( $_SESSION ['carbase'] ['price'])):"";

	$html = str_replace ( '{PRICE}',$PRICE, $html );

	$html = str_replace ( '{PROBEG}', $_SESSION ['carbase'] ['probeg'], $html );

	$html = str_replace ( '{OBEM}', $_SESSION ['carbase'] ['obem'], $html );

	$html = str_replace ( '{POWER}', $_SESSION ['carbase'] ['power'], $html );

	$html = str_replace ( '{PEREDACH}', $_SESSION ['carbase'] ['peredach'], $html );

	$html = str_replace ( '{DVEREY}', $_SESSION ['carbase'] ['dverey'], $html );

	$html = str_replace ( '{DESCR}', $_SESSION ['carbase'] ['descr'], $html );

	$html = str_replace ( '{PHOTO_1}', $_SESSION ['carbase'] ['photo_1'], $html );

$html = str_replace ( '{PHOTO_2}',$_SESSION ['carbase'] ['photo_2'], $html );

$html = str_replace ( '{PHOTO_3}', $_SESSION ['carbase'] ['photo_3'], $html );

$html = str_replace ( '{PHOTO_4}', $_SESSION ['carbase'] ['photo_4'], $html );

$html = str_replace ( '{PHOTO_5}', $_SESSION ['carbase'] ['photo_5'], $html );



	$html = str_replace ( '{CHECKED_NEW}', $check = ($_SESSION ['carbase'] ['new'] == 1) ? " checked=\"checked\" " : "", $html );



	$html = str_replace ( '{PRAV_RUL_CHECKED}', $check = ($_SESSION ['carbase'] ['PRAV_RUL'] == 1) ? " checked=\"checked\" " : "", $html );

	$html = str_replace ( '{AKPP_CHECKED}', $check = ($_SESSION ['carbase'] ['AKPP'] == 1) ? " checked=\"checked\" " : "", $html );

	$html = str_replace ( '{METALLIK_CHECKED}', $check = ($_SESSION ['carbase'] ['METALL'] == 1) ? " checked=\"checked\" " : "", $html );

	$html = str_replace ( '{NERASTAMOG_CHECKED}', $check = ($_SESSION ['carbase'] ['NE_RASTAM'] == 1) ? " checked=\"checked\" " : "", $html );

	$html = str_replace ( '{BEGPROBEG_CHECKED}', $check = ($_SESSION ['carbase'] ['BEZ_PROB'] == 1) ? " checked=\"checked\" " : "", $html );


	$tpl = $html;


	$tpl = str_replace ( '{FIO}', $_SESSION ['carbase'] ['name'], $tpl );

	$tpl = str_replace ( '{EMAIL}', $_SESSION ['carbase'] ['email'], $tpl );

	$tpl = str_replace ( '{TEL1}', $_SESSION ['carbase'] ['tel1'], $tpl );



	unset ( $_SESSION ['carbase'] );




	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=myCar">Мои автомобили</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=addCar">Размещение объявление</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );



	return $tpl;



}



// каталог моделей





function MarkView() {



	unset ( $_SESSION ['searchForm'] );

	$numcolumn = 3;

	$html = "<table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" width=\"100%\" valign=top align=center><tr>";

	$tc = 0;

	$tdwidth = intval ( 100 / $numcolumn );



	$query = "SELECT count(a.ID) as COUNT, a.CAR_MARK, i.TRADEMARK FROM " . TABLE_AUTO . " a, AUTO_USERS c, AUTO_TRADEMARK i where ";

	$query .= "i.ID=a.CAR_MARK  and a.ACTIVE=1 ";



	// $query = "SELECT count(ID), f.MODEL,i.TRADEMARK, b.CAR_TYPE as TYPE FROM ".TABLE_AUTO.

	// " a, AUTO_CAR_TYPE b, AUTO_USERS c, AUTO_MODEL f,AUTO_TRADEMARK i where ";

	// $query.= "b.ID=a.CAR_TYPE ";

	// $query.= " and f.ID=a.CAR_MODEL";

	// $query.= " and i.ID=a.CAR_MARK";

	$query .= " and (a.ID_USER=c.id_author and c.locked=0 and c.lock_admin=0)";

	$query .= (isset ( $_GET ['new'] )) ? " and a.NEW=" . mysql_escape_string ( intval ( $_GET ['new'] ) ) : "";

	$query .= (isset ( $_GET ['type'] )) ? " and a.CAR_TYPE=" . mysql_escape_string ( intval ( $_GET ['type'] ) ) : "";

	/*

	if (isset($_GET['new'])) {



	$new=(int)$_GET['new'];

	$query.= ($new>="0") ? " and a.NEW=".$new : "";

	}



	if (isset($_GET['type'])) {



	$type=(int)$_GET['type'];

	$query.= ($type>"0") ? " and a.CAR_TYPE=".$type: "";

	}



	*/



	$query .= " group by a.CAR_MARK, i.TRADEMARK";



	//   $html.=$query;

	//$count_rows=mysql_num_rows($res);

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

		//$html.=$query;

	}

	/*

	$total = mysql_result( $res, 0, 0 );



	if ( $total == 0 ) {

	$html.=file_get_contents( './templates/searchno.html' );

	return $html;

	}*/

	//$html.=$total;





	while ( $row = @mysql_fetch_array ( $res ) ) {

		//	$html.=$tc;

		$count = $row ["COUNT"];

		// $html=$count;

		$car_mark = $row ["CAR_MARK"];

		$title = $row ["TRADEMARK"];

		$car_type = $row ["CAR_TYPE"];



		$view_new_old = "";



		$view_new_old = "";

		$new_link = "";

		if (isset ( $_GET ['new'] ))

			$new_link = "&new=" . abs ( intval ( $_GET ['new'] ) );

		$html .= "<td valign=\"top\" align=left width=\"" . $tdwidth . "%\" style=\" border-right:dotted; border-color:#cccccc; border-width:1px;\"  >";

		$html .= "<a class=cat href=?action=ModelView&mark=" . $car_mark . $new_link . "&type=" . abs ( intval ( $_GET ['type'] ) ) . ">" . $title . "</a> - " . $count . " " . $view_new_old;







		$tc ++;

		if ($tc == $numcolumn) {

			$tc = 0;

			$html .= "</td></tr><tr>";

		} else {

			$html .= "</td>";

		}

	}

	$html .= "</table>";



	$html2 = file_get_contents ( './templates/catalogForm.html' );

	$titul = "Каталог";



	if ((intval ( $_GET ['new'] >= 0 ) and (intval ( $_GET ['type'] ) == 1))) {

		$titul .= (intval ( $_GET ['new'] ) == 1) ? " > Новые автомобили" : " > Подержанные автомобили";

	}



	/* Категория техники */



	/* Категория техники */



	$CAR_TYPE2 = "";

	if (isset ( $_GET ['type'] )) {

		$query3 = "SELECT * FROM `AUTO_CAR_TYPE` WHERE ID=" . $_GET ['type'];

		$res3 = @mysql_query ( $query3 );

		while ( $row3 = @mysql_fetch_array ( $res3 ) ) {



			$CAR_TYPE2 = $row3 ["CAR_TYPE"];

			$car_type = $row3 ["CAR_TYPE"];

		}



	}



	if (intval ( $_GET ['type'] ) > 1) {

		$titul .= $CAR_TYPE2 != "" ? " >" . $CAR_TYPE2 : "";

	}



	$html2 = str_replace ( '{CAR}', $car_type, $html2 );



	$titul .= ($CAR_TYPE2 != "") ? " > " . $CAR_TYPE2 : "";

	$_SESSION ['pageTitle'] = $car_type;//$titul;





	$html2 = str_replace ( '{notebook}', $html, $html2 );




	//;;;;;;;;;;;;;;;;//
	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span>'.($car_type ? ' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=MarkView&type='.($_REQUEST['type'] ? $_REQUEST['type'] : '').'">'.$car_type.'</a></span>' : '').(isset($_REQUEST['new']) ? ' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=MarkView&new='.($_REQUEST['new'] ? '1' : '0').'&type='.($_REQUEST['type'] ? $_REQUEST['type'] : '1').'">'.($_REQUEST['new'] ? 'Новые автомобили' : 'Подержанные автомобили').'</a></span>' : '');//.($_REQUEST['mark'] ? ' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=ModelView&mark='.$car_mark.'&new='.($new ? '1' : '0').'&type=1">'.strtolower(trim($row ["TRADEMARK"])).'</a></span>' : '').'$'.' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=searchView&id_typeCode='.$car_type.'&id_markCode='.$car_mark.'&id_modelCode='.$car_tr.'&new='.($new ? '1' : '0').'&type=1">'.strtolower($car_model).'</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

	return $html2;



}



function ListView() {

	/*

	$_SESSION['searchForm']['id_typeCode']=$_GET['type'];

	$_SESSION['searchForm']['id_markCode']=$_GET['car_mark'];

	$_SESSION['searchForm']['id_modelCode']=$_GET['car_model'];

	*/

	$html = searchView ();

	//$html=ShowTableCar("",'showSearchForm','ShowCar');





	return $html;

}



function SaloonCity() {



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ["searchSaloon"] );



	}

	$_SESSION ['pageTitle'] = "Автосалоны";

	$html = file_get_contents ( './templates/searchSaloon.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=showSaloon';

	$html = str_replace ( '{action}', $action, $html );

	$html = str_replace ( '{SALOON}', $sal = isset ( $_SESSION ["searchSaloon"] ["saloonname"] ) ? $_SESSION ["searchSaloon"] ["saloonname"] : "", $html );



	$query = "SELECT * FROM AUTO_CAR_TYPE";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($typelist ['ID'] == $_SESSION ['searchSaloon'] ['id_typeCode']) ? " selected" : "";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . " >" . $typelist ['CAR_TYPE'] . "</option>";

		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );



	if (isset ( $_SESSION ['searchSaloon'] ['id_typeCode'] )) {



		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $_SESSION ['searchSaloon'] ['id_typeCode'];

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $_SESSION ['searchSaloon'] ['id_markCode']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );

	}

	$html = str_replace ( '{CITY_FIND}', "", $html );



	$query = "SELECT * FROM AUTO_REGION order by REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] == $_SESSION ['searchSaloon'] ['id_region']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . ">" . $regionlist ['REGION'] . "</option>";

		}

	}



	$html = str_replace ( '{REGION}', $region, $html );



	if (isset ( $_SESSION ['searchSaloon'] ['id_region'] )) {

		$query = "SELECT * FROM AUTO_CITY  where REGION=" . $_SESSION ['searchSaloon'] ['id_region'] . " order by CITY";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$region = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $regionlist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($regionlist ['ID'] == $_SESSION ['searchSaloon'] ['city']) ? " selected" : "";

				$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . ">" . $regionlist ['CITY'] . "</option>";

			}

		}



		$html = str_replace ( '{CITY}', $region, $html );

	}



	$query = "select count(a.id_author) as count,b.ID, b.CITY,a.region from AUTO_USERS a, AUTO_CITY b where a.city=b.ID and a.status='autosaloon' and a.locked=0 and a.lock_admin=0 group by b.CITY, b.ID";



	//   $html.=$query;

	//$count_rows=mysql_num_rows($res);

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

		//$html.=$query;

	}



	if ((($_POST ["del"]) != 1) || (($_GET ["del"]) != 1)) {

		$html1 = file_get_contents ( './templates/regionSaloon.html' );

		$saloon = "<table width=\"200\" valign=top align=\"center\" border=\"0\" cellpadding=\"0\ cellspacing=\"0\" class=\"postTable2\"> <tr>

              <td height=\"30\" bgcolor=\"#333333\" style=\"background:url(img/border2.jpg) no-repeat\" colspan=2><div class=\"head2\">Автосалоны по городам </div></td>

            </tr>";

		while ( $row = mysql_fetch_array ( $res ) ) {

			$saloon .= "<tr><td>";

			$saloon .= "<a class=cat2 href=?action=showSaloon&id_region=" . $row ['region'] . "&id_city=" . $row ['ID'] . ">" . $row ['CITY'] . "</a></td><td>";

			$saloon .= $row ['count'] . "</td></tr>";



		}

		$saloon .= "</table>";

		$html1 = str_replace ( '{SALOON}', $saloon, $html1 );

		$html = str_replace ( '{FOUND}', $html1, $html );



		$sql1 = 'SELECT distinct b.CAR_TYPE  FROM `AUTO_CAR_BASE` b, AUTO_USERS a where b.ID_USER=a.id_author and a.status="autosaloon" and a.locked=0 and a.lock_admin=0 and b.ACTIVE=1 ORDER BY CAR_TYPE';



		$res = mysql_query ( $sql1 );

		if (! $res) {

			$msg = 'Ошибка при получении списка ';

			$err = 'Ошибка при выполнении запроса: <br/>' . $where . $sql1 . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

			//$html.=$query;

		}

		$html1 = "";

		$html2 = "";

		while ( $row = mysql_fetch_array ( $res ) ) {

			$html1 .= SaloonCount ( 1, $row ["CAR_TYPE"] );

			$html2 .= SaloonCount ( 0, $row ["CAR_TYPE"] );

		}



		if ($html1 != "0") {

			//$html3 = file_get_contents( './templates/CatSaloon1.html' );

			//  $html3 = str_replace( '{SALOON_11}', $html3, $html1 );

			$html = str_replace ( '{SALOON_1}', $html1, $html );

		} else {

			$html = str_replace ( '{SALOON_1}', "", $html );

		}

		if ($html2 != "0") {

			//	$html3 = file_get_contents( './templates/CatSaloon2.html' );

			//   $html3 = str_replace( '{SALOON_22}', $html3, $html2 );

			$html = str_replace ( '{SALOON_2}', $html2, $html );

		} else {

			$html = str_replace ( '{SALOON_2}', "", $html );

		}

	}



	else {



	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=SaloonCity&del=1">Автосалоны</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}



function SaloonCount($new, $type_car) {



	$numcolumn = 3;



	$tc = 0;

	$tdwidth = intval ( 100 / $numcolumn );



	//$sql3 = 'SELECT * FROM `AUTO_CAR_TYPE` WHERE ID='.$type_car;





	$sql3 = "SELECT distinct b.CAR_TYPE, b.ID  FROM `AUTO_CAR_BASE` a, `AUTO_CAR_TYPE` b, `AUTO_USERS` u WHERE a.ID_USER=u.id_author and u.lock_admin=0 and u.locked=0 and u.status='autosaloon' and a.CAR_TYPE=" . $type_car . " and a.NEW=" . $new . " and a.CAR_TYPE=b.ID and a.ACTIVE=1";

	$html = "";

	$res3 = mysql_query ( $sql3 );

	if (@mysql_num_rows ( $res3 ) > 0) {



		$html = "<table width=\"99%\" valign=top align=\"center\" border=\"0\" cellpadding=\"0\ cellspacing=\"0\" class=\"postTable2\"><tr>";



		while ( $row3 = @mysql_fetch_array ( $res3 ) ) {

			$type_car_name = $row3 ["CAR_TYPE"];

		}

		$new_old = ($new == 1) ? "новые" : "подержанные";



		$html .= "<td height=\"30\" colspan=\"3\" width=\"100%\" bgcolor=\"#999999\"  style=\"background:url(img/border4.jpg) repeat-x\"><div class=\"head2\">Автосалоны, продающие " . $new_old . " " . $type_car_name . "</div></td></tr><tr>";

		// $sql = 'SELECT count(a.ID) as COUNT, a.CAR_MARK,b.TRADEMARK FROM `AUTO_GROUP` a, `AUTO_TRADEMARK` b WHERE '

		//     . 'a.CAR_TYPE='.$type_car.' and a.NEW='.$new.' and b.ID=a.CAR_MARK group by a.CAR_MARK, b.TRADEMARK';





		$sql = 'SELECT count(distinct(a.ID_USER)) as COUNT, a.CAR_MARK, b.TRADEMARK FROM `AUTO_CAR_BASE` a, `AUTO_TRADEMARK` b, `AUTO_USERS` c WHERE ' . 'a.CAR_TYPE=' . $type_car . ' and a.NEW=' . $new . ' and b.ID=a.CAR_MARK and a.ID_USER=c.id_author and c.lock_admin=0 and c.locked=0 and c.status="autosaloon" and a.ACTIVE=1 group by a.CAR_MARK, b.TRADEMARK';



		//  $html.=$sql;

		$res = mysql_query ( $sql );

		//    if (@mysql_num_rows($res)>0)

		//  {

		while ( $row = @mysql_fetch_array ( $res ) ) {

			$count = $row ["COUNT"];

			$car_mark = $row ["CAR_MARK"];

			//$id_saloon = $row["ID_SALOON"];

			$trademark = $row ["TRADEMARK"];



			$html .= "<td valign=\"top\" align=left width=\"" . $tdwidth . "%\" border=\"0\">";

			$html .= "<table widht=\"100%\" align=left valign=\"top\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"postTable3\"><tr><td>";

			$html .= "<tr><td>";

			// $html.="<div >";

			$html .= "<a class=cat2 href=?action=showSaloon&id_markCode=" . $car_mark . "&id_typeCode=" . $type_car . ">" . $trademark . "</a>";

			$html .= "</td><td align=\"right\">";

			$html .= $count . "</td></tr></table>";



			$tc ++;

			if ($tc == $numcolumn) {

				$tc = 0;

				$html .= "</td></tr><tr>";

			} else {

				$html .= "</td>";

			}

		}

		// }





		$html .= "</table>";

	}

	return $html;



}



function ModelView() {



	if (isset ( $_GET ['mark'] )) {

		$numcolumn = 3;

		$html = "<table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" width=\"100%\" valign=top align=center><tr>";

		$tc = 0;

		$tdwidth = intval ( 100 / $numcolumn );



		$query = "SELECT count(a.ID) as COUNT, a.CAR_MODEL, a.CAR_TYPE, a.CAR_MARK, f.MODEL,i.TRADEMARK FROM " . TABLE_AUTO . " a, AUTO_USERS c, AUTO_MODEL f, AUTO_TRADEMARK i where ";

		$query .= "f.ID=a.CAR_MODEL and a.ACTIVE=1 ";

		$query .= " and i.ID=a.CAR_MARK";



		$query .= " and (a.ID_USER=c.id_author and c.locked=0 and c.lock_admin=0 )";

		$query .= (isset ( $_GET ['new'] )) ? " and a.NEW=" . $_GET ['new'] : "";

		$query .= (isset ( $_GET ['mark'] )) ? " and a.CAR_MARK=" . $_GET ['mark'] : "";

		$query .= (isset ( $_GET ['type'] )) ? " and a.CAR_TYPE=" . $_GET ['type'] : "";

		$query .= " group by a.CAR_MODEL,a.CAR_TYPE,a.CAR_MARK, f.MODEL, i.TRADEMARK";

		// $html.=$query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка';

			$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

			//$html.=$query;

		}



		while ( $row = mysql_fetch_array ( $res ) ) {

			$count = $row ["COUNT"];

			$car_mark = $row ["CAR_MARK"];

			$title = $row ["MODEL"];

			$car_model = $row ["CAR_MODEL"];

			$car_type = $row ["CAR_TYPE"];

			$mark = $row ["TRADEMARK"];



			if (! isset ( $_GET ['new'] )) {



				$query2 = "SELECT count(a.ID) as COUNT, a.CAR_MODEL, a.CAR_TYPE, a.CAR_MARK, f.MODEL FROM " . TABLE_AUTO . " a, AUTO_USERS c, AUTO_MODEL f where ";

				$query2 .= "f.ID=a.CAR_MODEL and a.ACTIVE=1";

				$query2 .= " and (a.ID_USER=c.id_author and c.locked=0)";

				$query2 .= (isset ( $_GET ['mark'] )) ? " and a.CAR_MARK=" . $_GET ['mark'] : "";

				$query2 .= (isset ( $_GET ['type'] )) ? " and a.CAR_TYPE=" . $_GET ['type'] : "";

				$query2 .= " and a.NEW=0";

				$query2 .= " group by a.CAR_MODEL,a.CAR_TYPE,a.CAR_MARK, f.MODEL";



				$count_old = 0;

				$res2 = mysql_query ( $query2 );

				while ( $row2 = mysql_fetch_array ( $res2 ) ) {



					$count_old = $row2 ["COUNT"];

				}



				$query2 = "SELECT count(a.ID) as COUNT, a.CAR_MODEL, a.CAR_TYPE, a.CAR_MARK, f.MODEL FROM " . TABLE_AUTO . " a, AUTO_USERS c, AUTO_MODEL f where ";

				$query2 .= "f.ID=a.CAR_MODEL and a.ACTIVE=1";

				$query2 .= " and (a.ID_USER=c.id_author and c.locked=0)";

				$query2 .= (isset ( $_GET ['mark'] )) ? " and a.CAR_MARK=" . $_GET ['mark'] : "";

				$query2 .= (isset ( $_GET ['type'] )) ? " and a.CAR_TYPE=" . $_GET ['type'] : "";

				$query2 .= " and a.NEW=1";

				$query2 .= " group by a.CAR_MODEL,a.CAR_TYPE,a.CAR_MARK, f.MODEL";

				$count_new = 0;

				$res2 = mysql_query ( $query2 );

				while ( $row2 = mysql_fetch_array ( $res2 ) ) {



					$count_new = $row2 ["COUNT"];

				}

				$view_new_old = " ( <font color=red>" . $count_old . "</font> / <font color=green>" . $count_new . "</font> )";



			}



			$new_link = "";



			if (isset ( $_GET ['new'] ))

				$new_link = "&new=" . $_GET ['new'];

			$type_link = "&id_typeCode=" . $car_type;

			$carmark_link = "&id_markCode=" . $car_mark;

			$carmodel_link = "&id_modelCode=" . $car_model;



			$html .= "<td valign=\"top\" align=left width=\"" . $tdwidth . "%\" style=\" border-right:dotted; border-color:#cccccc; border-width:1px;\"  >";

			$html .= "<a class=cat href=?action=searchView" . $type_link . $carmark_link . $carmodel_link . $new_link . ">" . $title . "</a> - " . $count; // . " " . $view_new_old;



			$tc ++;

			if ($tc == $numcolumn) {

				$tc = 0;

				$html .= "</td></tr><tr>";

			} else {

				$html .= "</td>";

			}

		}

		$html .= "</table>";

		$html2 = file_get_contents ( './templates/catalogForm.html' );



		$titul = "Каталог";



		if (isset ( $_GET ['new'] )) {

			$titul .= ($_GET ['new'] == 1) ? " > Новые автомобили" : " > Подержанные автомобили";

		}



		$CAR_TYPE = "";

		if (isset ( $_GET ['type'] )) {

			$query3 = "SELECT * FROM `AUTO_CAR_TYPE` WHERE ID=" . $_GET ['type'];

			$res3 = mysql_query ( $query3 );

			while ( $row3 = mysql_fetch_array ( $res3 ) ) {



				$CAR_TYPE = $row3 ["CAR_TYPE"];

			}



		}



		$titul .= ($CAR_TYPE != "") ? " > " . $CAR_TYPE : "";

		$titul .= ($mark != "") ? " > " . $mark : "";



		$html2 = str_replace ( '{CAR}', $CAR_TYPE, $html2 );



		$titul .= " > " . $mark;

		//$_SESSION ['pageTitle'] = $titul;
		$_SESSION ['pageTitle'] = $CAR_TYPE;


		$html2 = str_replace ( '{notebook}', $html, $html2 );

		$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span>'.($car_type ? ' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=MarkView&type='.($_REQUEST['type'] ? $_REQUEST['type'] : '').'">'.$CAR_TYPE.'</a></span>' : '').(isset($_REQUEST['new']) ? ' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=MarkView&new='.($_REQUEST['new'] ? '1' : '0').'&type='.($_REQUEST['type'] ? $_REQUEST['type'] : '1').'">'.($_REQUEST['new'] ? 'Новые автомобили' : 'Подержанные автомобили').'</a></span>' : '').($_REQUEST['mark'] ? ' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=ModelView&mark='.$car_mark.'&new='.($new ? '1' : '0').'&type=1">'.trim($mark).'</a></span>' : '');// : '').'!!!!!!'.' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=searchView&id_typeCode='.$car_type.'&id_markCode='.$car_mark.'&id_modelCode='.$car_tr.'&new='.($new ? '1' : '0').'&type=1">'.strtolower($car_model).'</a></span>';

		$html2 = str_replace ( '{path}', $Gpath, $html2 );

		return $html2;

	}

}


//редактирование объявления
function editCar() {

	if (!(($_SESSION ['user'] ['status'] === "autosaloon") || ($_SESSION ['user'] ['status'] === "user") || ($_SESSION ['user'] ['status'] === "admin"))) die();

	$_SESSION ['pageTitle'] = "Изменение объявления - Автомобили";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}



	$html .= file_get_contents ( './templates/editMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=editCarSubmit';

	$html = str_replace ( '{action}', $action, $html );

	if (isset ( $_REQUEST['id'] ))
		$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_CAR_BASE WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}

	$query = "SELECT * FROM AUTO_CAR_TYPE order by CAR_TYPE ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($typelist ['ID'] === $data ['CAR_TYPE']) ? " selected" : "";

			//$type.="<option value='".$typelist['ID']."' ".$selwrk." >".$typelist['CAR_TYPE']."</option>";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . ">" . $typelist ['CAR_TYPE'] . "</option>";



		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );



		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $data ['CAR_TYPE'] . "  order by TRADEMARK";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок222';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] === $data ['CAR_MARK']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );






		$query = "SELECT * FROM AUTO_MODEL where TRADEMARK=" . $data ['CAR_MARK'] . " order by MODEL";

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка моделй';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$model = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $modellist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($modellist ['ID'] === $data ['CAR_MODEL']) ? " selected" : "";

				$model .= "<option value='" . $modellist ['ID'] . "' " . $selwrk . " >" . $modellist ['MODEL'] . "</option>";

			}

		}

		$html = str_replace ( '{MODEL}', $model, $html );




	$year1 = "";

	//$year1 .= "<option value='0'>____</option>";

	for($i = date ( 'Y' ); $i > 1949; $i --) {

		$selwrk1 = (intval($i) === intval($data ['YEAR_VYP'])) ? " selected" : "";



		$year1 .= "<option value='" . $i . "' " . $selwrk1 . " >" . $i . "</option>";

	}

	$html = str_replace ( '{YEAR1}', $year1, $html );



	$query = "SELECT * FROM AUTO_REGION order by REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка регионов';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] === $data ['REGION']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . ">" . $regionlist ['REGION'] . "</option>";

		}

	}

	$html = str_replace ( '{REGION}', $region, $html );







		$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $data ['REGION'] . " order by CITY";



		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка городов';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$city = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $citylist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($citylist ['ID'] === $data ['CITY']) ? " selected" : "";

				$city .= "<option value='" . $citylist ['ID'] . "' " . $selwrk . ">" . $citylist ['CITY'] . "</option>";

			}

		}

		$html = str_replace ( '{CITY}', $city, $html );



	$query = "SELECT * FROM AUTO_SOST";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка состояний';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($sostlist ['ID'] === $data ['SOST']) ? " selected" : "";

			$sost .= "<option value='" . $sostlist ['ID'] . "' " . $selwrk . " >" . $sostlist ['SOST'] . "</option>";

		}

	}

	$html = str_replace ( '{SOSTOYANIE}', $sost, $html );



	$query = "SELECT * FROM AUTO_TYPE_DVIG ORDER BY TYPE_DVIG";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка типов двигателей';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($sostlist ['ID'] === $data ['TYPE_DVIG']) ? " selected" : "";

			$sost .= "<option value='" . $sostlist ['ID'] . "' " . $selwrk . " >" . $sostlist ['TYPE_DVIG'] . "</option>";

		}

	}

	$html = str_replace ( '{DVIG}', $sost, $html );



	$query = "SELECT * FROM AUTO_TYPE_KUZ ORDER BY TYPE_KUZ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка типов кузовов';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($sostlist ['ID'] === $data ['TYPE_KUZ']) ? " selected" : "";

			$sost .= "<option value='" . $sostlist ['ID'] . "' " . $selwrk . " >" . $sostlist ['TYPE_KUZ'] . "</option>";

		}

	}

	$html = str_replace ( '{KUZOV}', $sost, $html );



	$query = "SELECT * FROM AUTO_COLOR ORDER BY COLOR";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка цветов';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($sostlist ['ID'] === $data ['COLOR']) ? " selected" : "";

			$sost .= "<option value='" . $sostlist ['ID'] . "' " . $selwrk . " >" . $sostlist ['COLOR'] . "</option>";

		}

	}

	$html = str_replace ( '{COLOR}', $sost, $html );



	$selwrk1 = ($data ['TYPE_PRIV'] == 1) ? " selected>" : ">";

	$selwrk2 = ($data ['TYPE_PRIV'] == 2) ? " selected>" : ">";

	$selwrk3 = ($data ['TYPE_PRIV'] == 3) ? " selected>" : ">";



	$privod = "<option value=\"0\">- любой -</option>";

	$privod .= "<option value=\"1\"" . $selwrk1 . " - заднеприводной -</option>";

	$privod .= "<option value=\"2\"" . $selwrk2 . " - переднеприводной -</option>";

	$privod .= "<option value=\"3\"" . $selwrk3 . " - полноприводной -</option>";



	$html = str_replace ( '{PRIVOD}', $privod, $html );



	$PRICE=(abs(round( $data ['PRICE']))>0)?abs(round( $data ['PRICE'])):"";

	$html = str_replace ( '{PRICE}',$PRICE, $html );

	$html = str_replace ( '{PROBEG}', ($data ['PROBEG'] ? $data ['PROBEG'] : ""), $html );

	$html = str_replace ( '{OBEM}', ($data ['V_DVIG'] ? $data ['V_DVIG'] : ""), $html );

	$html = str_replace ( '{POWER}', ($data ['POWER'] ? $data ['POWER'] : ""), $html );

	$html = str_replace ( '{PEREDACH}', ($data ['KOL_PERED'] ? $data ['KOL_PERED'] : ""), $html );

	$html = str_replace ( '{DVEREY}', ($data ['DVEREY'] ? $data ['DVEREY'] : ""), $html );

	$html = str_replace ( '{DESCR}', $data ['DESCR'], $html );


	//44444444444444444444444
	$DEL_PHOTO = '';
	$ii=0;



	for ($i=1;$i<=6;$i++) {

	if ($data['PHOTO_'.$i]) $ii++;



	//if ($ii === 1) $DEL_PHOTO .='<tr>';
	//<tr><td valign="middle" colspan="3" style=" border-bottom: 1px solid #666; font-size:1px; width: 100%">&nbsp;</td></tr>
	if (($ii !== 0) && ($ii % 4 === 0)) $DEL_PHOTO .='</tr><tr>';

	if ($data['PHOTO_'.$i]) {

	$DEL_PHOTO .= '
<td>Фото №'.$i.'<br>
              <img src="show_image.php?filename=photo/'.$data['PHOTO_'.$i].'&width=80"/><br/><input type="checkbox" name="x_DEL_PHOTO_'.$i.'" value="1"/> <span class="style7">Удалить</span>
</td>';

	}
	//if ($ii === 6 ) $DEL_PHOTO .='</tr>';
	}

	$html = str_replace ( '{DEL_PHOTO}', $DEL_PHOTO, $html );
	//photo



	$html = str_replace ( '{CHECKED_NEW}', $check = ($data ['NEW'] == 1) ? " checked=\"checked\" " : "", $html );



	$html = str_replace ( '{PRAV_RUL_CHECKED}', $check = ($data ['PRAV_RUL'] == 1) ? " checked=\"checked\" " : "", $html );

	$html = str_replace ( '{AKPP_CHECKED}', $check = ($data ['AKPP'] == 1) ? " checked=\"checked\" " : "", $html );

	$html = str_replace ( '{METALLIK_CHECKED}', $check = ($data ['METALL'] == 1) ? " checked=\"checked\" " : "", $html );

	$html = str_replace ( '{NERASTAMOG_CHECKED}', $check = ($data ['NE_RASTAM'] == 1) ? " checked=\"checked\" " : "", $html );

	$html = str_replace ( '{BEGPROBEG_CHECKED}', $check = ($data ['BEZ_PROB'] == 1) ? " checked=\"checked\" " : "", $html );




	$tpl = $html;



	unset ( $_SESSION ['carbase'] );


	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=myCar">Мои автомобили</a></span> / <span class="und"><a href="">Изменения объявления</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}




function editCarSubmit() {


	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input







	$descr = substr ( $_POST ['x_DESCR'], 0, 500 );



	$price = round ( abs ( RemoveXSS ( $_POST ['x_PRICE'] ) ) );



	$id_typeCode = intval ( $_POST ['id_typeCode'] );

	$id_markCode = intval ( $_POST ['id_markCode'] );

	$id_modelCode = intval ( $_POST ['id_modelCode'] );



	$yearCode = (! ( $_POST ['yearCode'] )) ? "" : intval ( $_POST ['yearCode'] );



	$sostoyanie = ($_POST ['x_SOST']) ? abs ( intval ( $_POST ['x_SOST'] ) ) : "";

	$probeg = ($_POST ['x_PROBEG']) ? abs ( intval ( $_POST ['x_PROBEG'] ) ) : "";

	$dvig = ($_POST ['x_DVIG']) ? abs ( intval ( $_POST ['x_DVIG'] ) ) : "";

	$obem = ($_POST ['x_V_DVIG']) ? RemoveXSS ( $_POST ['x_V_DVIG'] ) : "";

	$obem = strtr($obem, ',', '.');

	$power = ($_POST ['x_POWER']) ? RemoveXSS ( $_POST ['x_POWER'] ) : "";

	$privod = ($_POST ['x_TYPE_PRIV'] != "") ? abs ( intval ( $_POST ['x_TYPE_PRIV'] ) ) : "";

	$peredach = ($_POST ['x_KOL_PERED']) ? abs ( intval ( $_POST ['x_KOL_PERED'] ) ) : "";



	$kuzov = ($_POST ['x_TYPE_KUZ'] != "") ? abs ( intval ( $_POST ['x_TYPE_KUZ'] ) ) : "";

	$dverey = ($_POST ['x_DVEREY']) ? abs ( intval ( $_POST ['x_DVEREY'] ) ) : "";



	$color = ($_POST ['x_COLOR'] != "") ? abs ( intval ( $_POST ['x_COLOR'] ) ) : "";



	$metallik = (isset ( $_POST ['x_METALL'] )) ? "1" : "0";

	$prav_rul = (isset ( $_POST ['x_PRAV_RUL'] )) ? "1" : "0";

	$akpp = (isset ( $_POST ['x_AKPP'] )) ? "1" : "0";

	$nerastamog = (isset ( $_POST ['x_NE_RASTAM'] )) ? "1" : "0";

	$bezprobeg = (isset ( $_POST ['x_BEZ_PROB'] )) ? "1" : "0";





	$descr = RemoveXSS ( $_POST ['x_DESCR'] );



	if ($probeg == "0")

		$new = 1;

	else

		$new = 0;

	$new = (isset ( $_POST ['x_NEW'] )) ? "1" : "0";


	// Проверяем, заполнены ли обязательные поля

	$error = '';





	if (! preg_match ( "|^[\d]*[\.,]?[\d]*$|", $obem ))



	{

		$error = $error . '<li>Не верный формат объема двигателя</li>' . "\n";

		$obem = "";

	}



	if (strlen ( $about ) > 500)

		$error = $error . '<li>длина поля "Описание" более 500 символов</li>' . "\n";


	if ($id_typeCode <= 0)

		$error = $error . '<li>Не выбран тип ТС</li>' . "\n";

	;

	if ($id_markCode <= 0)

		$error = $error . '<li>Не выбрана марка</li>' . "\n";

	;

	if ($id_modelCode <= 0)

		$error = $error . '<li>Не выбрана модель</li>' . "\n";

	if (!$yearCode)

		$error = $error . '<li>Не выбран год выпуска</li>' . "\n";

	if ($price <= 0)

		$error = $error . '<li>Не указана стоимость</li>' . "\n";







	// Проверяем корректность URL домашней странички




	$IMGCOUNT = 6;

	for($ii = 1; $ii <= $IMGCOUNT; $ii ++) {

		if (! empty ( $_FILES ['x_PHOTO_' . $ii] ['name'] )) {

			//$ext = strrchr ( $_FILES ['x_PHOTO_' . $ii] ['name'], "." );

			unset($imgS);
			$imgS = getimagesize($_FILES ['x_PHOTO_' . $ii]['tmp_name']);

			$extensions = array ("image/jpg", "image/png", "image/jpeg", "image/gif" );

//&&&&&&&&&&&&&&&&//

			if (! in_array ( strtolower($imgS['mime']), $extensions ))

				$error = $error . '<li>Недопустимый формат файла №' . $ii . '</li>' . "\n";



			if ($_FILES ['x_PHOTO_' . $ii] ['size'] > MAX_PHOTO_SIZE)

				$error = $error . '<li>Размер файла №' . $ii . ' больше ' . (MAX_PHOTO_SIZE / 1024) . ' Кб</li>' . "\n";

		}

	}



	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['price'] = $price;

		$_SESSION ['carbase'] ['region'] = $regionCode;

		$_SESSION ['carbase'] ['city'] = $cityCode;

		$_SESSION ['carbase'] ['descr'] = $descr;

		$_SESSION ['carbase'] ['yearCode'] = $yearCode;

		$_SESSION ['carbase'] ['sostoyanie'] = $sostoyanie;

		$_SESSION ['carbase'] ['probeg'] = $probeg;

		$_SESSION ['carbase'] ['id_typeCode'] = $id_typeCode;

		$_SESSION ['carbase'] ['id_markCode'] = $id_markCode;

		$_SESSION ['carbase'] ['id_modelCode'] = $id_modelCode;

		$_SESSION ['carbase'] ['dvig'] = $dvig;

		$_SESSION ['carbase'] ['obem'] = $obem;

		$_SESSION ['carbase'] ['power'] = $power;

		$_SESSION ['carbase'] ['privod'] = $privod;

		$_SESSION ['carbase'] ['peredach'] = $peredach;

		$_SESSION ['carbase'] ['PRAV_RUL'] = $prav_rul;

		$_SESSION ['carbase'] ['AKPP'] = $akpp;

		$_SESSION ['carbase'] ['kuzov'] = $kuzov;

		$_SESSION ['carbase'] ['dverey'] = $dverey;

		$_SESSION ['carbase'] ['METALL'] = $metallik;

		$_SESSION ['carbase'] ['color'] = $color;

		$_SESSION ['carbase'] ['NE_RASTAM'] = $nerastamog;

		$_SESSION ['carbase'] ['BEZ_PROB'] = $bezprobeg;

		$_SESSION ['carbase'] ['new'] = $new;




		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=editCar' );//????????????????????????

		die ();

	}






	// Формируем SQL-запрос
if (($_SESSION ['user'] ['status'] === "autosaloon") || ($_SESSION ['user'] ['status'] === "user") || ($_SESSION ['user'] ['status'] === "admin")) {
//

for($i=1;$i<=6;$i++) {
	if ($_REQUEST['x_DEL_PHOTO_'.$i]) {

	$query = "SELECT PHOTO_$i FROM AUTO_CAR_BASE WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND " : '')." ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

	}

	unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.	$ph['PHOTO_'.$i]);


			$query = "UPDATE AUTO_CAR_BASE

				SET PHOTO_$i='' WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND " : '')." ID='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );

		}
}

for($i=1;$i<=6;$i++) {

		$img="";

        if (! empty ( $_FILES ['x_PHOTO_'.$i] ['tmp_name'] )) {

				$query = "SELECT PHOTO_$i FROM AUTO_CAR_BASE WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND " : '')." ID='".$_SESSION['edID']."'";

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

			}

			if ($ph['PHOTO_'.$i]) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph['PHOTO_'.$i]);

			$img = water ($_FILES ['x_PHOTO_'.$i]  );
					$query = "UPDATE AUTO_CAR_BASE

		    SET PHOTO_$i='" .  $img . "' WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND " : '')." ID='".$_SESSION['edID']."'";

		$res = mysql_query ( $query );
		}
}







		if ($_POST ['a_add'] == "A") {
//сюдасюда

			$query = "SELECT ID_USER FROM AUTO_CAR_BASE

		    WHERE ID=".$_SESSION['edID'];

			$res = mysql_query ( $query );
			for($u_id=array();$row=mysql_fetch_assoc($res);$u_id=$row);


			$query = "SELECT * FROM AUTO_USERS

		    WHERE id_author='" . $u_id['ID_USER'] . "'";

			$res = mysql_query ( $query );
			for($data=array();$row=mysql_fetch_assoc($res);$data=$row);
			$regionCode=$data['region'];
			$cityCode=$data['city'];


			$query = "UPDATE AUTO_CAR_BASE

		SET
		`CAR_TYPE`='" . mysql_escape_string ( $id_typeCode ) . "',
		`CAR_MARK`='" . mysql_escape_string ( $id_markCode ) . "',
		`CAR_MODEL`='" . mysql_escape_string ( $id_modelCode ) . "',
		`NEW`='" . mysql_escape_string ( $new ) . "',
		`PRAV_RUL`='" . mysql_escape_string ( $prav_rul ) . "',
		`YEAR_VYP`='" . mysql_escape_string ( $yearCode ) . "',
		`SOST`='" . mysql_escape_string ( $sostoyanie ) . "',
		`PROBEG`='" . mysql_escape_string ( $probeg ) . "',
		`PRICE`='" . mysql_escape_string ( $price ) . "',
		`TYPE_DVIG`='" . mysql_escape_string ( $dvig ) . "',
		`V_DVIG`='" . mysql_escape_string ( $obem ) . "',
		`POWER`='" . mysql_escape_string ( $power ) . "',
		`TYPE_PRIV`='" . mysql_escape_string ( $privod ) . "',
		`KOL_PERED`='" . mysql_escape_string ( $peredach ) . "',
		`AKPP`='" . mysql_escape_string ( $akpp ) . "',
		`TYPE_KUZ`='" . mysql_escape_string ( $kuzov ) . "',
		`DVEREY`='" . mysql_escape_string ( $dverey ) . "',
		`COLOR`='" . mysql_escape_string ( $color ) . "',
		`METALL`='" . mysql_escape_string ( $metallik ) . "',
		`NE_RASTAM`='" . mysql_escape_string ( $nerastamog ) . "',
		`BEZ_PROB`='" . mysql_escape_string ( $bezprobeg ) . "',
		`DESCR`='" . mysql_escape_string ( $descr ) . "' WHERE `ID`='".$_SESSION['edID']."'".(($_SESSION['user']['status'] !== 'admin') ? " AND `ID_USER`='".$_SESSION['user']['id_author']."'" : '');
//тату
			$res = mysql_query ( $query );



			if (! $res) {

				$msg = 'Ошибка при добавлении автомобиля';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			$ID_CAR = $_SESSION['edID'];



			$html .= file_get_contents ( './templates/changeCar.html' );

			$html = str_replace ( '{URL}', '?action=ShowCar&id='.$ID_CAR, $html );

			$html = str_replace ( '{edit}', '?action=editCar&id='.$ID_CAR, $html );

		}

}

	return $html;



}




function price() {

	//echo $_GET['id'];

	if (abs ( intval ( $_GET ['id'] ) ) > 0) //if (isset($_GET['id']))

{

		$html2 = file_get_contents ( './templates/priceForm.html' );



		$query = "SELECT a.id_author,a.name,c.REGION,d.CITY,a.address,a.about,a.url,a.email,a.tel1 from AUTO_USERS a,  AUTO_REGION c,AUTO_CITY d where ";

		$query .= " c.ID=a.region ";

		$query .= " and d.ID=a.city ";

		$query .= " and a.id_author=" . abs ( intval ( $_GET ['id'] ) );

		$query .= " and a.locked=0 ";

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка моделй';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}



		$RowCnt = 0;

		//$html2 =file_get_contents( './templates/saloonForm.html' );

		if (mysql_num_rows ( $res ) > 0) {

			while ( $autolist = mysql_fetch_array ( $res ) ) {

				$namesaloon = $autolist ['name'];

				$address = $autolist ['address'];

				$_SESSION ['pageTitle'] = "Прайс лист Автосалона:" . $namesaloon . ", г. " . $autolist ['CITY'] . ", " . $address;

				$html2 = str_replace ( '{SALOON}', $namesaloon, $html2 );

				$html2 = str_replace ( '{ADDRESS}', "г. " . $autolist ['CITY'] . ", " . $address, $html2 );

				$html2 = str_replace ( '{DESCR}', $autolist ['about'], $html2 );

				$html2 = str_replace ( '{TEL}', $autolist ['tel1'], $html2 );

				$html2 = str_replace ( '{LINK}', "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $autolist ['id_author'] . "><span class=\"url\">Карточка aвтосалона</span></a>", $html2 );

				$iduser = $autolist ['id_author'];

			}

		}

		if (! isset ( $_SESSION ['price'] ['sql'] )) {

			$_SESSION ['price'] ['sql'] = " a.ID_USER=" . $iduser;

		}

		$html2 = str_replace ( '{FOUND}', ShowTableCar ( $_SESSION ['price'] ['sql'], 'price&id=' . $_GET ['id'], 'ShowCar' ), $html2 );

		;

		save_log ( VIEW_PRICE, 'saloon', $_GET ['id'] );

		$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href=?action=SaloonByID&id='.$_REQUEST['id'].'>'.$namesaloon.'</a></span> / <span class="und"><a href="">Прайс лист автомобилей</a></span>';

		$html2 = str_replace ( '{path}', $Gpath, $html2 );

		return $html2;

	} else {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . "?action=SaloonCity&del=1" );

	}



}

function priceSpares() {

	//echo $_GET['id'];

	if (abs ( intval ( $_GET ['id'] ) ) > 0) //if (isset($_GET['id']))

{

		$html2 = file_get_contents ( './templates/priceForm.html' );



		$query = "SELECT a.id_author,a.name,c.REGION,d.CITY,a.address,a.about,a.url,a.email,a.tel1 from AUTO_USERS a,  AUTO_REGION c,AUTO_CITY d where ";

		$query .= " c.ID=a.region ";

		$query .= " and d.ID=a.city ";

		$query .= " and a.id_author=" . abs ( intval ( $_GET ['id'] ) );

		$query .= " and a.locked=0 ";

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка моделй';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}



		$RowCnt = 0;

		//$html2 =file_get_contents( './templates/saloonForm.html' );

		if (mysql_num_rows ( $res ) > 0) {

			while ( $autolist = mysql_fetch_array ( $res ) ) {

				$namesaloon = $autolist ['name'];

				$address = $autolist ['address'];

				$_SESSION ['pageTitle'] = "Прайс лист Автосалона:" . $namesaloon . ", г. " . $autolist ['CITY'] . ", " . $address;

				$html2 = str_replace ( '{SALOON}', $namesaloon, $html2 );

				$html2 = str_replace ( '{ADDRESS}', "г. " . $autolist ['CITY'] . ", " . $address, $html2 );

				$html2 = str_replace ( '{DESCR}', $autolist ['about'], $html2 );

				$html2 = str_replace ( '{TEL}', $autolist ['tel1'], $html2 );

				$html2 = str_replace ( '{LINK}', "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $autolist ['id_author'] . "><span class=\"url\">Карточка aвтосалона</span></a>", $html2 );

				$iduser = $autolist ['id_author'];

			}

		}

		if (! isset ( $_SESSION ['price'] ['sql'] )) {

			$_SESSION ['price'] ['sql'] = " a.ID_USER=" . $iduser;

		}

		$html2 = str_replace ( '{FOUND}', ShowTableSpares ( $_SESSION ['price'] ['sql'], 'priceSpares&id=' . $_GET ['id'], 'showSpares' ), $html2 );

		;

		save_log ( VIEW_PRICE, 'saloon', $_GET ['id'] );

		$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href=?action=SaloonByID&id='.$_REQUEST['id'].'>'.$namesaloon.'</a></span> / <span class="und"><a href="">Прайс лист товаров и запчастей</a></span>';

		$html2 = str_replace ( '{path}', $Gpath, $html2 );

		return $html2;

	} else {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . "?action=SaloonCity&del=1" );

	}



}




function SaloonByID($print = 0) {

	unset ( $_SESSION ['price'] ['sql'] );

	if (abs ( intval ( $_GET ['id'] ) ) > 0) //if (isset($_GET['id']))

{



		$query = "SELECT  a.id_author,a.name,c.REGION,d.CITY,a.address,a.about,a.url,a.email,a.tel1,a.tel2,a.descr from AUTO_USERS a,  AUTO_REGION c,AUTO_CITY d where ";

		$query .= " c.ID=a.region ";

		$query .= " and d.ID=a.city ";

		$query .= " and a.id_author=" . abs ( intval ( $_GET ['id'] ) );

		$query .= " and a.locked=0 and a.lock_admin=0 ";

		// echo abs(intval($_GET['id']));

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка моделй';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}



		$RowCnt = 0;

		if ($print)
			$html2 = file_get_contents ( './templates/saloonPrint.html' );
		else if ($_REQUEST['stat'])
			$html2 = file_get_contents ( './templates/saloonStatForm.html' );
		else
			$html2 = file_get_contents ( './templates/saloonForm.html' );

//..!!


		if (@mysql_num_rows ( $res ) > 0) {

			while ( $autolist = @mysql_fetch_array ( $res ) ) {

				$namesaloon = $autolist ['name'];

				$address = $autolist ['address'];



				$html2 = str_replace ( '{SALOON}', $namesaloon, $html2 );

				$html2 = str_replace ( '{CITY}', " " . $autolist ['CITY'], $html2 );

				$html2 = str_replace ( '{REGION}', $autolist ['REGION'], $html2 );

				$html2 = str_replace ( '{TEL}', $autolist ['tel1'], $html2 );

				//$html2 = str_replace( '{TEL2}',$autolist['tel2'], $html2 );

				//$html2 = str_replace( '{SALOON}',$namesaloon, $html2 );

				$html2 = str_replace ( '{ADDRESS}', $address, $html2 );

				$html2 = str_replace ( '{TEL1}', $autolist ['tel1'], $html2 );

				if (isset ( $autolist ['tel2'] )) {

					$tel2 = "<tr><td><span><strong>Тел./факс: </strong></span>" . $autolist ['tel2'] . "</td></tr>";

					$html2 = str_replace ( '{TEL2}', $tel2, $html2 );

				} else

					$html2 = str_replace ( '{TEL2}', "", $html2 );



				$html2 = str_replace ( '{ID}', $autolist ['id_author'], $html2 );

				if (isset ( $autolist ['descr'] )) {

					$html2 = str_replace ( '{DOPOLN}', $autolist ['descr'], $html2 );

				} else {

					$html2 = str_replace ( '{DOPOLN}', $autolist ['about'], $html2 );



				}

				$html2 = str_replace ( '{WEB}', "<a href=?action=redirect&url=" . $autolist ['url'] . "&id=" . $autolist ['id_author'] . ">" . $autolist ['url'] . "</a>", $html2 );

				$html2 = str_replace ( '{EMAIL}', "<a href=?action=sendMailForm&idUser=" . $autolist ['id_author'] . ">Написать письмо</a>", $html2 );



				//$html2 = str_replace( '{EMAIL}',"<a href=\"mailto:".$autolist['email']."\">".$autolist['email']."</a>", $html2 );

				$_SESSION ['pageTitle'] = "" . $namesaloon . ". " . $autolist ['REGION'] . ", " . $autolist ['CITY'] . ", " . $address . ", тел:" . $autolist ['tel1'];

			}



			//$title=$mark." ".$car_model." - ".$price." тыс.руб., ".$year_vyp." г., телефон:".$tel ;

			$query = "SELECT count(a.CAR_TYPE) as COUNT, a.CAR_TYPE as TYPE,  b.CAR_TYPE as CAR_TYPE, a.ID_USER FROM AUTO_CAR_BASE a, AUTO_CAR_TYPE b where a.CAR_TYPE=b.ID and a.ID_USER=" . abs ( $_GET ['id'] ) . " and a.ACTIVE=1 group by a.CAR_TYPE ,  b.CAR_TYPE, a.ID_USER";

			//echo  $query;

			$res = @mysql_query ( $query );

			$html3 = "<table width=\"100%\" bgcolor=\"#F5F5F5\" class=\"postTable2\">";

			while ( $row = @mysql_fetch_array ( $res ) ) {

				// $query1 = "SELECT count(*) as COUNT FROM AUTO_CAR_BASE a where a.CAR_TYPE=".$row['TYPE']." and a.ID_USER=".$row['ID_SALOON']." and a.ACTIVE=1";

				//echo  $query;

				// $res1 = @mysql_query( $query1 );

				//     while ($row1 = @mysql_fetch_array($res1))

				//	{

				//	$count=$row1['COUNT'];

				//}

				$html3 .= "<tr><td>" . $row ['CAR_TYPE'] . "</td><td width=\"25\">" . $row ['COUNT'] . "</td></tr>";

			}

			$html3 .= "</table>";

			$html2 = str_replace ( '{AUTOMOBILE}', $html3, $html2 );

			$html2 = str_replace ( '{PRICE}', "<a href=?action=price&id=" . $_GET ['id'] . ">Прайс лист</a>", $html2 );

			///
			$query = "SELECT count(a.CAR_TYPE) as COUNT, a.CAR_TYPE as TYPE,  b.CAR_TYPE as CAR_TYPE, a.ID_USER FROM AUTO_SPARES a, AUTO_CAR_TYPE b where a.CAR_TYPE=b.ID and a.ID_USER=" . abs ( $_GET ['id'] ) . " group by a.CAR_TYPE ,  b.CAR_TYPE, a.ID_USER";

			//echo  $query;

			$res = @mysql_query ( $query );

			$html3 = "<table width=\"100%\" bgcolor=\"#F5F5F5\" class=\"postTable2\">";

			while ( $row = @mysql_fetch_array ( $res ) ) {

				// $query1 = "SELECT count(*) as COUNT FROM AUTO_CAR_BASE a where a.CAR_TYPE=".$row['TYPE']." and a.ID_USER=".$row['ID_SALOON']." and a.ACTIVE=1";

				//echo  $query;

				// $res1 = @mysql_query( $query1 );

				//     while ($row1 = @mysql_fetch_array($res1))

				//	{

				//	$count=$row1['COUNT'];

				//}

				$html3 .= "<tr><td>" . $row ['CAR_TYPE'] . "</td><td width=\"25\">" . $row ['COUNT'] . "</td></tr>";

			}

			$html3 .= "</table>";

			$html2 = str_replace ( '{AUTOSPARES}', $html3, $html2 );

			$html2 = str_replace ( '{PRICESPARES}', "<a href=?action=priceSpares&id=" . $_GET ['id'] . ">Прайс лист</a>", $html2 );
			///
/*
			$query = "SELECT * FROM AUTO_SALOON_PHOTO where ID_SALOON=" . $_GET ['id'];

			//echo  $query;

			$res = @mysql_query ( $query );

			if (@mysql_num_rows ( $res ) > 0) {

				$PHOTO = ""; //<div id=\"gallery\"><ul>";

				while ( $row = @mysql_fetch_array ( $res ) ) {

					$PHOTO .= "<a href=\"show_image.php?filename=photo_saloon/" . $row ["PHOTO"] . "&width=640\"  class=\"lightbox\" rel=\"roadtrip\" border=0 title=\"" . $row ["DESCR"] . "\"><img src=\"show_image.php?filename=photo_saloon/" . $row ["PHOTO"] . "&width=190\" border=2/ ></a>";

					//$PHOTO.=$row["PHOTO"];

				}

				//$PHOTO.="</ul></div>";

			} else {

				$PHOTO = "<div class=\"head5\">НЕТ ФОТО</div>";

			}
*/

$query = "SELECT * FROM AUTO_SALOON_PHOTO where ID_SALOON=" . $_GET ['id'];

			//echo  $query;

			$res = @mysql_query ( $query );

			if (@mysql_num_rows ( $res ) > 0) {

				//$PHOTO = ""; //<div id=\"gallery\"><ul>";

					//$PHOTO [$i] = $row ["PHOTO_1"];
					for ($PHOTO_PIC = array(); $row = mysql_fetch_assoc($res); $PHOTO_PIC = $row) {}

			}


			//$PHOTO [1] = $row ["PHOTO_1"];

			//$PHOTO [2] = $row ["PHOTO_2"];


			if (($PHOTO_PIC["PHOTO_1"] !== "") || ($PHOTO_PIC["PHOTO_2"] !== "") || ($PHOTO_PIC["PHOTO_3"] !== "")) {

				$PHOTO = "<table width=\"190\" cellpadding=\"2\">";

				for($i = 1; $i <= 3; $i ++) {

					if ((isset ( $PHOTO_PIC ["PHOTO_".$i] )) and ($print == 1) and ( $PHOTO_PIC ["PHOTO_".$i] !="")) {

						$PHOTO .= "<tr><td>";

						$PHOTO .= "<a href=\"show_image.php?filename=photo_saloon/" . $PHOTO_PIC ["PHOTO_" . $i] . "&width=640\" class=\"lightbox\" rel=\"roadtrip\"><img src=\"show_image.php?filename=photo_saloon/" . $PHOTO_PIC ["PHOTO_" . $i] . "&width=160\"   /></a></br></br>";

						$PHOTO .= "</td></tr>";

					}

					if ((isset ( $PHOTO_PIC ["PHOTO_" . $i] )) and ($print != 1) and ( $PHOTO_PIC ["PHOTO_" . $i] !="")) {

						$PHOTO .= "<tr><td>";

						$PHOTO .= "<a href=\"show_image.php?filename=photo_saloon/" . $PHOTO_PIC ["PHOTO_" . $i] . "&width=640\"  class=\"lightbox\" rel=\"roadtrip\"><img src=\"show_image.php?filename=photo_saloon/" . $PHOTO_PIC ["PHOTO_" . $i] . "&width=180\" style=\"border:#FFFFFF thin;\" /></a></br>";

						$PHOTO .= "</td></tr>";

					}



				}

				$PHOTO .= "</table>";

			if (empty($PHOTO_PIC)) $PHOTO = "<div class=\"head5\">НЕТ ФОТО</div>";

			} else {

				//$PHOTO = "<div class=\"head5\">НЕТ ФОТО</div>";

			}


//??????????????????????????????????????????????????????????????????????????


			$html2 = str_replace ( '{PHOTO}', $PHOTO, $html2 );

			$html3 = showStat ( 'saloon', $_GET ['id'] );

			$html2 = str_replace ( '{STATS}', $html3, $html2 );



			$html3 = showNews ( $_GET ['id'], 0, 0, 'SaloonByID' );

			$html2 = str_replace ( '{NEWS}', $html3, $html2 );



			save_log ( VIEW_SALOON, 'saloon', $_GET ['id'] );

			$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=SaloonCity&del=1">Автосалоны</a></span> / <span class="und"><a href=?action=SaloonByID&id='.$_REQUEST['id'].'>'.$namesaloon.'</a></span>'.($_REQUEST['stat'] ? ' / <span class="und"><a href="">Cтатистика автосалона</a></span>' : '');

			$html2 = str_replace ( '{path}', $Gpath, $html2 );

	$table = "<table width=100% align=\"left\"><tr>";

	$table .= "<td align=\"left\" valign=\"middle\" style=\"padding-left: 40px;\">";

	$table .= "[<a href=?action=News&id_saloon=" . $_REQUEST['id'] . ">Новости автосалона</a>]";

	//$table.="</td><td width=\"120\" valign=\"middle\" align=\"left\"><span class=\"notebook\">Записать в блокнот";

	$table .= "</td><td valign=\"middle\" align=\"center\">[<a href=?action=SaloonByID&id=".$_REQUEST['id']."&stat=1>Статистика автосалона</a>]";

	$table .= "</td><td valign=\"middle\" align=\"right\" style=\"padding-right: 40px;\">[<a href=?action=PrintSaloon&id=" . $_GET ["id"] . " target=_new>Распечатать</a>]";

	$table .= "</td></tr></table>";

	$html2 = str_replace ( '{ADVANCED}', $table, $html2 );

			return $html2;

		} else

			header ( 'Location: ' . $_SERVER ['PHP_SELF'] . "?action=SaloonCity&del=1" );

	} else

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . "?action=SaloonCity&del=1" );



}

function News() {

	$sal_name="";

	if ((abs ( intval ( $_GET ["id_saloon"] ) > 0 ) and (! isset ( $_GET ["id_news"] )))) //abs(intval($_GET['id'])

{

		$html2 = file_get_contents ( './templates/newsSaloon.html' );

		$query = "SELECT  a.id_author,a.name,c.REGION,d.CITY,a.address,a.about,a.url,a.email from AUTO_USERS a,  AUTO_REGION c,AUTO_CITY d where ";

		$query .= " c.ID=a.region ";

		$query .= " and d.ID=a.city ";

		$query .= " and a.status='autosaloon' ";

		$query .= " and a.id_author=" . abs ( intval ( $_GET ["id_saloon"] ) );

		$query .= " and a.locked=0 and a.lock_admin=0";



		$res = mysql_query ( $query );



		if (mysql_num_rows ( $res ) > 0) {

			while ( $autolist = mysql_fetch_array ( $res ) ) {

				$sal_name = $autolist ['name'];

				//$namesaloon=$autolist['name'];

				//$address=$autolist['address'];

				$id = $autolist ['id_author'];

				//  $html2 = str_replace( '{NAME}',$NAME_USER:"<a href=".$_SERVER['PHP_SELF']."?action=SaloonByID&id=".$autolist['id_author'].">".$NAME_USER."</a>", $html2 );

				$html2 = str_replace ( '{ID}', $autolist ['id_author'], $html2 );

				$html2 = str_replace ( '{AUTOSALOON}', $autolist ['name'], $html2 );

				$_SESSION ['pageTitle'] = "Новости Автосалона :" . $autolist ['name'] . ", " . $autolist ['CITY'] . ", " . $autolist ['address'];

				//	$_SESSION['pageTitle']="Новости:".$namesaloon.". ".$autolist['REGION'].", ".$autolist['CITY'].", ".$address.", тел:".$autolist['tel1'];





			}



			$html2 = str_replace ( '{NEWS}', showNews ( $id, 0, 0, 'News&id_saloon=' . $id ), $html2 );

			$html2 = str_replace ( '{SPEC}', showSpec ( $id, $id ), $html2 );

			$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=News">Новости автосалонов</a></span>'.( $_REQUEST['id_saloon'] ? ' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=News&id_saloon='.$_REQUEST['id_saloon'].'">'.$sal_name.'</a></span>' : '');

			$html2 = str_replace ( '{path}', $Gpath, $html2 );

			return $html2;

		} else {

			$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=News">Новости автосалонов</a></span>'.( $_REQUEST['id_saloon'] ? ' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=News&id_saloon='.$_REQUEST['id_saloon'].'">'.$autolist ['name'].'</a></span>' : '');

			$html2 = str_replace ( '{path}', $Gpath, $html2 );

			return "";

		}

	} elseif (abs ( intval ( $_GET ["id_news"] ) ) > 0) {

		$query = "SELECT UNIX_TIMESTAMP(DATA) as DATA,ZAGOL, SMALL_TEXT, TEXT, PHOTO, ID, ID_SALOON FROM AUTO_NEWS where ID=" . abs ( intval ( $_GET ["id_news"] ) );

		//echo  $query;





		$html2 = "";



		$res = @mysql_query ( $query );



		while ( $row = @mysql_fetch_array ( $res ) ) {
//..//..//
				switch (date ( "w", $row ["DATA"] )) {
					case 0: $w = "Воскресенье"; break;
					case 1: $w = "Понедельник"; break;
					case 2: $w = "Вторник"; break;
					case 3: $w = "Среда"; break;
					case 4: $w = "Четверг"; break;
					case 5: $w = "Пятница"; break;
					case 6: $w = "Суббота"; break;

				}

				switch (date ( "n", $row ["DATA"] )) {
					case 1: $m = "Января"; break;
					case 2: $m = "Февраля"; break;
					case 3: $m = "Марта"; break;
					case 4: $m = "Апреля"; break;
					case 5: $m = "Мая"; break;
					case 6: $m = "Июня"; break;
					case 7: $m = "Июля"; break;
					case 8: $m = "Августа"; break;
					case 9: $m = "Сентября"; break;
					case 10: $m = "Октября"; break;
					case 11: $m = "Ноября"; break;
					case 12: $m = "Декабря"; break;

				}

			$d = $w.", ".date ( "d", $row ["DATA"] )." ".$m." ".date ( "Y", $row ["DATA"] );

			$html2 = file_get_contents ( './templates/newsForm.html' );

			$html2 = str_replace ( '{DATE}', $d, $html2 );

			$html2 = str_replace ( '{TITLE}', $row ["ZAGOL"], $html2 );

			$ZAGOL = $row ["ZAGOL"];

			$html2 = str_replace ( '{NEWS}', nl2br ( $row ["TEXT"] ), $html2 );

			//$news=$row["SMALL_TEXT"];

			$saloon = $row ["ID_SALOON"];

			if (($row ["PHOTO"] != "") and ($row ["PHOTO"] != NULL))

				$PHOTO = "<img src=\"show_image.php?filename=photo/" . $row ["PHOTO"] . "&width=150\" border=0 style=\"margin-right: 20px\" alt=\"\" align=\"left\"  / >";

				//$PHOTO="<a href=\"photo/".$row["PHOTO"]."\" ><img src=\"show_image.php?filename=photo/".$row["PHOTO"]."&width=200\" border=0 style=\"margin-right: 20px\" alt=\"\" align=\"left\"  / ></a>";

			else

				$PHOTO = "";

			$html2 = str_replace ( '{IMG}', $PHOTO, $html2 );



		//$PHOTO.=$row["PHOTO"];

		}



		if ($saloon > 0) {

			//$html4 =file_get_contents( './templates/saloon_info.html' );

			$query = "SELECT  a.id_author, a.status ,a.name,c.REGION,d.CITY,a.address,a.about,a.url,a.email from AUTO_USERS a,  AUTO_REGION c,AUTO_CITY d where ";

			$query .= " c.ID=a.region ";

			$query .= " and d.ID=a.city ";

			$query .= " and a.id_author=" . $saloon;

			$query .= " and a.locked=0 and a.lock_admin=0";

			$res = mysql_query ( $query );

			if (! $res) {

				$msg = 'Ошибка при получении списка моделй';

				$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $err, true, '' );

			}



			$RowCnt = 0;



			if (mysql_num_rows ( $res ) > 0) {

				while ( $autolist = mysql_fetch_array ( $res ) ) {

					$sal_name = $autolist ['name'];

					$namesaloon = $autolist ['name'];

					$html2 = str_replace ( '{SALOON_NAME}', "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $autolist ['id_author'] . "><span class=\"title3\">" . $namesaloon . "</span></a>", $html2 );

					$STATUS = $autolist['status'];

				if ($autolist['status'] === 'admin') {
					$html2 = str_replace ( '{LINK}', "<a href=" . $_SERVER ['PHP_SELF'] . "><span class=\"url\">Администрация vash_domen.Ru</span></a>", $html2 );
					$_SESSION ['pageTitle'] = $ZAGOL." - Администрация vash_domen.Ru";
					}
				else {
					$html2 = str_replace ( '{LINK}', "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $autolist ['id_author'] . "><span class=\"url\">Карточка " . $namesaloon . "</span></a>", $html2 );

					$_SESSION ['pageTitle'] = "" . $namesaloon . ", " . $autolist ['REGION'] . ", г. " . $autolist ['CITY'];
					}
					//	$_SESSION['pageTitle']="Новости:".$namesaloon.". ".$autolist['REGION'].", ".$autolist['CITY'].", ".$address.", тел:".$autolist['tel1'];





				}

			}



			$html2 = str_replace ( '{SALOON}', $html4, $html2 );



		}

		$html2 = str_replace ( '{SALOON}', "", $html2 );

		$query = "SELECT * FROM AUTO_NEWS_PHOTO where ID_NEWS=" . abs ( intval ( $_GET ['id_news'] ) );

		//echo  $query;

		$res = @mysql_query ( $query );

		//$PHOTO="";

		$PHOTO = "";

		$i = 0;

		while ( $row = @mysql_fetch_array ( $res ) ) {



			$PHOTO .= "<li><a  class=\"lightbox\" rel=\"roadtrip\" href=\"show_image.php?filename=photo/" . $row ["PHOTO"] . "&width=640\" border=0 title=\"" . $row ["DESCR"] . "\"><img src=\"show_image.php?filename=photo/" . $row ["PHOTO"] . "&width=100\" border=0/ ></a>";



			$i ++;



		}



		$html2 = str_replace ( '{PHOTO}', $PHOTO, $html2 );
		if ($STATUS === 'admin') {
			$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=News">Новости автосалонов</a></span>';
		} else {
			$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=News">Новости автосалонов</a></span>'.( $_REQUEST['id_saloon'] ? ' / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=News&id_saloon='.$_REQUEST['id_saloon'].'">'.$sal_name.'</a></span>' : '');
		}
		$html2 = str_replace ( '{path}', $Gpath, $html2 );

		return $html2;



	} else {



		$html2 = file_get_contents ( './templates/newsForm2.html' );

		$html2 = str_replace ( '{NEWS}', showNews ( - 1, 0, 0, 'News' ), $html2 );

				$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=News">Новости автосалонов</a></span>';

		$html2 = str_replace ( '{path}', $Gpath, $html2 );

		return $html2;



	}

}



function AutoNews() {

	$_SESSION ['pageTitle'] = "Автоновости";



	if (($_GET ["id_news"] > 0)) {

		$query = "SELECT UNIX_TIMESTAMP(DATA) as DATA,SMALL_TEXT, TEXT, PHOTO, ID, ID_SALOON FROM AUTO_NEWS where ID=" . $_GET ["id_news"];

		//echo  $query;





		$html2 = file_get_contents ( './templates/newsAutoForm.html' );



		$res = @mysql_query ( $query );



		while ( $row = @mysql_fetch_array ( $res ) ) {

			$html2 = str_replace ( '{DATA}', date ( "d.m.Y", $row ["DATA"] ), $html2 );

			$html2 = str_replace ( '{NEWS}', $row ["TEXT"], $html2 );

			$news = $row ["SMALL_TEXT"];

		}



		$query = "SELECT * FROM AUTO_NEWS_PHOTO where ID_NEWS=" . $_GET ['id_news'];

		$res = @mysql_query ( $query );

		$PHOTO = "<div id=\"gallery\"><ul>";

		while ( $row = @mysql_fetch_array ( $res ) ) {

			$PHOTO .= "<li><a href=\"photo/" . $row ["PHOTO"] . "\" title=\"" . $row ["DESCR"] . "\"><img src=\"show_image.php?filename=photo/" . $row ["PHOTO"] . "&width=160\" border=0/ ></a></li>";

		}

		$PHOTO .= "</ul></div>";

		$PHOTO .= $pages;



		$html2 = str_replace ( '{PHOTO}', $PHOTO, $html2 );

		return $html2;



	} else {



		$html2 = file_get_contents ( './templates/newsAuto.html' );

		$html2 = str_replace ( '{NEWS}', showNews ( 0, 0, 0, 'AutoNews' ), $html2 );

		return $html2;



	}

}



function showSpec($id_saloon, $action) {

	if ($id_saloon != 0) {

		$query = "SELECT COUNT(*) FROM " . TABLE_AUTO . " a";

		$query .= ", AUTO_USERS b where a.ID_USER=b.id_author and b.locked=0 and b.lock_admin=0 and a.PREDL=1 and a.ACTIVE=1";

		$query .= " and b.status='autosaloon' and ID_USER=" . $id_saloon;

		$res = mysql_query ( $query );



		$total = mysql_result ( $res, 0, 0 );



		// Число страниц списка тем форума (постраничная навигация)

		$cntPages = ceil ( $total / THEMES_PER_PAGE );



		// Проверяем передан ли номер текущей страницы (постраничная навигация)

		if (isset ( $_GET ['page'] )) {

			$page = ( int ) $_GET ['page'];

			if ($page < 1)

				$page = 1;

		} else {

			$page = 1;

		}



		if ($page > $cntPages)

			$page = $cntPages;

			// Начальная позиция (постраничная навигация)

		$start = ($page - 1) * THEMES_PER_PAGE;



		// Строим постраничную навигацию, если это необходимо

		// if ( $cntPages > 1 ) {





		// Функция возвращает html меню для постраничной навигации

		$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );



		$query = "SELECT a.ID, a.CAR_TYPE, a.DVEREY,a.AKPP, a.POWER, a.V_DVIG, j.CITY, f.MODEL,i.TRADEMARK,r.REGION, b.CAR_TYPE as TYPE,a.YEAR_VYP,a.PROBEG,a.PRICE,a.PHOTO_1,a.CAR_MARK,a.ID_USER,UNIX_TIMESTAMP(a.DATE_VVOD) as DATE_VVOD, c.* FROM  " . TABLE_AUTO . " a, AUTO_CAR_TYPE b, AUTO_USERS c, AUTO_MODEL f,AUTO_TRADEMARK i,AUTO_CITY j,AUTO_REGION r where ";



		$query .= "b.ID=a.CAR_TYPE ";

		$query .= " and f.ID=a.CAR_MODEL";

		$query .= " and i.ID=a.CAR_MARK";

		$query .= " and a.CITY=j.ID";

		$query .= " and a.REGION=r.ID";

		// $query.=" and (a.ID_USER=c.id_author and c.locked=0) and a.ACTIVE=1";

		$query .= " and (a.ID_USER=c.id_author and c.locked=0 and c.lock_admin=0) and a.ACTIVE=1 and a.PREDL=1";// and c.status='autosaloon'

		$query .= " and a.ID_USER=" . $id_saloon;

		// $query.= $_SESSION['searchForm']['sql'];

		$query .= " ORDER BY a.DATE_VVOD ";

		$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;



		// echo $query;

		$res = mysql_query ( $query );

		$table = "<table width=\"100%\" bgcolor=\"#F5F5F5\" class=\"postTable2\">";

		while ( $autolist = @mysql_fetch_array ( $res ) ) {



			$photo = $autolist ['PHOTO_1'];



			//echo $query2;

			//echo $photo;

			if ($photo == "") {

				$img = "<img src=\"show_image.php?filename=photo/none" . $autolist ['CAR_TYPE'] . "_144x108.jpg&width=120\" border=0  alt=\"Подробнее\"/>";

			} else {

				$img = "<img src=\"show_image.php?filename=photo/" . $photo . "&width=120\" border=0  alt=\"Подробнее\"/>";

			}



			$table .= "<tr >";

			$table .= "<td align='center'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=ShowCar&id=" . $autolist ['ID'] . ">$img</a></br><br/>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=ShowCar&id=" . $autolist ['ID'] . "><b>" . $autolist ['TRADEMARK'] . " " . $autolist ['MODEL'] . "</b></a>";


/*
			$table .= $autolist ['YEAR_VYP'] . "г.";

			if ($autolist ['PROBEG']) $autolist ['PROBEG'];

				else $probeg = "";

			$table .= ", " . $probeg;
*/


			$table .= "</br><strong>" . abs ( $autolist ['PRICE'] ) . " руб.";

			$table .= "</strong><br>г. ".$autolist ['CITY'];

			$table .= "</td></tr>";

		}

		$table .= "</table>";

		return $table;

	} else {



		$query = "SELECT a.ID, a.CAR_TYPE, a.DVEREY,a.AKPP, a.POWER, a.V_DVIG, j.CITY, f.MODEL,i.TRADEMARK,r.REGION, b.CAR_TYPE as TYPE,a.YEAR_VYP,a.PROBEG,a.PRICE,a.CAR_MARK,a.PHOTO_1, a.ID_USER,UNIX_TIMESTAMP(a.DATE_VVOD) as DATE_VVOD, c.* FROM  " . TABLE_AUTO . " a, AUTO_CAR_TYPE b, AUTO_USERS c, AUTO_MODEL f,AUTO_TRADEMARK i,AUTO_CITY j,AUTO_REGION r where ";



		$query .= "b.ID=a.CAR_TYPE ";

		$query .= " and f.ID=a.CAR_MODEL";

		$query .= " and i.ID=a.CAR_MARK";

		$query .= " and a.CITY=j.ID";

		$query .= " and a.REGION=r.ID";

		$query .= " and (a.ID_USER=c.id_author and c.locked=0 and c.lock_admin=0) and a.ACTIVE=1 and a.PREDL=1";// and c.status='autosaloon'

		$query .= " ORDER BY RAND() LIMIT 0,7";

	//saloon//////

		//echo $query;

		$res = mysql_query ( $query );

		$table = "<table width=\"100%\" bgcolor=\"#F5F5F5\" class=\"postTable2\">";

		while ( $autolist = @mysql_fetch_array ( $res ) ) {



			$photo = $autolist ['PHOTO_1'];



			if ($photo == "") {

				$img = "<img src=\"show_image.php?filename=photo/none" . $autolist ['CAR_TYPE'] . "_144x108.jpg&width=120\" border=0  alt=\"Подробнее\"/>";

			} else {

				$img = "<img src=\"show_image.php?filename=photo/" . $photo . "&width=120\" border=0  alt=\"Подробнее\"/>";

			}



			$table .= "<tr>";

			$table .= "<td align='center'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=ShowCar&id=" . $autolist ['ID'] . ">$img</a><br />";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=ShowCar&id=" . $autolist ['ID'] . "><b>" . $autolist ['TRADEMARK'] . " " . $autolist ['MODEL'] . "</b></a>";
// – " .$autolist ['YEAR_VYP'] . "г.


/*
			$table .= $autolist ['YEAR_VYP'] . "г.";

			if ($autolist ['PROBEG']) $autolist ['PROBEG'];

				else $probeg = "";

			$table .= ", " . $probeg;
*/

			$table .= "</br><strong>" . abs ( $autolist ['PRICE'] ) . " руб.";

			$table .= "</strong><br>г. ".$autolist ['CITY'];

			$table .= "</td></tr>";



		}

		$table .= "</table>";

		return $table;

	}

}



function showReklama($id_blog) {


	if (intval($_REQUEST['id_blog']) === 52)
		$_SESSION ['pageTitle'] = "Предложение для автосалонов";
	else if (intval($_REQUEST['id_blog']) === 55)
		$_SESSION ['pageTitle'] = "Реклама на сайте";
	else if (intval($_REQUEST['id_blog']) === 56)
		$_SESSION ['pageTitle'] = "Правила и условия";
	else if (intval($_REQUEST['id_blog']) === 53)
		$_SESSION ['pageTitle'] = "Спецразмещение";

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=Reklama&id_blog='.$_REQUEST['id_blog'].'">'.$_SESSION ['pageTitle'].'</a></span>';

	$html2 = file_get_contents ( './templates/reklama.html' );

	if ($id_blog > 0) {



		$query = "SELECT b.ZAGOL,b.TEXT FROM AUTO_BLOG b where b.TYPE=0 and b.ID=" . $id_blog;

		//$query = "SELECT UNIX_TIMESTAMP(b.DATE) as DATE,b.ZAGOL, b.SMALL_TEXT,b.TEXT, b.PICTURE, b.ID, a.NAME FROM AUTO_BLOG b, AUTO_BLOG_TYPE a where b.ACTIVE=1 and b.TYPE=a.ID and b.TYPE=".$id." and b.ID=".$id_blog;





		$res = @mysql_query ( $query );



		while ( $row = @mysql_fetch_array ( $res ) ) {

			$ZAGOL = $row ["ZAGOL"];

			$TEXT = $row ["TEXT"];

		}



		//$_SESSION ['pageTitle'] = $ZAGOL;

		$info = "<div class=h10>" . $ZAGOL . "</div>";

		$info .= "<div>" . $TEXT . "</div";

		$html2 = str_replace ( '{INFO}', $info, $html2 );



	} else

		$html2 = str_replace ( '{INFO}', "", $html2 );

	$html2 = str_replace ( '{zag}', $_SESSION ['pageTitle'], $html2 );

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

	$nav = "";

	$nav .= ( ($_REQUEST['action']==='sendBack') ? '<b>' : '<i><a href=?action=sendBack>'  ).'Контакты'.( ($_REQUEST['action']==='sendBack') ? '<div></div></b>' : '</a></i>').( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='52')) ? '<b>' : '<i><a href=?action=Reklama&id_blog=52>'  ).'Предложение для автосалонов'.( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='52')) ? '<div></div></b>' : '</a></i>'  ).( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='55')) ? '<b>' : '<i><a href=?action=Reklama&id_blog=55>'  ).'Реклама на сайте'.( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='55')) ? '<div></div></b>' : '</a></i>'  ).( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='56')) ? '<b>' : '<i><a href=?action=Reklama&id_blog=56>'  ).'Правила и условия'.( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='56')) ? '<div></div></b>' : '</a></i>'  ).( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='53')) ? '<b>' : '<i><a href=?action=Reklama&id_blog=53>'  ).'Спецразмещение'.( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='53')) ? '<div></div></b>' : '</a></i>'  );

	$html2 = str_replace ( '{nav}', $nav, $html2 );

	return $html2;

}



function showBlog($id, $id_blog) {



	if ($id > 0) {

		//echo $_POST["id"];

		if ($id_blog === 0) {

			$html2 = file_get_contents ( './templates/blogForm.html' );

			$query = "SELECT COUNT(*) FROM AUTO_BLOG where ACTIVE=1 and TYPE=" . $id;

			// echo $query;

			$res = mysql_query ( $query );



			$total = @mysql_result ( $res, 0, 0 );



			if ($total == 0) {

				$html .= ""; //file_get_contents( './templates/searchno.html' );

				return $html;

			}



			// Число страниц списка тем форума (постраничная навигация)

			$cntPages = ceil ( $total / PHOTO_PER_PAGE );



			// Проверяем передан ли номер текущей страницы (постраничная навигация)

			if (isset ( $_GET ['page'] )) {

				$page = ( int ) $_GET ['page'];

				if ($page < 1)

					$page = 1;

			} else {

				$page = 1;

			}

			if ($page > $cntPages)

				$page = $cntPages;

				// Начальная позиция (постраничная навигация)

			$start = ($page - 1) * PHOTO_PER_PAGE;

			// Строим постраничную навигацию, если это необходимо

			// if ( $cntPages > 1 ) {

			$action .= '&id=' . $id;

			// Функция возвращает html меню для постраничной навигации

			$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=Blog&id=' . $id );

			//}

			$query = "SELECT UNIX_TIMESTAMP(b.DATE) as DATE,b.ZAGOL, b.VIEW, b.SMALL_TEXT, b.PICTURE, b.ID, a.NAME FROM AUTO_BLOG b, AUTO_BLOG_TYPE a where b.ACTIVE=1 and b.TYPE=a.ID and b.TYPE=" . $id . " and b.DATE < NOW() order by b.DATE desc, b.ID desc LIMIT " . $start . ", " . PHOTO_PER_PAGE;

			//echo  $query;
			$res = @mysql_query ( $query );

			$html3 = "<table width=\"100%\" border=\"0\" cellpadding=\"10\" cellspacing=\"0\" >";

			while ( $row = @mysql_fetch_array ( $res ) ) {

				$PHOTO = $row ["PICTURE"];

				$TYPE = $row ["NAME"];

				if ((! isset ( $PHOTO )) or ($PHOTO == ""))

					$PHOTO = "";

				else

					$PHOTO = "<img src=\"show_image.php?filename=photo/" . $PHOTO . "&width=94&height=70\" border=0/ align=\"center\" >";

				//<span class=date2>" . date ( "d.m.Y", $row ["DATE"] ) . "</span>



				switch (date ( "w", $row ["DATE"] )) {
					case 0: $w = "Воскресенье"; break;
					case 1: $w = "Понедельник"; break;
					case 2: $w = "Вторник"; break;
					case 3: $w = "Среда"; break;
					case 4: $w = "Четверг"; break;
					case 5: $w = "Пятница"; break;
					case 6: $w = "Суббота"; break;

				}

				switch (date ( "n", $row ["DATE"] )) {
					case 1: $m = "Января"; break;
					case 2: $m = "Февраля"; break;
					case 3: $m = "Марта"; break;
					case 4: $m = "Апреля"; break;
					case 5: $m = "Мая"; break;
					case 6: $m = "Июня"; break;
					case 7: $m = "Июля"; break;
					case 8: $m = "Августа"; break;
					case 9: $m = "Сентября"; break;
					case 10: $m = "Октября"; break;
					case 11: $m = "Ноября"; break;
					case 12: $m = "Декабря"; break;

				}

$_SESSION ['pageTitle'] = $row ["NAME"];

				$html3 .= "<tr><td colspan=\"2\" class=\"date2\" style=\"color: #666; padding-top: 1px; padding-bottom: 0px;\">".$w.", ".date ( "d", $row ["DATE"] )." ".$m." ".date ( "Y", $row ["DATE"] )."</td></tr><tr><td colspan=\"2\" style=\" padding-bottom: 0px; padding-top: 5px;\"><a style=\"text-decoration: underline;\" class=\"newA\" href=" . $_SERVER ['PHP_SELF'] . "?action=Blog&id=" . $id . "&id_blog=" . $row ["ID"] . ">" . $row ["ZAGOL"] . "</a></td></tr><tr>".( $PHOTO ? "<td width=\"94\" height=\"70\" align=\"center\" valign=\"middle\"  style=\"padding-bottom: 0px;\">" . $PHOTO . "</td>" : "")."<td ".( !$PHOTO ? 'colspan="2"' : '' )." style=\" ".( $PHOTO ? "padding-left: 0px;" : "padding-left: 10px;" )." \" valign=\"top\"><div style=\" text-align: justify; color: #666\">" . $row ["SMALL_TEXT"] . "</div></td></tr><tr><td width=94 class=\"date2\" style=\" color: #666; \">Просмотров (". $row ["VIEW"] .")</td><td style=\"padding-left: 0px;\" valign=\"middle\" ><div style=\"height: 11px; width: 100%; background-repeat: repeat-x; background-position: 36% 36%; background-image: url(img/line.gif);\"</td></tr>";



			}

			$html3 .= "</table>";

			$html3 .= $pages;

			$html2 = str_replace ( '{TYPE}', $TYPE, $html2 );

			$html2 = str_replace ( '{NEWS}', $html3, $html2 );



		} else {

			$view = 0;

			$html2 = file_get_contents ( './templates/blogForm2.html' );

			$query = "SELECT UNIX_TIMESTAMP(b.DATE) as DATE, b.VIEW ,b.ZAGOL, b.SMALL_TEXT,b.TEXT, b.PICTURE, b.ID, a.NAME,a.ID as AID FROM AUTO_BLOG b, AUTO_BLOG_TYPE a where b.ACTIVE=1 and b.TYPE=a.ID and b.TYPE=" . $id . " and b.ID=" . $id_blog . " order by b.ID desc";


			//echo  $query;





			$res = @mysql_query ( $query );

			//$html3="<table width=\"100%\" bgcolor=\"#FFFFFF\" >";





			while ( $row = @mysql_fetch_array ( $res ) ) {

				switch (date ( "w", $row ["DATE"] )) {
					case 0: $w = "Воскресенье"; break;
					case 1: $w = "Понедельник"; break;
					case 2: $w = "Вторник"; break;
					case 3: $w = "Среда"; break;
					case 4: $w = "Четверг"; break;
					case 5: $w = "Пятница"; break;
					case 6: $w = "Суббота"; break;

				}

				switch (date ( "n", $row ["DATE"] )) {
					case 1: $m = "Января"; break;
					case 2: $m = "Февраля"; break;
					case 3: $m = "Марта"; break;
					case 4: $m = "Апреля"; break;
					case 5: $m = "Мая"; break;
					case 6: $m = "Июня"; break;
					case 7: $m = "Июля"; break;
					case 8: $m = "Августа"; break;
					case 9: $m = "Сентября"; break;
					case 10: $m = "Октября"; break;
					case 11: $m = "Ноября"; break;
					case 12: $m = "Декабря"; break;

				}

				$view = $row ["VIEW"];

				$PHOTO1 = $row ["PICTURE"];

				if ((! isset ( $PHOTO1 )) or ($PHOTO1 == ""))

					$PHOTO = "";

				else

					$PHOTO = "<img src=\"show_image.php?filename=photo/" . $PHOTO1 . "&width=250\" border=0/ style=\"margin-right: 20px\" alt=\"\" align=\"left\" >";

				$TYPE = $row ["NAME"];

				$ZAGOL = "<span class=news2 style=\"cursor: auto;\">" . $row ["ZAGOL"] . "</span>";

				$ZAGOL2 = $row ["ZAGOL"];

				$DATE = $w.", ".date ( "d", $row ["DATE"] )." ".$m." ".date ( "Y", $row ["DATE"] );

				$TEXT = "<div>" . $row ["TEXT"] . "</div>";

				//<a href=?action=Blog&id=".$row3["ID"]."><span class=\"title4\" >".$row3["NAME"]."</span></a>





			//$html3.="<tr><td valign=\"top\"><span class=news2>".$row["ZAGOL"]."</span></br></br><span class=date2>".date("d.m.Y",$row["DATE"])."</span></br></br><span>".$row["TEXT"]."</span></br></td></tr>";

			//$html3.="<tr><td valign=\"top\"><span class=news2>".$row["ZAGOL"]."</span></br></br><span class=date2>".date("d.m.Y",$row["DATE"])."</span></br></br><span>".$row["TEXT"]."</span></br></td><td width=\"100\">{PICTURES}</td></tr>";





			}

			$query = "UPDATE AUTO_BLOG SET VIEW=".($view+1)." where ID=" . $id_blog;

			//echo $query;

			$res = @mysql_query ( $query );

			//$html3.="</table>";

			$_SESSION ['pageTitle'] = $ZAGOL2 . " " . $DATE;



			$html2 = str_replace ( '{TITLE}', $ZAGOL, $html2 );

			$html2 = str_replace ( '{DATE}', $DATE, $html2 );

			$html2 = str_replace ( '{IMG}', $PHOTO, $html2 );

			$html2 = str_replace ( '{TYPE}', $TYPE, $html2 );

			$html2 = str_replace ( '{NEWS}', $TEXT, $html2 );



			$query = "SELECT * FROM AUTO_BLOG_PICTURE where ID_BLOG=" . $id_blog;

			//$html2= $query;

			$res = @mysql_query ( $query );

			//$PICTURE="<table width=\"100%\" bgcolor=\"#FFFFFF\" valign=\"top\"><tr><td><div id=\"gallery\"><ul>";





			// $BLOCK="</p><table> ";

			$count = mysql_num_rows ( $res );

			$count_block = round ( mysql_num_rows ( $res ) / 3 );

			// $PHOTO="";

			$k = 0;

			$j = 1;

			$i = 1;

			while ( $row = @mysql_fetch_array ( $res ) ) {

				if ($j == 1) {

					if ($i > 1) {

						$PHOTO = "";

						$PHOTO .= "<p><table align=\"center\"><tr><td align\"center\">";



					} else

						$PHOTO = "<p><table align=\"center\"><tr><td align\"center\">";



				}

				$PHOTO .= "<a  href=photo/" . $row ["PICTURE"] . "  class=\"lightbox\" rel=\"roadtrip\"><img src=\"show_image.php?filename=photo/" . $row ["PICTURE"] . "&height=110\" border=0/ ></a>&nbsp";



				$k ++;

				if (($j == 3) or ($count == $k)) {

					$j = 0;

					//$PHOTO.="</td></tr></table></p><p>";





					$PHOTO .= "</td></tr></table></p>";

					$html2 = str_replace ( '{PIC_' . $i . '}', $PHOTO, $html2 );

					$i ++;

					//$k=0;

				}

				$j ++;

			}



		}

	if ($id_blog > 0) {
		$Gpath = $Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=Blog&id='.$id.'">'.$TYPE.'</a></span>';
	} else {
		$Gpath = $Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=Blog&id='.$id.'">'.$TYPE.'</a></span>';
	}

	}

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

	return $html2;

}



function showNews($id, $count, $id_news, $action) {



	if ($id_news == 0) {

		if ($id != - 1) {

			$where = "and a.ID_SALOON=" . $id;

		} else {

			$where = "and a.ID_SALOON>0";

		}

		$query = "SELECT COUNT(a.ID_SALOON) FROM AUTO_NEWS a, AUTO_USERS u where a.ID_SALOON=u.id_author and u.locked=0 and u.lock_admin=0  " . $where;

		//echo $query;
		$res = @mysql_query ( $query );
		$total = @mysql_result ( $res, 0, 0 );

		if ( $total == 0 ) {

			if ( $id != -1 ) {

				$html .= "Новостей нет."; //file_get_contents( './templates/searchno.html' );
				return $html;
			}

			else $total = 1;
		}

		// Число страниц списка тем форума (постраничная навигация)

		$cntPages = ceil ( $total / PHOTO_PER_PAGE );

		// Проверяем передан ли номер текущей страницы (постраничная навигация)

		if (isset ( $_GET ['page'] )) {

			$page = ( int ) $_GET ['page'];

			if ($page < 1)

				$page = 1;

		} else {

			$page = 1;

		}

		if ($page > $cntPages)

			$page = $cntPages;

			// Начальная позиция (постраничная навигация)

		$start = ($page - 1) * PHOTO_PER_PAGE;

		// Строим постраничную навигацию, если это необходимо

		// if ( $cntPages > 1 ) {

		$action .= '&id=' . $id;

		// Функция возвращает html меню для постраничной навигации

		$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

		//}

		$query = "SELECT UNIX_TIMESTAMP(DATA) as DATA, ZAGOL, SMALL_TEXT, PHOTO, ID, ID_SALOON, 0 BLOG FROM AUTO_NEWS a , AUTO_USERS u where a.ID_SALOON=u.id_author and u.locked=0 and u.lock_admin=0 " . $where . " and a.DATA < now()";

		if ( $id == -1 ) {

			$query = "($query) union (select unix_timestamp(DATE) DATA, ZAGOL, SMALL_TEXT, PICTURE PHOTO, ID, TYPE ID_SALOON, 1 BLOG from AUTO_BLOG where SHOW_IN_ANONS=1 and DATE < now())";
		}

		$query .= " order by DATA DESC, ID desc LIMIT $start," . PHOTO_PER_PAGE;

		//echo  $query;
		$res = @mysql_query ( $query );

		$html3 = "<table width=\"100%\" bgcolor=\"#F5F5F5\" class=\"postTable2\">";

		while ( $row = @mysql_fetch_array ( $res ) ) {

			$PHOTO = $row ["PHOTO"];

			if ((! isset ( $PHOTO )) or ($PHOTO == ""))

				$PHOTO = "nofotonews.gif";

			$href = $_SERVER ['PHP_SELF'] . ( $row['BLOG'] == 0 ? "?action=News&id_saloon={$row['ID_SALOON']}&id_news={$row ['ID']}" : "?action=Blog&id={$row['ID_SALOON']}&id_blog={$row['ID']}" );
			$html3 .= "<tr><td valign=\"top\"><a href=\"$href\"><span class=news>" . $row ["ZAGOL"] . "</span></a></br><span>" . nl2br($row ["SMALL_TEXT"]) . "</span></br><span class=date2>" . date ( "d.m.Y", $row ["DATA"] ) . "</span></br></td><td width=\"120\" ><img src=\"show_image.php?filename=photo/" . $PHOTO . "&width=120\" border=0/ ></td></tr>";

		//$PHOTO.="<li><a href=\"photo/".$row["PHOTO"]."\" title=\"".$title."\"><img src=\"show_image.php?filename=photo/".$row["PHOTO"]."&width=160\" border=0/ ></a></li>";

		//$PHOTO.=$row["PHOTO"];

		}

		$html3 .= "<tr><td valign=\"top\" colspan=2>";

		if ($count == 6) {

			$html3 .= "<a href=\"?action=News\">Все новости</a>";

		} else

			$html3 .= $pages;

		$html3 .= "</td></tr></table>";

	} else {

		$query = "SELECT UNIX_TIMESTAMP(DATA) as DATA,SMALL_TEXT,TEXT, PHOTO, ID, ID_SALOON FROM AUTO_NEWS a, AUTO_USERS u where a.ID_SALOON=u.id_author and u.locked=0 and u.lock_admin=0  " . $where . " and ID=" . $id_news;

		//echo  $query;

		$res = @mysql_query ( $query );

		$html3 = "<table width=\"100%\" bgcolor=\"#F5F5F5\" class=\"postTable2\">";

		while ( $row = @mysql_fetch_array ( $res ) ) {

			$PHOTO = $row ["PHOTO"];

			if ((! isset ( $PHOTO )) or ($PHOTO == ""))

				$PHOTO = "none1.jpg";

			$html3 .= "<tr><td valign=\"top\"><span class=news>" . $row ["SMALL_TEXT"] . "</span></br><span class=date>" . date ( "d.m.Y", $row ["DATA"] ) . "</span></td><td width=\"120\" ><img src=\"show_image.php?filename=photo/" . $PHOTO . "&width=120\" border=0/ ></td></tr>";
		}

		$html3 .= "</table>";

		//	$html3.=$pages;
	}

	return $html3;
}



function showStat($type, $id) {

	//  settype($id,"integer");

	if ($type == 'saloon') {

		$html3 = "<table width=\"100%\" bgcolor=\"#F5F5F5\" class=\"postTable2\">";
		$html3 .= "<tr bgcolor=\"#999999\"><td >Наименование</td><td width=\"70\">За сегодня</td><td width=\"70\">За месяц</td><td>Всего</td></tr>";
		$html3 .= "<tr><td>" . VIEW_CAR . "</td><td width=\"70\">{VIEW_CAR1}</td><td width=\"70\">{VIEW_CAR30}</td><td width=\"70\">{VIEW_CAR0}</td></tr>";
		$html3 .= "<tr><td>" . VIEW_PRICE . "</td><td width=\"70\">{VIEW_PRICE1}</td><td width=\"70\">{VIEW_PRICE30}</td><td width=\"70\">{VIEW_PRICE0}</td></tr>";
		$html3 .= "<tr><td>" . VIEW_SALOON . "</td><td width=\"70\">{VIEW_SALOON1}</td><td width=\"70\">{VIEW_SALOON30}</td><td width=\"70\">{VIEW_SALOON0}</td></tr>";
		$html3 .= "<tr><td>" . VIEW_SITE . "</td><td width=\"70\">{VIEW_SITE1}</td><td width=\"70\">{VIEW_SITE30}</td><td width=\"70\">{VIEW_SITE0}</td></tr>";
		$html3 .= "</table>";

		$datelist = time () - 86400 * 30;

		$vdate = strftime ( '%Y-%m-%d', $datelist );

		$query = "SELECT count(TYPE) as COUNT, TYPE FROM AUTO_LOGS_SALOON where ID_SALOON=" . $id;

		$query .= " and DATE>='" . $vdate . "' ";

		$query .= " GROUP by TYPE";



		//echo $query;

		$res = @mysql_query ( $query );

		while ( $row = @mysql_fetch_array ( $res ) ) {

			//$html3.= "<tr><td>".$row['TYPE']."</td><td width=\"50\">".$row['COUNT']."</td></tr>";

			if ($row ["TYPE"] == VIEW_CAR) {

				$html3 = str_replace ( '{VIEW_CAR30}', $row ["COUNT"], $html3 );

			}

			if ($row ["TYPE"] == VIEW_PRICE) {

				$html3 = str_replace ( '{VIEW_PRICE30}', $row ["COUNT"], $html3 );

			}

			if ($row ["TYPE"] == VIEW_SALOON) {

				$html3 = str_replace ( '{VIEW_SALOON30}', $row ["COUNT"], $html3 );

			}

			if ($row ["TYPE"] == VIEW_SITE) {

				$html3 = str_replace ( '{VIEW_SITE30}', $row ["COUNT"], $html3 );

			}

		}



		$datelist = time () - 86400 * 0;

		$vdate = strftime ( '%Y-%m-%d', $datelist );

		$query = "SELECT count(TYPE) as COUNT, TYPE FROM AUTO_LOGS_SALOON where ID_SALOON=" . $id;

		$query .= " and DATE>='" . $vdate . "' ";

		$query .= " GROUP by TYPE";



		//echo $query;

		$res = @mysql_query ( $query );

		while ( $row = @mysql_fetch_array ( $res ) ) {

			//$html3.= "<tr><td>".$row['TYPE']."</td><td width=\"50\">".$row['COUNT']."</td></tr>";

			if ($row ["TYPE"] == VIEW_CAR) {

				$html3 = str_replace ( '{VIEW_CAR1}', $row ["COUNT"], $html3 );

			}

			if ($row ["TYPE"] == VIEW_PRICE) {

				$html3 = str_replace ( '{VIEW_PRICE1}', $row ["COUNT"], $html3 );

			}

			if ($row ["TYPE"] == VIEW_SALOON) {

				$html3 = str_replace ( '{VIEW_SALOON1}', $row ["COUNT"], $html3 );

			}

			if ($row ["TYPE"] == VIEW_SITE) {

				$html3 = str_replace ( '{VIEW_SITE1}', $row ["COUNT"], $html3 );

			}

		}

		//  $datelist=time()-86400*30;

		//$vdate=strftime('%Y-%m-%d',$datelist);

		$query = "SELECT count(TYPE) as COUNT, TYPE FROM AUTO_LOGS_SALOON where ID_SALOON=" . $id;

		//$query.=" and DATE>='".$vdate."' ";

		$query .= " GROUP by TYPE";



		//echo $query;

		$res = @mysql_query ( $query );

		while ( $row = @mysql_fetch_array ( $res ) ) {

			//$html3.= "<tr><td>".$row['TYPE']."</td><td width=\"50\">".$row['COUNT']."</td></tr>";

			if ($row ["TYPE"] == VIEW_CAR) {

				$html3 = str_replace ( '{VIEW_CAR0}', $row ["COUNT"], $html3 );

			}

			if ($row ["TYPE"] == VIEW_PRICE) {

				$html3 = str_replace ( '{VIEW_PRICE0}', $row ["COUNT"], $html3 );

			}

			if ($row ["TYPE"] == VIEW_SALOON) {

				$html3 = str_replace ( '{VIEW_SALOON0}', $row ["COUNT"], $html3 );

			}

			if ($row ["TYPE"] == VIEW_SITE) {

				$html3 = str_replace ( '{VIEW_SITE0}', $row ["COUNT"], $html3 );

			}

		}

		//$html3.="</table>";

		$html3 = str_replace ( '{VIEW_CAR0}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_PRICE0}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_SALOON0}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_SITE0}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_CAR1}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_PRICE1}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_SALOON1}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_SITE1}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_CAR30}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_PRICE30}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_SALOON30}', 0, $html3 );

		$html3 = str_replace ( '{VIEW_SITE30}', 0, $html3 );

	} else {



	}

	return $html3;

}



function redirect() {

	if ((isset ( $_GET ['url'] )) or (isset ( $_GET ['id'] ))) {



		if (substr ( $GET ['url'], 1, 7 ) == 'http://') {

			save_log ( VIEW_SITE, 'saloon', abs ( $_GET ['id'] ) );

			header ( 'Location: ' . substr ( $_GET ['url'], 8, Length ( $GET ['url'] - 7 ) ) . "" );

		} else {

			save_log ( VIEW_SITE, 'saloon', abs ( $_GET ['id'] ) );

			header ( 'Location: http://' . $_GET ['url'] . "" );

		}



		die ();



	} else {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . "" );

		die ();

	}

}



function ShowCar($print = 0) {

	if (isset ( $_GET ['id'] )) // and ((int)$_GET['id']>0))

{
		$query = "SELECT a.*, f.MODEL,i.TRADEMARK, c.status FROM " . TABLE_AUTO . " a, AUTO_USERS c, AUTO_MODEL f, AUTO_TRADEMARK i where ";

		$query .= "f.ID=a.CAR_MODEL";

		$query .= " and i.ID=a.CAR_MARK";

		$query .= " and a.ID=" . ( int ) $_GET ['id'];

		$query .= " and (a.ID_USER=c.id_author and c.locked=0 and c.lock_admin=0) and a.ACTIVE=1";

		//   $query.=" group by a.CAR_MODEL,a.CAR_TYPE,a.CAR_MARK, f.MODEL, i.TRADEMARK";

		// $html.=$query;

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка';

			$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

			//$html.=$query;

		}



		while ( $row = mysql_fetch_array ( $res ) ) {

			//	$html.=$tc;

			$count = $row ["COUNT"];

			$car_mark = $row ["CAR_MARK"];

			$title = $row ["MODEL"];

			$car_tr = $row ["CAR_MODEL"];

			$car_model = $row ["MODEL"];

			$car_type = $row ["CAR_TYPE"];

			$mark = $row ["TRADEMARK"];

			$price = round ( $row ["PRICE"] );

			$year_vyp = $row ["YEAR_VYP"];

			$id_user = $row ["ID_USER"];

			$TYPE_KUZ = $row ["TYPE_KUZ"];

			$new = $row ["NEW"];

			$REGION = $row ["REGION"];

			$CITY = $row ["CITY"];

			$PRAV_RUL = $row ["PRAV_RUL"];

			$SOST = $row ["SOST"];

			$PROBEG = $row ["PROBEG"];

			$TYPE_DVIG = $row ["TYPE_DVIG"];

			$V_DVIG = $row ["V_DVIG"];

			$POWER = $row ["POWER"];

			$TYPE_PRIV = $row ["TYPE_PRIV"];

			$KOL_PERED = $row ["KOL_PERED"];

			$AKPP = $row ["AKPP"];

			$DVEREY = $row ["DVEREY"];

			$COLOR = $row ["COLOR"];

			$METALL = $row ["METALL"];

			$PROIZV = $row ["PROIZV"];

			$NE_RASTAM = $row ["NE_RASTAM"];

			$BEZ_PROB = $row ["BEZ_PROB"];

			$DESCR = $row ["DESCR"];

			$ID_USER = $row ["ID_USER"];

			$status = $row ["status"];

			$date = date( 'd.m.Y', strtotime( $row['DATE_VVOD'] ) );

			$PHOTO [1] = $row ["PHOTO_1"];

			$PHOTO [2] = $row ["PHOTO_2"];

			$PHOTO [3] = $row ["PHOTO_3"];

			$PHOTO [4] = $row ["PHOTO_4"];

			$PHOTO [5] = $row ["PHOTO_5"];

			$PHOTO [6] = $row ["PHOTO_6"];

			$j = 0;

			$PHOTO = "";

			for($i = 1; $i <= 6; $i ++) {

				if ((isset ( $row ["PHOTO_" . $i] )) and ( $row ["PHOTO_" . $i] !="")) {

					$j ++;

				}

			}

			if ($j > 0) {

				$PHOTO = "<table width=\"190\" cellpadding=\"2\">";
				$ii = 0;
				for($i = 1; $i <= 6; $i ++) {

				if ((isset ( $row ["PHOTO_" . $i] )) and ( $row ["PHOTO_" . $i] !="")) $ii++;

					if (($_REQUEST['ph']==="1") || !$_REQUEST['ph']) {
					if ($ii < 4) {



					if ((isset ( $row ["PHOTO_" . $i] )) and ($print == 1) and ( $row ["PHOTO_" . $i] !="")) {

						$PHOTO .= "<tr><td style=\"border-bottom: 1px solid #666;\">";

						$PHOTO .= "<a href=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=640\" class=\"lightbox\" rel=\"roadtrip\"><img src=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=160\"   /></a></br></br>";

						$PHOTO .= "</td></tr>";



					}

					if ((isset ( $row ["PHOTO_" . $i] )) and ($print != 1) and ( $row ["PHOTO_" . $i] !="")) {

						$PHOTO .= "<tr><td style=\"border-bottom: 1px solid #666;\">";

						$PHOTO .= "<a href=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=640\"  class=\"lightbox\" rel=\"roadtrip\"><img src=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=180\" style=\"border:#FFFFFF thin;\" /></a></br>";

						$PHOTO .= "</td></tr>";



					}
					}

					for ($d=($ii);$d<=6;$d++) {
					if ((isset ( $row ["PHOTO_" . $d] )) and ($print != 1) and ( $row ["PHOTO_" . $d] !="")) {
						$PHOTO .= "<span style=\"display: none;\"><a href=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $d] . "&width=640\"  class=\"lightbox\" rel=\"roadtrip\"></a></span>";
					}
					}

					}

					else if ($_REQUEST['ph']==="2") {

					for ($d=1;$d<$ii;$d++) {
					if ((isset ( $row ["PHOTO_" . $d] )) and ($print != 1) and ( $row ["PHOTO_" . $d] !="")) {
						$PHOTO .= "<span style=\"display: none;\"><a href=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $d] . "&width=640\"  class=\"lightbox\" rel=\"roadtrip\"></a></span>";
					}
					}

					if ($ii > 3) {
					if ((isset ( $row ["PHOTO_" . $i] )) and ($print == 1) and ( $row ["PHOTO_" . $i] !="")) {

						$PHOTO .= "<tr><td style=\"border-bottom: 1px solid #666;\">";

						$PHOTO .= "<a href=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=640\" class=\"lightbox\" rel=\"roadtrip\"><img src=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=160\"   /></a></br></br>";

						$PHOTO .= "</td></tr>";



					}

					if ((isset ( $row ["PHOTO_" . $i] )) and ($print != 1) and ( $row ["PHOTO_" . $i] !="")) {

						$PHOTO .= "<tr><td style=\"border-bottom: 1px solid #666;\">";

						$PHOTO .= "<a href=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=640\"  class=\"lightbox\" rel=\"roadtrip\"><img src=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=180\" style=\"border:#FFFFFF thin;\" /></a></br>";

						$PHOTO .= "</td></tr>";



					}
					}
					}

				}


				if ($ii===1) {$ii1='1';$ii2='';}
				else if ($ii===2) {$ii1='1&nbsp;2';$ii2='';}
				else if ($ii===3) {$ii1='1&nbsp;2&nbsp;3';$ii2='';}
				else if ($ii===4) {$ii1='1&nbsp;2&nbsp;3';$ii2='4';}
				else if ($ii===5) {$ii1='1&nbsp;2&nbsp;3';$ii2='4&nbsp;5';}
				else if ($ii===6) {$ii1='1&nbsp;2&nbsp;3';$ii2='4&nbsp;5&nbsp;6';}

//^^//
				$PHOTO .= "<tr>".(($_REQUEST['action'] !== 'Print') ? '<td align="center">'.(!(($_REQUEST['ph']==="1") || !$_REQUEST['ph']) ? '<a href=?action=ShowCar&id=' . $_GET ["id"] . '&ph=1><b>'.$ii1.'</b></a>' : '<span style="color: #666; font-weight: bold;">'.$ii1.'</span>').'&nbsp;'.((($_REQUEST['ph'] !== "2") && ($ii>3)) ? '<a href=?action=ShowCar&id=' . $_GET ["id"] . '&ph=2><b>'.$ii2.'</b></a>' : '<span style="color: #666; font-weight: bold;">'.$ii2.'</span>')."</td>" : "")."</tr></table>";



			} else {

				$PHOTO = "<div class=\"head5\">НЕТ ФОТО</div>";

			}

		}



		if ($status != "autosaloon") {

			if ($print == 1) {

				$html2 = file_get_contents ( './templates/carPrint.html' );

			} else {

				$html2 = file_get_contents ( './templates/carForm.html' );

			}

			$query = "SELECT * FROM AUTO_USERS where ";

			$query .= "id_author=" . $id_user . " and locked=0";



		} else {



			if ($print == 1) {

				$html2 = file_get_contents ( './templates/carPrint2.html' );

			} else {

				$html2 = file_get_contents ( './templates/carForm2.html' );

			}
//тарарам
			$query = "SELECT  a.* from AUTO_USERS a  where ";

			$query .= " a.id_author=" . $id_user;

			$query .= " and a.locked=0";



			save_log ( VIEW_CAR, 'saloon', $id_user );

		}

		save_log ( VIEW_CAR, 'user', $_GET ['id'] );



		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка';

			$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

			//$html.=$query;

		}

		while ( $row = mysql_fetch_array ( $res ) ) {

			$tel = $row ["tel1"];

			$NAME_USER = $row ["name"];

			$TEL2 = $row ["tel2"];

			$nach1 = $row ["nach1"];

			$nach2 = $row ["nach2"];

			$end1 = $row ["end1"];

			$end2 = $row ["end2"];

			$url = $row ["url"];

			$email = $row ["email"];

			$status = $row ["status"];
//скоп
			if (!$DESCR) $DESCR="Информация отсутствует";

			$html2 = str_replace ( '{DESCR}', $DESCR, $html2 );

			$html2 = str_replace ( '{ADDRESS}', $row ['address'], $html2 );

			$html2 = str_replace ( '{DOPOLN}', nl2br($DESCR), $html2 );

			$html2 = str_replace ( '{WEB}', "<a href=?action=redirect&url=" . $row ['url'] . ">" . $row ['url'] . "</a>", $html2 );

			$html2 = str_replace ( '{EMAIL}', "<a href=?action=sendMailForm&idUser=" . $row ['id_author'] . ">Написать письмо</a>", $html2 );

			$html2 = str_replace ( '{SALOON}', "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $row ['id_author'] . ">" . $NAME_USER . "</a>", $html2 );

			$html2 = str_replace ( '{NAME}', $NAME_USER, $html2 );

			$html2 = str_replace ( '{SALOON_PRICE}', "<a href=?action=price&id=" . $ID_USER . ">Все автомобили автосалона " . $NAME_USER . " </a>", $html2 );



		}



		$CAR_123 = "" . $mark . " " . $car_model . " - " . $price . " руб., " . $year_vyp . " г.";



		$title = $mark . " " . $car_model . " - " . $price . " руб., " . $year_vyp . " г., телефон:" . $tel;

		$html2 = str_replace ( '{CAR}', $mark . " " . $car_model . " - " . $price . " руб., " . $year_vyp . " г.", $html2 );

		$html2 = str_replace ( '{TEL}', $tel, $html2 );
		$html2 = str_replace ( '{DATE}', $date, $html2 );



		$nach1 = ($nach1 > 0) ? " c " . $nach1 : "";

		$end1 = ($end1 > 0) ? " до " . $end1 . " часов" : "";

		$nach2 = ($nach2 > 0) ? " c " . $nach2 : "";

		$end2 = ($end2 > 0) ? " до " . $end2 . " часов" : "";

		if ($TEL2 != "") {

			if ($status != "autosaloon") {

				$TEL2 = "<tr><td><span><strong>Телефон 2: </strong></span>" . $TEL2 . $nach2 . $end2 . "</td></tr>";

			} else {

				$TEL2 = "<tr><td><span><strong>Тел./факс: </strong></span>" . $TEL2 . $nach2 . $end2 . "</td></tr>";

			}



		} else

			$TEL2 = "";



		$html2 = str_replace ( '{TEL1}', $tel . $nach1 . $end1, $html2 );

		$html2 = str_replace ( '{TEL2}', $TEL2, $html2 );



	}

	$table = "<table width=100% align=\"left\"><tr>";

	$table .= "<td align=\"left\" width=\"20\" valign=\"middle\">";

	$table .= "<div class=\"notebook\" valign=\"middle\"><img src=\"img/basket_add.gif\" width=\"19\" hieght=\"17\" border=\"0\"

	id=\"notebook_" . $_GET ["id"] . "\" name=\"" . $_GET ["id"] . "\" title=\"Записать в блокнот\"></div></td><td width=120 valign=middle>

	Записать в блокнот

	";

	//$table.="</td><td width=\"120\" valign=\"middle\" align=\"left\"><span class=\"notebook\">Записать в блокнот";

	$table .= "</td><td valign=\"middle\" align=\"center\">[<a href=?action=sendMailForm&idUser=" . $ID_USER . ">Уточнить наличие и условия приобретения</a>]";

	$table .= "</td><td width=\"100\" valign=\"middle\" align=\"left\">[<a href=?action=Print&id=" . $_GET ["id"] . " target=_new>Распечатать</a>]";

	$table .= "</td></tr></table>";

	$html2 = str_replace ( '{ADVANCED}', $table, $html2 );



	$html2 = str_replace ( '{PRICE}', $price . " руб.", $html2 );

	$html2 = str_replace ( '{YEAR}', $year_vyp, $html2 );



	$query = "SELECT * FROM AUTO_TYPE_KUZ where ID=" . $TYPE_KUZ;

	$res = @mysql_query ( $query );

	while ( $row = @mysql_fetch_array ( $res ) ) {

		$TYPE_KUZ = $row ["TYPE_KUZ"];

	}

	$SPEC_KUZOV = "";

	$SPEC_KUZOV = ($TYPE_KUZ) ? $TYPE_KUZ : "";

	$SPEC_KUZOV .= (($SPEC_KUZOV != "") and ($DVEREY > 0)) ? ", " : "";

	$SPEC_KUZOV .= ($DVEREY > 0) ? "дверей " . $DVEREY : "";

	//$DVEREY=($PRAV_RUL==1)?$DVEREY.",":$DVEREY;

	$SPEC_KUZOV .= (($SPEC_KUZOV != "") and ($PRAV_RUL == 1)) ? ", " : "";

	$SPEC_KUZOV .= ($PRAV_RUL == 1) ? "правый руль" : "";

	$html2 = str_replace ( '{KUZOV}', $SPEC_KUZOV, $html2 );

	//$html2 = str_replace( '{KUZOV}',$TYPE_KUZ.$DVEREY.$PRAV_RUL, $html2 );





	$query = "SELECT * FROM AUTO_COLOR where ID=" . $COLOR;

	$res = mysql_query ( $query );

	while ( $row = @mysql_fetch_array ( $res ) ) {

		$COLOR = $row ["COLOR"];

	}

	$COLOR = ($COLOR) ? $COLOR : "";

	$COLOR .= ($METALL == 1) ? ", " : "";

	$METALL = ($METALL == 1) ? "металлик" : "";



	$html2 = str_replace ( '{COLOR}', $COLOR . $METALL, $html2 );



	//$TYPE_DVIG=$row["TYPE_DVIG"];





	$query = "SELECT * FROM AUTO_TYPE_DVIG where ID=" . $TYPE_DVIG;

	//echo  $query;

	$res = @mysql_query ( $query );

	while ( $row = @mysql_fetch_array ( $res ) ) {

		$TYPE_DVIG = $row ["TYPE_DVIG"];

	}

	//echo $TYPE_DVIG;

	$SPEC_DVIG = "";

	$SPEC_DVIG = ($TYPE_DVIG) ? $TYPE_DVIG : "";

	$SPEC_DVIG .= (($SPEC_DVIG != "") and ($V_DVIG > 0)) ? ", " : "";

	$SPEC_DVIG .= ($V_DVIG > 0) ? "объем " . $V_DVIG . " л." : "";

	$SPEC_DVIG .= (($SPEC_DVIG != "") and ($POWER > 0)) ? ", " : "";

	$SPEC_DVIG .= ($POWER > 0) ? "мощность " . $POWER . " л.с." : "";



	$html2 = str_replace ( '{DVIG}', $SPEC_DVIG, $html2 );



	$SPEC_PROBEG = "";

	$SPEC_PROBEG = ($PROBEG > 0) ? $PROBEG . " тыс.км." : "";

	$SPEC_PROBEG .= (($SPEC_PROBEG != "") and ($BEZ_PROB == 1)) ? ", " : "";

	$SPEC_PROBEG .= ($BEZ_PROB == 1) ? "без пробега по РФ" : "";

	$SPEC_PROBEG .= (($SPEC_PROBEG != "") and ($NE_RASTAM == 1)) ? ", " : "";

	$SPEC_PROBEG .= ($NE_RASTAM == 1) ? "не растаможен" : "";

	$SPEC_PROBEG .= ($SPEC_PROBEG == "") ? "-" : "";

	$html2 = str_replace ( '{PROBEG}', $SPEC_PROBEG, $html2 );



	$AKPP = ($AKPP == 1) ? "АКПП" : "МКПП";

	$KOL_PERED = ($KOL_PERED > 0) ? ", " . $KOL_PERED . " ступенчатая " : "";

	$html2 = str_replace ( '{PEREDACH}', $AKPP . $KOL_PERED, $html2 );



	switch ($TYPE_PRIV) {

		case '1' : // главная страница форума

			$TYPE_PRIV = "заднеприводной";

			break;

		case '2' : // главная страница форума

			$TYPE_PRIV = "переднеприводной";

			break;

		case '3' : // главная страница форума

			$TYPE_PRIV = "полноприводной";

			break;

		default :

			$TYPE_PRIV = "";



	}



	$html2 = str_replace ( '{PRIVOD}', $TYPE_PRIV, $html2 );



	$query = "SELECT * FROM AUTO_SOST where ID=" . $SOST;

	$res = @mysql_query ( $query );

	while ( $row = @mysql_fetch_array ( $res ) ) {

		$SOST = $row ["SOST"];

	}

	$SOST = ($SOST) ? $SOST : "";

	$html2 = str_replace ( '{SOST}', $SOST, $html2 );



	$query = "SELECT * FROM AUTO_REGION where ID=" . $REGION;

	//echo  $query;

	$res = @mysql_query ( $query );

	while ( $row = @mysql_fetch_array ( $res ) ) {

		$REGION = $row ["REGION"];

	}

	$REGION = ($REGION != "") ? $REGION : "";



	$query = "SELECT * FROM AUTO_CITY where ID=" . $CITY;

	//echo  $query;

	$res = @mysql_query ( $query );

	while ( $row = @mysql_fetch_array ( $res ) ) {

		$CITY = $row ["CITY"];

	}

	$CITY = ($CITY != "") ? $CITY : "";



	$_SESSION ['pageTitle'] = $CAR_123 . ", " . $REGION . ", г. " . $CITY;

	$html2 = str_replace ( '{REGION}', $REGION . ", г. " . $CITY, $html2 );

	$html2 = str_replace ( '{DOPOLN}', $DESCR, $html2 );

	$html2 = str_replace ( '{PHOTO}', $PHOTO, $html2 );
///////////
	$Gpath = $Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=MarkView&new='.($new ? '1' : '0').'&type=1">'.($new ? 'Новые автомобили' : 'Подержанные автомобили').'</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=ModelView&mark='.$car_mark.'&new='.($new ? '1' : '0').'&type=1">'.trim($mark).'</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=searchView&id_typeCode='.$car_type.'&id_markCode='.$car_mark.'&id_modelCode='.$car_tr.'&new='.($new ? '1' : '0').'&type=1">'.$car_model.'</a></span>';


	$html2 = str_replace ( '{path}', $Gpath, $html2 );

	$html2 = str_replace ( '{NUM}', abs($_REQUEST['id']), $html2 );

	return $html2;



}



function saveView() {

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=saveView">Блокнот</a></span>';

	unset ( $_SESSION ['searchForm'] ['sql'] );

	$_SESSION ['pageTitle'] = "Блокнот";



	$count1 = count ( $_COOKIE );

	$count = 0;

	$SQL = "(";



	foreach ( $_COOKIE as $index => $val ) {



		if (substr ( $index, 0, 9 ) == "notebook_") {



			$SQL .= (($SQL) != "(") ? " or " : "";

			$SQL .= " a.ID='" . $val . "'";

			$count ++;



		}



	}



	$SQL .= ")";

	if ($SQL == "()") {



		$html = file_get_contents ( './templates/searchno.html' );

	} else {



		$html = file_get_contents ( './templates/notebookForm.html' );

		$html = str_replace ( '{notebook}', ShowTableCar ( $SQL, 'saveView', 'ShowCar' ), $html );



	}

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html; //."-".$SQL;

}



// Функция возвращает html главной страница форума (список форумов)

function getMainPage(&$pageTitle) {

	$_SESSION ['pageTitle']="Сибирский автомобильный портал - покупка, продажа легковых, грузовых, автобусов, спецтехники, водного транспорта, снегоходы в России";

	$Gpath = '<a href="http://'.$_SERVER['SERVER_NAME'].'">главная</a>';

	//$pageTitle = $pageTitle . ' / Автомобильный портал Сибири';

	unset ( $_SESSION ['searchForm'] );

	$html = file_get_contents ( './templates/mainForm.html' );

	$html = str_replace ( '{MAIN_2}', "<a href=/>На главную</a>", $html );

	$html = str_replace ( '{main}', showSearchForm (), $html );

	$html = str_replace ( '{path}', $Gpath, $html );

	//$html.=showSearchForm();





	return $html;



}



function showSaloon() {



	$html = file_get_contents ( './templates/searchSaloon.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=showSaloon';

	$html = str_replace ( '{action}', $action, $html );

	$_SESSION ['pageTitle'] = "Поиск автосалона";



	if (isset ( $_GET ["del"] ) and ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchSaloon'] );

	}



	//if (isset($_GET["mark"])) { $_SESSION['searchSaloon']['id_markCode']=$_GET["mark"] ;}

	if (isset ( $_GET ["id_typeCode"] )) {

		$_SESSION ['searchSaloon'] ['id_typeCode'] = RemoveXSS ( mysql_escape_string ( $_GET ["id_typeCode"] ) );

	}

	if (isset ( $_GET ["id_markCode"] )) {

		$_SESSION ['searchSaloon'] ['id_markCode'] = RemoveXSS ( mysql_escape_string ( $_GET ["id_markCode"] ) );

	}

	if (isset ( $_GET ["id_region"] )) {

		$_SESSION ['searchSaloon'] ['id_region'] = RemoveXSS ( mysql_escape_string ( $_GET ["id_region"] ) );

	}

	if (isset ( $_GET ["id_city"] )) {

		$_SESSION ['searchSaloon'] ['id_city'] = RemoveXSS ( mysql_escape_string ( $_GET ["id_city"] ) );

	}

	if (isset ( $_GET ["saloonname"] )) {

		$_SESSION ['searchSaloon'] ['saloonname'] = RemoveXSS ( mysql_escape_string ( $_GET ["saloonname"] ) );

	}



	// if (isset($_POST["mark"])) {$_SESSION['searchSaloon']['id_markCode']=$_POST["mark"] ;}

	if (isset ( $_POST ["id_typeCode"] )) {

		$_SESSION ['searchSaloon'] ['id_typeCode'] = RemoveXSS ( mysql_escape_string ( $_POST ["id_typeCode"] ) );

	}

	if (isset ( $_POST ["id_region"] )) {

		$_SESSION ['searchSaloon'] ['id_region'] = RemoveXSS ( mysql_escape_string ( $_POST ["id_region"] ) );

	}

	if (isset ( $_POST ["id_city"] )) {

		$_SESSION ['searchSaloon'] ['id_city'] = RemoveXSS ( mysql_escape_string ( $_POST ["id_city"] ) );

	}

	if (isset ( $_POST ["id_markCode"] )) {

		$_SESSION ['searchSaloon'] ['id_markCode'] = RemoveXSS ( mysql_escape_string ( $_POST ["id_markCode"] ) );

	}

	if (isset ( $_POST ["saloonname"] )) {

		$_SESSION ['searchSaloon'] ['saloonname'] = RemoveXSS ( mysql_escape_string ( $_POST ["saloonname"] ) );

	}



	$html = str_replace ( '{SALOON}', RemoveXSS ( mysql_escape_string ( $_POST ['saloonname'] ) ), $html );



	$query = "SELECT * FROM AUTO_CAR_TYPE";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($typelist ['ID'] == $_SESSION ['searchSaloon'] ['id_typeCode']) ? " selected" : "";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . " >" . $typelist ['CAR_TYPE'] . "</option>";

		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );



	if (isset ( $_SESSION ['searchSaloon'] ['id_typeCode'] )) {



		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $_SESSION ['searchSaloon'] ['id_typeCode'];

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $_SESSION ['searchSaloon'] ['id_markCode']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );



	}



	$query = "SELECT * FROM AUTO_REGION order by REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] == $_SESSION ['searchSaloon'] ['id_region']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . ">" . $regionlist ['REGION'] . "</option>";

		}

	}



	$html = str_replace ( '{REGION}', $region, $html );



	if (isset ( $_SESSION ['searchSaloon'] ['id_region'] )) {

		$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $_SESSION ['searchSaloon'] ['id_region'] . " order by CITY";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок123123</br>';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$city = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $regionlist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($regionlist ['ID'] == $_SESSION ['searchSaloon'] ['id_city']) ? " selected" : "";

				$city .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . ">" . $regionlist ['CITY'] . "</option>";

			}

		}

		$html = str_replace ( '{CITY}', $city, $html );

		if ($_SESSION ['searchSaloon'] ['id_city'] > 0) {

			$query2 = "SELECT CITY FROM AUTO_CITY where ID=" . $_SESSION ['searchSaloon'] ['id_city'];

			$res2 = mysql_query ( $query2 );

			$city = mysql_result ( $res2, 0, 0 );

			$html = str_replace ( '{CITY_FIND}', ", г. " . $city, $html );

		} else {

			$html = str_replace ( '{CITY_FIND}', "", $html );

		}



	}

	$html = str_replace ( '{CITY_FIND}', "", $html );

	$html = str_replace ( '{FOUND}', showSearchSaloon ( 'showSaloon' ), $html );

	//$html.=showSearchForm();



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=SaloonCity&del=1">Автосалоны</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;



}



function showSearchSaloon($action) {

	$where = "";

	if (isset ( $_SESSION ['searchSaloon'] ['id_typeCode'] ) && $_SESSION ['searchSaloon'] ['id_typeCode'] > 0)

		$where .= "b.CAR_TYPE=" . $_SESSION ['searchSaloon'] ['id_typeCode'];

	if (isset ( $_SESSION ['searchSaloon'] ['id_markCode'] ) && $_SESSION ['searchSaloon'] ['id_markCode'] > 0)

		$where = ($where != "") ? $where . " and b.CAR_MARK=" . $_SESSION ['searchSaloon'] ['id_markCode'] : "b.CAR_MARK=" . $_SESSION ['searchSaloon'] ['id_markCode'];

	if (isset ( $_SESSION ['searchSaloon'] ['id_region'] ) && $_SESSION ['searchSaloon'] ['id_region'] > 0)

		$where = ($where != "") ? $where . " and a.region=" . $_SESSION ['searchSaloon'] ['id_region'] : "a.region=" . $_SESSION ['searchSaloon'] ['id_region'];

	if (isset ( $_SESSION ['searchSaloon'] ['id_city'] ) && $_SESSION ['searchSaloon'] ['id_city'] > 0)

		$where = ($where != "") ? $where . " and a.city=" . $_SESSION ['searchSaloon'] ['id_city'] : "a.city=" . $_SESSION ['searchSaloon'] ['id_city'];

	if (isset ( $_SESSION ['searchSaloon'] ['saloonname'] ) && $_SESSION ['searchSaloon'] ['saloonname'] != "")

		$where = ($where != "") ? $where . " and a.NAME LIKE '%" . $_SESSION ['searchSaloon'] ['saloonname'] . "%'" : "a.name LIKE '%" . $_SESSION ['searchSaloon'] ['saloonname'] . "%'";



	$column = mysql_escape_string ( $_GET ['order'] );

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];

	if ((isset ( $_SESSION ['searchSaloon'] ['id_typeCode'] ) && $_SESSION ['searchSaloon'] ['id_typeCode'] > 0) or (isset ( $_SESSION ['searchSaloon'] ['id_markCode'] ) && $_SESSION ['searchSaloon'] ['id_markCode'] > 0)) {

		$all = 11;

		$query = "SELECT COUNT(distinct(a.id_author)) FROM AUTO_USERS a, AUTO_CAR_BASE b ";

		// $query = "SELECT COUNT(a.id_author) FROM AUTO_USERS a, AUTO_GROUP b ";





		$query .= " where a.locked=0 and a.lock_admin=0 and a.status='autosaloon'";

		$query .= " and a.id_author=b.ID_USER  and b.ACTIVE=1 ";

	} else {

		$all = 10;



		$query = "SELECT COUNT(distinct(a.id_author)) FROM AUTO_USERS a ";

		// $query = "SELECT COUNT(a.id_author) FROM AUTO_USERS a, AUTO_GROUP b ";





		$query .= " where a.locked=0 and a.lock_admin=0 and a.status='autosaloon'";

		// $query.=" and a.id_author=b.ID_USER ";





	}

	$query .= ($where != "") ? " and " . $where : "";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка тем111 форума' . $query;

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );



	if ($total == 0) {

		$html .= file_get_contents ( './templates/searchno.html' );

		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	// 	$table.="<table width=\"100%\"><tr><td widht=\"100\"></td><td align=\"right\">".$pages."</td></tr></table>";

	$table .= "";

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete





	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";

	//$table.="<td valign=\"top\" width=\"50\" align=\"center\" >фото</td>";





	$table .= "<td valign=\"top\" width=\"150\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'name' ) . "&ordertype=";

	$table .= SortOrder ( 'name' );

	$table .= " \">Автосалон";

	if ($_SESSION ['sort'] ['name'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['name'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	$table .= "<td valign=\"top\" width=\"120\" align=\"center\">";



	$table .= "Сервис";



	$table .= "</td>";



	$table .= "<td valign=\"top\" width=\"60\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'address' ) . "&ordertype=";

	$table .= SortOrder ( 'address' );

	$table .= " \">Адрес ";



	if ($_SESSION ['sort'] ['address'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['address'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	//$table.="Адреса</td>";





	$table .= "<td valign=\"top\" width=\"25\" align=\"center\">";

	$table .= "Всех авто</td>";



	if ($all == 11) {

		$query = "SELECT distinct a.id_author,a.name,c.REGION,d.CITY,a.address,a.about,count(b.ID) as TOTAL from AUTO_USERS a, AUTO_REGION c,AUTO_CITY d, AUTO_CAR_BASE b where ";

		// $query = "SELECT distinct a.id_author,a.name,c.REGION,d.CITY,a.address,a.about from AUTO_USERS a, AUTO_GROUP b, AUTO_REGION c,AUTO_CITY d where ";





		$query .= " b.ID_USER=a.id_author and b.ACTIVE=1 and ";

	} else {

		$query = "SELECT distinct a.id_author,a.name,c.REGION,d.CITY,a.address,a.about from AUTO_USERS a, AUTO_REGION c,AUTO_CITY d where ";

	}

	$query .= " c.ID=a.region ";

	$query .= " and d.ID=a.city ";

	$query .= " and a.locked=0 and a.lock_admin=0 and a.status='autosaloon'";



	$query .= (($where) != "") ? " and " . $where : "";

	$query .= " group by a.id_author,a.name,c.REGION,d.CITY,a.address,a.about ";

	// $query.= $_SESSION['searchForm']['sql'];

	if (isset ( $_GET ['order'] ) == 'address')

		$query .= " ORDER BY d.CITY " . mysql_escape_string ( $_GET ['ordertype'] );

	if ((isset ( $_GET ['order'] )) && (isset ( $_GET ['order'] ) != 'address'))

		$query .= " ORDER BY a." . mysql_escape_string ( $_GET ['order'] ) . " " . mysql_escape_string ( $_GET ['ordertype'] );

		//$query.=" group by a.id_author,a.name,c.REGION,d.CITY,a.address,a.about ";

	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;

	//echo $query;

	//    echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй123';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;



	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {

			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}



			$table .= "<tr class=$CssClass>";

			$table .= "<td align='center'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $autolist ['id_author'] . "><strong>" . $autolist ['name'] . "</strong></a>";

			$table .= "</td>";



			$table .= "<td class='phpmakerlist'>";

			$table .= nl2br($autolist ["about"]);

			$table .= "</td>";



			$table .= "<td valign=\"middle\">";

			$table .= "<strong>г. " . $autolist ['CITY'] . ", " . $autolist ["address"] . "</strong></br><strong>" . $autolist ["tel1"] . "</strong>"; //$name.$region.$city.$status;

			$table .= "</td>";



			$query1 = "SELECT COUNT(b.ID) FROM AUTO_CAR_BASE b ";

			$query1 .= " where b.ID_USER=" . $autolist ['id_author'] . " and b.ACTIVE=1";

			//$query.=" and a.id_author=b.ID_SALOON ";





			// echo($query);

			$res1 = mysql_query ( $query1 );

			if (! $res1) {

				$msg = 'Ошибка при получении списка тем111 форума';

				$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $err, true, '' );

			}

			$total1 = mysql_result ( $res1, 0, 0 );

			$table .= "<td align=\"center\" ><strong>" . $total1;

			$table .= "</strong></td>";

			$table .= "</tr>";

		}

		$table .= "</td></tr></table>";

	}

	$table .= "<table width=\"100%\"><tr><td widht=\"100\">Всего: <b>" . $total . "</b></td><td align=\"right\">" . $pages . "</td></tr></table>";



	$table .= "</td></tr></table>";



	return $table;

}



function showSearchAdv() {

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=showSearchAdv&del=1">Расширенный поиск</a><span>';

	if (abs ( $_GET ["del"] == 1 ))

		unset ( $_SESSION ['searchForm'] );

	$html = file_get_contents ( './templates/foundForm2.html' );

	$html = str_replace ( '{found}', showSearchFormAdv (), $html );

	$html = str_replace ( '{path}', $Gpath, $html );

	//$html.=showSearchForm();





	return $html;



}



function showSearchFormAdv() {



	$html = file_get_contents ( './templates/searchFormadv.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=searchView';

	$html = str_replace ( '{action}', $action, $html );



	if ((isset ( $_GET ['id_typeCode'] )) and (abs ( intval ( $_GET ['id_typeCode'] ) ) > 0)) {

		$_SESSION ['searchForm'] ['id_typeCode'] = abs ( intval ( $_GET ['id_typeCode'] ) );

	}



	if (! isset ( $_SESSION ['searchForm'] ['id_typeCode'] )) {

		$_SESSION ['searchForm'] ['id_typeCode'] = 1;

	}

	for($i = 1; $i < 9; $i ++) {

		if ($_SESSION ['searchForm'] ['id_typeCode'] == $i) {

			$html = str_replace ( '{select' . $i . '}', "class='navDiv3'", $html );

			$html = str_replace ( '{id_typeCode}', $i, $html );

		} else {

			$html = str_replace ( '{select' . $i . '}', "class='navDiv2'", $html );

		}



		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $_SESSION ['searchForm'] ['id_typeCode'].' ORDER BY TRADEMARK';

		$res = mysql_query ( $query );

		if (! $res) {



			die ();

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = @mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $_SESSION ['searchForm'] ['id_markCode']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );

	}

	if (isset ( $_SESSION ['searchForm'] ['id_markCode'] )) {

		$query = "SELECT * FROM AUTO_MODEL where TRADEMARK=" . $_SESSION ['searchForm'] ['id_markCode']. " ORDER BY MODEL";

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка моделй';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$model = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $modellist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($modellist ['ID'] == $_SESSION ['searchForm'] ['id_modelCode']) ? " selected" : "";

				$model .= "<option value='" . $modellist ['ID'] . "' " . $selwrk . " >" . $modellist ['MODEL'] . "</option>";

			}

		}

		$html = str_replace ( '{MODEL}', $model, $html );

	}



	$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] == $_SESSION ['searchForm'] ['id_region']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

		}

	}



	$html = str_replace ( '{REGION}', $region, $html );



	if (isset ( $_SESSION ['searchForm'] ['id_region'] )) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $_SESSION ['searchForm'] ['id_region'].' ORDER BY CITY';

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $_SESSION ['searchForm'] ['id_city']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['CITY'] . "</option>";

			}

		}

		$html = str_replace ( '{CITY}', $mark, $html );

	}



	$year1 = "";

	$year1 .= "<option value='0'>____</option>";

	for($i = date ( 'Y' ); $i > 1949; $i --) {

		$selwrk1 = ($i == $_SESSION ['searchForm'] ['year1']) ? " selected" : "";



		$year1 .= "<option value='" . $i . "' " . $selwrk1 . " >" . $i . "</option>";

	}

	$html = str_replace ( '{YEAR1}', $year1, $html );

	$year2 .= "<option value='0'>____</option>";

	if ($_SESSION ['searchForm'] ['year1'] > 0) {

		for($i = date ( 'Y' ); $i >= $_SESSION ['searchForm'] ['year1']; $i --) {

			$selwrk2 = ($i == $_SESSION ['searchForm'] ['year2']) ? " selected" : "";

			$year2 .= "<option value='" . $i . "' " . $selwrk2 . " >" . $i . "</option>";

		}



	}

	$html = str_replace ( '{YEAR2}', $year2, $html );



	$html = str_replace ( '{DATE_LIST' . $_SESSION ['searchForm'] ['date_list'] . '}', ' selected', $html );



	$html = str_replace ( '{PRICE1}', $PR = ($_SESSION ['searchForm'] ['price1'] != 0) ? $_SESSION ['searchForm'] ['price1'] : "", $html );

	$html = str_replace ( '{PRICE2}', $PR = ($_SESSION ['searchForm'] ['price2'] != 0) ? $_SESSION ['searchForm'] ['price2'] : "", $html );



	// выбираем цвет





	$query = "SELECT * FROM AUTO_COLOR order by COLOR";

	$res = mysql_query ( $query );

	if (! $res) {



		die ();

	}

	$color = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $colorlist = @mysql_fetch_array ( $res ) ) {

			$selwrk = ($colorlist ['ID'] == $_SESSION ['searchForm'] ['id_color']) ? " selected" : "";

			$color .= "<option value='" . $colorlist ['ID'] . "' " . $selwrk . " >" . $colorlist ['COLOR'] . "</option>";

		}

	}

	$html = str_replace ( '{COLOR}', $color, $html );



	$query = "SELECT * FROM AUTO_SOST";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "<option value='-1' >- любое -</option>";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$sost .= "<option value='" . $sostlist ['ID'] . "' >" . $sostlist ['SOST'] . "</option>";

		}

	}

	$html = str_replace ( '{SOSTOYANIE}', $sost, $html );



	$query = "SELECT * FROM AUTO_TYPE_DVIG ORDER BY TYPE_DVIG";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "<option value='-1' >- любой -</option>";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$sost .= "<option value='" . $sostlist ['ID'] . "' >" . $sostlist ['TYPE_DVIG'] . "</option>";

		}

	}

	$html = str_replace ( '{DVIG}', $sost, $html );



	$query = "SELECT * FROM AUTO_TYPE_KUZ ORDER BY TYPE_KUZ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "<option value='-1' >- любой -</option>";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$sost .= "<option value='" . $sostlist ['ID'] . "' >" . $sostlist ['TYPE_KUZ'] . "</option>";

		}

	}

	$html = str_replace ( '{KUZOV}', $sost, $html );

	if (isset ( $_SESSION ['searchForm'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableCar ( $_SESSION ['searchForm'] ['sql'], 'showSearch', 'ShowCar' ), $html );

		;



	} else {

		$html = str_replace ( '{FOUND}', "", $html );

		;

	}

	$_SESSION ['pageTitle'] = "Расширенный поиск";



	return $html;

}



function showSearch() {

	if (abs ( $_GET ["del"] == 1 ))

		unset ( $_SESSION ['searchForm'] );

	$html = file_get_contents ( './templates/foundForm.html' );

	$html = str_replace ( '{found}', showSearchForm (), $html );
	$html = str_replace ( '{path}', '', $html );

	//$html.=showSearchForm();





	return $html;



}



function showSearchForm() {



	$html = file_get_contents ( './templates/searchForm.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=searchView';

	$html = str_replace ( '{action}', $action, $html );



	if ($_SESSION ['searchForm'] ['new'] == 1) {

		$html = str_replace ( '{CHECK_NEW}', " checked ", $html );

	}



	if ($_SESSION ['searchForm'] ['foto'] == 1) {

		$html = str_replace ( '{CHECK_PHOTO}', " checked ", $html );

	}



	if (! isset ( $_SESSION ['searchForm'] ['id_typeCode'] )) {

		$_SESSION ['searchForm'] ['id_typeCode'] = 1;

	}

	for($i = 1; $i < 9; $i ++) {

		if ($_SESSION ['searchForm'] ['id_typeCode'] == $i) {

			$html = str_replace ( '{select' . $i . '}', "class='navDiv3'", $html );

			$html = str_replace ( '{id_typeCode}', $i, $html );

		} else {

			$html = str_replace ( '{select' . $i . '}', "class='navDiv2'", $html );

		}



		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $_SESSION ['searchForm'] ['id_typeCode'].' ORDER BY TRADEMARK';

		$res = mysql_query ( $query );

		if (! $res) {

			die ();

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = @mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $_SESSION ['searchForm'] ['id_markCode']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );

	}

	if (isset ( $_SESSION ['searchForm'] ['id_markCode'] )) {

		$query = "SELECT * FROM AUTO_MODEL where TRADEMARK=" . $_SESSION ['searchForm'] ['id_markCode'].' ORDER BY MODEL';

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка моделй';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$model = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $modellist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($modellist ['ID'] == $_SESSION ['searchForm'] ['id_modelCode']) ? " selected" : "";

				$model .= "<option value='" . $modellist ['ID'] . "' " . $selwrk . " >" . $modellist ['MODEL'] . "</option>";

			}

		}

		$html = str_replace ( '{MODEL}', $model, $html );

	}



	$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] == $_SESSION ['searchForm'] ['id_region']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

		}

	}



	$html = str_replace ( '{REGION}', $region, $html );



	if (isset ( $_SESSION ['searchForm'] ['id_region'] )) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $_SESSION ['searchForm'] ['id_region'].' ORDER BY CITY';

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $_SESSION ['searchForm'] ['id_city']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['CITY'] . "</option>";

			}

		}

		$html = str_replace ( '{CITY}', $mark, $html );

	}



	$year1 = "";

	$year1 .= "<option value='0'>____</option>";

	for($i = date ( 'Y' ); $i > 1949; $i --) {

		$selwrk1 = ($i == $_SESSION ['searchForm'] ['year1']) ? " selected" : "";



		$year1 .= "<option value='" . $i . "' " . $selwrk1 . " >" . $i . "</option>";

	}

	$html = str_replace ( '{YEAR1}', $year1, $html );

	//$html.="</br>".$_SESSION['searchForm']['year1']."</br>";

	$year2 .= "<option value='0'>____</option>";

	if ($_SESSION ['searchForm'] ['year1'] > 0) {

		for($i = date ( 'Y' ); $i >= $_SESSION ['searchForm'] ['year1']; $i --) {

			$selwrk2 = ($i == $_SESSION ['searchForm'] ['year2']) ? " selected" : "";

			$year2 .= "<option value='" . $i . "' " . $selwrk2 . " >" . $i . "</option>";

		}



	}

	$query = "SELECT COUNT(*) FROM " . TABLE_AUTO . " a";

	$query .= ", AUTO_USERS b where a.ID_USER=b.id_author and b.locked=0 and b.lock_admin=0 and a.ACTIVE=1";

	//$query.=($where!="")? " and ".$where:"";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );

	$html = str_replace ( '{TOTAL}', $total, $html );

	$html = str_replace ( '{YEAR2}', $year2, $html );



	if ($_SESSION ['searchForm'] ['date_list'] > 0) {

		$html = str_replace ( '{DATE_LIST' . $_SESSION ['searchForm'] ['date_list'] . '}', ' selected ', $html );

	} else {

		$html = str_replace ( '{DATE_LIST}', ' selected ', $html );

	}

	$html = str_replace ( '{PRICE1}', $PR = ($_SESSION ['searchForm'] ['price1'] != 0) ? $_SESSION ['searchForm'] ['price1'] : "", $html );

	$html = str_replace ( '{PRICE2}', $PR = ($_SESSION ['searchForm'] ['price2'] != 0) ? $_SESSION ['searchForm'] ['price2'] : "", $html );



	// если фильтр установлен выбираем записи

	$html .= "<br>";



	if (isset ( $_SESSION ['searchForm'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableCar ( $_SESSION ['searchForm'] ['sql'], 'showSearch', 'ShowCar' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', file_get_contents ( './templates/defaultForm.html' ), $html );

	}

	return $html;

}



function ShowTableCar($where, $action, $ShowCarInfo) {

//туда-сюда

	$column = $_GET ['order'];

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];

	//echo $where;

	$query = "SELECT COUNT(*) FROM " . TABLE_AUTO . " a";
if ($_REQUEST['action'] !== "myCar") {
	$query .= ", AUTO_USERS b where a.ID_USER=b.id_author and b.locked=0 and b.lock_admin=0 and a.ACTIVE=1 ";
} else {
	$query .= ", AUTO_USERS b where a.ID_USER=b.id_author and b.locked=0 and b.lock_admin=0 and a.ACTIVE=1 ".(($_SESSION['user']['status'] !== 'admin') ? "and a.ID_USER='".$_SESSION['user']['id_author']."' " : '');
}

	$query .= ($where != "") ? " and " . $where : "";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );



	if ($total == 0) {

		if ($_REQUEST['action'] !== "myCar") {
		$html .= file_get_contents ( './templates/searchno.html' );
		} else {
		$html .= file_get_contents ( './templates/searchnomyCar.html' );
		}
		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	$table .= "<table width=\"100%\"><tr><td>";

	if ($_REQUEST['action'] !== "myCar") $table .= "</td></tr></table>";
		else $table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_add.gif); width: 142px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=addCar\"></a>" . "</td></tr></table>";

	if ($_REQUEST['action'] === "myCar") $table .= '<form name="chform" method="post">';

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete





	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";



	if ($_REQUEST['action'] === "myCar") {
	$table .= "<td valign=\"top\" width=\"30\" align=\"center\">";

	$table .= "<input type=\"checkbox\" title=\"Отметить все\" onclick=\"checkAll(this);\" name=\"chAll\"/></td>";
	}

	if ($_SESSION['user']['status'] === 'admin' && $_REQUEST['action'] === 'myCar') {

	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'id' ) . "&ordertype=";

	$table .= SortOrder ( 'id' );

	$table .= " \">№";

	if ($_SESSION ['sort'] ['id'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['id'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";
	}

	$table .= "<td valign=\"middle\" width=\"100\" align=\"center\">фото</td>";



	$table .= "<td valign=\"middle\" width=\"280\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'type' ) . "&ordertype=";

	$table .= SortOrder ( 'type' );

	$table .= " \">автомобиль";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

	if ($_SESSION['user']['status'] === 'admin' && $_REQUEST['action'] === 'myCar' ) {

	$table .= "<td valign=\"middle\" width=\"180\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'name' ) . "&ordertype=";

	$table .= SortOrder ( 'name' );

	$table .= " \">продавец";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	}

	$table .= "<td valign=\"middle\" width=\"40\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'year_vyp' ) . "&ordertype=";

	$table .= SortOrder ( 'year_vyp' );

	$table .= " \">год";

	if ($_SESSION ['sort'] ['year_vyp'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['year_vyp'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	$table .= "<td valign=\"middle\" width=\"60\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'probeg' ) . "&ordertype=";

	$table .= SortOrder ( 'probeg' );

	$table .= " \">пробег, тыс. км. ";

	if ($_SESSION ['sort'] ['probeg'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['probeg'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'price' ) . "&ordertype=";

	$table .= SortOrder ( 'price' );

	$table .= " \">цена, руб.";

	if ($_SESSION ['sort'] ['price'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['price'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

	if ($_REQUEST['action'] !== "myCar") {

	$table .= "<td valign=\"middle\" width=\"100\" align=\"center\">";

	$table .= "продавец</td>";




	$table .= "<td valign=\"middle\" width=\"20\" align=\"center\">";

	$table .= "выбор";

	$table .= "</td></tr>";

} else {
	if ($_SESSION['user']['status'] !== 'user') {
		$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";
		$table .= "спец. предложение</td>";
	}
}

	$query = "SELECT a.ID, a.NEW, a.CAR_TYPE, a.DVEREY,a.AKPP, a.POWER, a.V_DVIG, j.CITY, f.MODEL,i.TRADEMARK,r.REGION, b.CAR_TYPE as TYPE,a.YEAR_VYP,a.PROBEG,a.PRICE,a.TYPE_KUZ, a.TYPE_DVIG, a.CAR_MARK,a.PHOTO_1,a.PREDL as SPEC,a.ID_USER,UNIX_TIMESTAMP(a.DATE_VVOD) as DATE_VVOD, c.* FROM  " . TABLE_AUTO . " a, AUTO_CAR_TYPE b, AUTO_USERS c, AUTO_MODEL f,AUTO_TRADEMARK i,AUTO_CITY j,AUTO_REGION r where ";



	$query .= "b.ID=a.CAR_TYPE ";


	$query .= " and f.ID=a.CAR_MODEL";

	$query .= " and i.ID=a.CAR_MARK";

	$query .= " and a.CITY=j.ID";

	$query .= " and a.REGION=r.ID";

	if ($_REQUEST['action'] === "myCar" && ($_SESSION['user']['status'] !== 'admin'))
		$query .= " and a.ID_USER='".$_SESSION['user']['id_author']."'";


	//$query.= " and c.CITY=k.ID";

	$query .= " and (a.ID_USER=c.id_author and c.locked=0 and c.lock_admin=0) and a.ACTIVE=1";

	$query .= (($where) != "") ? " and " . $where : "";

	// $query.= $_SESSION['searchForm']['sql'];

	if (isset ( $_GET ['order'] ))

		$query .= " ORDER BY " . $_GET ['order'] . " " . $_GET ['ordertype'];

	else

		$query .= " ORDER BY a.DATE_VVOD desc";

	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;



	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;

	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {

			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}

			$photo = (isset ( $autolist ['PHOTO_1'] )) ? $autolist ['PHOTO_1'] : "";

			if ($photo == "") {

				$img = "<img src=\"photo/none" . $autolist ['CAR_TYPE'] . ".jpg\" border=0  alt=\"Подробнее\"/>";

			} else {

				$img = "<img src=\"show_image.php?filename=photo/" . $photo . "&width=75\" border=0  alt=\"Подробнее\"/>";

			}



			$table .= "<tr class=$CssClass>";




			if ($_REQUEST['action'] === "myCar") {
			//
			$table .= '<td align="center"><input type="checkbox" value="'. $autolist ['ID'] . '" name="ch[]"/></a>';

			$table .= "</td>";
			//
			}

			if ($_REQUEST['action'] === "myCar" && $_SESSION['user']['status'] === 'admin') {
			//
			$table .= '<td align="center">'. $autolist ['ID'];

			$table .= "</td>";
			//
			}

			$table .= "<td align='center'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=$ShowCarInfo&id=" . $autolist ['ID'] . ">$img</a>";

			$table .= "</td>";



			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=$ShowCarInfo&id=" . $autolist ['ID'] . "><b>" . $autolist ['TRADEMARK'] . " " . $autolist ['MODEL'] . "</b>, ";



			if ($autolist ['TYPE_KUZ'] > 0) {



				$query2 = "SELECT * FROM AUTO_TYPE_KUZ where ID=" . $autolist ['TYPE_KUZ'];

				$res2 = mysql_query ( $query2 );

				if (! $res2) {

					$msg = 'Ошибка при получении списка марок';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}



				if (mysql_num_rows ( $res2 ) > 0) {

					while ( $sostlist = mysql_fetch_array ( $res2 ) ) {



						$table .= $sostlist ['TYPE_KUZ'] . ", ";

					}

				}



			}



			if ($autolist ['DVEREY'] > 0)

				$table .= $autolist ['DVEREY'] . "-двер., ";



			if ($autolist ['TYPE_DVIG'] > 0)



			{



				$query2 = "SELECT * FROM AUTO_TYPE_DVIG where ID=" . $autolist ['TYPE_DVIG'];

				$res2 = mysql_query ( $query2 );

				if (! $res2) {

					$msg = 'Ошибка при получении списка марок';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}



				if (mysql_num_rows ( $res2 ) > 0) {

					while ( $sostlist = mysql_fetch_array ( $res2 ) ) {



						$table .= $sostlist ['TYPE_DVIG'] . ", ";

					}

				}



			}

			if ($autolist ['V_DVIG'] > 0)

				$table .= $autolist ['V_DVIG'] . " л., ";

			if ($autolist ['POWER'] > 0)

				$table .= $autolist ['POWER'] . " л.c. ";

			$table .= "</a></br>" . date ( "d.m.Y", $autolist ['DATE_VVOD'] );

			$table .= "</td>";

			if ($_SESSION['user']['status'] === 'admin' && $_REQUEST['action'] === 'myCar') {

			if ($autolist ['status'] === "autosaloon" || $autolist ['status'] === "admin") {

				$table .= "<td valign=\"center\" align=\"center\">";

				$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=chinfo&id=" . $autolist ['id_author'] . "><strong>" . $autolist ["name"] . "</strong></a></br>" . $CITY_A; //$name.$region.$city.$status;

				$table .= "</td>";



			} else {

				$table .= "<td valign=\"center\" align=\"center\">";

				$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=chinfo&id=" . $autolist ['id_author'] . "><strong>" . $autolist ["name"] . "</strong></a></br>" . $CITY_A; //$name.$region.$city.$status;

				$table .= "</td>";

			}

			}

			$table .= "<td align=\"center\">" . $autolist ['YEAR_VYP'];

			$table .= "</td>";

			$probeg = $autolist ['PROBEG'];
			if (!$autolist ['NEW']) {
			if ($autolist ['PROBEG'])

				$probeg = $autolist ['PROBEG'];
			else
				$probeg = "";
			} else $probeg = "новый";
			$table .= "<td align=\"center\">" . $probeg;

			$table .= "</td>";



			$table .= "<td align=\"center\">" . round ( $autolist ['PRICE'] );

			$table .= "</td>";

			if ($_REQUEST['action'] !== "myCar") {

			$CITY_A = $autolist ['REGION'] . ", г. " . $autolist ['CITY'];

			if ($autolist ['status'] == "autosaloon") {

				$table .= "<td valign=\"top\">";

				$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $autolist ['id_author'] . "><strong>" . $autolist ["name"] . "</strong></a></br>" . $CITY_A . "</br><strong>" . $autolist ["tel1"] . "</strong>"; //$name.$region.$city.$status;

				$table .= "</td>";



			} else {

				$table .= "<td valign=\"top\">";

				$table .= $CITY_A . "</br><a href=" . $_SERVER ['PHP_SELF'] . "?action=$ShowCarInfo&id=" . $autolist ['ID'] . "><strong></strong></a>"; //$name.$region.$city.$status;

				$table .= "</td>";

			}



			$table .= "<td align=\"center\">";

			$table .= "<span class=\"notebook\"><img src=\"img/basket_add.gif\" width=\"19\" hieght=\"17\" border=\"0\"

	id=\"notebook_" . $autolist ['ID'] . "\" name=\"" . $autolist ['ID'] . "\" title=\"Записать в блокнот\">

	</span>";

			$table .= "</td>";

			} else {
			if ($_SESSION['user']['status'] !== 'user') {
				$table .= '<td align="center">'.($autolist ['SPEC'] ? 'Да' : 'Нет');
				$table .= "</td>";
			}
			}

			$table .= "</tr>";



		}

		$table .= "</td></tr></table>";

	}

	if ($_REQUEST['action'] === "myCar") {

if ($_SESSION['user']['status'] !== 'user') {
$table .= "<table width=\"100%\"><tr><td widht=\"100\"><table><tr><td>С отмеченным:
<select id=\"mode\" name=\"mode\">
<option value=\"2\">Добавить в предложения</option>
<option value=\"3\">Убрать из предложений</option>
<option value=\"1\">Удалить</option>
</select></td><td><a href=\"#\" style=\"background-image:url(img/btn_apply.gif); width: 84px; height: 21px;\"  onclick=\"this.blur(); javascript: document.searchForm.submit();\" class=\"button_apply\"></a></td></tr></table></form></td><td align=\"right\">" . $pages . "</td></tr></table>";
} else {
$table .= "<table width=\"100%\"><tr><td widht=\"100\"><table><tr><td>С отмеченным:
<select id=\"mode\" name=\"mode\">
<option value=\"1\">Удалить</option>
</select></td><td><a href=\"#\" style=\"background-image:url(img/btn_apply.gif); width: 84px; height: 21px;\"  onclick=\"this.blur(); javascript: document.searchForm.submit();\" class=\"button_apply\"></a></td></tr></table></form></td><td align=\"right\">" . $pages . "</td></tr></table>";

}
	} else {
	$table .= "<table width=\"100%\"><tr><td widht=\"100\">Всего: <b>" . $total . "</b></td><td align=\"right\">" . $pages . "</td></tr></table>";
	}


	$table .= "</td></tr></table>";



	return $table;

}



function getContact() {

	$_SESSION ['pageTitle'] = "Контакты";

	$info = file_get_contents ( './templates/contact.html' );



	return $info;

}

function sendBack() {

		$_SESSION ['pageTitle'] = "Контакты";

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=sendBack">Контакты</a></span>';

	if (isset ( $_SESSION ['captcha_keystring'] ))

		unset ( $_SESSION ['captcha_keystring'] );

	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['sendForm'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['sendForm'] ['error'], $info );

		$html = $html . $info . "\n";

		$name = htmlspecialchars ( $_SESSION ['sendForm'] ['name'] );

		$email = htmlspecialchars ( $_SESSION ['sendForm'] ['email'] );

		$vopros = htmlspecialchars ( $_SESSION ['sendForm'] ['vopros'] );

		$tel = htmlspecialchars ( $_SESSION ['sendForm'] ['tel'] );

		unset ( $_SESSION ['sendForm'] );

	} else {

		$name = '';

		$email = '';

		$vopros = '';

		$tel = '';

	}



	// Считываем в переменную файл шаблона, содержащего

	// форму для добавления нового пользователя

	$tpl = file_get_contents ( './templates/back.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=Back';

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{name}', $name, $tpl );

	$tpl = str_replace ( '{email}', $email, $tpl );

	$tpl = str_replace ( '{vopros}', $vopros, $tpl );

	$tpl = str_replace ( '{tel}', $tel, $tpl );



	$kcaptcha = './kcaptcha/kc.php?' . session_name () . '=' . session_id ();

	$tpl = str_replace ( '{kcaptcha}', $kcaptcha, $tpl );

	$tpl = str_replace ( '{keystring}', '', $tpl );



	$html = $html . $tpl;

	$html = str_replace ( '{path}', $Gpath, $html );

	$nav = "";

	$nav .= ( ($_REQUEST['action']==='sendBack') ? '<b>' : '<i><a href=?action=sendBack>'  ).'Контакты'.( ($_REQUEST['action']==='sendBack') ? '<div></div></b>' : '</a></i>').( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='52')) ? '<b>' : '<i><a href=?action=Reklama&id_blog=52>'  ).'Предложение для автосалонов'.( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='52')) ? '<div></div></b>' : '</a></i>'  ).( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='55')) ? '<b>' : '<i><a href=?action=Reklama&id_blog=55>'  ).'Реклама на сайте'.( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='55')) ? '<div></div></b>' : '</a></i>'  ).( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='56')) ? '<b>' : '<i><a href=?action=Reklama&id_blog=56>'  ).'Правила и условия'.( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='56')) ? '<div></div></b>' : '</a></i>'  ).( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='53')) ? '<b>' : '<i><a href=?action=Reklama&id_blog=53>'  ).'Спецразмещение'.( (($_REQUEST['action']==='Reklama') && ($_REQUEST['id_blog']==='53')) ? '<div></div></b>' : '</a></i>'  );

	$html = str_replace ( '{nav}', $nav, $html );

	return $html;



}



function getBack() {



	if (! isset ( $_POST ['name'] ) or ! isset ( $_POST ['vopros'] ) or ! isset ( $_POST ['email'] ) or // !isset( $_POST['timezone'] ) or

! isset ( $_POST ['keystring'] )) {



		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=sendBack' );

		die ();

	} else {



		$name = substr ( $_POST ['name'], 0, 60 );

		$vopros = substr ( $_POST ['vopros'], 0, 500 );

		$tel = substr ( $_POST ['tel'], 0, 60 );

		$email = substr ( $_POST ['email'], 0, 60 );

		//$address = $_POST['signature'], 0, 500 );

		$keystring = substr ( $_POST ['keystring'], 0, 6 );



		// Обрезаем лишние пробелы

		$name = trim ( $name );

		$tel = trim ( $tel );

		//  $password  = trim( $password );

		//  $confirm   = trim( $confirm );

		$email = trim ( $email );

		$keystring = trim ( $keystring );



		// Проверяем, заполнены ли обязательные поля

		$error = '';

		if (empty ( $name ))

			$error = $error . '<li>не заполнено поле "ФИО"</li>' . "\n";

		if (empty ( $vopros ))

			$error = $error . '<li>не заполнено поле "Сообщение"</li>' . "\n";


		if (empty ( $email ))

			$error = $error . '<li>не заполнено поле "Адрес e-mail"</li>' . "\n";

			// if ( empty( $tel1 ) ) $error = $error.'<li>не заполнено поле "Телефон 1"</li>'."\n";





		if (empty ( $keystring ))

			$error = $error . '<li>не заполнено поле "Код"</li>' . "\n";

			// Проверяем, не слишком ли короткий пароль

		if (! empty ( $keystring )) {

			// Проверяем поле "код" на недопустимые символы

			if (! ereg ( "[23456789abcdeghkmnpqsuvxyz]+", $keystring ))

				$error = $error . '<li>поле "Код" содержит недопустимые символы</li>' . "\n";

				// Проверяем, совпадает ли код с картинки

			if (! isset ( $_SESSION ['captcha_keystring'] ) or $_SESSION ['captcha_keystring'] != $keystring)

				$error = $error . '<li>не совпадает код с картинки</li>' . "\n";

		}

		unset ( $_SESSION ['captcha_keystring'] );



		// Проверяем поля формы на недопустимые символы
/*
		if (! empty ( $name ) and ! preg_match ( "#^[- _0-9a-zА-Яа-я]+$#i", $name ))

			$error = $error . '<li>поле "Имя" содержит недопустимые символы</li>' . "\n";

		if (! empty ( $vopros ) and ! preg_match ( "#^[- _0-9a-zА-Яа-я]+$#i", $vopros ))

			$error = $error . '<li>поле "Сообщение" содержит недопустимые символы</li>' . "\n";
*/
			// Проверяем корректность e-mail

		if (! empty ( $email ) and ! preg_match ( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ))

			$error = $error . '<li>поле "E-mail" должно соответствовать формату somebody@somewhere.ru</li>' . "\n";



		if (! empty ( $error )) {

			$_SESSION ['sendForm'] = array ();

			$_SESSION ['sendForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

			$_SESSION ['sendForm'] ['name'] = RemoveXSS ( $name );

			$_SESSION ['sendForm'] ['email'] = RemoveXSS ( $email );

			$_SESSION ['sendForm'] ['vopros'] = RemoveXSS ( $vopros );

			$_SESSION ['sendForm'] ['tel'] = RemoveXSS ( $tel );

			//$_SESSION['sendForm']['tel2'] = $tel2;





			header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=sendBack' );

			die ();

		}



		if (getenv ( 'HTTP_X_FORWARDED_FOR' )) {

			$ip = getenv ( 'HTTP_X_FORWARDED_FOR' );

		}

		{

			$ip = getenv ( 'REMOTE_ADDR' );

		}

		$host = gethostbyaddr ( "$ip" );

		if (! $ip) {

			$ip = "unknown";

		}

		if (! $host) {

			$host = "unknown";

		}

		$text_message = "";



		$text_message = $text_message . "Контактное лицо:" . $name . "\n";

		$text_message = $text_message . "Телефон:" . $tel . "\n";

		$text_message = $text_message . "E-mail:" . $email . "\n";

		$text_message = $text_message . "Вопрос:" . $vopros . "\n";

		$text_message = $text_message . "IP:" . $ip . "\n";

		$text_message = $text_message . "Компьютер:" . $host . "\n";



		if (mail ( "info@vash_domen.ru", 'Вопрос', $text_message )) {

			//$error1 = '<li>Ваше сообщение отправлено!</li>' . "\n";

		$msg = '<b><center><br><p>Ваше сообщение отправлено</p><br></center</b>';

			unset ( $_SESSION ['sendForm'] );

		} else

			//$error1 = '<li><color=red>Ваше сообщение не отправлено!</color></li>' . "\n";



		//$_SESSION ['sendForm'] ['error'] = '<p class="errorMsg">Отправка сообщения:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error1 . '</ul>' . "\n";
		$msg = '<b><center><br><p>При отправки сообщения произошла ошибка</p><br></center</b>';

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $msg, $html );

	return $html;
		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=sendBack' );

		//return $info;





	//}

	}

	//return $info;





}

//смена пароля
function getChangePasswd() {

	$_SESSION ['pageTitle'] = "Смена пароля";



	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['sendForm'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['sendForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['sendForm'] );

	} else {

		$pass1 = '';

		$pass2 = '';

	}



	// Считываем в переменную файл шаблона, содержащего

	// форму для добавления нового пользователя

	$tpl = file_get_contents ( './templates/chpass.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=chpassAct';

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{login}', $_SESSION['user']['email'], $tpl );


	$html = $html . $tpl;

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=chpass">Смена пароля</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;



}


//смена пароля
function changePasswd() {



	if ((! isset ( $_POST ['pass1'] ) or ! isset ( $_POST ['pass2'] ) )) {



		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chpass' );

		die ();

	} else {



		$pass1 = substr ( $_POST ['pass1'], 0, 20 );

		$pass2 = substr ( $_POST ['pass2'], 0, 20 );



		// Обрезаем лишние пробелы

		$pass1 = trim ( $pass1 );
		$pass2 = trim ( $pass2 );

		//  $password  = trim( $password );

		//  $confirm   = trim( $confirm );






		// Проверяем, заполнены ли обязательные поля

		$error = '';

		if (empty ( $pass1 ))

			$error = $error . '<li>не заполнено поле "Новый пароль"</li>' . "\n";

		if (empty ( $pass2 ))

			$error = $error . '<li>не заполнено поле "Подтверждение пароля"</li>' . "\n";

		if ($pass1 !== $pass2)

			$error = $error . '<li>пароли не совпадают - корректно подтвердите новый пароль</li>' . "\n";








		// Проверяем поля формы на недопустимые символы

		if (! empty ( $pass1 ) and ! preg_match ( "#^[-_0-9a-zA-Z]+$#i", $pass1 ))

			$error = $error . '<li>поле "Новый пароль" содержит недопустимые символы</li>' . "\n";

		if (! empty ( $pass2 ) and ! preg_match ( "#^[-_0-9a-zA-Z]+$#i", $pass2 ))

			$error = $error . '<li>поле "Подтверждение пароля" содержит недопустимые символы</li>' . "\n";

		if (! empty ( $error )) {

			$_SESSION ['sendForm'] = array ();

			$_SESSION ['sendForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

			$_SESSION ['sendForm'] ['pass1'] = RemoveXSS ( $pass1 );

			$_SESSION ['sendForm'] ['pass2'] = RemoveXSS ( $pass2 );

			header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chpass' );

			die ();

		}

//666

	$query = "UPDATE AUTO_USERS SET passw='".md5($pass1)."' WHERE id_author='" .  $_SESSION['user']['id_author'] . "'";

	$res = mysql_query ( $query );

		if ($res) {

			$error1 = '<br/>

      <center><p><strong>Пароль был успешно изменён</strong></p></center>

    <br/>' . "\n";



		} else

			$error1 = '<br/>

      <center><p><strong>Пароль не был изменён</strong></p></center>

    <br/>' . "\n";



		//$_SESSION ['sendForm'] ['error'] = '<ul class="errorMsg">' . "\n" . $error1 . '</ul>' . "\n";

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $error1, $html );

	unset ( $_SESSION ['sendForm'] );

	return $html;

	}

}
/////////////

function getaddUser() {

	$_SESSION ['pageTitle']="Добавление пользователя";



	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['addNewSaloonForm'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['addNewSaloonForm'] ['error'], $info );

		$html = $html . $info . "\n";

		$name = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['name'] );

		$email = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['email'] );

		$web = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['web'] );

		$about = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['about'] );

		$region = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['region'] );

		$city = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['city'] );

		$address = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['address'] );

		$tel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['tel1'] );

		$tel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['tel2'] );

		$stel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['stel1'] );

		$stel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['stel2'] );

		$dotel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['dotel1'] );

		$dotel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['dotel2'] );

		$descr = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['descr'] );



		//  $timezone  = $_SESSION['addNewUserForm']['timezone'];

		// $icq       = htmlspecialchars( $_SESSION['addNewUserForm']['icq'] );

		// $url       = htmlspecialchars( $_SESSION['addNewUserForm']['url'] );

		// $about     = htmlspecialchars( $_SESSION['addNewUserForm']['about'] );

		//  $signature = htmlspecialchars( $_SESSION['addNewUserForm']['signature'] );

		unset ( $_SESSION ['addNewSaloonForm'] );

	} else {

		$name = '';

		//$email = '';

		$tel2 = '';

		$tel1 = '';

		$stel1 = '';

		$stel2 = '';

		$dotel1 = '';

		$dotel2 = '';

		$web = '';

		$descr = '';

		$about = '';

		//$region = 0;

		//$city =0;

		$address = '';

		// $timezone  = 0;

	//  $icq       = '';

	//  $url       = '';

	//  $about     = '';

	//  $signature = '';

	}



	// Считываем в переменную файл шаблона, содержащего

	// форму для добавления нового пользователя



	$action = $_SERVER ['PHP_SELF'] . '?action=addUserAdm';


	$tpl = file_get_contents ( './templates/addUser.html' );





	//photo

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{name}', $name, $tpl );

	$tpl = str_replace ( '{email}', $email, $tpl );

	$tpl = str_replace ( '{tel1}', $tel1, $tpl );

	$tpl = str_replace ( '{tel2}', $tel2, $tpl );

	$tpl = str_replace ( '{stel1}', $nach1, $tpl );

	$tpl = str_replace ( '{stel2}', $nach2, $tpl );

	$tpl = str_replace ( '{dotel1}', $end1, $tpl );

	$tpl = str_replace ( '{dotel2}', $end2, $tpl );



	$tpl = str_replace ( '{web}', $url, $tpl );

	$tpl = str_replace ( '{address}', $address, $tpl );

	$tpl = str_replace ( '{about}', $about, $tpl );

	$tpl = str_replace ( '{descr}', $descr, $tpl );

	$reg = $region;

	$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}
//тут..
	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] === $reg) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

		}

	}

	$tpl = str_replace ( '{REGION}', $region, $tpl );



	if (isset ( $region )) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION='" . $reg."'";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] === $city) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['CITY'] . "</option>";

			}

		}

		$tpl = str_replace ( '{CITY}', $mark, $tpl );

	}







	$html = $html . $tpl;

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=addUser">Добавление пользователя</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}



function addUser() {



	// Если не переданы данные формы - значит функция была вызвана по ошибке

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$name = substr ( $_POST ['name'], 0, 60 );

	//$password = substr ( $_POST ['password'], 0, 30 );

	//$confirm = substr ( $_POST ['confirm'], 0, 30 );

	$password = substr ( $_POST ['password'], 0, 30 );

	$confirm = substr ( $_POST ['confirm'], 0, 30 );

	$email = substr ( $_POST ['email'], 0, 60 );

	//$signature = substr( $_POST['signature'], 0, 500 );


	$tel1 = substr ( $_POST ['tel1'], 0, 25 );

	$tel2 = substr ( $_POST ['tel2'], 0, 25 );

	$stel1 = substr ( $_POST ['stel1'], 0, 2 );

	$stel2 = substr ( $_POST ['stel2'], 0, 2 );

	$dotel1 = substr ( $_POST ['dotel1'], 0, 2 );

	$dotel2 = substr ( $_POST ['dotel2'], 0, 2 );

	$web = substr ( $_POST ['web'], 0, 60 );

	$descr = substr ( $_POST ['descr'], 0, 3000 );

	$about = substr ( $_POST ['about'], 0, 500 );



	$address = substr ( $_POST ['address'], 0, 128 );

	$city = $_POST ['cityCode'];

	$region = $_POST ['id_region'];

	// Обрезаем лишние пробелы

	$name = trim ( $name );

	$about = trim ( $about );

	$about = strip_tags ( $about );

	//$password = trim ( $password );

	//$confirm = trim ( $confirm );

	$email = trim ( $email );



	$web = trim ( $web );

	$descr = trim ( $descr );



	$name = RemoveXSS ( $name );

	$about = RemoveXSS ( $about );

	//$about = RemoveXSS ( $about );

	//$password = RemoveXSS ( $password );

	//$confirm = RemoveXSS ( $confirm );

	$email = RemoveXSS ( $email );



	$web = RemoveXSS ( $web );

	$descr = RemoveXSS ( $descr );


	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if ($region <= 0)

		$error = $error . '<li>не выбран регион</li>' . "\n";

	if ($city <= 0)

		$error = $error . '<li>не выбран город</li>' . "\n";

	if (empty ( $email ))

		$error = $error . '<li>не заполнено поле "Ваш e-mail"</li>' . "\n";

	if (empty ( $name )) {

		$error = $error . '<li>не заполнено поле "Ваше имя"</li>' . "\n";

	}

	if (empty ( $password ))

		$error = $error . '<li>не заполнено поле "Пароль"</li>' . "\n";

	if (empty ( $confirm ))

		$error = $error . '<li>не заполнено поле "Повторите пароль"</li>' . "\n";

	if (! empty ( $password ) and strlen ( $password ) < MIN_PASSWORD_LENGTH)

		$error = $error . '<li>длина пароля должна быть не меньше ' . MIN_PASSWORD_LENGTH . ' символов</li>' . "\n";

		// Проверяем, совпадают ли пароли

	if (! empty ( $password ) and ! empty ( $confirm ) and $password != $confirm)

		$error = $error . '<li>не совпадают пароли</li>' . "\n";

	if (! empty ( $name ) and ! preg_match ( "#^[- _0-9a-zА-Яа-я]+$#i", $name ))

		$error = $error . '<li>поле "Ваше имя" содержит недопустимые символы</li>' . "\n";

	if (! empty ( $password ) and ! preg_match ( "#^[-_0-9a-z]+$#i", $password ))

		$error = $error . '<li>поле "Пароль" содержит недопустимые символы</li>' . "\n";

	if (! empty ( $confirm ) and ! preg_match ( "#^[-_0-9a-z]+$#i", $confirm ))

		$error = $error . '<li>поле "Повторите пароль" содержит недопустимые символы</li>' . "\n";

	if (empty ( $tel1 ))

		$error = $error . '<li>не заполнено поле "Телефон"</li>' . "\n";

	$query = "SELECT * FROM " . TABLE_USERS . "

		    WHERE email LIKE '" . mysql_real_escape_string ( $email ) . "'";

	$res = mysql_query ( $query );



	if (! $res) {

		$msg = 'Ошибка при регистрации нового пользователя';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0)

		$error = $error . '<li>Такой адрес "' . $email . '" уже зарегистрирован</li>' . "\n";






	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {

		$_SESSION ['addNewSaloonForm'] = array ();

		$_SESSION ['addNewSaloonForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['addNewSaloonForm'] ['name'] = $name;

		$_SESSION ['addNewSaloonForm'] ['email'] = $email;

		$_SESSION ['addNewSaloonForm'] ['tel1'] = $tel1;

		$_SESSION ['addNewSaloonForm'] ['tel2'] = $tel2;

		$_SESSION ['addNewSaloonForm'] ['stel1'] = $stel1;

		$_SESSION ['addNewSaloonForm'] ['stel2'] = $stel2;

		$_SESSION ['addNewSaloonForm'] ['dotel1'] = $dotel1;

		$_SESSION ['addNewSaloonForm'] ['dotel2'] = $dotel2;

		$_SESSION ['addNewSaloonForm'] ['web'] = $web;

		$_SESSION ['addNewSaloonForm'] ['address'] = $address;

		$_SESSION ['addNewSaloonForm'] ['region'] = $region;

		$_SESSION ['addNewSaloonForm'] ['city'] = $city;

		$_SESSION ['addNewSaloonForm'] ['about'] = $about;

		$_SESSION ['addNewSaloonForm'] ['descr'] = $descr;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=addUser' );

		die ();

	}



	// Все поля заполнены правильно - продолжаем регистрацию

	$query = "INSERT INTO " . TABLE_USERS . "

		    SET email='" .  mysql_real_escape_string($email) . "',

			passw='" . mysql_real_escape_string ( md5 ( $password ) ) . "',

			puttime=NOW(),

			status='user',

			region=".abs ( intval ( $region ) ).",

			city=".abs ( intval ( $city ) ).",

			name='" .  mysql_real_escape_string($name) . "',

		    tel1='" . mysql_real_escape_string ( $tel1 ) . "',

		    tel2='" . (mysql_real_escape_string ( $tel2 ) ? mysql_real_escape_string ( $tel2 ) : "") . "',

		    nach1='" . (mysql_real_escape_string ( $stel1 ) ? mysql_real_escape_string ( $stel1 ) : mysql_real_escape_string ( $stel1 )) . "',

		    end1='" . (mysql_real_escape_string ( $dotel1 ) ? mysql_real_escape_string ( $dotel1 ) : "") . "',

		    nach2='" . (mysql_real_escape_string ( $stel2 ) ? mysql_real_escape_string ( $stel2 ) : "") . "',

		    end2='" . (mysql_real_escape_string ( $dotel2 ) ? mysql_real_escape_string ( $dotel2 ) : "") . "'";


	$res = mysql_query ( $query );

	//echo $query;

	if (! $res) {

		$msg = 'Ошибка при изменении контактной информации';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}




	$msg = '<br/>

      <center><p><strong>Пользователь был успешно добавлен</strong></p></center>

    <br/>';

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $msg, $html );



	return $html;

}
/////////////

function getaddAutosaloon() {

	$_SESSION ['pageTitle']="Добавление автосалона";



	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['addNewSaloonForm'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['addNewSaloonForm'] ['error'], $info );

		$html = $html . $info . "\n";

		$name = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['name'] );

		$email = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['email'] );

		$web = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['web'] );

		$about = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['about'] );

		$region = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['region'] );

		$city = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['city'] );

		$address = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['address'] );

		$tel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['tel1'] );

		$tel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['tel2'] );

		$stel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['stel1'] );

		$stel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['stel2'] );

		$dotel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['dotel1'] );

		$dotel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['dotel2'] );

		$descr = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['descr'] );



		//  $timezone  = $_SESSION['addNewUserForm']['timezone'];

		// $icq       = htmlspecialchars( $_SESSION['addNewUserForm']['icq'] );

		// $url       = htmlspecialchars( $_SESSION['addNewUserForm']['url'] );

		// $about     = htmlspecialchars( $_SESSION['addNewUserForm']['about'] );

		//  $signature = htmlspecialchars( $_SESSION['addNewUserForm']['signature'] );

		unset ( $_SESSION ['addNewSaloonForm'] );

	} else {

		$name = '';

		//$email = '';

		$tel2 = '';

		$tel1 = '';

		$stel1 = '';

		$stel2 = '';

		$dotel1 = '';

		$dotel2 = '';

		$web = '';

		$descr = '';

		$about = '';

		//$region = 0;

		//$city =0;

		$address = '';

		// $timezone  = 0;

	//  $icq       = '';

	//  $url       = '';

	//  $about     = '';

	//  $signature = '';

	}



	// Считываем в переменную файл шаблона, содержащего

	// форму для добавления нового пользователя



	$action = $_SERVER ['PHP_SELF'] . '?action=addAutosaloonAdm';


	$tpl = file_get_contents ( './templates/addAutosaloon.html' );


	//photo

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{name}', $name, $tpl );

	$tpl = str_replace ( '{email}', $email, $tpl );

	$tpl = str_replace ( '{tel1}', $tel1, $tpl );

	$tpl = str_replace ( '{tel2}', $tel2, $tpl );

	$tpl = str_replace ( '{stel1}', $nach1, $tpl );

	$tpl = str_replace ( '{stel2}', $nach2, $tpl );

	$tpl = str_replace ( '{dotel1}', $end1, $tpl );

	$tpl = str_replace ( '{dotel2}', $end2, $tpl );



	$tpl = str_replace ( '{web}', $url, $tpl );

	$tpl = str_replace ( '{address}', $address, $tpl );

	$tpl = str_replace ( '{about}', $about, $tpl );

	$tpl = str_replace ( '{descr}', $descr, $tpl );

	$reg = $region;



	$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}
//тут..
	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] === $reg) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

		}

	}

	$tpl = str_replace ( '{REGION}', $region, $tpl );



	if (isset ( $region )) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION='" . $reg."'";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] === $city) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['CITY'] . "</option>";

			}

		}

		$tpl = str_replace ( '{CITY}', $mark, $tpl );

	}







	$html = $html . $tpl;

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=addAutosaloon">Добавление автосалона</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}



function addAutosaloon() {



	// Если не переданы данные формы - значит функция была вызвана по ошибке

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$name = substr ( $_POST ['name'], 0, 60 );

	$password = substr ( $_POST ['password'], 0, 30 );

	$confirm = substr ( $_POST ['confirm'], 0, 30 );

	$email = substr ( $_POST ['email'], 0, 60 );

	//$signature = substr( $_POST['signature'], 0, 500 );


	$tel1 = substr ( $_POST ['tel1'], 0, 25 );

	$tel2 = substr ( $_POST ['tel2'], 0, 25 );

	$stel1 = substr ( $_POST ['stel1'], 0, 2 );

	$stel2 = substr ( $_POST ['stel2'], 0, 2 );

	$dotel1 = substr ( $_POST ['dotel1'], 0, 2 );

	$dotel2 = substr ( $_POST ['dotel2'], 0, 2 );

	$web = substr ( $_POST ['web'], 0, 60 );

	$descr = substr ( $_POST ['descr'], 0, 3000 );

	$about = substr ( $_POST ['about'], 0, 500 );



	$address = substr ( $_POST ['address'], 0, 128 );

	$city = $_POST ['cityCode'];

	$region = $_POST ['id_region'];

	// Обрезаем лишние пробелы

	$name = trim ( $name );

	$about = trim ( $about );

	$about = strip_tags ( $about );

	$password = trim ( $password );

	$confirm = trim ( $confirm );

	$email = trim ( $email );



	$web = trim ( $web );

	$descr = trim ( $descr );



	$name = RemoveXSS ( $name );

	$about = RemoveXSS ( $about );


	$password = RemoveXSS ( $password );

	$confirm = RemoveXSS ( $confirm );

	$email = RemoveXSS ( $email );



	$web = RemoveXSS ( $web );

	$descr = RemoveXSS ( $descr );


	// Проверяем, заполнены ли обязательные поля

	$error = '';


	if (empty ( $address ))

		$error = $error . '<li>не заполнено поле "Адрес"</li>' . "\n";

	if (strlen ( $about ) > 250)

		$error = $error . '<li>длина поля "Описание" более 500 символов</li>' . "\n";

	if (strlen ( $descr ) > 3000)

		$error = $error . '<li>длина поля "Подробное описание" более 3000 символов</li>' . "\n";

	if (empty ( $about ))

		$error = $error . '<li>не заполнено поле "Описание"</li>' . "\n";




	if ($region <= 0)

		$error = $error . '<li>не выбран регион</li>' . "\n";

	if ($city <= 0)

		$error = $error . '<li>не выбран город</li>' . "\n";

	if (empty ( $password ))

		$error = $error . '<li>не заполнено поле "Пароль"</li>' . "\n";

	if (empty ( $confirm ))

		$error = $error . '<li>не заполнено поле "Повторите пароль"</li>' . "\n";

	if (! empty ( $password ) and strlen ( $password ) < MIN_PASSWORD_LENGTH)

		$error = $error . '<li>длина пароля должна быть не меньше ' . MIN_PASSWORD_LENGTH . ' символов</li>' . "\n";

		// Проверяем, совпадают ли пароли

	if (! empty ( $password ) and ! empty ( $confirm ) and $password != $confirm)

		$error = $error . '<li>не совпадают пароли</li>' . "\n";

	if (! empty ( $name ) and ! preg_match ( "#^[- _0-9a-zА-Яа-я]+$#i", $name ))

		$error = $error . '<li>поле "Название автосалона" содержит недопустимые символы</li>' . "\n";


	if (! empty ( $password ) and ! preg_match ( "#^[-_0-9a-z]+$#i", $password ))

		$error = $error . '<li>поле "Пароль" содержит недопустимые символы</li>' . "\n";

	if (! empty ( $confirm ) and ! preg_match ( "#^[-_0-9a-z]+$#i", $confirm ))

		$error = $error . '<li>поле "Повторите пароль" содержит недопустимые символы</li>' . "\n";



	if (empty ( $name ))

		$error = $error . '<li>не заполнено поле "Название автосалона"</li>' . "\n";

	if (empty ( $tel1 ))

		$error = $error . '<li>не заполнено поле "Телефон"</li>' . "\n";

	$query = "SELECT * FROM " . TABLE_USERS . "

		    WHERE email LIKE '" . mysql_real_escape_string ( $email ) . "'";

	$res = mysql_query ( $query );



	if (! $res) {

		$msg = 'Ошибка при регистрации нового пользователя';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0)

		$error = $error . '<li>Такой адрес "' . $email . '" уже зарегистрирован</li>' . "\n";


		$IMGCOUNT =	3;

	for($ii = 1; $ii <= $IMGCOUNT; $ii ++) {

		if (! empty ( $_FILES ['x_PHOTO_' . $ii] ['name'] )) {

			//$ext = strrchr ( $_FILES ['x_PHOTO_' . $ii] ['name'], "." );

			unset($imgS);
			$imgS = getimagesize($_FILES ['x_PHOTO_' . $ii]['tmp_name']);

			$extensions = array ("image/jpg", "image/png", "image/jpeg", "image/gif" );

//&&&&&&&&&&&&&&&&//

			if (! in_array ( strtolower($imgS['mime']), $extensions ))

				$error = $error . '<li>Недопустимый формат файла №' . $ii . '</li>' . "\n";



			if ($_FILES ['x_PHOTO_' . $ii] ['size'] > MAX_PHOTO_SIZE)

				$error = $error . '<li>Размер файла №' . $ii . ' больше ' . (MAX_PHOTO_SIZE / 1024) . ' Кб</li>' . "\n";

		}

	}


	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {

		$_SESSION ['addNewSaloonForm'] = array ();

		$_SESSION ['addNewSaloonForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['addNewSaloonForm'] ['name'] = $name;

		$_SESSION ['addNewSaloonForm'] ['email'] = $email;

		$_SESSION ['addNewSaloonForm'] ['tel1'] = $tel1;

		$_SESSION ['addNewSaloonForm'] ['tel2'] = $tel2;

		$_SESSION ['addNewSaloonForm'] ['stel1'] = $stel1;

		$_SESSION ['addNewSaloonForm'] ['stel2'] = $stel2;

		$_SESSION ['addNewSaloonForm'] ['dotel1'] = $dotel1;

		$_SESSION ['addNewSaloonForm'] ['dotel2'] = $dotel2;

		$_SESSION ['addNewSaloonForm'] ['web'] = $web;

		$_SESSION ['addNewSaloonForm'] ['address'] = $address;

		$_SESSION ['addNewSaloonForm'] ['region'] = $region;

		$_SESSION ['addNewSaloonForm'] ['city'] = $city;

		$_SESSION ['addNewSaloonForm'] ['about'] = $about;

		$_SESSION ['addNewSaloonForm'] ['descr'] = $descr;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=addAutosaloon' );

		die ();

	}


	    $img_1="";

        $img_2="";

		$img_3="";


		if (! empty ( $_FILES ['x_PHOTO_1'] ['tmp_name'] )) {

			$img_1 = water1 ($_FILES ['x_PHOTO_1'] );

		}

		if (! empty ( $_FILES ['x_PHOTO_2'] ['tmp_name'] )) {

			$img_2 = water1 ( $_FILES ['x_PHOTO_2'] );

		}


		if (! empty ( $_FILES ['x_PHOTO_3'] ['tmp_name'] )) {

			$img_3 = water1 ( $_FILES ['x_PHOTO_3'] );

		}

	$query = "INSERT INTO " . TABLE_USERS . "

		    SET email='" .  mysql_real_escape_string($email) . "',

			name='" .  mysql_real_escape_string($name) . "',

			passw='" . mysql_real_escape_string ( md5 ( $password ) ) . "',

			puttime=NOW(),

			status='autosaloon',

		    tel1='" . mysql_real_escape_string ( $tel1 ) . "',

		    tel2='" . (mysql_real_escape_string ( $tel2 ) ? mysql_real_escape_string ( $tel2 ) : "") . "',

		    nach1='" . (mysql_real_escape_string ( $stel1 ) ? mysql_real_escape_string ( $stel1 ) : mysql_real_escape_string ( $stel1 )) . "',

		    end1='" . (mysql_real_escape_string ( $dotel1 ) ? mysql_real_escape_string ( $dotel1 ) : "") . "',

		    nach2='" . (mysql_real_escape_string ( $stel2 ) ? mysql_real_escape_string ( $stel2 ) : "") . "',

		    end2='" . (mysql_real_escape_string ( $dotel2 ) ? mysql_real_escape_string ( $dotel2 ) : "") . "',

		    url='" . (mysql_real_escape_string ( $web ) ? mysql_real_escape_string ( $web ) : "") . "',

		    region='" . mysql_real_escape_string ( $region ) . "',

		    city='" . mysql_real_escape_string ( $city ) . "',

		    address='" . (mysql_real_escape_string ( $address ) ? mysql_real_escape_string ( $address ) : "") . "',

		    about= '" . (mysql_real_escape_string ( $about ) ? mysql_real_escape_string ( $about ) : "") . "',

			descr= '" . (mysql_real_escape_string ( $descr ) ? mysql_real_escape_string ( $descr ) : "") . "'";


	$res = mysql_query ( $query );



	if (! $res) {

		$msg = 'Ошибка при изменении контактной информации';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$id = mysql_insert_id ();

	$query = "INSERT INTO AUTO_SALOON_PHOTO SET PHOTO_1='" .  $img_1 . "', PHOTO_2='" .  $img_2 . "', PHOTO_3='" .  $img_3 . "', ID_SALOON='".$id."'";

	$res = mysql_query ( $query );


	$msg = '<br/>

      <center><p><strong>Автосалон был успешно добавлен</strong></p></center>

    <br/>';

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $msg, $html );



	return $html;

}
/////////////


function getChangeInfo() {

	$_SESSION ['pageTitle']="Контактная информация";



	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['addNewSaloonForm'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['addNewSaloonForm'] ['error'], $info );

		$html = $html . $info . "\n";

		$name = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['name'] );

		//$email = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['email'] );

		$web = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['web'] );

		$about = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['about'] );

		$region = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['region'] );

		$city = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['city'] );

		$address = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['address'] );

		$tel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['tel1'] );

		$tel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['tel2'] );

		$stel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['stel1'] );

		$stel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['stel2'] );

		$dotel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['dotel1'] );

		$dotel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['dotel2'] );

		$descr = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['descr'] );



		//  $timezone  = $_SESSION['addNewUserForm']['timezone'];

		// $icq       = htmlspecialchars( $_SESSION['addNewUserForm']['icq'] );

		// $url       = htmlspecialchars( $_SESSION['addNewUserForm']['url'] );

		// $about     = htmlspecialchars( $_SESSION['addNewUserForm']['about'] );

		//  $signature = htmlspecialchars( $_SESSION['addNewUserForm']['signature'] );

		unset ( $_SESSION ['addNewSaloonForm'] );

	} else {

		$name = '';

		//$email = '';

		$tel2 = '';

		$tel1 = '';

		$stel1 = '';

		$stel2 = '';

		$dotel1 = '';

		$dotel2 = '';

		$web = '';

		$descr = '';

		$about = '';

		//$region = 0;

		//$city =0;

		$address = '';

		// $timezone  = 0;

	//  $icq       = '';

	//  $url       = '';

	//  $about     = '';

	//  $signature = '';

	}



	// Считываем в переменную файл шаблона, содержащего

	// форму для добавления нового пользователя



	$action = $_SERVER ['PHP_SELF'] . '?action=chinfoAct';


	$query = "SELECT * FROM AUTO_USERS WHERE id_author='".(($_SESSION['user']['status'] !== 'admin' || !$_REQUEST['id']) ? $_SESSION['user']['id_author'] : $_REQUEST['id'])."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}

	$_SESSION['edID'] = $_REQUEST['id'];
	if (!$_SESSION['edID']) $_SESSION['edID']=$_SESSION['user']['id_author'];
	$_SESSION['edSTATUS'] = $data['status'];


	if (!$_REQUEST['id']) {
	if ($data ['status'] === 'user') {

		$tpl = file_get_contents ( './templates/chinfoUser.html' );

	} else
	if ($data ['status'] === 'autosaloon' || $data ['status'] === 'admin') {

		$tpl = file_get_contents ( './templates/chinfoSaloon.html' );

	}
	} else {
	if ($data ['status'] === 'user') {

		$tpl = file_get_contents ( './templates/chinfoUserAdm.html' );

	} else
	if ($data ['status'] === 'autosaloon' || $data ['status'] === 'admin') {

		$tpl = file_get_contents ( './templates/chinfoSaloonAdm.html' );

	}
	}

//--//
	//photo
	$query = "SELECT * FROM AUTO_SALOON_PHOTO WHERE ID_SALOON='".(($_SESSION['user']['status'] !== 'admin') ? $_SESSION['user']['id_author'] : $_REQUEST['id'])."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

	}

	$DEL_PHOTO = '';

	if ($ph['PHOTO_1'])

	$DEL_PHOTO = $DEL_PHOTO . '


            <td>

              <img src="show_image.php?filename=photo_saloon/'.$ph['PHOTO_1'].'&width=80"/><br/><input type="checkbox" name="x_DEL_PHOTO_1" value="1"/> <span class="style7">Удалить</span>

            </td>

          ';

	if ($ph['PHOTO_2'])

	$DEL_PHOTO = $DEL_PHOTO . '


            <td>

              <img src="show_image.php?filename=photo_saloon/'.$ph['PHOTO_2'].'&width=80"/><br/><input type="checkbox" name="x_DEL_PHOTO_2" value="1"/> <span class="style7">Удалить</span>

            </td>

          ';

	if ($ph['PHOTO_3'])

	$DEL_PHOTO = $DEL_PHOTO . '


            <td>

              <img src="show_image.php?filename=photo_saloon/'.$ph['PHOTO_3'].'&width=80"/><br/><input type="checkbox" name="x_DEL_PHOTO_3" value="1"/> <span class="style7">Удалить</span>

            </td>

          ';




	$tpl = str_replace ( '{DEL_PHOTO}', $DEL_PHOTO, $tpl );
	//photo

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{name}', $data['name'], $tpl );

	$tpl = str_replace ( '{email}', $_SESSION['user']['email'], $tpl );

	$tpl = str_replace ( '{tel1}', $data['tel1'], $tpl );

	$tpl = str_replace ( '{tel2}', $data['tel2'], $tpl );

	$tpl = str_replace ( '{stel1}', $data['nach1'], $tpl );

	$tpl = str_replace ( '{stel2}', $data['nach2'], $tpl );

	$tpl = str_replace ( '{dotel1}', $data['end1'], $tpl );

	$tpl = str_replace ( '{dotel2}', $data['end2'], $tpl );



	$tpl = str_replace ( '{web}', $data['url'], $tpl );

	$tpl = str_replace ( '{address}', $data['address'], $tpl );

	$tpl = str_replace ( '{about}', $data['about'], $tpl );

	$tpl = str_replace ( '{descr}', $data['descr'], $tpl );



	$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}
//тут..
	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = (intval($regionlist ['ID']) === intval($data['region'])) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

		}

	}

	$tpl = str_replace ( '{REGION}', $region, $tpl );



	if (isset ( $region )) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION='" . $data['region']."'";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] === $data['city']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['CITY'] . "</option>";

			}

		}

		$tpl = str_replace ( '{CITY}', $mark, $tpl );

	}







	$html = $html . $tpl;

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=chinfo">Контактная информация</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}



function changeInfo() {



	// Если не переданы данные формы - значит функция была вызвана по ошибке

	if (!($_SESSION ['user'] ['status'] === 'autosaloon') && !($_SESSION ['user'] ['status'] === 'user') && !($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$name = substr ( $_POST ['name'], 0, 60 );

	//$password = substr ( $_POST ['password'], 0, 30 );

	//$confirm = substr ( $_POST ['confirm'], 0, 30 );

	$email = substr ( $_POST ['email'], 0, 60 );

	//$signature = substr( $_POST['signature'], 0, 500 );


	$tel1 = substr ( $_POST ['tel1'], 0, 25 );

	$tel2 = substr ( $_POST ['tel2'], 0, 25 );

	$stel1 = substr ( $_POST ['stel1'], 0, 2 );

	$stel2 = substr ( $_POST ['stel2'], 0, 2 );

	$dotel1 = substr ( $_POST ['dotel1'], 0, 2 );

	$dotel2 = substr ( $_POST ['dotel2'], 0, 2 );

	$web = substr ( $_POST ['web'], 0, 60 );

	$descr = substr ( $_POST ['descr'], 0, 3000 );

	$about = substr ( $_POST ['about'], 0, 500 );



	$address = substr ( $_POST ['address'], 0, 128 );

	$city = $_POST ['cityCode'];

	$region = $_POST ['id_region'];

	// Обрезаем лишние пробелы

	$name = trim ( $name );

	$about = trim ( $about );

	$about = strip_tags ( $about );

	//$password = trim ( $password );

	//$confirm = trim ( $confirm );

	$email = trim ( $email );



	$web = trim ( $web );

	$descr = trim ( $descr );



	$name = RemoveXSS ( $name );

	$about = RemoveXSS ( $about );

	//$about = RemoveXSS ( $about );

	//$password = RemoveXSS ( $password );

	//$confirm = RemoveXSS ( $confirm );

	$email = RemoveXSS ( $email );



	$web = RemoveXSS ( $web );

	$descr = RemoveXSS ( $descr );


	//$_SESSION['edID'] = $_REQUEST['id'];
	//$_SESSION['edSTATUS'] = $data['status'];


	// Проверяем, заполнены ли обязательные поля

	$error = '';
if ($_SESSION['edSTATUS'] === 'autosaloon' || $_SESSION['edSTATUS'] === 'admin') {

	if (empty ( $address ))

		$error = $error . '<li>не заполнено поле "Адрес"</li>' . "\n";

	if (strlen ( $about ) > 250)

		$error = $error . '<li>длина поля "Описание" более 500 символов</li>' . "\n";

	if (strlen ( $descr ) > 3000)

		$error = $error . '<li>длина поля "Подробное описание" более 3000 символов</li>' . "\n";

	if (empty ( $about ))

		$error = $error . '<li>не заполнено поле "Описание"</li>' . "\n";

	$IMGCOUNT =	3;

	for($ii = 1; $ii <= $IMGCOUNT; $ii ++) {

		if (! empty ( $_FILES ['x_PHOTO_' . $ii] ['name'] )) {

			//$ext = strrchr ( $_FILES ['x_PHOTO_' . $ii] ['name'], "." );

			unset($imgS);
			$imgS = getimagesize($_FILES ['x_PHOTO_' . $ii]['tmp_name']);

			$extensions = array ("image/jpg", "image/png", "image/jpeg", "image/gif" );

//&&&&&&&&&&&&&&&&//

			if (! in_array ( strtolower($imgS['mime']), $extensions ))

				$error = $error . '<li>Недопустимый формат файла №' . $ii . '</li>' . "\n";



			if ($_FILES ['x_PHOTO_' . $ii] ['size'] > MAX_PHOTO_SIZE)

				$error = $error . '<li>Размер файла №' . $ii . ' больше ' . (MAX_PHOTO_SIZE / 1024) . ' Кб</li>' . "\n";

		}

	}

}
	if ($region <= 0)

		$error = $error . '<li>не выбран регион</li>' . "\n";

	if ($city <= 0)

		$error = $error . '<li>не выбран город</li>' . "\n";



	if (empty ( $name )) {

		if ($_SESSION['edSTATUS'] === 'autosaloon' || $_SESSION['edSTATUS'] === 'admin')
			$error = $error . '<li>не заполнено поле "Название автосалона"</li>' . "\n";
		else
			$error = $error . '<li>не заполнено поле "Ваше имя"</li>' . "\n";

	}




	if (empty ( $tel1 ))

		$error = $error . '<li>не заполнено поле "Телефон"</li>' . "\n";








	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {

		$_SESSION ['addNewSaloonForm'] = array ();

		$_SESSION ['addNewSaloonForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['addNewSaloonForm'] ['name'] = $name;

		//$_SESSION ['addNewSaloonForm'] ['email'] = $email;

		$_SESSION ['addNewSaloonForm'] ['tel1'] = $tel1;

		$_SESSION ['addNewSaloonForm'] ['tel2'] = $tel2;

		$_SESSION ['addNewSaloonForm'] ['stel1'] = $stel1;

		$_SESSION ['addNewSaloonForm'] ['stel2'] = $stel2;

		$_SESSION ['addNewSaloonForm'] ['dotel1'] = $dotel1;

		$_SESSION ['addNewSaloonForm'] ['dotel2'] = $dotel2;

		$_SESSION ['addNewSaloonForm'] ['web'] = $web;

		$_SESSION ['addNewSaloonForm'] ['address'] = $address;

		$_SESSION ['addNewSaloonForm'] ['region'] = $region;

		$_SESSION ['addNewSaloonForm'] ['city'] = $city;

		$_SESSION ['addNewSaloonForm'] ['about'] = $about;

		$_SESSION ['addNewSaloonForm'] ['descr'] = $descr;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}

	if ($_SESSION['edSTATUS'] === 'autosaloon' || $_SESSION['edSTATUS'] === 'admin') {

///111111111
		if ($_REQUEST['x_DEL_PHOTO_1']) {

	$query = "SELECT PHOTO_1 FROM AUTO_SALOON_PHOTO WHERE ID_SALOON='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

	}

	unlink($_SERVER['DOCUMENT_ROOT'].'/photo_saloon/'.	$ph['PHOTO_1']);
	//

			$query = "UPDATE AUTO_SALOON_PHOTO

				SET PHOTO_1='' WHERE ID_SALOON='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );
/*
			$query = "DELETE FROM AUTO_SALOON_PHOTO

		    WHERE ID_SALOON='".$_SESSION['user']['id_author']."'";

			$res = mysql_query ( $query );
*/
		}
///

	    $img_1="";

        if (! empty ( $_FILES ['x_PHOTO_1'] ['tmp_name'] )) {

				$query = "SELECT PHOTO_1 FROM AUTO_SALOON_PHOTO WHERE ID_SALOON='".$_SESSION['edID']."'";

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

				}

			if ($ph['PHOTO_1']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo_saloon/'.$ph['PHOTO_1']);

			$query = "UPDATE AUTO_SALOON_PHOTO

				SET PHOTO_1='' WHERE ID_SALOON='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );
			/*
			$query = "DELETE FROM AUTO_SALOON_PHOTO

		    WHERE ID_SALOON='".$_SESSION['user']['id_author']."'";

			$res = mysql_query ( $query );
			*/
			$img_1 = water1 ($_FILES ['x_PHOTO_1'] );

			//пппппп
				$query = "SELECT COUNT(*) FROM AUTO_SALOON_PHOTO WHERE ID_SALOON='".$_SESSION['edID']."'";

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph_count=array();$row=mysql_fetch_assoc($res);$ph_count=$row);

				}


			//пппп

			if ($ph_count['COUNT(*)']) {

			$query = "UPDATE AUTO_SALOON_PHOTO

		    SET PHOTO_1='" .  $img_1 . "', ID_SALOON='".$_SESSION['edID']."'";

			} else {

			$query = "INSERT INTO AUTO_SALOON_PHOTO

		    SET PHOTO_1='" .  $img_1 . "', ID_SALOON='".$_SESSION['edID']."'";

			}

		$res = mysql_query ( $query );

		}

////22222222
		if ($_REQUEST['x_DEL_PHOTO_2']) {

	$query = "SELECT PHOTO_2 FROM AUTO_SALOON_PHOTO WHERE ID_SALOON='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

	}

	unlink($_SERVER['DOCUMENT_ROOT'].'/photo_saloon/'.	$ph['PHOTO_2']);
	//

			$query = "UPDATE AUTO_SALOON_PHOTO

				SET PHOTO_2='' WHERE ID_SALOON='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );
/*
			$query = "DELETE FROM AUTO_SALOON_PHOTO

		    WHERE ID_SALOON='".$_SESSION['user']['id_author']."'";

			$res = mysql_query ( $query );
*/
		}
///

	    $img_2="";

        if (! empty ( $_FILES ['x_PHOTO_2'] ['tmp_name'] )) {

				$query = "SELECT PHOTO_2 FROM AUTO_SALOON_PHOTO WHERE ID_SALOON='".$_SESSION['edID']."'";

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

			}

			if ($ph['PHOTO_2']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo_saloon/'.$ph['PHOTO_2']);

			$query = "UPDATE AUTO_SALOON_PHOTO

				SET PHOTO_2='' WHERE ID_SALOON='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );
			/*
			$query = "DELETE FROM AUTO_SALOON_PHOTO

		    WHERE ID_SALOON='".$_SESSION['user']['id_author']."'";

			$res = mysql_query ( $query );
			*/
			$img_2 = water1 ($_FILES ['x_PHOTO_2'] );

			//пппппп
				$query = "SELECT COUNT(*) FROM AUTO_SALOON_PHOTO WHERE ID_SALOON='".$_SESSION['edID']."'";

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph_count=array();$row=mysql_fetch_assoc($res);$ph_count=$row);

				}


			//пппп
						if ($ph_count['COUNT(*)']) {

			$query = "UPDATE AUTO_SALOON_PHOTO

		    SET PHOTO_2='" .  $img_2 . "', ID_SALOON='".$_SESSION['edID']."'";

			} else {

			$query = "INSERT INTO AUTO_SALOON_PHOTO

		    SET PHOTO_2='" .  $img_2 . "', ID_SALOON='".$_SESSION['edID']."'";

			}

		$res = mysql_query ( $query );

		}

//////33333333

		if ($_REQUEST['x_DEL_PHOTO_3']) {

	$query = "SELECT PHOTO_3 FROM AUTO_SALOON_PHOTO WHERE ID_SALOON='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

	}

	unlink($_SERVER['DOCUMENT_ROOT'].'/photo_saloon/'.	$ph['PHOTO_3']);
	//

			$query = "UPDATE AUTO_SALOON_PHOTO

				SET PHOTO_3='' WHERE ID_SALOON='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );
/*
			$query = "DELETE FROM AUTO_SALOON_PHOTO

		    WHERE ID_SALOON='".$_SESSION['user']['id_author']."'";

			$res = mysql_query ( $query );
*/
		}
///

	    $img_3="";

        if (! empty ( $_FILES ['x_PHOTO_3'] ['tmp_name'] )) {

				$query = "SELECT PHOTO_3 FROM AUTO_SALOON_PHOTO WHERE ID_SALOON='".$_SESSION['edID']."'";

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

			}

			if ($ph['PHOTO_3']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo_saloon/'.$ph['PHOTO_3']);

			$query = "UPDATE AUTO_SALOON_PHOTO

				SET PHOTO_3='' WHERE ID_SALOON='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );
			/*
			$query = "DELETE FROM AUTO_SALOON_PHOTO

		    WHERE ID_SALOON='".$_SESSION['user']['id_author']."'";

			$res = mysql_query ( $query );
			*/
			$img_3 = water1 ($_FILES ['x_PHOTO_3'] );

			//пппппп
				$query = "SELECT COUNT(*) FROM AUTO_SALOON_PHOTO WHERE ID_SALOON='".$_SESSION['edID']."'";

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph_count=array();$row=mysql_fetch_assoc($res);$ph_count=$row);

				}


			//пппп
						if ($ph_count['COUNT(*)']) {

			$query = "UPDATE AUTO_SALOON_PHOTO

		    SET PHOTO_3='" .  $img_3 . "', ID_SALOON='".$_SESSION['edID']."'";

			} else {

			$query = "INSERT INTO AUTO_SALOON_PHOTO

		    SET PHOTO_3='" .  $img_3 . "', ID_SALOON='".$_SESSION['edID']."'";

			}

		$res = mysql_query ( $query );

		}





	}//photo

	// Все поля заполнены правильно - продолжаем регистрацию

	$query = "UPDATE " . TABLE_USERS . "

		    SET name='" .  mysql_real_escape_string($name) . "',

		    tel1='" . mysql_real_escape_string ( $tel1 ) . "',

		    tel2='" . (mysql_real_escape_string ( $tel2 ) ? mysql_real_escape_string ( $tel2 ) : "") . "',

		    nach1='" . (mysql_real_escape_string ( $stel1 ) ? mysql_real_escape_string ( $stel1 ) : mysql_real_escape_string ( $stel1 )) . "',

		    end1='" . (mysql_real_escape_string ( $dotel1 ) ? mysql_real_escape_string ( $dotel1 ) : "") . "',

		    nach2='" . (mysql_real_escape_string ( $stel2 ) ? mysql_real_escape_string ( $stel2 ) : "") . "',

		    end2='" . (mysql_real_escape_string ( $dotel2 ) ? mysql_real_escape_string ( $dotel2 ) : "") . "',

		    url='" . (mysql_real_escape_string ( $web ) ? mysql_real_escape_string ( $web ) : "") . "',

		    region='" . mysql_real_escape_string ( $region ) . "',

		    city='" . mysql_real_escape_string ( $city ) . "',

		    address='" . (mysql_real_escape_string ( $address ) ? mysql_real_escape_string ( $address ) : "") . "',

		    about= '" . (mysql_real_escape_string ( $about ) ? mysql_real_escape_string ( $about ) : "") . "',

			descr= '" . (mysql_real_escape_string ( $descr ) ? mysql_real_escape_string ( $descr ) : "") . "' WHERE id_author='".$_SESSION['edID']."'";


	$res = mysql_query ( $query );

	//echo $query;

	if (! $res) {

		$msg = 'Ошибка при изменении контактной информации';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}




	$msg = '<br/>

      <center><p><strong>Контактная информация была успешно изменена</strong></p></center>

    <br/>';

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $msg, $html );



	return $html;

}
/////////////


function nospace($string) {

	$string = preg_replace ( "/(\n \s{2,})/", " ", $string );

	return $string;

}



// Функция возвращает форму для регистрации нового пользователя на форуме

function getAddNewSaloonForm() {

$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=loginForm">Размещение объявлений</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=addNewSaloonForm">Регистрация автосалона</a></span>';

	$_SESSION ['pageTitle']="Регистрация автосалона";

	if (isset ( $_SESSION ['captcha_keystring'] ))

		unset ( $_SESSION ['captcha_keystring'] );

	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['addNewSaloonForm'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['addNewSaloonForm'] ['error'], $info );

		$html = $html . $info . "\n";

		$name = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['name'] );

		$email = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['email'] );

		$web = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['web'] );

		$about = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['about'] );

		$region = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['region'] );

		$city = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['city'] );

		$address = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['address'] );

		$tel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['tel1'] );

		$tel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['tel2'] );

		$stel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['stel1'] );

		$stel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['stel2'] );

		$dotel1 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['dotel1'] );

		$dotel2 = htmlspecialchars ( $_SESSION ['addNewSaloonForm'] ['dotel2'] );



		//  $timezone  = $_SESSION['addNewUserForm']['timezone'];

		// $icq       = htmlspecialchars( $_SESSION['addNewUserForm']['icq'] );

		// $url       = htmlspecialchars( $_SESSION['addNewUserForm']['url'] );

		// $about     = htmlspecialchars( $_SESSION['addNewUserForm']['about'] );

		//  $signature = htmlspecialchars( $_SESSION['addNewUserForm']['signature'] );

		unset ( $_SESSION ['addNewSaloonForm'] );

	} else {

		$name = '';

		$email = '';

		$tel2 = '';

		$tel1 = '';

		$stel1 = '';

		$stel2 = '';

		$dotel1 = '';

		$dotel2 = '';

		$web = '';

		$about = '';

		//$region = 0;

		//$city =0;

		$address = '';

		// $timezone  = 0;

	//  $icq       = '';

	//  $url       = '';

	//  $about     = '';

	//  $signature = '';

	}



	// Считываем в переменную файл шаблона, содержащего

	// форму для добавления нового пользователя

	$tpl = file_get_contents ( './templates/addNewSaloonForm.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=addNewSaloon';

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{name}', $name, $tpl );

	$tpl = str_replace ( '{email}', $email, $tpl );

	$tpl = str_replace ( '{tel1}', $tel1, $tpl );

	$tpl = str_replace ( '{tel2}', $tel2, $tpl );

	$tpl = str_replace ( '{stel1}', $stel1, $tpl );

	$tpl = str_replace ( '{stel2}', $stel2, $tpl );

	$tpl = str_replace ( '{dotel1}', $dotel1, $tpl );

	$tpl = str_replace ( '{dotel2}', $dotel2, $tpl );



	$tpl = str_replace ( '{web}', $web, $tpl );

	$tpl = str_replace ( '{address}', $address, $tpl );

	$tpl = str_replace ( '{about}', $about, $tpl );



	$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region1 = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] == $region) ? " selected" : "";

			$region1 .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

		}

	}

	$tpl = str_replace ( '{REGION}', $region1, $tpl );



	if (isset ( $region )) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $region;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $city) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['CITY'] . "</option>";

			}

		}

		$tpl = str_replace ( '{CITY}', $mark, $tpl );

	}



	$kcaptcha = './kcaptcha/kc.php?' . session_name () . '=' . session_id ();

	$tpl = str_replace ( '{kcaptcha}', $kcaptcha, $tpl );

	$tpl = str_replace ( '{keystring}', '', $tpl );

	/*

	$options = '';

	for ( $i = -12; $i <= 12; $i++ ) {

	if ( $i < 1 )

	$value = $i.' часов';

	else

	$value = '+'.$i.' часов';

	if ( $i == $timezone )

	$options = $options . '<option value="'.$i.'" selected>'.$value.'</option>'."\n";

	else

	$options = $options . '<option value="'.$i.'">'.$value.'</option>'."\n";

	}

	//$tpl = str_replace( '{options}', $options, $tpl);

	//$tpl = str_replace( '{servertime}', date( "d.m.Y H:i:s" ), $tpl );

	*/

	$html = $html . $tpl;

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}



function addNewSaloon() {



	// Если не переданы данные формы - значит функция была вызвана по ошибке

	if (! isset ( $_POST ['name'] ) or ! isset ( $_POST ['password'] ) or ! isset ( $_POST ['confirm'] ) or ! isset ( $_POST ['email'] ) or ! isset ( $_POST ['about'] ) or ! isset ( $_POST ['id_region'] ) or ! isset ( $_POST ['cityCode'] ) or ! isset ( $_POST ['address'] ) or ! isset ( $_POST ['keystring'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=addNewSaloonForm' );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$name = substr ( $_POST ['name'], 0, 60 );

	$password = substr ( $_POST ['password'], 0, 30 );

	$confirm = substr ( $_POST ['confirm'], 0, 30 );

	$email = substr ( $_POST ['email'], 0, 60 );

	//$signature = substr( $_POST['signature'], 0, 500 );

	$keystring = substr ( $_POST ['keystring'], 0, 6 );

	$tel1 = substr ( $_POST ['tel1'], 0, 25 );

	$tel2 = substr ( $_POST ['tel2'], 0, 25 );

	$stel1 = substr ( $_POST ['stel1'], 0, 2 );

	$stel2 = substr ( $_POST ['stel2'], 0, 2 );

	$dotel1 = substr ( $_POST ['dotel1'], 0, 2 );

	$dotel2 = substr ( $_POST ['dotel2'], 0, 2 );

	$web = substr ( $_POST ['web'], 0, 60 );

	;

	$about = substr ( $_POST ['about'], 0, 500 );



	$address = substr ( $_POST ['address'], 0, 128 );

	;

	$city = $_POST ['cityCode'];

	$region = $_POST ['id_region'];

	// Обрезаем лишние пробелы

	$name = trim ( $name );

	$about = trim ( $about );

	$about = strip_tags ( $about );

	$password = trim ( $password );

	$confirm = trim ( $confirm );

	$email = trim ( $email );

	$keystring = trim ( $keystring );

	$web = trim ( $web );



	$name = RemoveXSS ( $name );

	$about = RemoveXSS ( $about );

	$about = RemoveXSS ( $about );

	$password = RemoveXSS ( $password );

	$confirm = RemoveXSS ( $confirm );

	$email = RemoveXSS ( $email );

	$keystring = RemoveXSS ( $keystring );

	$web = RemoveXSS ( $web );



	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (empty ( $address ))

		$error = $error . '<li>не заполнено поле "Адрес"</li>' . "\n";

	if ($region <= 0)

		$error = $error . '<li>не выбран регион Автосалона</li>' . "\n";

	if ($city <= 0)

		$error = $error . '<li>не выбран город Автосалона"</li>' . "\n";

	if (strlen ( $about ) > 500)

		$error = $error . '<li>длина поля "Описание" более 500 символов</li>' . "\n";

	if (empty ( $name ))

		$error = $error . '<li>не заполнено поле "Название автосалона"</li>' . "\n";

	if (empty ( $password ))

		$error = $error . '<li>не заполнено поле "Пароль"</li>' . "\n";

	if (empty ( $confirm ))

		$error = $error . '<li>не заполнено поле "Подтвердите пароль"</li>' . "\n";

	if (empty ( $about ))

		$error = $error . '<li>не заполнено поле "Описание"</li>' . "\n";



	if (empty ( $email ))

		$error = $error . '<li>не заполнено поле "Адрес e-mail"</li>' . "\n";

	if (empty ( $tel1 ))

		$error = $error . '<li>не заполнено поле "Телефон"</li>' . "\n";



	if (empty ( $keystring ))

		$error = $error . '<li>не заполнено поле "Код"</li>' . "\n";

		// Проверяем, не слишком ли короткий пароль

	if (! empty ( $password ) and strlen ( $password ) < MIN_PASSWORD_LENGTH)

		$error = $error . '<li>длина пароля должна быть не меньше ' . MIN_PASSWORD_LENGTH . ' символов</li>' . "\n";

		// Проверяем, совпадают ли пароли

	if (! empty ( $password ) and ! empty ( $confirm ) and $password != $confirm)

		$error = $error . '<li>не совпадают пароли</li>' . "\n";

		// Проверяем поле "код"

	if (! empty ( $keystring )) {

		// Проверяем поле "код" на недопустимые символы

		if (! ereg ( "[23456789abcdeghkmnpqsuvxyz]+", $keystring ))

			$error = $error . '<li>поле "Код" содержит недопустимые символы</li>' . "\n";

			// Проверяем, совпадает ли код с картинки

		if (! isset ( $_SESSION ['captcha_keystring'] ) or $_SESSION ['captcha_keystring'] != $keystring)

			$error = $error . '<li>не совпадает код с картинки</li>' . "\n";

	}

	unset ( $_SESSION ['captcha_keystring'] );



	// Проверяем поля формы на недопустимые символы

	//if ( !empty( $name ) and !preg_match( "#^[- _0-9a-zА-Яа-я]+$#i", $name ) )

	//$error = $error.'<li>поле "Название Автосалона" содержит недопустимые символы</li>'."\n";

	if (! empty ( $password ) and ! preg_match ( "#^[-_0-9a-z]+$#i", $password ))

		$error = $error . '<li>поле "Пароль" содержит недопустимые символы</li>' . "\n";

	if (! empty ( $confirm ) and ! preg_match ( "#^[-_0-9a-z]+$#i", $confirm ))

		$error = $error . '<li>поле "Подтвердите пароль" содержит недопустимые символы</li>' . "\n";

		//  if ( !empty( $icq ) and !preg_match( "#^[0-9]+$#", $icq ) )

	//   $error = $error.'<li>поле "ICQ" содержит недопустимые символы</li>'."\n";

	//  if ( !empty( $about ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9а-яА-Яa-z]+$#i", $about ) )

	//   $error = $error.'<li>поле "Интересы" содержит недопустимые символы</li>'."\n";

	//  if ( !empty( $signature ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9а-яА-Яa-z]+$#i", $signature ) )

	//   $error = $error.'<li>поле "Подпись" содержит недопустимые символы</li>'."\n";





	// Проверяем корректность e-mail

	if (! empty ( $email ) and ! preg_match ( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ))

		$error = $error . '<li>поле "Адрес e-mail" должно соответствовать формату somebody@somewhere.ru</li>' . "\n";

		/*

	 Проверяем корректность URL домашней странички

	  if ( !empty( $web ) and !preg_match( "#^(http:\/\/)?(www.)?[-0-9a-z]+\.[a-z]{2,6}\/?$#i", $url ) )

	    $error = $error.'<li>поле "Домашняя страничка" должно соответствовать формату http://www.homepage.ru</li>'."\n";

	*/



	// Выясняем не зарегистрировано ли уже это имя

	// Возможно три ситуации, которые необходимо предотвратить:

	// 1. Вводится ник, полностью совпадающий с уже существующим

	// 2. Вводится уже существующий кирилический ник, в котором

	//    одна или несколько букв заменены на латинские

	// 3. Вводится уже существующий латинский ник, в котором

	//    одна или несколько букв заменениы на кирилические





	// Массив кирилических букв

	/*

	$rus = array ("А", "а", "В", "Е", "е", "К", "М", "Н", "О", "о", "Р", "р", "С", "с", "Т", "Х", "х" );

	// Массив латинских букв

	$eng = array ("A", "a", "B", "E", "e", "K", "M", "H", "O", "o", "P", "p", "C", "c", "T", "X", "x" );

	$new_name = preg_replace ( "#[^- _0-9a-zА-Яа-я]#i", "", $name );

	// Заменяем русские буквы латинскими

	$eng_new_name = str_replace ( $rus, $eng, $new_name );

	// Заменяем латинские буквы русскими

	$rus_new_name = str_replace ( $eng, $rus, $new_name );

	*/

	// Формируем SQL-запрос

	$query = "SELECT * FROM " . TABLE_USERS . "

		    WHERE email LIKE '" . mysql_real_escape_string ( $email ) . "'";

	$res = mysql_query ( $query );



	if (! $res) {

		$msg = 'Ошибка при регистрации нового Автосалона';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0)

		$error = $error . '<li>Такой адрес "' . $email . '" уже зарегистрирован</li>' . "\n";

		/*

	if ( !empty( $_FILES['avatar']['name'] ) ) {

	$ext = strrchr( $_FILES['avatar']['name'], "." );

	$extensions = array( ".jpg", ".gif", ".bmp", ".png" );

	if ( !in_array( $ext, $extensions ) )

	$error = $error.'<li>недопустимый формат файла аватара</li>'."\n";

	if ( $_FILES['avatar']['size'] > MAX_AVATAR_SIZE )

	$error = $error.'<li>размер файла аватора больше '.(MAX_AVATAR_SIZE/1024).' Кб</li>'."\n";

	}

	*/



	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {

		$_SESSION ['addNewSaloonForm'] = array ();

		$_SESSION ['addNewSaloonForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['addNewSaloonForm'] ['name'] = $name;

		$_SESSION ['addNewSaloonForm'] ['email'] = $email;

		$_SESSION ['addNewSaloonForm'] ['tel1'] = $tel1;

		$_SESSION ['addNewSaloonForm'] ['tel2'] = $tel2;

		$_SESSION ['addNewSaloonForm'] ['stel1'] = $stel1;

		$_SESSION ['addNewSaloonForm'] ['stel2'] = $stel2;

		$_SESSION ['addNewSaloonForm'] ['dotel1'] = $dotel1;

		$_SESSION ['addNewSaloonForm'] ['dotel2'] = $dotel2;

		$_SESSION ['addNewSaloonForm'] ['web'] = $web;

		$_SESSION ['addNewSaloonForm'] ['address'] = $address;

		$_SESSION ['addNewSaloonForm'] ['region'] = $region;

		$_SESSION ['addNewSaloonForm'] ['city'] = $city;

		$_SESSION ['addNewSaloonForm'] ['about'] = $about;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=addNewSaloonForm' );

		die ();

	}



	// if ( !empty( $url ) and substr($url, 0, 7) != 'http://' ) $url = 'http://'.$url;





	// Уникальный код для активации учетной записи

	$code = md5 ( uniqid ( rand (), 1 ) );

	// Все поля заполнены правильно - продолжаем регистрацию

	$query = "INSERT INTO " . TABLE_USERS . "

		    (

		    name,

		    passw,

		    email,

		    timezone,

			puttime,

			last_visit,

			status,locked,lock_admin,

		    activation,

		    tel1,

		    tel2,

		    nach1,

		    end1,

		    nach2,

		    end2,

		    url,

		    region,

		    city,

		    address,

		    about

		    )

		    VALUES

		    (

		    '" . $name . "',

		    '" . mysql_real_escape_string ( md5 ( $password ) ) . "',

		    '" . mysql_real_escape_string ( $email ) . "',

		    7,

			NOW(),

			NOW(),

			'autosaloon','1','1',

		    '" . $code . "',

		    '" . mysql_real_escape_string ( $tel1 ) . "',

		    '" . mysql_real_escape_string ( $tel2 ) . "',

		    '" . mysql_real_escape_string ( $stel1 ) . "',

		    '" . mysql_real_escape_string ( $dotel1 ) . "',

		    '" . mysql_real_escape_string ( $stel2 ) . "',

		    '" . mysql_real_escape_string ( $dotel2 ) . "',

		    '" . mysql_real_escape_string ( $web ) . "',

		    '" . mysql_real_escape_string ( $region ) . "',

		    '" . mysql_real_escape_string ( $city ) . "',

		    '" . mysql_real_escape_string ( $address ) . "',

		    '" . mysql_real_escape_string ( $about ) . "'



		    );";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при регистрации нового пользователя';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$id = mysql_insert_id ();

	// if ( !empty( $_FILES['avatar']['name'] ) and

	//     move_uploaded_file ( $_FILES['avatar']['tmp_name'], './photo/'.$id ) ) chmod( './photo/'.$id, 0644 );





	// Посылаем письмо пользователю с просьбой активировать учетную запись

	$headers = "From:<" . ADMIN_EMAIL . ">\n";

	$headers = $headers . "Content-type: text/html; charset=\"windows-1251\"\n";

	$headers = $headers . "Return-path: <" . ADMIN_EMAIL . ">\n";

	$message = '<p>Добро пожаловать на vash_domen.Ru!</p>' . "\n";

	$message = $message . '<p>Пожалуйста сохраните это сообщение. Параметры вашей учётной записи таковы:</p>' . "\n";

	$message .= '<p>---------------</p>'. "\n";

	$message = $message . '<p>Логин: ' . $email . '</p><p>Пароль: ' . $password . '</p>' . "\n";

	$message .= '<p>---------------</p>'. "\n";

	$message = $message . '<p>Для активации вашей учетной записи перейдите по ссылке:</p>' . "\n";

	$link = 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['PHP_SELF'] . '?action=activateUser&code=' . $code;

	$message = $message . '<p><a href="' . $link . '">Активировать учетную запись</a></p>' . "\n";

	$message = $message . '<p>Не забывайте свой пароль: он хранится в нашей базе в зашифрованном

             виде, и мы не сможем вам его выслать. Если вы всё же забудете пароль, то сможете

             запросить новый, который придётся активировать таким же образом, как и вашу

             учётную запись.</p>' . "\n";

	$message = $message . '<p>Внимание!</p>';

	$message = $message . '<p>Ваша заявка на подключение автосалона будет рассмотрена администратором в течении 24 часов. После проверки ваша учётная запись будет активирована администратором и вы сможете приступить к работе.</p>';

	$message = $message . '<p>Спасибо, что зарегистрировались на нашем сайте.</p>';

	//$message = $message.'<p></p>';

	$message = $message . '<div>С Уважением,</div><div>Команда vash_domen.Ru</div>';

	$message = $message . '<div>E-mail: <a href="mailto:info@vash_domen.ru">info@vash_domen.ru</a></div>';

	$message = $message . '<div></div>';

	$subject = 'Регистрация на сайте vash_domen.Ru';

	$subject = '=?koi8-r?B?' . base64_encode ( convert_cyr_string ( $subject, "w", "k" ) ) . '?=';

	mail ( $email, $subject, $message, $headers );



	$headers = "From: <" . ADMIN_EMAIL . ">\n";

	$headers = $headers . "Content-type: text/html; charset=\"windows-1251\"\n";

	$headers = $headers . "Return-path: <" . ADMIN_EMAIL . ">\n";



	$query = "SELECT * FROM AUTO_REGION where ID=" . $region;

	$res = @mysql_query ( $query );

	while ( $row = @mysql_fetch_array ( $res ) ) {

		$region1 = $row ["REGION"];

	}



	$query = "SELECT * FROM AUTO_CITY where ID=" . $city;

	$res = @mysql_query ( $query );

	while ( $row = @mysql_fetch_array ( $res ) ) {

		$city1 = $row ["CITY"];

	}



	$message = '<p>Зарегистрирован новый автосалон: ' . $name . '</p>' . "\n";

	$message .= '<p>Регион: ' . $region1 . '</p>' . "\n";

	$message .= '<p>Город: ' . $city1 . '</p>' . "\n";

	$message .= '<p>Адрес: ' . $address . '</p>' . "\n";

	$message .= '<p>Телефон: ' . $tel1 . '</p>' . "\n";

	$message .= '<p>E-mail: ' . $email . '</p>' . "\n";

	$message .= '<p>Web: ' . $web . '</p>' . "\n";



	$subject = 'Регистрация автосалона ' . $name;

	$subject = '=?koi8-r?B?' . base64_encode ( convert_cyr_string ( $subject, "w", "k" ) ) . '?=';



	mail ( "info@vash_domen.ru", $subject, $message, $headers );



	//mail( 'gerasin_pa@mail.ru', $subject, $message, $headers );




//112233
	$msg = '<b><center><br><p>На Ваш e-mail выслано письмо с просьбой подтвердить регистрацию.</br>

          Чтобы завершить регистрацию и активировать учетную запись, зайдите

          по адресу, указанному в письме.</p><p><span align=center><a href=/>Перейти на главную страницу</a></span></p><br></center</b>';

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $msg, $html );



	return $html;

}



// Функция возвращает форму для регистрации нового пользователя на форуме

function getAddNewUserForm() {

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=loginForm">Размещение объявлений</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=addNewUser">Регистрация пользователей</a></span>';

	$_SESSION ['pageTitle']="Регистрация пользователей";

	if (isset ( $_SESSION ['captcha_keystring'] ))

		unset ( $_SESSION ['captcha_keystring'] );

	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['addNewUserForm'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['addNewUserForm'] ['error'], $info );

		$html = $html . $info . "\n";

		$name = htmlspecialchars ( $_SESSION ['addNewUserForm'] ['name'] );

		$email = htmlspecialchars ( $_SESSION ['addNewUserForm'] ['email'] );

		$tel1 = htmlspecialchars ( $_SESSION ['addNewUserForm'] ['tel1'] );

		$tel2 = htmlspecialchars ( $_SESSION ['addNewUserForm'] ['tel2'] );

		$stel1 = htmlspecialchars ( $_SESSION ['addNewUserForm'] ['stel1'] );

		$stel2 = htmlspecialchars ( $_SESSION ['addNewUserForm'] ['stel2'] );

		$dotel1 = htmlspecialchars ( $_SESSION ['addNewUserForm'] ['dotel1'] );

		$dotel2 = htmlspecialchars ( $_SESSION ['addNewUserForm'] ['dotel2'] );

		$region = htmlspecialchars ( $_SESSION ['addNewUserForm'] ['region'] );

		$city = htmlspecialchars ( $_SESSION ['addNewUserForm'] ['city'] );



		$region = RemoveXSS ( $region );

		$city = RemoveXSS ( $city );

		$name = RemoveXSS ( $name );

		$email = RemoveXSS ( $email );

		$tel1 = RemoveXSS ( $tel1 );

		$tel2 = RemoveXSS ( $tel2 );

		$stel1 = RemoveXSS ( $stel1 );

		$stel2 = RemoveXSS ( $stel2 );

		$dotel1 = RemoveXSS ( $dotel1 );

		$dotel2 = RemoveXSS ( $dotel2 );





		unset ( $_SESSION ['addNewUserForm'] );

	} else {

		$name = '';

		$email = '';

		$tel2 = '';

		$tel1 = '';

		$stel1 = '';

		$stel2 = '';

		$dotel1 = '';

		$dotel2 = '';

		$region = '';

		$city = '';





	}



	// Считываем в переменную файл шаблона, содержащего

	// форму для добавления нового пользователя

	$tpl = file_get_contents ( './templates/userReg.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=addNewUserSubmit';

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{name}', $name, $tpl );

	$tpl = str_replace ( '{email}', $email, $tpl );

	$tpl = str_replace ( '{tel1}', $tel1, $tpl );

	$tpl = str_replace ( '{tel2}', $tel2, $tpl );

	$tpl = str_replace ( '{stel1}', $stel1, $tpl );

	$tpl = str_replace ( '{stel2}', $stel2, $tpl );

	$tpl = str_replace ( '{dotel1}', $dotel1, $tpl );

	$tpl = str_replace ( '{dotel2}', $dotel2, $tpl );


	$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region1 = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] == $region) ? " selected" : "";

			$region1 .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

		}

	}

	$tpl = str_replace ( '{REGION}', $region1, $tpl );



	if (isset ( $region ) && ($region>0)) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $region;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $city) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['CITY'] . "</option>";

			}

		}

		$tpl = str_replace ( '{CITY}', $mark, $tpl );

	}

	$kcaptcha = './kcaptcha/kc.php?' . session_name () . '=' . session_id ();

	$tpl = str_replace ( '{kcaptcha}', $kcaptcha, $tpl );

	$tpl = str_replace ( '{keystring}', '', $tpl );



	$html = $html . $tpl;

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}



// Функция добавляет нового пользователя форума (запись в таблице БД TABLE_USERS)
//факфак
function addNewUser() {



	// Если не переданы данные формы - значит функция была вызвана по ошибке

	if (! isset ( $_POST ['name'] ) or ! isset ( $_POST ['email'] ) or // !isset( $_POST['timezone'] ) or

! isset ( $_POST ['keystring'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=addNewUser' );

		die ();

	}



	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$name = substr ( $_POST ['name'], 0, 32 );

	$password = substr ( $_POST ['password'], 0, 30 );

	$confirm = substr ( $_POST ['confirm'], 0, 30 );

	$email = substr ( $_POST ['email'], 0, 60 );

	//$signature = substr( $_POST['signature'], 0, 500 );

	$keystring = substr ( $_POST ['keystring'], 0, 6 );

	$tel1 = substr ( $_POST ['tel1'], 0, 25 );

	$tel2 = substr ( $_POST ['tel2'], 0, 25 );

	$stel1 = substr ( $_POST ['stel1'], 0, 2 );

	$stel2 = substr ( $_POST ['stel2'], 0, 2 );

	$dotel1 = substr ( $_POST ['dotel1'], 0, 2 );

	$dotel2 = substr ( $_POST ['dotel2'], 0, 2 );

	$city = $_POST ['cityCode'];

	$region = $_POST ['id_region'];

	// Обрезаем лишние пробелы

	$name = trim ( $name );

	$password = trim ( $password );

	$confirm = trim ( $confirm );

	$email = trim ( $email );

	$keystring = trim ( $keystring );



	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (empty ( $name ))

		$error = $error . '<li>не заполнено поле "Ваше имя"</li>' . "\n";

	if (empty ( $password ))

		$error = $error . '<li>не заполнено поле "Пароль"</li>' . "\n";

	if (empty ( $confirm ))

		$error = $error . '<li>не заполнено поле "Повторите пароль"</li>' . "\n";

	if (empty ( $email ))

		$error = $error . '<li>не заполнено поле "Ваш e-mail"</li>' . "\n";

	if (empty ( $tel1 ))

		$error = $error . '<li>не заполнено поле "Телефон 1"</li>' . "\n";


	if ($region <= 0)

		$error = $error . '<li>не выбран регион</li>' . "\n";

	if ($city <= 0)

		$error = $error . '<li>не выбран город</li>' . "\n";



	if (empty ( $keystring ))

		$error = $error . '<li>не заполнено поле "Введите код"</li>' . "\n";

		// Проверяем, не слишком ли короткий пароль

	if (! empty ( $password ) and strlen ( $password ) < MIN_PASSWORD_LENGTH)

		$error = $error . '<li>длина пароля должна быть не меньше ' . MIN_PASSWORD_LENGTH . ' символов</li>' . "\n";

		// Проверяем, совпадают ли пароли

	if (! empty ( $password ) and ! empty ( $confirm ) and $password != $confirm)

		$error = $error . '<li>не совпадают пароли</li>' . "\n";

		// Проверяем поле "код"

	if (! empty ( $keystring )) {

		// Проверяем поле "код" на недопустимые символы

		if (! ereg ( "[23456789abcdeghkmnpqsuvxyz]+", $keystring ))

			$error = $error . '<li>поле "Код" содержит недопустимые символы</li>' . "\n";

			// Проверяем, совпадает ли код с картинки

		if (! isset ( $_SESSION ['captcha_keystring'] ) or $_SESSION ['captcha_keystring'] != $keystring)

			$error = $error . '<li>не совпадает код с картинки</li>' . "\n";

	}

	unset ( $_SESSION ['captcha_keystring'] );



	// Проверяем поля формы на недопустимые символы

	if (! empty ( $name ) and ! preg_match ( "#^[- _0-9a-zА-Яа-я]+$#i", $name ))

		$error = $error . '<li>поле "Ваше имя" содержит недопустимые символы</li>' . "\n";

	if (! empty ( $password ) and ! preg_match ( "#^[-_0-9a-z]+$#i", $password ))

		$error = $error . '<li>поле "Пароль" содержит недопустимые символы</li>' . "\n";

	if (! empty ( $confirm ) and ! preg_match ( "#^[-_0-9a-z]+$#i", $confirm ))

		$error = $error . '<li>поле "Повторите пароль" содержит недопустимые символы</li>' . "\n";

		//  if ( !empty( $icq ) and !preg_match( "#^[0-9]+$#", $icq ) )

	//   $error = $error.'<li>поле "ICQ" содержит недопустимые символы</li>'."\n";

	//  if ( !empty( $about ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9а-яА-Яa-z]+$#i", $about ) )

	//   $error = $error.'<li>поле "Интересы" содержит недопустимые символы</li>'."\n";

	//  if ( !empty( $signature ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9а-яА-Яa-z]+$#i", $signature ) )

	//   $error = $error.'<li>поле "Подпись" содержит недопустимые символы</li>'."\n";





	// Проверяем корректность e-mail

	if (! empty ( $email ) and ! preg_match ( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ))

		$error = $error . '<li>поле "Ваш e-mail" должно соответствовать формату somebody@somewhere.ru</li>' . "\n";



	// Проверяем корректность URL домашней странички

	//  if ( !empty( $url ) and !preg_match( "#^(http:\/\/)?(www.)?[-0-9a-z]+\.[a-z]{2,6}\/?$#i", $url ) )

	//    $error = $error.'<li>поле "Домашняя страничка" должно соответствовать формату http://www.homepage.ru</li>'."\n";





	// Выясняем не зарегистрировано ли уже это имя

	// Возможно три ситуации, которые необходимо предотвратить:

	// 1. Вводится ник, полностью совпадающий с уже существующим

	// 2. Вводится уже существующий кирилический ник, в котором

	//    одна или несколько букв заменены на латинские

	// 3. Вводится уже существующий латинский ник, в котором

	//    одна или несколько букв заменениы на кирилические





	// Массив кирилических букв

	$rus = array ("А", "а", "В", "Е", "е", "К", "М", "Н", "О", "о", "Р", "р", "С", "с", "Т", "Х", "х" );

	// Массив латинских букв

	$eng = array ("A", "a", "B", "E", "e", "K", "M", "H", "O", "o", "P", "p", "C", "c", "T", "X", "x" );

	$new_name = preg_replace ( "#[^- _0-9a-zА-Яа-я]#i", "", $name );

	// Заменяем русские буквы латинскими

	$eng_new_name = str_replace ( $rus, $eng, $new_name );

	// Заменяем латинские буквы русскими

	$rus_new_name = str_replace ( $eng, $rus, $new_name );

	// Формируем SQL-запрос

	$query = "SELECT * FROM " . TABLE_USERS . "

		    WHERE email LIKE '" . mysql_real_escape_string ( $email ) . "'";

	$res = mysql_query ( $query );



	if (! $res) {

		$msg = 'Ошибка при регистрации нового пользователя';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0)

		$error = $error . '<li>Такой адрес "' . $email . '" уже зарегистрирован</li>' . "\n";

		/*

	if ( !empty( $_FILES['avatar']['name'] ) ) {

	$ext = strrchr( $_FILES['avatar']['name'], "." );

	$extensions = array( ".jpg", ".gif", ".bmp", ".png" );

	if ( !in_array( $ext, $extensions ) )

	$error = $error.'<li>недопустимый формат файла аватара</li>'."\n";

	if ( $_FILES['avatar']['size'] > MAX_AVATAR_SIZE )

	$error = $error.'<li>размер файла аватора больше '.(MAX_AVATAR_SIZE/1024).' Кб</li>'."\n";

	}

	*/





	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {

		$_SESSION ['addNewUserForm'] = array ();

		$_SESSION ['addNewUserForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['addNewUserForm'] ['name'] = $name;

		$_SESSION ['addNewUserForm'] ['email'] = $email;

		$_SESSION ['addNewUserForm'] ['tel1'] = $tel1;

		$_SESSION ['addNewUserForm'] ['tel2'] = $tel2;

		$_SESSION ['addNewUserForm'] ['stel1'] = $stel1;

		$_SESSION ['addNewUserForm'] ['stel2'] = $stel2;

		$_SESSION ['addNewUserForm'] ['dotel1'] = $dotel1;

		$_SESSION ['addNewUserForm'] ['dotel2'] = $dotel2;

		$_SESSION ['addNewUserForm'] ['region'] = $region;

		$_SESSION ['addNewUserForm'] ['city'] = $city;

		//   $_SESSION['addNewUserForm']['timezone'] = $timezone;

		//   $_SESSION['addNewUserForm']['icq'] = $icq;

		//   $_SESSION['addNewUserForm']['url'] = $url;

		//  $_SESSION['addNewUserForm']['about'] = $about;

		//   $_SESSION['addNewUserForm']['signature'] = $signature;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=addNewUser' );

		die ();

	}



	// if ( !empty( $url ) and substr($url, 0, 7) != 'http://' ) $url = 'http://'.$url;





	// Уникальный код для активации учетной записи

	$code = md5 ( uniqid ( rand (), 1 ) );

	// Все поля заполнены правильно - продолжаем регистрацию

	$query = "INSERT INTO " . TABLE_USERS . "

		    (

		    name,

		    passw,

		    email,

			puttime,

			last_visit,

			status,locked,

		    activation,

		    tel1,

		    tel2,

		    nach1,

		    end1,

		    nach2,

		    end2,

			region,

			city

		    )

		    VALUES

		    (

		    '" . mysql_real_escape_string ( $name ) . "',

		    '" . mysql_real_escape_string ( md5 ( $password ) ) . "',

		    '" . mysql_real_escape_string ( $email ) . "',

			NOW(),

			NOW(),

			'user','1',

		    '" . $code . "',

		    '" . mysql_real_escape_string ( $tel1 ) . "',

		    '" . mysql_real_escape_string ( $tel2 ) . "',

		    '" . mysql_real_escape_string ( $stel1 ) . "',

		    '" . mysql_real_escape_string ( $dotel1 ) . "',

		    '" . mysql_real_escape_string ( $stel2 ) . "',

		    '" . mysql_real_escape_string ( $dotel2 ) . "',

		    '" . abs ( intval ( $region ) ) . "',

			'" . abs ( intval ( $city ) ) . "'

		    );";
//плюй
	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при регистрации нового пользователя';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$id = mysql_insert_id ();

	// if ( !empty( $_FILES['avatar']['name'] ) and

	//     move_uploaded_file ( $_FILES['avatar']['tmp_name'], './photo/'.$id ) ) chmod( './photo/'.$id, 0644 );





	// Посылаем письмо пользователю с просьбой активировать учетную запись

	$headers = "From:<" . ADMIN_EMAIL . ">\n";

	$headers = $headers . "Content-type: text/html; charset=\"windows-1251\"\n";

	$headers = $headers . "Return-path: <" . ADMIN_EMAIL . ">\n";

	$message = '<p>Добро пожаловать на vash_domen.Ru!</p>' . "\n";

	$message = $message . '<p>Пожалуйста сохраните это сообщение. Параметры вашей учётной записи таковы:</p>' . "\n";

	$message .= '<p>---------------</p>'. "\n";

	$message = $message . '<p>Логин: ' . $email . '</p><p>Пароль: ' . $password . '</p>' . "\n";

	$message .= '<p>---------------</p>'. "\n";

	$message = $message . '<p>Для активации вашей учетной записи перейдите по ссылке:</p>' . "\n";

	$link = 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['PHP_SELF'] . '?action=activateUser&code=' . $code;

	$message = $message . '<p><a href="' . $link . '">Активировать учетную запись</a></p>' . "\n";

	$message = $message . '<p>Не забывайте свой пароль: он хранится в нашей базе в зашифрованном

             виде, и мы не сможем вам его выслать. Если вы всё же забудете пароль, то сможете

             запросить новый, который придётся активировать таким же образом, как и вашу

             учётную запись.</p>' . "\n";

	$message = $message . '<p>Спасибо, что зарегистрировались на нашем сайте.</p>';

	//$message = $message.'<p></p>';

	$message = $message . '<div>С Уважением,</div><div>Команда vash_domen.Ru</div>';

	$message = $message . '<div>E-mail: <a href="mailto:info@vash_domen.ru">info@vash_domen.ru</a></div>';

	$message = $message . '<div></div>';

	$subject = 'Регистрация на сайте vash_domen.Ru';

	$subject = '=?koi8-r?B?' . base64_encode ( convert_cyr_string ( $subject, "w", "k" ) ) . '?=';

	mail ( $email, $subject, $message, $headers );

	//mail( 'gerasin_pa@mail.ru', $subject, $message, $headers );





	$msg = '<b><center><br><p>На Ваш e-mail выслано письмо с просьбой подтвердить регистрацию.</br>

          Чтобы завершить регистрацию и активировать учетную запись, зайдите

          по адресу, указанному в письме.</p><p><span align=center><a href=/>Перейти на главную страницу</a></span></p><br></center</b>';

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $msg, $html );

	return $html;

}



// Активация учетной записи нового пользователя

function activateUser() {

	// Если не передан параметр $code - значит функция вызвана по ошибке

	if (! isset ( $_GET ['code'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	// Т.к. код зашифрован с помощью md5, то он представляет собой

	// 32-значное шестнадцатеричное число

	$code = substr ( $_GET ['code'], 0, 32 );

	$code = preg_replace ( "#[^0-9a-f]#i", '', $code );



	$query = "SELECT id_author FROM " . TABLE_USERS . " WHERE activation='" . mysql_real_escape_string ( $code ) . "' LIMIT 1";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при активации учетной записи. Обратитесь к администратору.';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		$id = mysql_result ( $res, 0, 0 );

		$query = "UPDATE " . TABLE_USERS . "

	          SET locked='0', activation='', last_visit=NOW()

			  WHERE id_author=" . $id;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при активации учетной записи. Обратитесь к администратору.';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

	}

	$mmm = '<br/>

      <center><p><strong>Ваша учетная запись активирована</strong></p></center>

    <br/>';

	return showInfoMessage ( $mmm, '' );

}



// Если пользователь забыл свой пароль, он может получить новый,

// заполнив эту форму (свой логин и e-mail)

function newPasswordForm() {

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=newPasswordForm">Прислать новый пароль</a></span>';

	$html = '';

	if (isset ( $_SESSION ['newPasswordForm'] ['error'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['newPasswordForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['newPasswordForm'] ['error'] );

	}

	$action = $_SERVER ['PHP_SELF'] . '?action=sendNewPassword';

	$tpl = file_get_contents ( './templates/newPasswordForm.html' );

	$tpl = str_replace ( '{action}', $action, $tpl );

	$html = $html . $tpl;

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}



// Функция высылает на e-mail пользователя новый пароль

function sendNewPassword() {



	// Если не переданы методом POST логин и e-mail - перенаправляем пользователя

	if (! isset ( $_POST ['email'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}



	// Обрезаем переменные до длины, указанной в параметре maxlength тега input





	$email = substr ( $_POST ['email'], 0, 60 );



	// Обрезаем лишние пробелы





	$email = trim ( $email );



	// Проверяем, заполнены ли обязательные поля

	$error = "";



	if (empty ( $email ))

		$error = $error . '<li>не заполнено поле "Адрес e-mail"</li>' . "\n";



	// Проверяем поля формы на недопустимые символы





	// Проверяем корректность e-mail

	if (! empty ( $email ) and ! preg_match ( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ))

		$error = $error . '<li>поле "Адрес e-mail" должно соответствовать формату somebody@somewhere.ru</li>' . "\n";

		// Проверять существование такого пользователя есть смысл только в том

	// случае, если поля не пустые и не содержат недопустимых символов

	if (empty ( $error )) {

		$query = "SELECT id_author FROM " . TABLE_USERS . "

              WHERE email='" . mysql_real_escape_string ( $email ) . "'";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при создании нового пароля.';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, 'action=newPasswordForm' );

		}



		// Если пользователь с таким логином и e-mail существует

		if (mysql_num_rows ( $res ) > 0) {

			// Небольшой код, который читает содержимое директории activate

			// и удаляет старые файлы для активации пароля (были созданы более суток назад)

			if ($dir = @opendir ( '/activate' )) {

				chdir ( '/activate' );

				$tmp = 24 * 60 * 60;

				while ( false !== ($file = readdir ( $dir )) ) {

					if (is_file ( $file ))

						if ((time () - filemtime ( $file )) > $tmp)

							unlink ( $file );

				}

				chdir ( '..' );

				closedir ( $dir );

			}

			// Как происходит процедура восстановления пароля? Пользователь ввел свой логин

			// и e-mail, мы проверяем существование такого пользователя в таблице БД. Потом

			// генерируем с помощью функции getNewPassword() новый пароль, создаем файл с именем

			// md5( $newPassword ) в директории activate. Файл содержит ID пользователя.

			// В качестве кода активации выступает хэш пароля - md5( $newPassword ).

			// Когда пользователь перейдет по ссылке в письме для активации своего нового пароля,

			// мы проверяем наличие в директории activatePassword файла с именем кода активации,

			// и если он существует, активируем новый пароль.

			$id = mysql_result ( $res, 0, 0 );

			$newPassword = getNewPassword ();

			$code = md5 ( $newPassword );

			// file_put_contents( './activate/'.$code, $id );

			$fp = fopen ( 'activate/' . $code, "w" );

			fwrite ( $fp, $id );

			fclose ( $fp );

			// Посылаем письмо пользователю с просьбой активировать пароль

			$headers = "From: " . $_SERVER ['SERVER_NAME'] . " <" . ADMIN_EMAIL . ">\n";

			$headers = $headers . "Content-type: text/html; charset=\"windows-1251\"\n";

			$headers = $headers . "Return-path: <" . ADMIN_EMAIL . ">\n";

			$message = '<p>Здравствуйте!</p>' . "\n";

			$message = $message . '<p>Вы получили это письмо потому, что вы (либо кто-то, выдающий себя за вас) попросили выслать новый пароль к вашей учётной записи на Сайт www.vash_domen.ru. Если вы не просили выслать пароль, то не обращайте внимания на это письмо, если же подобные письма будут продолжать приходить, обратитесь к администратору сайта.</p>' . "\n";

			$message = $message . '<p>Прежде чем использовать новый пароль, вы должны его активировать.

	             Для этого перейдите по ссылке:</p>' . "\n";

			$link = 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['PHP_SELF'] . '?action=activatePassword&code=' . $code;

			$message = $message . '<p><a href="' . $link . '">Активировать пароль</a></p>' . "\n";

			$message = $message . '<p>В случае успешной активации вы сможете входить в систему, используя

	             <b>следующий пароль: ' . $newPassword . '</b></p>' . "\n";

			$message = $message . '<p>Вы сможете сменить этот пароль на странице редактирования профиля.

	             Если у вас возникнут какие-то трудности, обратитесь к администратору сайта.</p>' . "\n";

	$message = $message . '<div>С Уважением,</div><div>Команда vash_domen.Ru</div>';

	$message = $message . '<div>E-mail: <a href="mailto:info@vash_domen.ru">info@vash_domen.ru</a></div>';

	$message = $message . '<div></div>';

			$subject = 'Активация пароля на сайте ' . $_SERVER ['SERVER_NAME'];

			$subject = '=?koi8-r?B?' . base64_encode ( convert_cyr_string ( $subject, "w", "k" ) ) . '?=';

			mail ( $email, $subject, $message, $headers );



			$msg = '<center><p><b>На ваш e-mail выслано письмо. Чтобы активировать новый пароль, зайдите

	          по адресу, указанному в письме.</br><a href=/>Перейти на главную страницу</b></a></p></center>';

			$html = file_get_contents ( './templates/infoMessage.html' );

			$html = str_replace ( '{infoMessage}', $msg, $html );



			return $html;

		} else {

			$error = $error . '<li>неправильный e-mail</li>' . "\n";

		}

	}



	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя

	if (! empty ( $error )) {

		$_SESSION ['newPasswordForm'] = array ();

		$_SESSION ['newPasswordForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены

    ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=newPasswordForm' );

		die ();

	}



}



// Активация нового пароля

function activatePassword() {

	if (! isset ( $_GET ['code'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	// Т.к. код активации создан с помощью md5, то он

	// представляет собой 32-значное шестнадцатеричное число

	$code = substr ( $_GET ['code'], 0, 32 );

	$code = preg_replace ( "#[^0-9a-f]#i", '', $code );

	if (is_file ( 'activate/' . $code ) and ((time () - filemtime ( 'activate/' . $code )) < 24 * 60 * 60)) {

		$file = file ( 'activate/' . $code );

		unlink ( 'activate/' . $code );

		$id_user = ( int ) trim ( $file [0] );

		$query = "UPDATE " . TABLE_USERS . "

              SET passw='" . mysql_real_escape_string ( $code ) . "'

			  WHERE id_author=" . $id_user;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при активации нового пароля. Обратитесь к администратору.';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, 'action=newPasswordForm' );

		}
	$message = '<br/>

      <center><p><strong>Ваш новый пароль успешно активирован.</strong></p></center>

    <br/>';


	} else {
	$message = '<br/>

      <center><p><strong>Ошибка при активации нового пароля. Обратитесь к администратору.</strong></p></center>

    <br/>';

	}



	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $message, $html );

	return $html;

}



// Функция возвращает случайно сгенерированный пароль

function getNewPassword() {

	//$length = rand( 5 );

	$password = '';

	for($i = 0; $i < 5; $i ++) {

		$range = rand ( 1, 3 );

		switch ($range) {

			case 1 :

				$password = $password . chr ( rand ( 48, 57 ) );

				break;

			case 2 :

				$password = $password . chr ( rand ( 65, 90 ) );

				break;

			case 3 :

				$password = $password . chr ( rand ( 97, 122 ) );

				break;

		}

	}

	return $password;

}



// Функция возвращает html формы для редактирования данных о пользователе

function getEditUserForm() {

	// Если информацию о пользователе пытается редактировать

	// не зарегистрированный пользователь

	if (! isset ( $_SESSION ['user'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}



	if ($_SESSION ['user'] ['status'] === "autosaloon") {

		header ( 'Location: http://' . $_SERVER ['SERVER_NAME'] . "/control" );

		die ();

	}



	$html = '';



	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['editUserForm'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['editUserForm'] ['error'], $info );

		$html = $html . $info . "\n";

		$email = htmlspecialchars ( $_SESSION ['user'] ['email'] );

		$name = htmlspecialchars ( $_SESSION ['editUserForm'] ['name'] );



		$tel1 = htmlspecialchars ( $_SESSION ['editUserForm'] ['tel1'] );

		$tel2 = htmlspecialchars ( $_SESSION ['editUserForm'] ['tel2'] );

		$nach1 = htmlspecialchars ( $_SESSION ['editUserForm'] ['nach1'] );

		$nach2 = htmlspecialchars ( $_SESSION ['editUserForm'] ['nach2'] );

		$end1 = htmlspecialchars ( $_SESSION ['editUserForm'] ['end1'] );

		$end2 = htmlspecialchars ( $_SESSION ['editUserForm'] ['end2'] );

		//$timezone  = $_SESSION['editUserForm']['timezone'];

		//$icq       = htmlspecialchars( $_SESSION['editUserForm']['icq'] );

		//$url       = htmlspecialchars( $_SESSION['editUserForm']['url'] );

		//$about     = htmlspecialchars( $_SESSION['editUserForm']['about'] );

		//$signature = htmlspecialchars( $_SESSION['editUserForm']['signature'] );

		unset ( $_SESSION ['editUserForm'] );

	} else {

		$email = htmlspecialchars ( $_SESSION ['user'] ['email'] );

		$name = htmlspecialchars ( $_SESSION ['user'] ['name'] );

		$tel1 = htmlspecialchars ( $_SESSION ['user'] ['tel1'] );

		$tel2 = htmlspecialchars ( $_SESSION ['user'] ['tel2'] );

		$stel1 = htmlspecialchars ( $_SESSION ['user'] ['nach1'] );

		$stel2 = htmlspecialchars ( $_SESSION ['user'] ['nach2'] );

		$dotel1 = htmlspecialchars ( $_SESSION ['user'] ['end1'] );

		$dotel2 = htmlspecialchars ( $_SESSION ['user'] ['end2'] );

		//  $timezone  = $_SESSION['user']['timezone'];

	//  $icq       = htmlspecialchars( $_SESSION['user']['icq'] );

	//  $url       = htmlspecialchars( $_SESSION['user']['url'] );

	//  $about     = htmlspecialchars( $_SESSION['user']['about'] );

	//  $signature = htmlspecialchars( $_SESSION['user']['signature'] );

	}



	$action = $_SERVER ['PHP_SELF'] . '?action=updateUser';



	$tpl = file_get_contents ( './templates/editUserForm.html' );

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{email}', $email, $tpl );

	$tpl = str_replace ( '{name}', htmlspecialchars ( $name ), $tpl );

	$tpl = str_replace ( '{tel1}', htmlspecialchars ( $tel1 ), $tpl );

	$tpl = str_replace ( '{tel2}', htmlspecialchars ( $tel2 ), $tpl );

	$tpl = str_replace ( '{stel1}', htmlspecialchars ( $stel1 ), $tpl );

	$tpl = str_replace ( '{stel2}', htmlspecialchars ( $stel2 ), $tpl );

	$tpl = str_replace ( '{dotel1}', htmlspecialchars ( $dotel1 ), $tpl );

	$tpl = str_replace ( '{dotel2}', htmlspecialchars ( $dotel2 ), $tpl );



	//$tpl = str_replace( '{icq}', htmlspecialchars( $icq ), $tpl );

	//$tpl = str_replace( '{url}', htmlspecialchars( $url ), $tpl );

	//$tpl = str_replace( '{about}', htmlspecialchars( $about ), $tpl );

	//$tpl = str_replace( '{signature}', htmlspecialchars( $signature ), $tpl );

	/*

	$options = '';

	for ( $i = -12; $i <= 12; $i++ ) {

	if ( $i < 1 )

	$value = $i.' часов';

	else

	$value = '+'.$i.' часов';

	if ( $i == $_SESSION['user']['timezone'] )

	$options = $options . '<option value="'.$i.'" selected>'.$value.'</option>'."\n";

	else

	$options = $options . '<option value="'.$i.'">'.$value.'</option>'."\n";

	}

	$tpl = str_replace( '{options}', $options, $tpl);

	$tpl = str_replace( '{servertime}', date( "d.m.Y H:i:s" ), $tpl );

	// Если ранее был загружен файл - надо предоставить возможность удалить его

	$unlinkfile = '';

	if ( is_file( './photo/'.$_SESSION['user']['id_author'] ) ) {

	$unlinkfile = '<br/><input type="checkbox" name="unlink" value="1" />

	Удалить загруженный ранее файл'."\n";

	}

	$tpl = str_replace( '{unlinkfile}', $unlinkfile, $tpl );

	*/

	$html = $html . $tpl;



	return $html;

}



// Функция обновляет данные пользователя (обновляет запись в таблице TABLE_USERS)

function updateUser() {

	// Если это не зарегистрированный пользователь - функция вызвана по ошибке

	if (! isset ( $_SESSION ['user'] )) {

		//header( 'Location: '.$_SERVER['PHP_SELF'] );

		die ();

	}



	// Если не переданы данные формы - функция вызвана по ошибке

	if (! isset ( $_POST ['name'] ) or ! isset ( $_POST ['tel1'] )) {

		//header( 'Location: '.$_SERVER['PHP_SELF'] );

		die ();

	}



	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$password = substr ( $_POST ['password'], 0, 30 );

	$newpassword = substr ( $_POST ['newpassword'], 0, 30 );

	$confirm = substr ( $_POST ['confirm'], 0, 30 );

	//$email        = substr( $_POST['email'], 0, 60 );

	$name = substr ( $_POST ['name'], 0, 30 );

	$tel1 = substr ( $_POST ['tel1'], 0, 20 );

	$tel2 = substr ( $_POST ['tel2'], 0, 20 );

	$stel1 = substr ( $_POST ['stel1'], 0, 2 );

	$stel2 = substr ( $_POST ['stel2'], 0, 2 );

	$dotel1 = substr ( $_POST ['dotel1'], 0, 2 );

	$dotel2 = substr ( $_POST ['dotel2'], 0, 2 );



	// Обрезаем лишние пробелы

	$password = trim ( $password );

	$newpassword = trim ( $newpassword );

	$confirm = trim ( $confirm );

	$name = trim ( $name );



	// Проверяем, заполнены ли обязательные поля

	$error = '';



	// Если заполнено поле "Текущий пароль" - значит пользователь

	// хочет изменить его или поменять свой e-mail

	$changePassword = false;

	// $changeEmail = false;

	if (! empty ( $_POST ['password'] )) {

		if (md5 ( $_POST ['password'] ) != $_SESSION ['user'] ['passw'])

			$error = $error . '<li>текущий пароль введен не верно</li>' . "\n";

			// Надо выяснить, что хочет сделать пользователь:

		// поменять свой e-mail, изменить пароль или и то и другое

		if (! empty ( $newpassword )) { // хочет изменить пароль

			$changePassword = true;

			if (empty ( $confirm ))

				$error = $error . '<li>не заполнено поле "Подтвердите пароль"</li>' . "\n";

				// Проверяем, не слишком ли короткий новый пароль

			if (strlen ( $newpassword ) < MIN_PASSWORD_LENGTH)

				$error = $error . '<li>длина пароля должна быть не меньше ' . MIN_PASSWORD_LENGTH . ' символов</li>' . "\n";

				// Проверяем, совпадают ли пароли

			if (! empty ( $confirm ) and $newpassword != $confirm)

				$error = $error . '<li>не совпадают пароли</li>' . "\n";

				// Проверяем поля формы на недопустимые символы

			if (! preg_match ( "#^[-_0-9a-z]+$#i", $newpassword ))

				$error = $error . '<li>поле "Новый пароль" содержит недопустимые символы</li>' . "\n";

			if (! empty ( $confirm ) and ! preg_match ( "#^[-_0-9a-z]+$#i", $confirm ))

				$error = $error . '<li>поле "Подтвердите пароль" содержит недопустимые символы</li>' . "\n";

		}

		/*

		if ( $email != $_SESSION['user']['email'] ) { // хочет изменить e-mail

		$changeEmail = true;

		if ( empty( $email ) ) $error = $error.'<li>не заполнено поле "Адрес e-mail"</li>'."\n";

		// Проверяем корректность e-mail

		if ( !empty( $email ) and !preg_match( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ) )

		$error = $error.'<li>поле "Адрес e-mail" должно соответствовать формату

		somebody@somewhere.ru</li>'."\n";

		}

		*/

	}



	// Проверяем поля формы на недопустимые символы

	if (empty ( $name ))

		$error = $error . '<li>Имя продавца должно быть заполнено</li>' . "\n";

	if (empty ( $tel1 ))

		$error = $error . '<li>Поле Телефон 1 должно быть заполнено</li>' . "\n";

		/*

	if ( !empty( $icq ) and !preg_match( "#^[0-9]+$#", $icq ) )

	$error = $error.'<li>поле "ICQ" содержит недопустимые символы</li>'."\n";

	if ( !empty( $about ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9а-яА-Яa-z]+$#i", $about ) )

	$error = $error.'<li>поле "Интересы" содержит недопустимые символы</li>'."\n";

	if ( !empty( $signature ) and !preg_match( "#^[-\[\].;:,?!\/)(_\"\s0-9а-яА-Яa-z]+$#i", $signature ) )

	$error = $error.'<li>поле "Подпись" содержит недопустимые символы</li>'."\n";



	// Проверяем корректность URL домашней странички

	if ( !empty( $url ) and !preg_match( "#^(http:\/\/)?(www.)?[-0-9a-z]+\.[a-z]{2,6}\/?$#i", $url ) )

	$error = $error.'<li>поле "Домашняя страничка" должно соответствовать формату http://www.homepage.ru</li>'."\n";



	if ( !empty( $_FILES['avatar']['name'] ) ) {

	$ext = strrchr( $_FILES['avatar']['name'], "." );

	$extensions = array( ".jpg", ".gif", ".bmp", ".png" );

	if ( !in_array( $ext, $extensions ) )

	$error = $error.'<li>недопустимый формат файла аватара</li>'."\n";

	if ( $_FILES['avatar']['size'] > MAX_AVATAR_SIZE )

	$error = $error.'<li>размер файла аватора больше '.(MAX_AVATAR_SIZE/1024).' Кб</li>'."\n";

	}



	$timezone = (int)$_POST['timezone'];

	if ( $timezone < -12 or $timezone > 12 ) $timezone = 0;

	*/

	// Если были допущены ошибки при заполнении формы -

	// перенаправляем посетителя на страницу редактирования

	if (! empty ( $error )) {

		$_SESSION ['editUserForm'] = array ();

		$_SESSION ['editUserForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['editUserForm'] ['email'] = $email;

		$_SESSION ['editUserForm'] ['name'] = $name;

		$_SESSION ['editUserForm'] ['tel1'] = $tel1;

		$_SESSION ['editUserForm'] ['tel2'] = $email;

		$_SESSION ['editUserForm'] ['nach1'] = $stel1;

		$_SESSION ['editUserForm'] ['nach2'] = $stel2;

		$_SESSION ['editUserForm'] ['end1'] = $dotel1;

		$_SESSION ['editUserForm'] ['end2'] = $dotel2;



		// $_SESSION['editUserForm']['timezone'] = $timezone;

		// $_SESSION['editUserForm']['icq'] = $icq;

		// $_SESSION['editUserForm']['url'] = $url;

		// $_SESSION['editUserForm']['about'] = $about;

		// $_SESSION['editUserForm']['signature'] = $signature;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=editUserForm' );

		die ();

	}

	/*

	// Если выставлен флажок "Удалить загруженный ранее файл"

	if ( isset( $_POST['unlink'] ) and is_file( './photo/'.$_SESSION['user']['id_author'] ) ) {

	unlink( './photo/'.$_SESSION['user']['id_author'] );

	}

	if ( !empty( $_FILES['avatar']['name'] ) and

	move_uploaded_file ( $_FILES['avatar']['tmp_name'], './photo/'.$_SESSION['user']['id_author'] ) ) {

	chmod( './photo/'.$_SESSION['user']['id_author'], 0644 );

	}

	*/

	// Все поля заполнены правильно - записываем изменения в БД

	$tmp = '';

	if ($changePassword) {

		$tmp = $tmp . "passw='" . mysql_real_escape_string ( md5 ( $newpassword ) ) . "', ";

		$_SESSION ['user'] ['passw'] = md5 ( $newpassword );

	}

	/*

	if ( $changeEmail ) {

	$tmp = $tmp."email='".mysql_real_escape_string( $email )."', ";

	$_SESSION['user']['email'] = $email;

	}

	*/

	$query = "UPDATE " . TABLE_USERS . " SET " . $tmp . "

		    name='" . mysql_real_escape_string ( $name ) . "',

		    tel1='" . $tel1 . "',

		    tel2='" . $tel2 . "',

		    nach1='" . mysql_real_escape_string ( $stel1 ) . "',

		    nach2='" . mysql_real_escape_string ( $stel2 ) . "',

		    end1='" . mysql_real_escape_string ( $dotel1 ) . "',

		    end2='" . mysql_real_escape_string ( $dotel2 ) . "'

		    WHERE id_author=" . $_SESSION ['user'] ['id_author'];

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при обновлении профиля';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	// Теперь надо обновить данные о пользователе в массиве $_SESSION['user']

	if ($changePassword)

		$_SESSION ['user'] ['passw'] = md5 ( $newpassword );

		// if ( $changeEmail ) $_SESSION['user']['email'] = $email;

	$_SESSION ['user'] ['name'] = $name;

	$_SESSION ['user'] ['tel1'] = $tel1;

	$_SESSION ['user'] ['tel2'] = $tel2;

	$_SESSION ['user'] ['nach1'] = $stel1;

	$_SESSION ['user'] ['nach2'] = $stel2;

	$_SESSION ['user'] ['end1'] = $dotel1;

	$_SESSION ['user'] ['end2'] = $dotel2;



	//$_SESSION['user']['signature'] = $signature;

	// ... и в массиве $_COOKIE

	if (isset ( $_COOKIE ['autologin'] )) {

		$tmppos = strrpos ( $_SERVER ['PHP_SELF'], '/' ) + 1;

		$path = substr ( $_SERVER ['PHP_SELF'], 0, $tmppos );

		setcookie ( 'autologin', 'yes', time () + 3600 * 24 * COOKIE_TIME, $path );

		setcookie ( 'username', $_SESSION ['user'] ['email'], time () + 3600 * 24 * COOKIE_TIME, $path );

		setcookie ( 'password', $_SESSION ['user'] ['passw'], time () + 3600 * 24 * COOKIE_TIME, $path );

	}



	return showInfoMessage ( 'Ваш профиль был изменён', '' );

}



function save_log($type, $type_user, $user) {



	/*

	if (($_SESSION["LOG"][$user]!=$user)

	and ($_SESSION["LOG"][$type]!=$type)

	and ($_SESSION["LOG"][$type_user]!=$type_user)

	)*/



	if (isset ( $type ) and isset ( $type_user ) and isset ( $user )) {



		if (getenv ( 'HTTP_X_FORWARDED_FOR' )) {

			$ip = getenv ( 'HTTP_X_FORWARDED_FOR' );

		}

		{

			$ip = getenv ( 'REMOTE_ADDR' );

		}

		if (! $ip) {

			$ip = "unknown";

		}



		$date_1 = time ();

		$vdate_1 = strftime ( '%Y-%m-%d', $date_1 );



		if (($type_user == "saloon") and (! isset ( $_SESSION ["LOG"] [$user] [$type] ))) {

			$query = "INSERT INTO AUTO_LOGS_SALOON

            (

			ID_SALOON,

			IP,

			TYPE,

			DATE

			)

			VALUES

			(

			'" . $user . "',

			'" . $ip . "',

			'" . $type . "',

			'" . $vdate_1 . "'

			)";



			$res = @mysql_query ( $query );

			if ($res) {

				$_SESSION ["LOG"] [$user] [$type] = 1;

			}

		} elseif (($type_user == "user") and (! isset ( $_SESSION ["LOG_CAR"] [$user] [$type] ))) {

			$query = "INSERT INTO AUTO_LOGS

            (

			ID_CAR,

			IP,

			DATE

			)

			VALUES

			(

			'" . $user . "',

			'" . $ip . "',

			'" . $vdate_1 . "'

			)";

			$res = @mysql_query ( $query );

			if ($res) {

				$_SESSION ["LOG_CAR"] [$user] [$type] = 1;

			}

		}



	}



}



// Функция возврашает информацию о пользователе; ID пользователя передается методом GET





// Функция возвращает html формы для отправки личного сообщения

function getSendMsgForm() {

	// Незарегистрированный пользователь не может отправлять личные сообщения





	/*if ( !isset( $_SESSION['user'] ) ) {

	header( 'Location: '.$_SERVER['PHP_SELF'] );

	die();

	}

	*/



	$html = '<h1>Личные сообщения</h1>' . "\n";

	$html = $html . getMessagesMenu ();



	$toUser = '';

	if (isset ( $_GET ['idUser'] )) {

		$id = ( int ) $_GET ['idUser'];

		if ($id > 0) {

			$query = "SELECT name FROM " . TABLE_USERS . " WHERE id_author=" . $id;

			$res = mysql_query ( $query );

			if ($res) {

				if (mysql_num_rows ( $res ) > 0)

					$toUser = mysql_result ( $res, 0, 0 );

			}

		}

	}

	$subject = '';

	$message = '';



	if (isset ( $_SESSION ['viewMessage'] ) and ! empty ( $_SESSION ['viewMessage'] ['message'] )) {

		$view = file_get_contents ( './templates/previewMessage.html' );

		$view = str_replace ( '{message}', print_page ( $_SESSION ['viewMessage'] ['message'] ), $view );

		$html = $html . $view . "\n";

		$toUser = htmlspecialchars ( $_SESSION ['viewMessage'] ['toUser'] );

		$subject = htmlspecialchars ( $_SESSION ['viewMessage'] ['subject'] );

		$message = htmlspecialchars ( $_SESSION ['viewMessage'] ['message'] );

		unset ( $_SESSION ['viewMessage'] );

	}



	$action = $_SERVER ['PHP_SELF'] . '?action=sendMessage';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['sendMessageForm'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['sendMessageForm'] ['error'], $info );

		$html = $html . $info . "\n";

		$toUser = htmlspecialchars ( $_SESSION ['sendMessageForm'] ['toUser'] );

		$subject = htmlspecialchars ( $_SESSION ['sendMessageForm'] ['subject'] );

		$message = htmlspecialchars ( $_SESSION ['sendMessageForm'] ['message'] );

		unset ( $_SESSION ['sendMessageForm'] );

	}



	$tpl = file_get_contents ( './templates/sendMessageForm.html' );

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{toUser}', $toUser, $tpl );

	$tpl = str_replace ( '{subject}', $subject, $tpl );

	$tpl = str_replace ( '{message}', $message, $tpl );



	$html = $html . $tpl;



	return $html;

}



// Отправка личного сообщения (добавляется новая запись в таблицу БД TABLE_MESSAGES)

function sendMessage() {

	// Незарегистрированный пользователь не может отправлять личные сообщения

	if (! isset ( $_SESSION ['user'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}



	// Если не переданы данные формы - функция вызвана по ошибке

	if (! isset ( $_POST ['toUser'] ) or ! isset ( $_POST ['subject'] ) or ! isset ( $_POST ['message'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}



	$msgLen = strlen ( $_POST ['message'] );



	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$toUser = substr ( $_POST ['toUser'], 0, 30 );

	$subject = substr ( $_POST ['subject'], 0, 60 );

	$message = substr ( $_POST ['message'], 0, MAX_MESSAGE_LENGTH );

	// Обрезаем лишние пробелы

	$toUser = trim ( $toUser );

	$subject = trim ( $subject );

	$message = trim ( $message );



	// Если пользователь хочет посмотреть на сообщение перед отправкой

	if (isset ( $_POST ['viewMessage'] )) {

		$_SESSION ['viewMessage'] = array ();

		$_SESSION ['viewMessage'] ['toUser'] = $toUser;

		$_SESSION ['viewMessage'] ['subject'] = $subject;

		$_SESSION ['viewMessage'] ['message'] = $message;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=sendMsgForm' );

		die ();

	}



	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (empty ( $toUser ))

		$error = $error . '<li>не заполнено поле "Для пользователя"</li>' . "\n";

	if (empty ( $subject ))

		$error = $error . '<li>не заполнено поле "Заголовок сообщения"</li>' . "\n";

	if (empty ( $message ))

		$error = $error . '<li>не заполнено поле "Текст сообщения"</li>' . "\n";

	if ($msgLen > MAX_MESSAGE_LENGTH)

		$error = $error . '<li>длина сообщения больше ' . MAX_MESSAGE_LENGTH . ' символов</li>' . "\n";

		// Проверяем поля формы на недопустимые символы

	if (! empty ( $toUser ) and ! preg_match ( "#^[- _0-9a-zА-Яа-я]+$#i", $toUser ))

		$error = $error . '<li>поле "Для пользователя" содержит недопустимые символы</li>' . "\n";

	if (! empty ( $subject ) and ! preg_match ( "#^[-.;:,?!\/)(_\"\s0-9а-яА-Яa-z]+$#i", $subject ))

		$error = $error . '<li>поле "Заголовок сообщения" содержит недопустимые символы</li>' . "\n";

		// Проверяем, есть ли такой пользователь

	if (! empty ( $toUser )) {

		$to = preg_replace ( "#[^- _0-9a-zА-Яа-я]#i", '', $toUser );

		$query = "SELECT id_author FROM " . TABLE_USERS . " WHERE name='" . $to . "' LIMIT 1";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Произошла ошибка при отправке сообщения';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, 'action=sendMsgForm' );

		}

		if (mysql_num_rows ( $res ) == 0)

			$error = $error . '<li>пользователь с именем <strong>' . $to . '</strong> не зарегистрирован</li>' . "\n";

		if ((mysql_num_rows ( $res ) == 1) and (mysql_result ( $res, 0, 0 ) == $_SESSION ['user'] ['id_author']))

			$error = $error . '<li>нельзя послать сообщение самому себе</li>' . "\n";

	}

	// Если были допущены ошибки при заполнении формы -

	// перенаправляем посетителя для исправления ошибок

	if (! empty ( $error )) {

		$_SESSION ['sendMessageForm'] = array ();

		$_SESSION ['sendMessageForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['sendMessageForm'] ['toUser'] = $toUser;

		$_SESSION ['sendMessageForm'] ['subject'] = $subject;

		$_SESSION ['sendMessageForm'] ['message'] = $message;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=sendMsgForm' );

		die ();

	}



	// Все поля заполнены правильно - "посылаем" сообщение

	$to = mysql_result ( $res, 0, 0 );

	$from = $_SESSION ['user'] ['id_author'];



	$query = "INSERT INTO " . TABLE_MESSAGES . "

            VALUES

			(

			NULL,

			" . $to . ",

			" . $from . ",

            NOW(),

			'" . mysql_real_escape_string ( $subject ) . "',

			'" . mysql_real_escape_string ( $message ) . "',

            0,

            0

			)";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Произошла ошибка при отправке сообщения';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, 'action=showOutBox' );

	}



	return showInfoMessage ( 'Ваше сообщение успешно отправлено', 'action=showOutBox' );

}



// Функция возвращает html формы для отправки письма через форум

function getSendMailForm() {

	// Если письмо пытается отправить незарегистрированный пользователь

	$_SESSION ['pageTitle'] = "Написать письмо";



	$_SESSION ['url'] = $_SERVER ['PHP_SELF'] . "?action=sendMailForm&idUser=" . $_GET ['idUser'];
/*
	if (! isset ( $_SESSION ['user'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . "?action=loginForm" );

		die ();



	}
*/
		if (isset ( $_SESSION ['captcha_keystring'] ))

		unset ( $_SESSION ['captcha_keystring'] );

	$html = '';



	$toUser = '';

	if (isset ( $_GET ['idUser'] )) {

		$id = ( int ) $_GET ['idUser'];

		if ($id > 0) {

			$query = "SELECT name,email FROM " . TABLE_USERS . " WHERE id_author=" . $id;

			$res = mysql_query ( $query );

			if ($res) {

				if (mysql_num_rows ( $res ) > 0) {

					$toUser = mysql_result ( $res, 0, 0 );

					$toMail = mysql_result ( $res, 0, 1 );

				}

			}

		}

	}

	$subject = '';

	$message = '';



	$action = $_SERVER ['PHP_SELF'] . '?action=sendMail';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['sendMailForm'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['sendMailForm'] ['error'], $info );

		$html = $html . $info . "\n";

		$toUser = htmlspecialchars ( $_SESSION ['sendMailForm'] ['toUser'] );

		$UserID = htmlspecialchars ( $_SESSION ['sendMailForm'] ['UserID'] );

		$subject = htmlspecialchars ( $_SESSION ['sendMailForm'] ['subject'] );

		$message = htmlspecialchars ( $_SESSION ['sendMailForm'] ['message'] );

		unset ( $_SESSION ['sendMailForm'] );

	}

	//$_SESSION['sendMail']=$toMail;

	$tpl = file_get_contents ( 'templates/sendMailForm.html' );

	$tpl = str_replace ( '{UserID}', $id, $tpl );

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{toUser}', $toUser, $tpl );

	$tpl = str_replace ( '{subject}', $subject, $tpl );

	$tpl = str_replace ( '{message}', $message, $tpl );


	$kcaptcha = './kcaptcha/kc.php?' . session_name () . '=' . session_id ();

	$tpl = str_replace ( '{kcaptcha}', $kcaptcha, $tpl );

	$tpl = str_replace ( '{keystring}', '', $tpl );


	$html = $html . $tpl;

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="">Написать письмо</a></span>';
$html = str_replace ( '{path}', $Gpath, $html );
	return $html;

}



// Отправка письма пользователю сайта

function sendMail() {

	// Если не переданы данные формы - функция вызвана по ошибке

	if (! isset ( $_POST ['subject'] ) or ! isset ( $_POST ['message'] ))
// or ! isset ( $_POST ['UserID'] )


	{

		//	echo $_POST['toUser'];

		//	echo $_POST['subject'];

		//	echo $_POST['message'];

		//echo $_SESSION['sendMail'];

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	// Если письмо пытается отправить незарегистрированный пользователь





	$_SESSION ['url'] = $_SERVER ['PHP_SELF'] . "?action=sendMailForm&idUser=" . $_POST ['UserID'];
/*
	if (! isset ( $_SESSION ['user'] )) {

		header ( 'Location:' . $_SERVER ['PHP_SELF'] . "?action=loginForm" );

		die ();



	}
*/


	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$toUser = $_POST ['UserID'];

	$toUser1 = $_POST ['toUser'];

	$subject = substr ( $_POST ['subject'], 0, 60 );

	$message = substr ( $_POST ['message'], 0, MAX_MAILBODY_LENGTH );

	$keystring = substr ( $_POST ['keystring'], 0, 6 );

	// Обрезаем лишние пробелы

	//$toUser  = trim( $toUser );

	$subject = trim ( $subject );

	$message = trim ( $message );

	$keystring = trim ( $keystring );

	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (empty ( $toUser ))

		$error = $error . '<li>не заполнено поле "Для пользователя"</li>' . "\n";

	if (empty ( $subject ))

		$error = $error . '<li>не заполнено поле "Заголовок письма"</li>' . "\n";

	if (empty ( $message ))

		$error = $error . '<li>не заполнено поле "Текст письма"</li>' . "\n";

	if (empty ( $keystring ))

		$error = $error . '<li>не заполнено поле "Код"</li>' . "\n";

			// Проверяем, не слишком ли короткий пароль

	if (! empty ( $keystring )) {

			// Проверяем поле "код" на недопустимые символы

		if (! ereg ( "[23456789abcdeghkmnpqsuvxyz]+", $keystring ))

			$error = $error . '<li>поле "Код" содержит недопустимые символы</li>' . "\n";

				// Проверяем, совпадает ли код с картинки

		if (! isset ( $_SESSION ['captcha_keystring'] ) or $_SESSION ['captcha_keystring'] != $keystring)

				$error = $error . '<li>не совпадает код с картинки</li>' . "\n";

	}

	unset ( $_SESSION ['captcha_keystring'] );

		// Проверяем поля формы на недопустимые символы

	// if ( !empty( $toUser ) and !preg_match( "#^[- _0-9a-zА-Яа-я]+$#i", $toUser ) )

	//   $error = $error.'<li>поле "Для пользователя" содержит недопустимые символы</li>'."\n";

	if (! empty ( $subject ) and ! preg_match ( "#^[-.;:,?!\/)(_\"\s0-9а-яА-Яa-z]+$#i", $subject ))

		$error = $error . '<li>поле "Заголовок письма" содержит недопустимые символы</li>' . "\n";

		// Проверяем, есть ли такой пользователь

	/*

	*/

	if (! empty ( $toUser )) {

		//$to = preg_replace( "#[^- _0-9a-zа-яА-Я]#i", '', $toUser );

		$query = "SELECT id_author, name, email FROM " . TABLE_USERS . " WHERE id_author='" . $toUser . "' LIMIT 1";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Произошла ошибка при отправке письма';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		if (mysql_num_rows ( $res ) == 0)

			$error = $error . '<li>пользователь с именем <strong>' . $to . '</strong> не зарегистрирован</li>' . "\n";

	}



	// Если были допущены ошибки при заполнении формы -

	// перенаправляем посетителя для исправления ошибок

	if (! empty ( $error )) {

		$_SESSION ['sendMailForm'] = array ();

		$_SESSION ['sendMailForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['sendMailForm'] ['toUser'] = $toUser1;

		$_SESSION ['sendMailForm'] ['UserID'] = $toUser;

		$_SESSION ['sendMailForm'] ['subject'] = $subject;

		$_SESSION ['sendMailForm'] ['message'] = $message;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=sendMailForm&idUser=' . $toUser );

		die ();

	}



	$toUser = mysql_fetch_array ( $res );



	//$toUser = $_SESSION['sendMail'] ;

	$fromUser = $_SESSION ['user'] ['name'];



	$message = '<p>Тема: ' . $subject . "</p><p>" . $message . "</p>";

	//$link = 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ['PHP_SELF'] . '?action=sendMailForm&idUser=' . $_SESSION ['user'] ['id_author'];

	//$message = $message.'<p><a href="'.$link.'">[ОТВЕТИТЬ]</a></p>'."\n";

	//$message .= '<p>Для ответа пользователю ' . $fromUser . ' перейдите по ссылке <a href=' . $link . '> [ Ответить ]</a>' . "\n";

	// формируем заголовки письма

	$headers = "From: " . $_SERVER ['SERVER_NAME'] . " <" . ADMIN_EMAIL . ">\n";

	$headers = $headers . "Content-type: text/html; charset=\"windows-1251\"\n";



	$headers = $headers . "Return-path: <" . ADMIN_EMAIL . ">\n";

	$subject = 'Письмо с автомобильного портала "СИБИРЬ-АВТО"';

	$subject = '=?koi8-r?B?' . base64_encode ( convert_cyr_string ( $subject, "w", "k" ) ) . '?=';

	if (mail ( $toUser ['email'], $subject, $message, $headers ))

		return showInfoMessage ( '<b><center><br><p>Ваше письмо успешно отправлено</p><br></center></b>', '' );

	else

		return showInfoMessage ( '<b><center><br><p>Произошла ошибка при отправке письма</p><br></center></b>', '' );

}



// Вспомогательная функция - после выполнения пользователем каких-либо действий

// выдает информационное сообщение и делает редирект на нужную страницу с задержкой

function showInfoMessage($message, $queryString) {

	if (! empty ( $queryString ))

		$queryString = '?' . $queryString;

	header ( 'Refresh: ' . REDIRECT_DELAY . '; url=' . $_SERVER ['PHP_SELF'] . $queryString );

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $message, $html );

	return $html;

}



// Вспомогательная функция - выдает сообщение об ошибке

// и делает редирект на нужную страницу с задержкой

function showErrorMessage($message = '', $error = '', $redirect = false, $queryString = '') {

	if ($redirect) {

		if (! empty ( $queryString ))

			$queryString = '?' . $queryString;

		header ( 'Refresh: ' . REDIRECT_DELAY . '; url=' . $_SERVER ['PHP_SELF'] . $queryString );

	}

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $message, $html );

	if (DEBUG_MODE or 1) {

		$tpl = file_get_contents ( './templates/errorMessage.html' );

		$tpl = str_replace ( '{errorMessage}', $error, $tpl );

		$html = $html . $tpl . "\n";

	}

	unset ( $_SESSION ['searchForm'] );

	return $html;

}



// Эта функция производит обновление времени последнего посещения зарегистрированного

// пользователя. Вызывается при каждом просмотре страницы форума зарегестрированным

// пользователем (если пользователь авторизовался)

function setTimeVisit() {

	$query = "UPDATE " . TABLE_USERS . "

	        SET last_visit=NOW()

			WHERE id_author=" . $_SESSION ['user'] ['id_author'];

	mysql_query ( $query );

}

//Запчасти и товары

function addSparesSubmit() {




	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}


	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input


	$name = substr ( $_POST ['x_FIO'], 0, 60 );

	$email = substr ( $_POST ['x_mail'], 0, 60 );

	$tel1 = substr ( $_POST ['x_tel1'], 0, 40 );


	$zag = substr ( $_POST ['x_ZAG'], 0, 60 );

	$descr = substr ( $_POST ['x_DESCR'], 0, 500 );



	//	$city = intval ( $_POST ['cityCode'] );

	//	$region = intval ( $_POST ['regionCode'] );





	// Обрезаем лишние пробелы

	$zag = trim ( $zag );

	$descr = trim ( $descr );

	$name = trim ( $name );

	$email = trim ( $email );

	$tel1 = trim ( $tel1 );


	$name = RemoveXSS ( $name );

	$email = RemoveXSS ( $email );

	$tel1 = RemoveXSS ( $tel1 );



	$zag = RemoveXSS ( $zag );

	$descr = RemoveXSS ( $descr );


	$keystring = RemoveXSS ( $keystring );

	$price = round ( abs ( RemoveXSS ( $_POST ['x_PRICE'] ) ) );



	$regionCode = intval ( $_POST ['regionCode'] );

	$cityCode = intval ( $_POST ['cityCode'] );



	$id_typeCode = intval ( $_POST ['id_typeCode'] );

	$id_markCode = intval ( $_POST ['id_markCode'] );

	$id_modelCode = intval ( $_POST ['id_modelCode'] );



	//$yearCode = (! isset ( $_POST ['yearCode'] )) ? "NULL" : intval ( $_POST ['yearCode'] );



	$razdel = ($_POST ['x_RAZD'] != "") ? abs ( intval ( $_POST ['x_RAZD'] ) ) : "";



	// Проверяем, заполнены ли обязательные поля

	$error = '';




		//
	if (strlen ( $descr ) > 500)

		$error = $error . '<li>длина текста объявления более 500 символов</li>' . "\n";

	if (strlen ( $zag ) > 200)

		$error = $error . '<li>длина заголовка объявления более 200 символов</li>' . "\n";


	if (empty ( $zag ))

		$error = $error . '<li>не указан заголовок объявления</li>' . "\n";

	if (empty ( $descr ))

		$error = $error . '<li>не заполнен текст объявления</li>' . "\n";

	if ($id_typeCode <= 0)

		$error = $error . '<li>Не выбран тип ТС</li>' . "\n";



	if ($razdel <= 0)

		$error = $error . '<li>Не выбран раздел</li>' . "\n";

	if ($price <= 0)

		$error = $error . '<li>Не указана стоимость</li>' . "\n";



if ($_SESSION['user']['status'] === 'admin') {

		if (empty ( $email ))

			$error = $error . '<li>не заполнено поле "E-mail"</li>' . "\n";

		if (! empty ( $email ) and ! preg_match ( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $email ))

			$error = $error . '<li>поле "Адрес e-mail" должно соответствовать формату somebody@somewhere.ru</li>' . "\n";

		if (empty ( $name ))

			$error = $error . '<li>не заполнено поле "ФИО"</li>' . "\n";

		if (! empty ( $name ) and ! preg_match ( "#^[- _0-9a-zА-Яа-я]+$#i", $name ))

			$error = $error . '<li>поле "Имя" содержит недопустимые символы</li>' . "\n";

		if (empty ( $tel1 ))

			$error = $error . '<li>не заполнено поле "Телефон"</li>' . "\n";

	if ($regionCode <= 0)

		$error = $error . '<li>не выбран регион</li>' . "\n";

	if ($cityCode <= 0)

		$error = $error . '<li>не выбран город</li>' . "\n";

}






	$IMGCOUNT = 6;

	for($ii = 1; $ii <= $IMGCOUNT; $ii ++) {

		if (! empty ( $_FILES ['x_PHOTO_' . $ii] ['name'] )) {

			//$ext = strrchr ( $_FILES ['x_PHOTO_' . $ii] ['name'], "." );

			unset($imgS);
			$imgS = getimagesize($_FILES ['x_PHOTO_' . $ii]['tmp_name']);

			$extensions = array ("image/jpg", "image/png", "image/jpeg", "image/gif" );

//&&&&&&&&&&&&&&&&//

			if (! in_array ( strtolower($imgS['mime']), $extensions ))

				$error = $error . '<li>Недопустимый формат файла №' . $ii . '</li>' . "\n";



			if ($_FILES ['x_PHOTO_' . $ii] ['size'] > MAX_PHOTO_SIZE)

				$error = $error . '<li>Размер файла №' . $ii . ' больше ' . (MAX_PHOTO_SIZE / 1024) . ' Кб</li>' . "\n";

		}

	}



	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['carbase'] ['name'] = $name;

		$_SESSION ['carbase'] ['email'] = $email;

		$_SESSION ['carbase'] ['tel1'] = $tel1;

		$_SESSION ['carbase'] ['price'] = $price;

		$_SESSION ['carbase'] ['region'] = $regionCode;

		$_SESSION ['carbase'] ['city'] = $cityCode;

		$_SESSION ['carbase'] ['descr'] = $descr;

		$_SESSION ['carbase'] ['razdel'] = $razdel;

		$_SESSION ['carbase'] ['zag'] = $zag;

		$_SESSION ['carbase'] ['id_typeCode'] = $id_typeCode;

		$_SESSION ['carbase'] ['id_markCode'] = $id_markCode;

		$_SESSION ['carbase'] ['id_modelCode'] = $id_modelCode;

$_SESSION ['carbase'] ['photo_1'] = $_FILES ['x_PHOTO_1']['name'];

$_SESSION ['carbase'] ['photo_2'] = $_FILES ['x_PHOTO_2']['name'];





		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=addSpares' );

		die ();

	}



	// Формируем SQL-запрос
if (($_SESSION ['user'] ['status'] === "autosaloon") || ($_SESSION ['user'] ['status'] === "user") || ($_SESSION ['user'] ['status'] === "admin")) {

	//сессия

	if ($_SESSION ['user'] ['status'] === "user") {


	$query = "SELECT COUNT(*) FROM AUTO_SPARES

		    WHERE ID_USER='" .  $_SESSION['user']['id_author'] . "'";

	$res = mysql_query ( $query );



	if (! $res) {

		$msg = 'Ошибка при регистрации нового Объявления';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$c2=mysql_fetch_row($res);

	$COUNT_AD = $c2[0];

	if ($COUNT_AD  >= 3) {

		$msg = '<b><br><center><p>Вы не можете подать более 3 объявлений!</p></center></b></b>';

		return showInfoMessage ( $msg, '' );

	}
	}
///
        $img_1="";

        $img_2="";


        //echo $_FILES ['x_PHOTO_1']['tmp_name'];

		if (! empty ( $_FILES ['x_PHOTO_1'] ['tmp_name'] )) {

			$img_1 = water ($_FILES ['x_PHOTO_1'] );

		}

		if (! empty ( $_FILES ['x_PHOTO_2'] ['tmp_name'] )) {

			$img_2 = water ( $_FILES ['x_PHOTO_2'] );

		}


if ($_SESSION['user']['status'] === 'admin') {

	$query = "INSERT INTO " . TABLE_USERS . "

		    (

		    name,

		    passw,

		    email,

			puttime,

			last_visit,

			status,locked,lock_admin,

		    tel1,

		    region,

		    city


		    )

		    VALUES

		    (

		    '" . $name . "',

		    '" . mysql_real_escape_string ( md5 ( 'siberia-auto_ru777' ) ) . "',

		    '" . mysql_real_escape_string ( $email ) . "',

			NOW(),

			NOW(),

			'user','0','0',

		    '" . mysql_real_escape_string ( $tel1 ) . "',

		    '" . mysql_real_escape_string ( $regionCode ) . "',

		    '" . mysql_real_escape_string ( $cityCode ) . "'




		    );";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при регистрации нового пользователя';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$id = mysql_insert_id ();
}

//кукуку
		if ($_POST ['a_add'] == "A") {


		if ($_SESSION['user']['status'] !== 'admin') {
		/*
			$query = "SELECT ID_USER FROM AUTO_CAR_BASE

		    WHERE ID='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );
			for($u_id=array();$row=mysql_fetch_assoc($res);$u_id=$row);


			$query = "SELECT * FROM AUTO_USERS

		    WHERE id_author='" . $_SESSION['user']['id_author'] . "'";

			$res = mysql_query ( $query );
			for($data=array();$row=mysql_fetch_assoc($res);$data=$row);
		*/
			$regionCode=$_SESSION['user']['region'];
			$cityCode=$_SESSION['user']['city'];


		}

//car
			$query = "INSERT INTO AUTO_SPARES

		SET
		`cat_id`='" . mysql_escape_string ( $razdel ) . "',
		`CAR_TYPE`='" . mysql_escape_string ( $id_typeCode ) . "',
		`CAR_MARK`='" . mysql_escape_string ( $id_markCode ) . "',
		`CAR_MODEL`='" . mysql_escape_string ( $id_modelCode ) . "',
		`REGION`='" . mysql_escape_string ( $regionCode ) . "',
		`CITY`='" . mysql_escape_string ( $cityCode ) . "',
		`PRICE`='" . mysql_escape_string ( $price ) . "',
		".(($_SESSION['user']['status'] !== 'admin') ? "`ID_USER`='" . $_SESSION['user']['id_author'] . "'," : "`ID_USER`='" . $id . "',")."
		`DESCR`='" . mysql_escape_string ( $descr ) . "',
		`zag`='" . mysql_escape_string ( $zag ) . "',
		`DATE`=NOW(),
		`PHOTO_1`='".$img_1."',
		`PHOTO_2`=  '".$img_2. "'";

			$res = mysql_query ( $query );



			if (! $res) {

				$msg = 'Ошибка при добавлении объявления';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addyesSpares.html' );

			$html = str_replace ( '{URL}', '?action=showSpares&id='.$ID_CAR, $html );

			$html = str_replace ( '{edit}', '?action=editSpares&id='.$ID_CAR, $html );


		}



}

	return $html;



}

////////////////////////////
function showSpares($print = 0) {




	if (isset ( $_GET ['id'] )) // and ((int)$_GET['id']>0))

{

		$query = "SELECT a.*, s.name, f.MODEL, i.TRADEMARK, c.status, s.cat_id, s.name as CAT FROM AUTO_SPARES a, AUTO_SPARES_CATS s, ".AUTO_USERS." c, ".AUTO_MODEL." f, ".AUTO_TRADEMARK." i where ";

		$query .= "f.ID=a.CAR_MODEL";

		$query .= " and i.ID=a.CAR_MARK";

		$query .= " and a.ID=" . ( int ) $_GET ['id'];

		$query .= " and a.cat_id=s.cat_id and (a.ID_USER=c.id_author and c.locked=0 and c.lock_admin=0)";

		//   $query.=" group by a.CAR_MODEL,a.CAR_TYPE,a.CAR_MARK, f.MODEL, i.TRADEMARK";

		// $html.=$query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка';

			$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

			//$html.=$query;

		}



		while ( $row = mysql_fetch_array ( $res ) ) {

			//	$html.=$tc;

			//$count = $row ["COUNT"];

			$car_mark = $row ["CAR_MARK"];

			$title = $row ["MODEL"];

			$cat = $row ["CAT"];

			$cat_id = $row ["cat_id"];

			$car_model = $row ["MODEL"];

			$car_type = $row ["CAR_TYPE"];

			$mark = $row ["TRADEMARK"];

			$price = round ( $row ["PRICE"] );

			$zag = $row ["zag"];

			$razdel = $row ["name"];

			$id_user = (int) $row ["ID_USER"];

			$status = $row ["status"];

			$REGION = $row ["REGION"];

			$CITY = $row ["CITY"];

			$DESCR = $row ["DESCR"];

			$ID_USER = (int) $row ["ID_USER"];

			$date = $row ["DATE"];

			$PHOTO [1] = $row ["PHOTO_1"];

			$PHOTO [2] = $row ["PHOTO_2"];

			$j = 0;

			$PHOTO = "";

			for($i = 1; $i <= 2; $i ++) {

				if ((isset ( $row ["PHOTO_" . $i] )) and ( $row ["PHOTO_" . $i] !="")) {

					$j ++;

				}

			}

			if ($j > 0) {

				$PHOTO = "<table width=\"190\" cellpadding=\"2\">";

				for($i = 1; $i <= 2; $i ++) {

					if ((isset ( $row ["PHOTO_" . $i] )) and ($print == 1) and ( $row ["PHOTO_" . $i] !="")) {

						$PHOTO .= "<tr><td>";

						$PHOTO .= "<a href=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=640\" class=\"lightbox\" rel=\"roadtrip\"><img src=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=160\"   /></a></br></br>";

						$PHOTO .= "</td></tr>";

					}

					if ((isset ( $row ["PHOTO_" . $i] )) and ($print != 1) and ( $row ["PHOTO_" . $i] !="")) {

						$PHOTO .= "<tr><td>";

						$PHOTO .= "<a href=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=640\"  class=\"lightbox\" rel=\"roadtrip\"><img src=\"show_image.php?filename=photo/" . $row ["PHOTO_" . $i] . "&width=180\" style=\"border:#FFFFFF thin;\" /></a></br>";

						$PHOTO .= "</td></tr>";

					}



				}

				$PHOTO .= "</table>";



			} else {

				$PHOTO = "<div class=\"head5\">НЕТ ФОТО</div>";

			}

		}



			if ($print == 1) {

//тарарам
				if ($status !== 'autosaloon') {
					$html2 = file_get_contents ( './templates/sparesPrint.html' );}
				else {$html2 = file_get_contents ( './templates/sparesPrint2.html' );}

			} else {

				if ($status !== 'autosaloon') {
					$html2 = file_get_contents ( './templates/sparesForm.html' );}
				else {$html2 = file_get_contents ( './templates/sparesForm2.html' );}

			}

			$query = "SELECT * FROM AUTO_USERS where ";

			$query .= "id_author=" . intval( $ID_USER ) . " and locked=0";





		save_log ( VIEW_CAR, 'user', $_GET ['id'] );



		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка';

			$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

			//$html.=$query;

		}

		while ( $row = mysql_fetch_array ( $res ) ) {

			$tel = $row ["tel1"];

			$NAME_USER = $row ["name"];

			$TEL2 = $row ["tel2"];

			$nach1 = $row ["nach1"];

			$nach2 = $row ["nach2"];

			$end1 = $row ["end1"];

			$end2 = $row ["end2"];

			$url = $row ["url"];

			$email = $row ["email"];

			$status = $row ["status"];

//скоп
			if (!$DESCR) $DESCR="Информация отсутствует";

			$html2 = str_replace ( '{DESCR}', $DESCR, $html2 );

			$html2 = str_replace ( '{ADDRESS}', $row ['address'], $html2 );

			$html2 = str_replace ( '{DOPOLN}', nl2br($DESCR), $html2 );

			$html2 = str_replace ( '{WEB}', "<a href=?action=redirect&url=" . $row ['url'] . ">" . $row ['url'] . "</a>", $html2 );

			$html2 = str_replace ( '{EMAIL}', "<a href=?action=sendMailForm&idUser=" . $row ['id_author'] . ">Написать письмо</a>", $html2 );

			$html2 = str_replace ( '{SALOON}', "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $row ['id_author'] . ">" . $NAME_USER . "</a>", $html2 );

			$html2 = str_replace ( '{NAME}', $NAME_USER, $html2 );

			$html2 = str_replace ( '{SALOON_PRICE}', "<a href=?action=priceSpares&id=" . $ID_USER . ">Все товары и запчасти автосалона " . $NAME_USER . " </a>", $html2 );



		}



		//$CAR_123 = "" . $mark . " " . $car_model . " - " . $price . " руб., " . $year_vyp . " г.";



		$title = $mark . " " . $car_model . " - " . $price . " руб., " . $year_vyp . " г., телефон:" . $tel;

		$html2 = str_replace ( '{CAR}', $zag, $html2 );

		$html2 = str_replace ( '{TEL}', $tel, $html2 );



		$nach1 = ($nach1 > 0) ? " c " . $nach1 : "";

		$end1 = ($end1 > 0) ? " до " . $end1 . " часов" : "";

		$nach2 = ($nach2 > 0) ? " c " . $nach2 : "";

		$end2 = ($end2 > 0) ? " до " . $end2 . " часов" : "";

		if ($TEL2 != "") {

			if ($status != "autosaloon") {

				$TEL2 = "<tr><td><span><strong>Телефон 2: </strong></span>" . $TEL2 . $nach2 . $end2 . "</td></tr>";

			} else {

				$TEL2 = "<tr><td><span><strong>Тел./факс: </strong></span>" . $TEL2 . $nach2 . $end2 . "</td></tr>";

			}



		} else

			$TEL2 = "";



		$html2 = str_replace ( '{TEL1}', $tel . $nach1 . $end1, $html2 );

		$html2 = str_replace ( '{TEL2}', $TEL2, $html2 );



	}

	$table = "<table width=100% align=\"center\"><tr>";

	//$table .= "<td align=\"left\" style=\"padding-left: 40px;\" width=\"20\" valign=\"middle\">";

if ($ID_USER === $_SESSION ['user'] ['id_author']) {

	$table .= "<td style=\"padding-left: 40px;\" valign=\"middle\" align=\"left\">[<a href=?action=editSpares&id=" . $_GET ["id"] . ">Изменить</a>]</td>";
	$_SESSION['edID']=$_GET ["id"];
	}

	//$table.="</td><td width=\"120\" valign=\"middle\" align=\"left\"><span class=\"notebook\">Записать в блокнот";

	$table .= "<td width=\"100%\" style=\"".(($ID_USER !== $_SESSION ['user'] ['id_author']) ? "padding-left: 40px;" : "padding-left: 0px;")."\" valign=\"middle\" ".(($ID_USER !== $_SESSION ['user'] ['id_author']) ? "align=\"left\"" : "align=\"center\"").">[<a href=?action=sendMailForm&idUser=" . $ID_USER . ">Уточнить наличие и условия приобретения</a>]";

	$table .= "</td><td style=\"padding-right: 40px;\" valign=\"middle\" align=\"left\">[<a href=?action=PrintSpares&id=" . $_GET ["id"] . " target=_new>Распечатать</a>]";

	$table .= "</td></tr></table>";

	$html2 = str_replace ( '{ADVANCED}', $table, $html2 );



	$html2 = str_replace ( '{PRICE}', $price . " руб.", $html2 );



	$query = "SELECT * FROM AUTO_REGION where ID=" . $REGION;

	//echo  $query;

	$res = @mysql_query ( $query );

	while ( $row = @mysql_fetch_array ( $res ) ) {

		$REGION = $row ["REGION"];

	}

	$REGION = ($REGION != "") ? $REGION : "";



	$query = "SELECT * FROM AUTO_CITY where ID=" . $CITY;

	//echo  $query;

	$res = @mysql_query ( $query );

	while ( $row = @mysql_fetch_array ( $res ) ) {

		$CITY = $row ["CITY"];

	}

	$CITY = ($CITY != "") ? $CITY : "";



	$_SESSION ['pageTitle'] = $zag;

	$html2 = str_replace ( '{REGION}', $REGION . ", г. " . $CITY, $html2 );

	$html2 = str_replace ( '{DOPOLN}', $DESCR, $html2 );

	$html2 = str_replace ( '{PHOTO}', $PHOTO, $html2 );

	$html2 = str_replace ( '{MARKA}', $mark, $html2 );

	$html2 = str_replace ( '{MODEL}', $car_model, $html2 );


//
	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=sparesCats&id_typeCode=1&del=1">Товары и запчасти</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=sparesCats&id='.$cat_id.'">'.$cat.'</a></span>';
//


	$html2 = str_replace ( '{path}', $Gpath, $html2 );
	return $html2;



}


//////////////////////

function addNews() {

	if ($_SESSION ['user'] ['status'] !== "autosaloon" && $_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Размещение новости автосалона";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );
		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );
		$html = $html . $info . "\n";
	}

	$html .= file_get_contents ( './templates/addNews.html' );
	$action = $_SERVER ['PHP_SELF'] . '?action=addNewsSubmit';
	$html = str_replace ( '{action}', $action, $html );
	$html = str_replace ( '{DESCR}', $_SESSION ['carbase'] ['descr'], $html );
	$html = str_replace ( '{DESCR1}', $_SESSION ['carbase'] ['descr1'], $html );
	$html = str_replace ( '{ZAG}', $_SESSION ['carbase'] ['zag'], $html );
	$html = str_replace ( '{DATE}', empty( $_SESSION ['carbase'] ['date'] ) ? date('Y-m-d H:i:s') : $_SESSION ['carbase'] ['date'], $html );
	$html = str_replace ( '{PHOTO_1}', $_SESSION ['carbase'] ['photo_1'], $html );
	$tpl = $html;

	unset ( $_SESSION ['carbase'] );
	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=myNews">Новости автосалона</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=addNews">Размещение новости</a></span>';
	$tpl = str_replace ( '{path}', $Gpath, $tpl );
	return $tpl;
}

//////////////////////

function addNewsSubmit() {

	if ($_POST ['a_add'] != "A") { // && ($_POST ['a_add'] != "U"))

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );
		die ();
	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );
		$_POST = stripslashes_array ( $_POST );
		$_COOKIE = stripslashes_array ( $_COOKIE );
	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$zag = substr ( $_POST ['x_ZAG'], 0, 200 );
	$descr = substr ( $_POST ['x_DESCR'], 0, 250 );
	$descr1 = substr ( $_POST ['x_DESCR1'], 0, 65000 );
	// Обрезаем лишние пробелы

	$zag = trim ( $zag );
	$descr = trim ( $descr );
	$descr1 = trim ( $descr1 );
	$zag = RemoveXSS ( $zag );
	$descr = RemoveXSS ( $descr );
	$descr1 = RemoveXSS ( $descr1 );

	if ( empty( $_POST['date'] ) ) $date = date('Y-m-d H:i:s');
	else $date = date( 'Y-m-d H:i:s', strtotime( $_POST['date'] ) );

	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $zag ) > 200)
		$error = $error . '<li>длина заголовка более 200 символов</li>' . "\n";

	if (strlen ( $descr ) > 250)
		$error = $error . '<li>краткое описание более 250 символов</li>' . "\n";

	if (empty ( $zag ))
		$error = $error . '<li>не указан заголовок новости</li>' . "\n";

	if (empty ( $descr ))
		$error = $error . '<li>не заполнено краткое описание новости</li>' . "\n";

	if (empty ( $descr1 ))
		$error = $error . '<li>не заполнен текст новости</li>' . "\n";

	unset ( $_SESSION ['captcha_keystring'] );

	$IMGCOUNT = 1;

	for($ii = 1; $ii <= $IMGCOUNT; $ii ++) {

		if (! empty ( $_FILES ['x_PHOTO_' . $ii] ['name'] )) {

			//$ext = strrchr ( $_FILES ['x_PHOTO_' . $ii] ['name'], "." );

			unset($imgS);
			$imgS = getimagesize($_FILES ['x_PHOTO_' . $ii]['tmp_name']);

			$extensions = array ("image/jpg", "image/png", "image/jpeg", "image/gif" );

//&&&&&&&&&&&&&&&&//

			if (! in_array ( strtolower($imgS['mime']), $extensions ))
				$error = $error . '<li>Недопустимый формат файла №' . $ii . '</li>' . "\n";

			if ($_FILES ['x_PHOTO_' . $ii] ['size'] > MAX_PHOTO_SIZE)
				$error = $error . '<li>Размер файла №' . $ii . ' больше ' . (MAX_PHOTO_SIZE / 1024) . ' Кб</li>' . "\n";

		}

	}



	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {

		$_SESSION ['carbase'] = array ();
		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";
		$_SESSION ['carbase'] ['descr1'] = $descr1;
		$_SESSION ['carbase'] ['descr'] = $descr;
		$_SESSION ['carbase'] ['zag'] = $zag;
		$_SESSION ['carbase'] ['date'] = $date;
		$_SESSION ['carbase'] ['photo_1'] = $_FILES ['x_PHOTO_1']['name'];

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=addNews' );
		die ();
	}

	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "autosaloon" || $_SESSION ['user'] ['status'] === "admin") {

        $img_1="";
        //echo $_FILES ['x_PHOTO_1']['tmp_name'];

		if (! empty ( $_FILES ['x_PHOTO_1'] ['tmp_name'] )) {
			$img_1 = water2 ($_FILES ['x_PHOTO_1'] );
		}

		if ($_POST ['a_add'] == "A") {

//car
			$query = "INSERT INTO AUTO_NEWS

		SET
		`ID_SALOON`='" . $_SESSION['user']['id_author'] . "',
		`SMALL_TEXT`='" . mysql_escape_string ( $descr ) . "',
		`TEXT`='" . mysql_escape_string ( $descr1 ) . "',
		`ZAGOL`='" . mysql_escape_string ( $zag ) . "',
		`DATA`='$date',
		`PHOTO`='".$img_1."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении новости';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addyesNews.html' );

			$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );
		}
}
	return $html;
}

//////////////////////
//////////////////////

function editNews() {

	if ($_SESSION ['user'] ['status'] !== "autosaloon" && $_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение новости автосалона";
	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );
		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );
		$html = $html . $info . "\n";
	}

	$html .= file_get_contents ( './templates/editNews.html' );

	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_NEWS WHERE ID='".$_SESSION['edID']."' ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';
		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );
	}

	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);
	}

	$action = $_SERVER ['PHP_SELF'] . '?action=editNewsSubmit';
	$html = str_replace ( '{action}', $action, $html );

	/*
	$html = str_replace ( '{DESCR}', $_SESSION ['carbase'] ['descr'], $html );

	$html = str_replace ( '{DESCR1}', $_SESSION ['carbase'] ['descr1'], $html );

	$html = str_replace ( '{ZAG}', $_SESSION ['carbase'] ['zag'], $html );

	$html = str_replace ( '{PHOTO_1}', $_SESSION ['carbase'] ['photo_1'], $html );
	*/


	$html = str_replace ( '{DESCR}', $data ['SMALL_TEXT'], $html );
	$html = str_replace ( '{DESCR1}', $data ['TEXT'], $html );
	$html = str_replace ( '{ZAG}', $data ['ZAGOL'], $html );
	$html = str_replace ( '{DATE}', $data ['DATA'], $html );

	if ($data['PHOTO'])

		$DEL_PHOTO = '
	          <tr >

            <td valign="top" ><span class="style7"></span></td>

            <td valign="top"><span>

              <img src="show_image.php?filename=photo/'.$data['PHOTO'].'&width=75"/><br/><input type="checkbox" name="x_DEL_PHOTO_1" value="1"/> <span class="style7">Удалить</span>

            </span></td>

          </tr>';
	else $DEL_PHOTO = '';

	$html = str_replace ( '{DEL_PHOTO}', $DEL_PHOTO, $html );
	//photo


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=myNews">Новости автосалона</a></span> / <span class="und"><a href="">Изменение новости автосалона</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;
}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function editNewsSubmit() {

	if ($_POST ['a_add'] != "A") {// && ($_POST ['a_add'] != "U"))

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );
		die ();
	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );
		$_POST = stripslashes_array ( $_POST );
		$_COOKIE = stripslashes_array ( $_COOKIE );
	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input
	$zag = substr ( $_POST ['x_ZAG'], 0, 200 );
	$descr = substr ( $_POST ['x_DESCR'], 0, 250 );
	$descr1 = substr ( $_POST ['x_DESCR1'], 0, 65000 );

	// Обрезаем лишние пробелы

	$zag = trim ( $zag );
	$descr = trim ( $descr );
	$descr1 = trim ( $descr1 );
	$zag = RemoveXSS ( $zag );
	$descr = RemoveXSS ( $descr );
	$descr1 = RemoveXSS ( $descr1 );
	// Проверяем, заполнены ли обязательные поля

	if ( empty( $_POST['date'] ) ) $date = date('Y-m-d H:i:s');
	else $date = date( 'Y-m-d H:i:s', strtotime( $_POST['date'] ) );

	$error = '';

	if (strlen ( $zag ) > 200)
		$error = $error . '<li>длина заголовка более 200 символов</li>' . "\n";

	if (strlen ( $descr ) > 250)
		$error = $error . '<li>краткое описание более 250 символов</li>' . "\n";

	if (empty ( $zag ))
		$error = $error . '<li>не указан заголовок новости</li>' . "\n";

	if (empty ( $descr ))
		$error = $error . '<li>не заполнено краткое описание новости</li>' . "\n";

	if (empty ( $descr1 ))
		$error = $error . '<li>не заполнен текст новости</li>' . "\n";

	$IMGCOUNT = 1;

	for($ii = 1; $ii <= $IMGCOUNT; $ii ++) {

		if (! empty ( $_FILES ['x_PHOTO_' . $ii] ['name'] )) {

			//$ext = strrchr ( $_FILES ['x_PHOTO_' . $ii] ['name'], "." );

			unset($imgS);
			$imgS = getimagesize($_FILES ['x_PHOTO_' . $ii]['tmp_name']);

			$extensions = array ("image/jpg", "image/png", "image/jpeg", "image/gif" );

//&&&&&&&&&&&&&&&&//

			if (! in_array ( strtolower($imgS['mime']), $extensions ))

				$error = $error . '<li>Недопустимый формат файла №' . $ii . '</li>' . "\n";



			if ($_FILES ['x_PHOTO_' . $ii] ['size'] > MAX_PHOTO_SIZE)

				$error = $error . '<li>Размер файла №' . $ii . ' больше ' . (MAX_PHOTO_SIZE / 1024) . ' Кб</li>' . "\n";

		}

	}



	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();
		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";
		$_SESSION ['carbase'] ['descr1'] = $descr1;
		$_SESSION ['carbase'] ['descr'] = $descr;
		$_SESSION ['carbase'] ['zag'] = $zag;
		$_SESSION ['carbase'] ['date'] = $date;
		$_SESSION ['carbase'] ['photo_1'] = $_FILES ['x_PHOTO_1']['name'];

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=editNews&id='.$_SESSION['edID'] );
		die ();
	}

	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "autosaloon" || $_SESSION ['user'] ['status'] === "admin") {

///
		if ($_REQUEST['x_DEL_PHOTO_1']) {

	$query = "SELECT PHOTO FROM AUTO_NEWS WHERE ID='" . $_SESSION['edID'] . "' ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';
		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';
		return showErrorMessage ( $msg, $err, true, '' );
	}

	if (mysql_num_rows ( $res ) > 0) {

		for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);
	}

	unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.	$ph['PHOTO']);
	//
			$query = "UPDATE AUTO_NEWS

				SET PHOTO='' WHERE ID='" . $_SESSION['edID'] . "' ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');

			$res = mysql_query ( $query );
	}
///

	    $img_1="";

        if (! empty ( $_FILES ['x_PHOTO_1'] ['tmp_name'] )) {

				$query = "SELECT PHOTO FROM AUTO_NEWS WHERE ID='" . $_SESSION['edID'] . "' ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

			}

			if ($ph['PHOTO']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph['PHOTO']);



			$img_1 = water2 ($_FILES ['x_PHOTO_1'] );

					$res = mysql_query ( $query );

		$query = "UPDATE AUTO_NEWS

		    SET PHOTO='" .  $img_1 . "' WHERE ID='" . $_SESSION['edID'] . "' ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');

		$res = mysql_query ( $query );

		}

		if ($_POST ['a_add'] == "A") {

//car
			//$date .= date(' H:i:s');
			$query = "UPDATE AUTO_NEWS

		SET
		`SMALL_TEXT`='" . mysql_escape_string ( $descr ) . "',
		`TEXT`='" . mysql_escape_string ( $descr1 ) . "',
		DATA='$date',
		`ZAGOL`='" . mysql_escape_string ( $zag ) . "' WHERE `ID`='" . $_SESSION['edID'] . "'  ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении новости';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			$ID_CAR = @mysql_insert_id ();

			$html .= file_get_contents ( './templates/changeNews.html' );
			$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );
			$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );
		}
}

	return $html;
}

//////////////////////

function getAddSpares() {

	$_SESSION ['pageTitle'] = "Размещение объявления – Товары и запчасти";

	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}


if (($_SESSION ['user'] ['status'] === "autosaloon") || ($_SESSION ['user'] ['status'] === "user")) {

	$html .= file_get_contents ( './templates/addSparesReg.html' );

} else
if (($_SESSION ['user'] ['status'] === "admin")) {

	$html .= file_get_contents ( './templates/addSparesAdm.html' );

} else {

	die();

}


	$action = $_SERVER ['PHP_SELF'] . '?action=addSparesSubmit';

	$html = str_replace ( '{action}', $action, $html );



	$query = "SELECT * FROM AUTO_CAR_TYPE order by CAR_TYPE ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($typelist ['ID'] == $_SESSION ['carbase'] ['id_typeCode']) ? " selected" : "";

			//$type.="<option value='".$typelist['ID']."' ".$selwrk." >".$typelist['CAR_TYPE']."</option>";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . ">" . $typelist ['CAR_TYPE'] . "</option>";



		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );



	if (isset ( $_SESSION ['carbase'] ['id_typeCode'] )) {

		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $_SESSION ['carbase'] ['id_typeCode'] . "  order by TRADEMARK";



		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок2';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $_SESSION ['carbase'] ['id_markCode']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );



	}



	if (isset ( $_SESSION ['carbase'] ['id_markCode'] )) {

		$query = "SELECT * FROM AUTO_MODEL where TRADEMARK=" . $_SESSION ['carbase'] ['id_markCode'] . " order by MODEL";

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка моделй';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$model = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $modellist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($modellist ['ID'] == $_SESSION ['carbase'] ['id_modelCode']) ? " selected" : "";

				$model .= "<option value='" . $modellist ['ID'] . "' " . $selwrk . " >" . $modellist ['MODEL'] . "</option>";

			}

		}

		$html = str_replace ( '{MODEL}', $model, $html );

	}



	$query = "SELECT * FROM AUTO_REGION order by REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка регионов';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] == $_SESSION ['carbase'] ['region']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . ">" . $regionlist ['REGION'] . "</option>";

		}

	}

	$html = str_replace ( '{REGION}', $region, $html );



	if (isset ( $_SESSION ['carbase'] ['region'] )) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $_SESSION ['carbase'] ['region'] . " order by CITY";

		//	$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $_SESSION ['carbase'] ['REGION'];

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка городов';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$city = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $citylist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($citylist ['ID'] == $_SESSION ['carbase'] ['city']) ? " selected" : "";

				$city .= "<option value='" . $citylist ['ID'] . "' " . $selwrk . ">" . $citylist ['CITY'] . "</option>";

			}

		}

		$html = str_replace ( '{CITY}', $city, $html );

	}

	$query = "SELECT * FROM AUTO_SPARES_CATS ORDER BY position";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка разделов';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($sostlist ['cat_id'] == $_SESSION ['carbase'] ['razdel']) ? " selected" : "";

			$sost .= "<option ".$selwrk." value='" . $sostlist ['cat_id'] . "' >" . $sostlist ['name'] . "</option>";

		}

	}

	$html = str_replace ( '{razdel}', $sost, $html );



	$query = "SELECT * FROM AUTO_TYPE_DVIG";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка типов двигателей';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$PRICE=(abs(round( $_SESSION ['carbase'] ['price']))>0)?abs(round( $_SESSION ['carbase'] ['price'])):"";

	$html = str_replace ( '{PRICE}',$PRICE, $html );



	$html = str_replace ( '{DESCR}', $_SESSION ['carbase'] ['descr'], $html );

	$html = str_replace ( '{ZAG}', $_SESSION ['carbase'] ['zag'], $html );

	$html = str_replace ( '{PHOTO_1}', $_SESSION ['carbase'] ['photo_1'], $html );

$html = str_replace ( '{PHOTO_2}',$_SESSION ['carbase'] ['photo_2'], $html );


	$tpl = $html;


	$tpl = str_replace ( '{FIO}', $_SESSION ['carbase'] ['name'], $tpl );

	$tpl = str_replace ( '{EMAIL}', $_SESSION ['carbase'] ['email'], $tpl );

	$tpl = str_replace ( '{TEL1}', $_SESSION ['carbase'] ['tel1'], $tpl );



	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=mySpares">Мои товары и запчасти</a></span> / <span class="und"><a href="">Размещение объявления</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );


	return $tpl;



}

//////////////////////

function showTableSpares($where, $action, $ShowCarInfo) {


	$column = $_GET ['order'];

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];

	//echo $where;

	$query = "SELECT COUNT(*) FROM AUTO_SPARES a";

	$query .= ", AUTO_USERS b where a.ID_USER=b.id_author and b.locked=0 and b.lock_admin=0 and a.CAR_TYPE='".$_SESSION ['searchFormSpares'] ['id_typeCode']."' ";

	$query .= ($where != "") ? " and " . $where : "";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );



	if ($total == 0) {

		$html .= file_get_contents ( './templates/searchnoSpares.html' );

		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table ".($_REQUEST['id'] ?  " style=\"position: relative; top: -15px;\"" : "" ). " class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	$table .= "<table width=\"100%\"><tr><td>";


	$table .= "</td></table>";

	$table .= "";

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete





	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";

	$table .= "<td valign=\"top\" width=\"100\" align=\"center\">фото</td>";



	$table .= "<td valign=\"top\" width=\"280\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'zag' ) . "&ordertype=";

	$table .= SortOrder ( 'zag' );

	$table .= " \">описание";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"top\" width=\"190\" align=\"center\">";

	$table .= "автомобиль</td>";


	$table .= "<td valign=\"top\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'price' ) . "&ordertype=";

	$table .= SortOrder ( 'price' );

	$table .= " \">цена, руб.";

	if ($_SESSION ['sort'] ['price'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['price'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	$table .= "<td valign=\"top\" width=\"190\" align=\"center\">";

	$table .= "продавец</td>";



	$table .= "</tr>";


	$query = "
		SELECT
			a.zag,
			a.ID,
			a.cat_id,
			a.CAR_TYPE,
			j.CITY,
			f.MODEL,
			i.TRADEMARK,
			r.REGION,
			b.CAR_TYPE as TYPE,
			a.PRICE,
			a.CAR_MARK,
			a.PHOTO_1,
			a.ID_USER,
			UNIX_TIMESTAMP(a.DATE) as DATE,
			c.*
		FROM
			AUTO_USERS c,
			AUTO_SPARES a
		left join
			AUTO_CAR_TYPE b
		on
			b.ID = a.CAR_TYPE
		left join
			AUTO_MODEL f
		on
			f.ID = a.CAR_MODEL
		left join
			AUTO_TRADEMARK i
		on
			i.ID = a.CAR_MARK
		left join
			AUTO_CITY j
		on
			a.CITY = j.ID
		left join
			AUTO_REGION r
		on
			a.REGION = r.ID
		where
			a.CAR_TYPE = '{$_SESSION ['searchFormSpares'] ['id_typeCode']}'
		and
			( a.ID_USER = c.id_author and c.locked = 0 and c.lock_admin = 0 )
		";

	if (isset ( $_GET ['order'] ))

		$query .= " ORDER BY " . $_GET ['order'] . " " . $_GET ['ordertype'];

	else

		$query .= " ORDER BY a.DATE desc";

	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;

	$res = mysql_query ( $query );

//echo "<!--$query-->";

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;



	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {

			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}

			$photo = (isset ( $autolist ['PHOTO_1'] )) ? $autolist ['PHOTO_1'] : "";

			if ($photo == "") {

				$img = "<img src=\"photo/nofoto.gif\" border=0  alt=\"Подробнее\"/>";

			} else {

				$img = "<img src=\"show_image.php?filename=photo/" . $photo . "&width=75\" border=0  alt=\"Подробнее\"/>";

			}



			$table .= "<tr class=$CssClass>";

			$table .= "<td align='center'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=$ShowCarInfo&id=" . $autolist ['ID'] . ">$img</a>";

			$table .= "</td>";



			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=$ShowCarInfo&id=" . $autolist ['ID'] . "><b>" . $autolist ['zag'] . "</b>, ";


			$table .= "</a></br>" . date ( "d.m.Y", $autolist ['DATE'] );

			$table .= "</td>";

//999888777

			$table .= "<td align=\"center\">" . ($autolist ['TRADEMARK'] !== " " ? $autolist ['TRADEMARK'] : '').($autolist ['MODEL'] !== " " ? ",</br>". $autolist ['MODEL'] : '') . "</td>";


			$table .= "<td align=\"center\">" . round ( $autolist ['PRICE'] );

			$table .= "</td>";

			$CITY_A = $autolist ['REGION'] . ", г. " . $autolist ['CITY'];

			if ($autolist ['status'] == "autosaloon") {

				$table .= "<td valign=\"center\" align=\"center\">";

				$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $autolist ['id_author'] . "><strong>" . $autolist ["name"] . "</strong></a></br>" . $CITY_A . "</br><strong>" . $autolist ["tel1"] . "</strong>"; //$name.$region.$city.$status;

				$table .= "</td>";



			} else {

				$table .= "<td valign=\"center\" align=\"center\">";

				$table .= $CITY_A . "</br><a href=" . $_SERVER ['PHP_SELF'] . "?action=$ShowCarInfo&id=" . $autolist ['ID'] . "><strong></strong></a>"; //$name.$region.$city.$status;

				$table .= "</td>";

			}





			$table .= "</tr>";



		}

		$table .= "</td></tr></table>";

	}

	$table .= "<table width=\"100%\"><tr><td widht=\"100\">Всего: <b>" . $total . "</b></td><td align=\"right\">" . $pages . "</td></tr></table>";



	$table .= "</td></tr></table>";



	return $table;

}

//редактировать товары и запчасти
function editSpares() {

	if (!(($_SESSION ['user'] ['status'] === "autosaloon") || ($_SESSION ['user'] ['status'] === "user") || ($_SESSION ['user'] ['status'] === "admin"))) die();

	$_SESSION ['pageTitle'] = "Изменение объявления – Товары и запчасти";






	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}


	$html .= file_get_contents ( './templates/editMySpares.html' );



	$action = $_SERVER ['PHP_SELF'] . '?action=editSparesSubmit';

	$html = str_replace ( '{action}', $action, $html );

	if ($_REQUEST['id'])
		$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_SPARES WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}
//


	$query = "SELECT * FROM AUTO_CAR_TYPE order by CAR_TYPE ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {
//туточки
			$selwrk = ($typelist ['ID'] === $data ['CAR_TYPE']) ? " selected" : "";

			//$type.="<option value='".$typelist['ID']."' ".$selwrk." >".$typelist['CAR_TYPE']."</option>";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . ">" . $typelist ['CAR_TYPE'] . "</option>";



		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );



	//if (isset ( $_SESSION ['carbase'] ['id_typeCode'] )) {

		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $data ['CAR_TYPE'] . "  order by TRADEMARK";



		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок2';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] === $data ['CAR_MARK']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );



	//}



	//if (isset ( $_SESSION ['carbase'] ['id_markCode'] )) {

		$query = "SELECT * FROM AUTO_MODEL where TRADEMARK=" . $data ['CAR_MARK'] . " order by MODEL";

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка моделй';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$model = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $modellist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($modellist ['ID'] === $data ['CAR_MODEL']) ? " selected" : "";

				$model .= "<option value='" . $modellist ['ID'] . "' " . $selwrk . " >" . $modellist ['MODEL'] . "</option>";

			}

		}

		$html = str_replace ( '{MODEL}', $model, $html );

	//}



	$query = "SELECT * FROM AUTO_REGION order by REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка регионов';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] === $data ['REGION']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . ">" . $regionlist ['REGION'] . "</option>";

		}

	}

	$html = str_replace ( '{REGION}', $region, $html );



	//if (isset ( $_SESSION ['carbase'] ['region'] )) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $data ['REGION'] . " order by CITY";

		//	$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $_SESSION ['carbase'] ['REGION'];

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка городов';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$city = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $citylist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($citylist ['ID'] === $data ['CITY']) ? " selected" : "";

				$city .= "<option value='" . $citylist ['ID'] . "' " . $selwrk . ">" . $citylist ['CITY'] . "</option>";

			}

		}

		$html = str_replace ( '{CITY}', $city, $html );

	//}

	$query = "SELECT * FROM AUTO_SPARES_CATS ORDER BY position";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка разделов';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$sost = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $sostlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($sostlist ['cat_id'] === $data ['cat_id']) ? " selected" : "";

			$sost .= "<option ".$selwrk." value='" . $sostlist ['cat_id'] . "' >" . $sostlist ['name'] . "</option>";

		}

	}

	$html = str_replace ( '{razdel}', $sost, $html );



	$query = "SELECT * FROM AUTO_TYPE_DVIG";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка типов двигателей';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}




	$PRICE=(abs(round( $data ['PRICE']))>0)?abs(round( $data ['PRICE'])):"";

	$html = str_replace ( '{PRICE}',$PRICE, $html );



	$html = str_replace ( '{DESCR}', $data ['DESCR'], $html );

	$html = str_replace ( '{ZAG}', $data ['zag'], $html );

	//44444444444444444444444
	$DEL_PHOTO = '';

	if ($data['PHOTO_1'])

	$DEL_PHOTO = $DEL_PHOTO . '
<td>Фото №1<br>
              <img src="show_image.php?filename=photo/'.$data['PHOTO_1'].'&width=80"/><br/><input type="checkbox" name="x_DEL_PHOTO_1" value="1"/> <span class="style7">Удалить</span>
</td>';

	if ($data['PHOTO_2'])

	$DEL_PHOTO = $DEL_PHOTO.'
<td>Фото №2<br>
              <img src="show_image.php?filename=photo/'.$data['PHOTO_2'].'&width=80"/><br/><input type="checkbox" name="x_DEL_PHOTO_2" value="1"/> <span class="style7">Удалить</span>
</td>';


	$html = str_replace ( '{DEL_PHOTO}', $DEL_PHOTO, $html );
	//photo


	$tpl = $html;



	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=mySpares">Мои товары и запчасти</a></span> / <span class="und"><a href="">Изменение объявления</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );


	return $tpl;



}


function editSparesSubmit() {




	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}


	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input



	$zag = substr ( $_POST ['x_ZAG'], 0, 60 );

	$descr = substr ( $_POST ['x_DESCR'], 0, 500 );



	//	$city = intval ( $_POST ['cityCode'] );

	//	$region = intval ( $_POST ['regionCode'] );





	// Обрезаем лишние пробелы

	$zag = trim ( $zag );

	$descr = trim ( $descr );

	$keystring = trim ( $keystring );



	$zag = RemoveXSS ( $zag );

	$descr = RemoveXSS ( $descr );




	$price = round ( abs ( RemoveXSS ( $_POST ['x_PRICE'] ) ) );



	//$regionCode = intval ( $_POST ['regionCode'] );

	//$cityCode = intval ( $_POST ['cityCode'] );



	$id_typeCode = intval ( $_POST ['id_typeCode'] );

	$id_markCode = intval ( $_POST ['id_markCode'] );

	$id_modelCode = intval ( $_POST ['id_modelCode'] );



	//$yearCode = (! isset ( $_POST ['yearCode'] )) ? "NULL" : intval ( $_POST ['yearCode'] );



	$razdel = ($_POST ['x_RAZD'] != "") ? abs ( intval ( $_POST ['x_RAZD'] ) ) : "";

	// Проверяем, заполнены ли обязательные поля

	$error = '';





	if (strlen ( $descr ) > 500)

		$error = $error . '<li>длина текста объявления более 500 символов</li>' . "\n";

	if (strlen ( $zag ) > 200)

		$error = $error . '<li>длина заголовка объявления более 200 символов</li>' . "\n";

	if (empty ( $zag ))

		$error = $error . '<li>не указан заголовок объявления</li>' . "\n";

	if (empty ( $descr ))

		$error = $error . '<li>не заполнен текст объявления</li>' . "\n";

	if ($id_typeCode <= 0)

		$error = $error . '<li>Не выбран тип ТС</li>' . "\n";


	if ($razdel <= 0)

		$error = $error . '<li>Не выбран раздел</li>' . "\n";

	;

	if ($price <= 0)

		$error = $error . '<li>Не указана стоимость</li>' . "\n";

	;




	unset ( $_SESSION ['captcha_keystring'] );



	$IMGCOUNT = 2;

	for($ii = 1; $ii <= $IMGCOUNT; $ii ++) {

		if (! empty ( $_FILES ['x_PHOTO_' . $ii] ['name'] )) {

			//$ext = strrchr ( $_FILES ['x_PHOTO_' . $ii] ['name'], "." );

			unset($imgS);
			$imgS = getimagesize($_FILES ['x_PHOTO_' . $ii]['tmp_name']);

			$extensions = array ("image/jpg", "image/png", "image/jpeg", "image/gif" );

//&&&&&&&&&&&&&&&&//

			if (! in_array ( strtolower($imgS['mime']), $extensions ))

				$error = $error . '<li>Недопустимый формат файла №' . $ii . '</li>' . "\n";



			if ($_FILES ['x_PHOTO_' . $ii] ['size'] > MAX_PHOTO_SIZE)

				$error = $error . '<li>Размер файла №' . $ii . ' больше ' . (MAX_PHOTO_SIZE / 1024) . ' Кб</li>' . "\n";

		}

	}



	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";



		$_SESSION ['carbase'] ['price'] = $price;

		$_SESSION ['carbase'] ['region'] = $regionCode;

		$_SESSION ['carbase'] ['city'] = $cityCode;

		$_SESSION ['carbase'] ['descr'] = $descr;

		$_SESSION ['carbase'] ['razdel'] = $razdel;

		$_SESSION ['carbase'] ['zag'] = $zag;

		$_SESSION ['carbase'] ['id_typeCode'] = $id_typeCode;

		$_SESSION ['carbase'] ['id_markCode'] = $id_markCode;

		$_SESSION ['carbase'] ['id_modelCode'] = $id_modelCode;

$_SESSION ['carbase'] ['photo_1'] = $_FILES ['x_PHOTO_1']['name'];

$_SESSION ['carbase'] ['photo_2'] = $_FILES ['x_PHOTO_2']['name'];





		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=editSpares' );

		die ();

	}






	// Формируем SQL-запрос
if (($_SESSION ['user'] ['status'] === "autosaloon") || ($_SESSION ['user'] ['status'] === "user") || ($_SESSION ['user'] ['status'] === "admin")) {

	//сессия



///ph1
		if ($_REQUEST['x_DEL_PHOTO_1']) {

	$query = "SELECT PHOTO_1 FROM AUTO_SPARES WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND" : '')." ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

	}

	unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.	$ph['PHOTO_1']);


			$query = "UPDATE AUTO_SPARES

				SET PHOTO_1='' WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND" : '')." ID='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );

		}
///ph2
		if ($_REQUEST['x_DEL_PHOTO_2']) {

	$query = "SELECT PHOTO_2 FROM AUTO_SPARES WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND" : '')." ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

	}

	unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.	$ph['PHOTO_2']);


			$query = "UPDATE AUTO_SPARES

				SET PHOTO_2='' WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND" : '')." ID='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );

		}

///img1
	    $img_1="";

        if (! empty ( $_FILES ['x_PHOTO_1'] ['tmp_name'] )) {

				$query = "SELECT PHOTO_1 FROM AUTO_SPARES WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND" : '')." ID='".$_SESSION['edID']."'";

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

			}

			if ($ph['PHOTO_1']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph['PHOTO_1']);

			$img_1 = water ($_FILES ['x_PHOTO_1'] );
					$query = "UPDATE AUTO_SPARES

		    SET PHOTO_1='" .  $img_1 . "' WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND" : '')." ID='".$_SESSION['edID']."'";

		$res = mysql_query ( $query );
		}


///img2
	    $img_2="";

        if (! empty ( $_FILES ['x_PHOTO_2'] ['tmp_name'] )) {

				$query = "SELECT PHOTO_2 FROM AUTO_SPARES WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND" : '')." ID='".$_SESSION['edID']."'";

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

			}

			if ($ph['PHOTO_2']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph['PHOTO_2']);

			$img_2 = water ($_FILES ['x_PHOTO_2'] );
					$query = "UPDATE AUTO_SPARES

		    SET PHOTO_2='" .  $img_2 . "' WHERE ".(($_SESSION['user']['status'] !== 'admin') ? "ID_USER='".$_SESSION['user']['id_author']."' AND" : '')." ID='".$_SESSION['edID']."'";

		$res = mysql_query ( $query );
		}


///




		if ($_POST ['a_add'] == "A") {


			$query = "SELECT * FROM AUTO_USERS

		    WHERE id_author='" .  $_SESSION['user']['id_author'] . "'";

			$res = mysql_query ( $query );
			for($data=array();$row=mysql_fetch_assoc($res);$data=$row);
			$regionCode=$data['region'];
			$cityCode=$data['city'];

			$query = "UPDATE AUTO_SPARES

		SET
		`cat_id`='" . mysql_escape_string ( $razdel ) . "',
		`CAR_TYPE`='" . mysql_escape_string ( $id_typeCode ) . "',
		`CAR_MARK`='" . mysql_escape_string ( $id_markCode ) . "',
		`CAR_MODEL`='" . mysql_escape_string ( $id_modelCode ) . "',
		`PRICE`='" . mysql_escape_string ( $price ) . "',
		`DESCR`='" . mysql_escape_string ( $descr ) . "',
		`zag`='" . mysql_escape_string ( $zag ) . "' WHERE `ID`='".$_SESSION['edID']."'".(($_SESSION['user']['status'] !== 'admin') ? " AND `ID_USER`='".$_SESSION['user']['id_author']."'" : '');

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении объявления';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/changeSpares.html' );

			$html = str_replace ( '{URL}', '?action=showSpares&id='.$_SESSION['edID'], $html );

			$html = str_replace ( '{edit}', '?action=editSpares&id='.$_SESSION['edID'], $html );


		}



}

	return $html;



}

///////////////////////
function getSparesCats() {

	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormSpares'] );



	}

if ($_REQUEST['id']) {

$query = "SELECT cat_id, name FROM AUTO_SPARES_CATS where cat_id=" . mysql_escape_string($_REQUEST ['id']);

$res = mysql_query ( $query );

if ($res)
	for ($name_cat = array(); $row = mysql_fetch_assoc($res); $name_cat = $row);

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=sparesCats&id_typeCode=1&del=1">Товары и запчасти</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=sparesCats&id='.$name_cat['cat_id'].'">'.$name_cat['name'].'</a></span>';
} else {
	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=sparesCats&id_typeCode=1&del=1">Товары и запчасти</a></span>';
}

	$_SESSION ['pageTitle'] = "Товары и запчасти";

	$html = '';

	if (isset ( $_SESSION ['loginForm'] ['error'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );

	}

	$html = file_get_contents ( './templates/sparesCats.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=searchSpares';

	$html = str_replace ( '{action}', $action, $html );

	if (($_SESSION['user']['status']==='user') || ($_SESSION['user']['status']==='autosaloon'))
		$addspares='?action=addSpares';
	else
		$addspares='?action=loginForm';

	$html = str_replace ( '{ADDSPARES}', $addspares, $html );

/*
	if ((isset ( $_GET ['id_typeCode'] )) and (abs ( intval ( $_GET ['id_typeCode'] ) ) > 0)) {

		$_SESSION ['searchForm'] ['id_typeCode'] = abs ( intval ( $_GET ['id_typeCode'] ) );

	}


	if (! isset ( $_SESSION ['searchForm'] ['id_typeCode'] )) {

		$_SESSION ['searchForm'] ['id_typeCode'] = 1;

	}
*/

if ((isset ( $_GET ['id_typeCode'] )) and (abs ( intval ( $_GET ['id_typeCode'] ) ) > 0)) {

		$_SESSION ['searchFormSpares'] ['id_typeCode'] = abs ( intval ( $_GET ['id_typeCode'] ) );

	}



	if (! isset ( $_SESSION ['searchFormSpares'] ['id_typeCode'] )) {

		$_SESSION ['searchForm'] ['id_typeCode'] = 1;

	}

	for($i = 1; $i < 9; $i ++) {

		if ($_SESSION ['searchFormSpares'] ['id_typeCode'] == $i) {

			$html = str_replace ( '{select' . $i . '}', "class='navDiv3'", $html );

			$html = str_replace ( '{id_typeCode}', $i, $html );

		} else {

			$html = str_replace ( '{select' . $i . '}', "class='navDiv2'", $html );

		}



		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $_SESSION ['searchFormSpares'] ['id_typeCode'];

		$res = mysql_query ( $query );

		if (! $res) {



			die ();

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = @mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] === $_SESSION ['searchFormSpares'] ['id_markCode']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );

	}
if (!$_REQUEST['id']) {
//////
		$query = "SELECT * FROM AUTO_SPARES_CATS ORDER BY position";

		$res = mysql_query ( $query );

		if (! $res) {

			die ();

		}

		$cats = "";

		if (mysql_num_rows ( $res ) > 0) {

		$cats.='<table class="RB" width=560><tr>';
		$i=0;

			while ( $catlist = @mysql_fetch_array ( $res ) ) {
				if ((($i % 2) === 0) && ($i !== 0)) $cats .= '</tr><tr>';
				$cats .= '<td><a href="'.$action.'&id='.$catlist['cat_id'].'">'.$catlist['name'].'</a></td>';
				$i++;

			}

		$cats.='</tr>';
		$cats.='</table>';

		}
////
} else {
$cats = '

<table class="form_main" width="100%" cellspacing="0" cellpadding="0" align="center">
<tr>
<td class="title" align="left">'.$name_cat['name'].'</td>
</tr>
</table>

';
$fuck = true;
}



$query = "SELECT * FROM AUTO_CAR_TYPE order by CAR_TYPE ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($typelist ['ID'] === $_SESSION ['carbase'] ['id_typeCode']) ? " selected" : "";

			//$type.="<option value='".$typelist['ID']."' ".$selwrk." >".$typelist['CAR_TYPE']."</option>";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . ">" . $typelist ['CAR_TYPE'] . "</option>";



		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );


	for($i = 1; $i < 9; $i ++) {

		if ($_SESSION ['searchFormSpares'] ['id_typeCode'] == $i) {

			$html = str_replace ( '{select' . $i . '}', "class='navDiv3'", $html );

			$html = str_replace ( '{id_typeCode}', $i, $html );

		} else {

			$html = str_replace ( '{select' . $i . '}', "class='navDiv2'", $html );

		}



		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE='" . $_SESSION ['searchFormSpares'] ['id_typeCode']."'";

		$res = mysql_query ( $query );

		if (! $res) {

			die ();

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = @mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] === $_SESSION ['searchFormSpares'] ['id_markCode']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );

	}

	if (isset ( $_SESSION ['searchFormSpares'] ['id_markCode'] )) {

		$query = "SELECT * FROM AUTO_MODEL where TRADEMARK='" . $_SESSION ['searchForm'] ['id_markCode']."'";

		//echo $query;

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка моделй';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$model = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $modellist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($modellist ['ID'] === $_SESSION ['searchFormSpares'] ['id_modelCode']) ? " selected" : "";

				$model .= "<option value='" . $modellist ['ID'] . "' " . $selwrk . " >" . $modellist ['MODEL'] . "</option>";

			}

		}

		$html = str_replace ( '{MODEL}', $model, $html );

	}



	$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] === $_SESSION ['searchFormSpares'] ['id_region']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

		}

	}



	$html = str_replace ( '{REGION}', $region, $html );



	if (isset ( $_SESSION ['searchFormSpares'] ['id_region'] )) {



		$query = "SELECT * FROM AUTO_CITY where ID_REGION=" . $_SESSION ['searchFormSpares'] ['id_region'];

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] === $_SESSION ['searchFormSpares'] ['id_city']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['CITY'] . "</option>";

			}

		}

		$html = str_replace ( '{CITY}', $mark, $html );

	}


	$query = "SELECT COUNT(*) FROM AUTO_SPARES a";

	$query .= ", AUTO_USERS b where a.ID_USER=b.id_author and b.locked=0 and b.lock_admin=0 and a.CAR_TYPE='".$_SESSION ['searchFormSpares'] ['id_typeCode']."'";

	//$query.=($where!="")? " and ".$where:"";
//777
	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );

	////////
	$query = "SELECT COUNT(*) FROM AUTO_SPARES";

	//$query.=($where!="")? " and ".$where:"";
//777
	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$totalAll = mysql_result ( $res, 0, 0 );


	///////////

	$html = str_replace ( '{TOTAL}', $totalAll, $html );

	$html = str_replace ( '{CATS}', $cats, $html );




	// если фильтр установлен выбираем записи

	$html .= "";

	//777

	if (isset ( $_SESSION ['searchFormSpares'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', showTableSpares ( $_SESSION ['searchFormSpares'] ['sql'], 'searchSpares', 'showSpares' ), $html );



	} else {

		$html = str_replace ( '{FOUND}', showTableSpares ( $_SESSION ['searchFormSpares'] ['sql'], 'searchSpares', 'showSpares' ), $html );
		//$html = str_replace ( '{FOUND}', file_get_contents ( './templates/defaultForm.html' ), $html );


	}

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}

//888
function getMySparesCats() {

	$_SESSION ['pageTitle'] = "Мои товары и запчасти";

	$html = '';

	if (isset ( $_SESSION ['loginForm'] ['error'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );

	}

	if (empty($_REQUEST['ch'])) {

	$html = file_get_contents ( './templates/mySparesCats.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=mySpares';

	$html = str_replace ( '{action}', $action, $html );

	$query = "SELECT COUNT(*) FROM AUTO_SPARES a";

	$query .= ", AUTO_USERS b where a.ID_USER=b.id_author and b.locked=0 and b.lock_admin=0 and a.CAR_TYPE='".$_SESSION ['searchFormMySpares'] ['id_typeCode']."'";

	//$query.=($where!="")? " and ".$where:"";
//777
	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );

	$html = str_replace ( '{TOTAL}', $total, $html );

	//$html = str_replace ( '{CATS}', $cats, $html );




	// если фильтр установлен выбираем записи

	$html .= "<br>";

	//777

	if (isset ( $_SESSION ['searchFormMySpares'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', showTableMySpares ( $_SESSION ['searchFormMySpares'] ['sql'], 'mySpares', 'editSpares' ), $html );



	} else {

		$html = str_replace ( '{FOUND}', showTableMySpares ( $_SESSION ['searchFormMySpares'] ['sql'], 'mySpares', 'editSpares' ), $html );
		//$html = str_replace ( '{FOUND}', file_get_contents ( './templates/defaultForm.html' ), $html );

	}
	} else {

	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1") {

				$query = "SELECT PHOTO_1,PHOTO_2 FROM AUTO_SPARES WHERE ".(($_SESSION['user']['status'] !== 'admin') ? " ID_USER='".$_SESSION['user']['id_author']."' AND" : '')."  ID IN ". $q;

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph[]=array();$row=mysql_fetch_assoc($res);$ph[]=$row);

			}
		for ($i=0;$i<count($ph);$i++) {
			if ($ph[$i]['PHOTO_1']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph[$i]['PHOTO_1']);
			if ($ph[$i]['PHOTO_2']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph[$i]['PHOTO_2']);

			}

	$query = "DELETE FROM AUTO_SPARES WHERE ".(($_SESSION['user']['status'] !== 'admin') ? " ID_USER='".$_SESSION['user']['id_author']."' AND" : '')."  ID IN ". $q;

}
	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Выбранные объявления были успешно удалены!<br><a href="?action=addSpares">Добавить объявление</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=mySpares">Мои товары и запчасти</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}
//888

//888
function showTableMySpares($where, $action, $ShowCarInfo) {

	$column = $_GET ['order'];

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];



	$query = "SELECT COUNT(*) FROM AUTO_SPARES a";
if ($_SESSION['user']['status'] !== 'admin')
	$query .= ", AUTO_USERS b where a.ID_USER=b.id_author and b.locked=0 and b.lock_admin=0 and a.ID_USER='".$_SESSION['user']['id_author']."' ";
else
	$query .= ", AUTO_USERS b where a.ID_USER=b.id_author and b.locked=0 and b.lock_admin=0 ";

	$query .= ($where != "") ? " and " . $where : "";



	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );

	//тута

	if ($total == 0) {

		$html .= file_get_contents ( './templates/searchnomySpares.html' );

		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	$table .= "<table width=\"100%\"><tr><td>";


	$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_add.gif); width: 142px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=addSpares\"></a>" . "</td></tr></table>";

	$table .= '<form name="chform" method="post">';

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete





	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";



	$table .= "<td valign=\"top\" width=\"30\" align=\"center\">";

	$table .= "<input type=\"checkbox\" title=\"Отметить все\" onclick=\"checkAll(this);\" name=\"chAll\"/></td>";

	if ($_SESSION['user']['status'] === 'admin') {

	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'id' ) . "&ordertype=";

	$table .= SortOrder ( 'id' );

	$table .= " \">№";

	if ($_SESSION ['sort'] ['id'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['id'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";
	}

	$table .= "<td valign=\"middle\" width=\"100\" align=\"center\">фото</td>";



	$table .= "<td valign=\"middle\" width=\"280\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'zag' ) . "&ordertype=";

	$table .= SortOrder ( 'zag' );

	$table .= " \">описание";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	$table .= "<td valign=\"middle\" width=\"190\" align=\"center\">";

	$table .= "автомобиль</td>";

	if ($_SESSION['user']['status'] === 'admin') {

	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'name' ) . "&ordertype=";

	$table .= SortOrder ( 'name' );

	$table .= " \">продавец";

	if ($_SESSION ['sort'] ['id'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['id'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";
	}

	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'price' ) . "&ordertype=";

	$table .= SortOrder ( 'price' );

	$table .= " \">цена, руб.";

	if ($_SESSION ['sort'] ['price'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['price'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";





	$table .= "</tr>";



	$query = "
		SELECT
			a.zag,
			a.ID,
			a.cat_id,
			a.CAR_TYPE,
			j.CITY,
			f.MODEL,
			i.TRADEMARK,
			r.REGION,
			b.CAR_TYPE as TYPE,
			a.PRICE,
			a.CAR_MARK,
			a.PHOTO_1,
			a.ID_USER,
			UNIX_TIMESTAMP(a.DATE) as DATE,
			c.*
		FROM
			AUTO_USERS c,
			AUTO_SPARES a
		left join
			AUTO_CAR_TYPE b
		on
			b.ID = a.CAR_TYPE
		left join
			AUTO_MODEL f
		on
			f.ID = a.CAR_MODEL
		left join
			AUTO_TRADEMARK i
		on
			i.ID = a.CAR_MARK
		left join
			AUTO_CITY j
		on
			a.CITY = j.ID
		left join
			AUTO_REGION r
		on
			a.REGION = r.ID
		where
			a.CAR_TYPE = '{$_SESSION ['searchFormSpares'] ['id_typeCode']}'
		and
			( a.ID_USER = c.id_author and c.locked = 0 and c.lock_admin = 0 )
		";

if ($_SESSION['user']['status'] !== 'admin')
	$query .= " and a.ID_USER='".$_SESSION['user']['id_author']."'";
	//$query .= " and a.CAR_TYPE='".$_SESSION ['searchForm'] ['id_typeCode']."'";

	if (isset ( $_GET ['order'] ))

		$query .= " ORDER BY " . $_GET ['order'] . " " . $_GET ['ordertype'];

	else

		$query .= " ORDER BY a.DATE desc";

	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;



	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {

			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}

			$photo = (isset ( $autolist ['PHOTO_1'] )) ? $autolist ['PHOTO_1'] : "";

			if ($photo == "") {

				$img = "<img src=\"photo/nofoto.gif\" border=0  alt=\"Подробнее\"/>";

			} else {

				$img = "<img src=\"show_image.php?filename=photo/" . $photo . "&width=75\" border=0  alt=\"Подробнее\"/>";

			}



			$table .= "<tr class=$CssClass>";

			//
			$table .= '<td align="center"><input type="checkbox" value="'. $autolist ['ID'] . '" name="ch[]"/></a>';

			$table .= "</td>";
			//

			if ($_SESSION['user']['status'] === 'admin') {
			//
			$table .= '<td align="center">'. $autolist ['ID'];

			$table .= "</td>";
			//
			}

			$table .= "<td align='center'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=$ShowCarInfo&id=" . $autolist ['ID'] . ">$img</a>";

			$table .= "</td>";



			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=$ShowCarInfo&id=" . $autolist ['ID'] . "><b>" . $autolist ['zag'] . "</b>, ";


			$table .= "</a></br>" . date ( "d.m.Y", $autolist ['DATE'] );

			$table .= "</td>";

			$table .= "<td align=\"center\">" . ($autolist ['MARK'] !== " " ? $autolist ['MARK'] : '').($autolist ['MODEL'] !== " " ? ",</br>". $autolist ['MODEL'] : '') . "</td>";

			$table .= "</td>";

			if ($_SESSION['user']['status'] === 'admin') {

			if ($autolist ['status'] === "autosaloon" || $autolist ['status'] === "admin") {

				$table .= "<td valign=\"center\" align=\"center\">";

				$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $autolist ['id_author'] . "><strong>" . $autolist ["name"] . "</strong></a></br>" . $CITY_A; //$name.$region.$city.$status;

				$table .= "</td>";



			} else {

				$table .= "<td valign=\"center\" align=\"center\">";

				$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=SaloonByID&id=" . $autolist ['id_author'] . "><strong>" . $autolist ["name"] . "</strong></a></br>" . $CITY_A; //$name.$region.$city.$status;

				$table .= "</td>";

			}

			}

			$table .= "<td align=\"center\">" . round ( $autolist ['PRICE'] );

			$table .= "</td>";








			$table .= "</tr>";



		}

		$table .= "</td></tr></table>";

	}

$table .= "<table width=\"100%\"><tr><td widht=\"100\"><table><tr><td>С отмеченным:
<select id=\"mode\" name=\"mode\">
<option value=\"1\">Удалить</option>
</select></td><td><a href=\"#\" style=\"background-image:url(img/btn_apply.gif); width: 84px; height: 21px;\"  onclick=\"this.blur(); javascript: document.searchForm.submit();\" class=\"button_apply\"></a></td></tr></table></form></td><td align=\"right\">" . $pages . "</td></tr></table>";

//парарам

	$table .= "</td></tr></table>";



	return $table;

}
//888

//888
function getMyNews() {

	if ($_SESSION['user']['status'] !=='autosaloon' && $_SESSION['user']['status'] !=='admin') {
		die();
	}

	$_SESSION ['pageTitle'] = "Новости автосалона";

	$html = '';

	if (isset ( $_SESSION ['loginForm'] ['error'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );

	}
	//

	if (empty($_REQUEST['ch'])) {

	$html = file_get_contents ( './templates/myNews.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=myNews';

	$html = str_replace ( '{action}', $action, $html );

	$query = "SELECT COUNT(*) FROM AUTO_NEWS a";

	$query .= ", AUTO_USERS b where a.ID_SALOON=b.id_author and b.locked=0 and b.lock_admin=0 and a.ID_SALOON='".$_SESSION ['user'] ['id_author']."'";

	//$query.=($where!="")? " and ".$where:"";
//777
	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );

	$html = str_replace ( '{TOTAL}', $total, $html );

	//$html = str_replace ( '{CATS}', $cats, $html );




	// если фильтр установлен выбираем записи

	$html .= "<br>";

	//777

	if (isset ( $_SESSION ['searchForm'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', showTableMyNews ( $_SESSION ['searchFormMyNews'] ['sql'], 'myNews', 'editNews' ), $html );



	} else {

		$html = str_replace ( '{FOUND}', showTableMyNews ( $_SESSION ['searchFormMyNews'] ['sql'], 'myNews', 'editNews' ), $html );
		//$html = str_replace ( '{FOUND}', file_get_contents ( './templates/defaultForm.html' ), $html );

	}
	} else {

	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1") {

				$query = "SELECT PHOTO FROM AUTO_NEWS WHERE ID_SALOON='".$_SESSION['user']['id_author']."' AND ID IN ". $q;

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph[]=array();$row=mysql_fetch_assoc($res);$ph[]=$row);

			}
		for ($i=0;$i<count($ph);$i++)
			if ($ph[$i]['PHOTO']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph[$i]['PHOTO']);


	$query = "DELETE FROM AUTO_NEWS WHERE ID IN ". $q ." AND ID_SALOON='".$_SESSION['user']['id_author']."'";

}
	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Выбранные новости были успешно удалены!<br><a href="?action=addNews">Добавить новость</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=myNews">Новости автосалона</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}
//888

function getAllNews() {
//вернись
	if ($_SESSION['user']['status'] !=='admin') {
		die();
	}

	$_SESSION ['pageTitle'] = "Новости автосалонов";

	$html = '';

	if (isset ( $_SESSION ['loginForm'] ['error'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );

	}
	//

	if (empty($_REQUEST['ch'])) {

	$html = file_get_contents ( './templates/allNews.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=allNews';

	$html = str_replace ( '{action}', $action, $html );

	$query = "SELECT COUNT(*) FROM AUTO_NEWS a";

	$query .= ", AUTO_USERS b where a.ID_SALOON=b.id_author and b.locked=0 and b.lock_admin=0 ";

	//$query.=($where!="")? " and ".$where:"";
//777
	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );

	$html = str_replace ( '{TOTAL}', $total, $html );

	//$html = str_replace ( '{CATS}', $cats, $html );




	// если фильтр установлен выбираем записи

	$html .= "<br>";

	//777

	if (isset ( $_SESSION ['searchForm'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', showTableMyNews ( $_SESSION ['searchFormMyNews'] ['sql'], 'allNews', 'editNews' ), $html );



	} else {

		$html = str_replace ( '{FOUND}', showTableMyNews ( $_SESSION ['searchFormMyNews'] ['sql'], 'allNews', 'editNews' ), $html );
		//$html = str_replace ( '{FOUND}', file_get_contents ( './templates/defaultForm.html' ), $html );

	}
	} else {

	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1") {

				$query = "SELECT PHOTO FROM AUTO_NEWS WHERE ID IN ". $q;

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph[]=array();$row=mysql_fetch_assoc($res);$ph[]=$row);

			}
		for ($i=0;$i<count($ph);$i++)
			if ($ph[$i]['PHOTO']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph[$i]['PHOTO']);


	$query = "DELETE FROM AUTO_NEWS WHERE ID IN ". $q;

}
	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Выбранные новости были успешно удалены!<br><a href="?action=addNews">Добавить новость</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=allNews">Новости автосалонов</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}

//888
function showTableMyNews($where, $action, $ShowCarInfo) {

	$column = $_GET ['order'];

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];

	//echo $where;

	$query = "SELECT COUNT(*) FROM AUTO_NEWS a";
//print_r($_SESSION);
//echo $_SESSION['user']['id_author'];
if ($_REQUEST['action'] === 'allNews')
	$query .= ", AUTO_USERS b where a.ID_SALOON=b.id_author and b.locked=0 and b.lock_admin=0  ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'].' ' : '');
else
	$query .= ", AUTO_USERS b where a.ID_SALOON=b.id_author and b.locked=0 and b.lock_admin=0  AND ID_SALOON='".$_SESSION['user']['id_author']."'";

	$query .= ($where != "") ? " and " . $where : "";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );

	//тута

	if ($total == 0) {

		$html .= file_get_contents ( './templates/searchnoallNews.html' );

		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	$table .= "<table width=\"100%\"><tr><td>";


	$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_news.gif); width: 122px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=addNews\"></a>" . "</td></tr></table>";

	$table .= '<form name="chform" method="post">';

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete


//121212


	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";


	$table .= "<td valign=\"top\" width=\"30\" align=\"center\">";

	$table .= "<input type=\"checkbox\" title=\"Отметить все\" onclick=\"checkAll(this);\" name=\"chAll\"/></td>";


	if ($_SESSION['user']['status'] === 'admin') {

	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'id' ) . "&ordertype=";

	$table .= SortOrder ( 'id' );

	$table .= " \">№";

	if ($_SESSION ['sort'] ['id'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['id'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";
	}


	$table .= "<td valign=\"top\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'DATA' ) . "&ordertype=";

	$table .= SortOrder ( 'DATA' );

	$table .= " \">дата";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"top\" width=\"180\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'ZAGOL' ) . "&ordertype=";

	$table .= SortOrder ( 'ZAGOL' );

	$table .= " \">заголовок";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	$table .= "<td valign=\"top\" width=\"290\" align=\"center\">";

	$table .= "краткое описание</td>";

	$table .= "<td valign=\"top\" width=\"100\" align=\"center\">фото</td>";




	$table .= "</tr>";



	$query = "SELECT a.ID as ID, a.ZAGOL as ZAGOL, a.SMALL_TEXT as SMALL_TEXT, a.TEXT as TEXT, UNIX_TIMESTAMP(a.DATA) as DATE, a.PHOTO as PHOTO, a.ID_SALOON, c.* FROM  AUTO_NEWS a, AUTO_USERS c where ";

	//$query .= "c.ID_USER=a.CAR_TYPE ";

//777
	//$query .= "b.ID='".$_SESSION ['searchForm'] ['id_markCode']."' ";

	// $query.= " and e.ID=a.TYPE_KUZ";

	// $query.= " and d.ID=a.TYPE_DVIG";

	//$query .= " and f.ID=a.CAR_MODEL";

	//$query .= " and i.ID=a.CAR_MARK";

	//$query .= " and a.CITY=j.ID";

	//$query .= " and a.REGION=r.ID";
if ($_SESSION['user']['status'] !=='admin' || $_REQUEST['action'] !== 'allNews')
	$query .= "a.ID_SALOON='".$_SESSION['user']['id_author']."' and ";
	//$query .= " and a.CAR_TYPE='".$_SESSION ['searchForm'] ['id_typeCode']."'";



	//$query.= " and c.CITY=k.ID";

	$query .= " (a.ID_SALOON=c.id_author and c.locked=0 and c.lock_admin=0)";

	$query .= (($where) != "") ? " and " . $where : "";

	// $query.= $_SESSION['searchForm']['sql'];

	if (isset ( $_GET ['order'] ))

		$query .= " ORDER BY " . $_GET ['order'] . " " . $_GET ['ordertype'];

	else

		$query .= " ORDER BY a.DATA desc";

	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;



	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;



	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {

			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}

			$photo = (isset ( $autolist ['PHOTO'] )) ? $autolist ['PHOTO'] : "";

			if ($photo == "") {

				$img = "Фото отсутствует";

			} else {

				$img = "<img src=\"show_image.php?filename=photo/" . $photo . "&width=75\" border=0  alt=\"Подробнее\"/>";

			}



			$table .= "<tr class=$CssClass>";

			//
			$table .= '<td align="center"><input type="checkbox" value="'. $autolist ['ID'] . '" name="ch[]"/></a>';

			$table .= "</td>";
			//

			if ($_SESSION['user']['status'] === 'admin') {
			//
			$table .= '<td align="center">'. $autolist ['ID'];

			$table .= "</td>";
			//
			}

			$table .= "<td class='phpmakerlist'>";

			$table .= date ( "d.m.Y", $autolist ['DATE'] );

			$table .= "</a></td>";

			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=$ShowCarInfo&id=" . $autolist ['ID'] . "><b>" . $autolist ['ZAGOL'] . "</b>";

			$table .= "</a></td>";

			$table .= "<td>" . nl2br($autolist ['SMALL_TEXT']);//тута

			$table .= "</td>";


			$table .= "<td align='center'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=$ShowCarInfo&id=" . $autolist ['ID'] . ">$img</a>";

			$table .= "</td>";









			$table .= "</tr>";



		}

		$table .= "</td></tr></table>";

	}

$table .= "<table width=\"100%\"><tr><td widht=\"100\"><table><tr><td>С отмеченным:
<select id=\"mode\" name=\"mode\">
<option value=\"1\">Удалить</option>
</select></td><td><a href=\"#\" style=\"background-image:url(img/btn_apply.gif); width: 84px; height: 21px;\"  onclick=\"this.blur(); javascript: document.searchForm.submit();\" class=\"button_apply\"></a></td></tr></table></form></form></td><td align=\"right\">" . $pages . "</td></tr></table>";

//парарам

	$table .= "</td></tr></table>";



	return $table;

}
//888


function searchSpares() {



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormSpares'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Поиск по объявлениям";

	//if (isset($_POST['or'])) $where[]="CAR_MODEL=".$_POST['id_modelCode'] ;

	$where = "";



	if ($_POST) {

		if (isset ( $_POST ['id'] ))

			$_SESSION ['searchFormSpares'] ['id'] = intval ( RemoveXSS ( $_POST ['id'] ) );

		if (isset ( $_POST ['id_typeCode'] ))

			$_SESSION ['searchFormSpares'] ['id_typeCode'] = intval ( RemoveXSS ( $_POST ['id_typeCode'] ) );

		if (isset ( $_POST ['search_word'] ))

			$_SESSION ['searchFormSpares'] ['search_word'] = RemoveXSS ( $_POST ['search_word'] ) ;


		if (isset ( $_POST ['id_markCode'] ))

			$_SESSION ['searchFormSpares'] ['id_markCode'] = intval ( RemoveXSS ( $_POST ['id_markCode'] ) );

		if (isset ( $_POST ['id_modelCode'] ))

			$_SESSION ['searchFormSpares'] ['id_modelCode'] = intval ( RemoveXSS ( $_POST ['id_modelCode'] ) );

		if (isset ( $_POST ['id_region'] ))

			$_SESSION ['searchFormSpares'] ['id_region'] = intval ( RemoveXSS ( $_POST ['id_region'] ) );

		if (isset ( $_POST ['cityCode'] ))

			$_SESSION ['searchFormSpares'] ['id_city'] = intval ( RemoveXSS ( $_POST ['cityCode'] ) );

		$_SESSION ['searchFormSpares'] ['foto'] = ($_POST ['foto'] > 0) ? 1 : 0;

		/*

		if ($_POST['new_model']>0)

		{

		$_SESSION['searchForm']['new']=1;

		}

		else

		{

		$_SESSION['searchForm']['new']=0;

		}



		if ($_POST['foto']>0)

		{

		$_SESSION['searchForm']['foto']=1;

		}

		else

		{

		$_SESSION['searchForm']['foto']=0;

		}

		*/

		//$_SESSION['searchForm']['new']=intval(RemoveXSS($_POST['new_model']));


		//if (isset ( $_POST ['id_typeCode'] ) and $_POST ['id_typeCode'] > 0)

			//$where .= "a.CAR_TYPE=" . intval ( mysql_escape_string ( $_POST ['id_typeCode'] ) );//????????????????????????????

		if (isset ( $_POST ['search_word'] ) and !($_POST ['search_word'] === ''))

			$where = ($where != "") ? $where . " and a.DESCR like '%" . mysql_escape_string ( $_POST ['search_word'] )  . "%'" : "a.DESCR like '%" .  mysql_escape_string ( $_POST ['search_word'] ) . "%'";

		if (isset ( $_POST ['id'] ) and $_POST ['id'] > 0)

			$where = ($where != "") ? $where . " and a.cat_id=" . intval ( mysql_escape_string ( $_POST ['id'] ) ) : "a.cat_id=" .  intval ( mysql_escape_string ( $_POST ['id'] ) );


		if (isset ( $_POST ['id_markCode'] ) and $_POST ['id_markCode'] > 0)

			$where = ($where != "") ? $where . " and a.CAR_MARK=" . intval ( mysql_escape_string ( $_POST ['id_markCode'] ) ) : "a.CAR_MARK=" . intval ( mysql_escape_string ( $_POST ['id_markCode'] ) );

		if (isset ( $_POST ['id_modelCode'] ) and $_POST ['id_modelCode'] > 0)

			$where = ($where != "") ? $where . " and a.CAR_MODEL=" . intval ( mysql_escape_string ( $_POST ['id_modelCode'] ) ) : "a.CAR_MODEL=" . intval ( mysql_escape_string ( $_POST ['id_modelCode'] ) );

		if (isset ( $_POST ['id_region'] ) and $_POST ['id_region'] > 0)

			$where = ($where != "") ? $where . " and a.REGION=" . intval ( mysql_escape_string ( $_POST ['id_region'] ) ) : "a.REGION=" . intval ( mysql_escape_string ( $_POST ['id_region'] ) );

		if (isset ( $_POST ['cityCode'] ) and $_POST ['cityCode'] > 0)

			$where = ($where != "") ? $where . " and a.CITY=" . intval ( mysql_escape_string ( $_POST ['cityCode'] ) ) : "a.CITY=" . intval ( mysql_escape_string ( $_POST ['cityCode'] ) );

		if ($_POST ['foto'] > 0)

			$where = ($where != "") ? $where . " and (a.PHOTO_1 <> '' or a.PHOTO_2 <> '') " : " (a.PHOTO_1 <> '' or a.PHOTO_2 <> '')  ";


	//$price1=$_POST['price1'];

	//$price2=$_POST['price2'];





	}



	if ($_GET) {

		if (isset ( $_GET ['search_word'] ))

			$_SESSION ['searchFormSpares'] ['search_word'] = RemoveXSS ( $_GET ['id_typeCode'] );

		if (isset ( $_GET ['id'] ))

			$_SESSION ['searchFormSpares'] ['id'] = intval ( RemoveXSS ( $_GET ['id'] ) );

		if (isset ( $_GET ['id_typeCode'] ))

			$_SESSION ['searchFormSpares'] ['id_typeCode'] = intval ( RemoveXSS ( $_GET ['id_typeCode'] ) );

		if (isset ( $_GET ['id_markCode'] ))

			$_SESSION ['searchFormSpares'] ['id_markCode'] = intval ( RemoveXSS ( $_GET ['id_markCode'] ) );

		if (isset ( $_GET ['id_modelCode'] ))

			$_SESSION ['searchFormSpares'] ['id_modelCode'] = intval ( RemoveXSS ( $_GET ['id_modelCode'] ) );

		if (isset ( $_GET ['id_region'] ))

			$_SESSION ['searchFormSpares'] ['id_region'] = intval ( RemoveXSS ( $_GET ['id_region'] ) );

		if (isset ( $_GET ['cityCode'] ))

			$_SESSION ['searchFormSpares'] ['id_city'] = intval ( RemoveXSS ( $_GET ['cityCode'] ) );

		if ($_GET ['foto'] > 0) {

			$_SESSION ['searchFormSpares'] ['foto'] = intval ( RemoveXSS ( $_GET ['foto'] ) );

		} else {

			//unset($_SESSION['searchForm']['foto']);

		}

		//$_SESSION['searchForm']['new']=intval($_GET['new_model']);



		if (isset ( $_GET ['id_typeCode'] ) and $_GET ['id_typeCode'] > 0)

			$where .= "a.CAR_TYPE=" . intval ( mysql_escape_string ( $_GET ['id_typeCode'] ) );

		if (isset ( $_GET ['search_word'] ) and !($_GET ['search_word'] === ''))

			$where = ($where != "") ? $where . " and a.DESCR like '%" . mysql_escape_string ( $_GET ['search_word'] )  . "%'" : "a.DESCR like '%" .  mysql_escape_string ( $_GET ['search_word'] ) . "%'";


		if (isset ( $_GET ['id_markCode'] ) and $_GET ['id_markCode'] > 0)

			$where = ($where != "") ? $where . " and a.CAR_MARK=" . intval ( mysql_escape_string ( $_GET ['id_markCode'] ) ) : "CAR_MARK=" . intval ( mysql_escape_string ( $_GET ['id_markCode'] ) );

		if (isset ( $_GET ['id'] ) and $_GET ['id'] > 0)

			$where = ($where != "") ? $where . " and a.cat_id=" . intval ( mysql_escape_string ( $_GET ['id'] ) ) : "a.cat_id=" .  intval ( mysql_escape_string ( $_GET ['id'] ) );


		if (isset ( $_GET ['id_modelCode'] ) and $_GET ['id_modelCode'] > 0)

			$where = ($where != "") ? $where . " and a.CAR_MODEL=" . intval ( mysql_escape_string ( $_GET ['id_modelCode'] ) ) : "CAR_MODEL=" . intval ( mysql_escape_string ( $_GET ['id_modelCode'] ) );

		if (isset ( $_GET ['id_region'] ) and $_GET ['id_region'] > 0)

			$where = ($where != "") ? $where . " and a.REGION=" . intval ( mysql_escape_string ( $_GET ['id_region'] ) ) : "a.REGION=" . intval ( mysql_escape_string ( $_GET ['id_region'] ) );

		if (isset ( $_GET ['cityCode'] ) and $_GET ['cityCode'] > 0)

			$where = ($where != "") ? $where . " and a.CITY=" . intval ( mysql_escape_string ( $_GET ['cityCode'] ) ) : "a.CITY=" . intval ( mysql_escape_string ( $_GET ['cityCode'] ) );


			//$price1=$_GET['price1'];

		//$price2=$_GET['price2'];



		if ($_GET ['foto'] > 0)

			$where = ($where != "") ? $where . " and (a.PHOTO_1 <> '' or a.PHOTO_2 <> '') " : " (a.PHOTO_1 <> '' or a.PHOTO_2 <> '')  ";



	//echo $_GET['id_typeCode'];

	}

	//echo $_GET['id_typeCode'];

	//echo $_POST['id_typeCode'];

	//$html.=$_POST['or_field'].'\n';





	//$html.=$_GET['id_typeCode']. $_GET['id_markCode'].$_GET['id_modelCode'];



	$_SESSION ['searchFormSpares'] ['sql'] = $where;
	//echo $_SESSION ['searchForm'] ['sql'];
	/*

	if ($price2<$price1)

	{

	$msg = 'Произошла ошибка при получении сообщения';

	$err = 'Ошибка при выполнении запроса: <br/>'.

	$query.'<br/>'.mysql_errno().':&nbsp;'.mysql_error().'<br/>'.

	'(Файл '. __FILE__ .', строка '. __LINE__ .')';

	//return showInfoMessage("price2<price1");

	$html.=showInfoMessage($msg);

	}

	*/



	//$where=array();





	//$html.=$_SESSION['id_markCode'];

	$html .= getSparesCats ();



	$html2 = file_get_contents ( './templates/foundSpares.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );



	return $html2;

}



// Функция возвращает html формы для авторизации на форуме

function getLoginForm() {

$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=loginForm">Размещение объявлений</a></span>';

$_SESSION ['pageTitle'] = "Размещение объявлений";

	$html = '';

	if (isset ( $_SESSION ['loginForm'] ['error'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );

	}

	$action = $_SERVER ['PHP_SELF'] . '?action=login';

	$newPassword = '<a href="' . $_SERVER ['PHP_SELF'] . '?action=newPasswordForm">Забыли пароль?</a>' . "\n";

	$register = '<a href="' . $_SERVER ['PHP_SELF'] . '?action=loginForm">Регистрация</a>' . "\n";



	$tpl = file_get_contents ( './templates/loginForm.html' );

	$tpl = str_replace ( '{action}', $action, $tpl );

	$tpl = str_replace ( '{newpassword}', $newPassword, $tpl );

	$tpl = str_replace ( '{register}', $register, $tpl );

	$html = $html . $tpl;

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;





}



// Вход на форум - обработчик формы авторизации

function login() {

	// Если не переданы данные формы - значит функция была вызвана по ошибке

	if (! isset ( $_POST ['username'] ) or ! isset ( $_POST ['password'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}



	// Защита от перебора пароля - при каждой неудачной попытке время задержки увеличивается

	if (isset ( $_SESSION ['loginForm'] ['count'] ))

		sleep ( $_SESSION ['loginForm'] ['count'] );



	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$name = substr ( $_POST ['username'], 0, 30 );

	$password = substr ( $_POST ['password'], 0, 30 );



	// Обрезаем лишние пробелы

	$name = trim ( $name );

	$password = trim ( $password );



	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (empty ( $name ))

		$error = $error . '<li>не заполнено поле "Имя"</li>' . "\n";

	if (empty ( $password ))

		$error = $error . '<li>не заполнено поле "Пароль"</li>' . "\n";



	// Проверяем поля формы на недопустимые символы

	if (! empty ( $name ) and ! preg_match ( "#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $name ))

		$error = $error . '<li>поле "Имя" содержит недопустимые символы</li>' . "\n";

	if (! empty ( $password ) and ! preg_match ( "#^[-_0-9a-z]+$#i", $password ))

		$error = $error . '<li>поле "Пароль" содержит недопустимые символы</li>' . "\n";



	// Проверять существование такого пользователя есть смысл только в том

	// случае, если поля не пустые и не содержат недопустимых символов

	if (empty ( $error )) {

		// Выполняем запрос на получение данных пользователя из БД

		$query = "SELECT *, UNIX_TIMESTAMP(last_visit) as unix_last_visit

              FROM " . TABLE_USERS . "

              WHERE email='" . mysql_real_escape_string ( $name ) . "'

			  AND passw='" . mysql_real_escape_string ( md5 ( $password ) ) . "'

			  LIMIT 1";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при авторизации пользователя';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, 'action=loginForm' );

		}

		if (mysql_num_rows ( $res ) == 0)

			$error = $error . '<li>Неправильный логин или пароль</li>' . "\n";

	}



	// Если были допущены ошибки при заполнении формы

	if (! empty ( $error )) {

		if (! isset ( $_SESSION ['loginForm'] ['count'] ))

			$_SESSION ['loginForm'] ['count'] = 1;

		else

			$_SESSION ['loginForm'] ['count'] = $_SESSION ['loginForm'] ['count'] + 1;

		$_SESSION ['loginForm'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=loginForm' );

		die ();

	}



	// Все поля заполнены правильно и такой пользователь существует - продолжаем...

	unset ( $_SESSION ['loginForm'] );

	$user = mysql_fetch_assoc ( $res );



	if (! empty ( $user ['activation'] ))
{
	$mmm = '<br/>

      <center><p><strong>Ваша учетная запись не активирована</strong></p></center>

    <br/>';
		return showInfoMessage ( $mmm, '' );
}


	// Если пользователь заблокирован

	if (($user ['locked']) or ($user ['lock_admin'])) {
	$mmm = '<br/>

      <center><p><strong>Ваша учетная запись заблокирована. Обратитесь к администратору.</strong></p></center>

    <br/>';
		return showInfoMessage ( $mmm, '' );
}
	$_SESSION ['user'] = $user;



	if ($user ['status'] == 'autosaloon') {

		define ( "EW_PROJECT_NAME", "saloon_control", TRUE ); // Project Name

		define ( "EW_RANDOM_KEY", 'A%Ysd4eceH3NG3No', TRUE ); // Random key for encryption





	// Функция getNewThemes() помещает в массив $_SESSION['newThemes'] ID тем,

	// в которых были новые сообщения со времени последнего посещения пользователя

	//getNewThemes();

	} else {

		define ( "EW_PROJECT_NAME", "user_control", TRUE ); // Project Name

		define ( "EW_RANDOM_KEY", '3#2e9dK&H#J3qh2L', TRUE ); // Random key for encryption

	}



	define ( "EW_SESSION_STATUS", EW_PROJECT_NAME . "_status", TRUE ); // Login Status

	define ( "EW_SESSION_USER_CITY", EW_SESSION_STATUS . "_city", TRUE ); // Login Status

	define ( "EW_SESSION_USER_REGION", EW_SESSION_STATUS . "_region", TRUE ); // User Name

	define ( "EW_SESSION_USER_NAME", EW_SESSION_STATUS . "_UserName", TRUE ); // User Name

	define ( "EW_SESSION_USER_ID", EW_SESSION_STATUS . "_UserID", TRUE ); // User ID

	$_SESSION [EW_SESSION_STATUS] = "login";

	$_SESSION [EW_SESSION_SYS_ADMIN] = 0; // Non System Administrator

	$_SESSION [EW_SESSION_USER_NAME] = $_SESSION ['user'] ['email']; // Load user name

	$_SESSION [EW_SESSION_USER_ID] = $_SESSION ['user'] ['id_author']; // Load User ID

	$_SESSION [EW_SESSION_USER_CITY] = $_SESSION ['user'] ['city']; // Load user name

	$_SESSION [EW_SESSION_USER_REGION] = $_SESSION ['user'] ['region']; // Load User ID





	// Выставляем cookie, если пользователь хочет входить на форум автоматически

	if (isset ( $_POST ['autologin'] )) {

		$tmppos = strrpos ( $_SERVER ['PHP_SELF'], '/' ) + 1;

		$path = substr ( $_SERVER ['PHP_SELF'], 0, $tmppos );

		setcookie ( 'autologin', 'yes', time () + 3600 * 24 * COOKIE_TIME, $path );

		setcookie ( 'username', $_SESSION ['user'] ['name'], time () + 3600 * 24 * COOKIE_TIME, $path );

		setcookie ( 'password', $_SESSION ['user'] ['passw'], time () + 3600 * 24 * COOKIE_TIME, $path );



	}

	/*

	if ($_SESSION['user']['status']=='autosaloon')

	{

	$expirytime = time() + 365*24*60*60;

	//echo "#@#$";

	//echo EW_PROJECT_NAME ;

	setcookie(EW_PROJECT_NAME . '[AutoLogin]',  "autologin", $expirytime); // Set up autologin cookies

	setcookie(EW_PROJECT_NAME . '[UserName]', $name, $expirytime); // Set up user name cookies

	setcookie(EW_PROJECT_NAME . '[Password]', TEAencrypt($password, EW_RANDOM_KEY), $expirytime); // Set up password cookies

	//User_LoggedIn($name);

	//header( '/saloon/');

	//die();

	}

	*/
/*
	if ($_SESSION ['action_url'] == "add") {

		if ($_SESSION ['user'] ['status'] == "autosaloon") {

			header ( 'Location: http://' . $_SERVER ['SERVER_NAME'] . "/control/CARBASEadd.php" );

			die ();

		}



		if ($_SESSION ['user'] ['status'] == "user") {

			header ( 'Location: http://' . $_SERVER ['SERVER_NAME'] . "/user/CARBASEadd.php" );

			die ();

		}

	}
*/
	// Авторизация прошла успешно - перенаправляем посетителя на главную страницу

	if (isset ( $_SESSION ['url'] )) {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );//$_SESSION ['url']

	} else {

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

	}

	die ();



}



// Функция осуществляет автоматический вход на форум

function autoLogin() {

	// Если не установлены cookie, содержащие логин и пароль

	if (! isset ( $_COOKIE ['username'] ) or ! isset ( $_COOKIE ['password'] )) {

		$tmppos = strrpos ( $_SERVER ['PHP_SELF'], '/' ) + 1;

		$path = substr ( $_SERVER ['PHP_SELF'], 0, $tmppos );

		if (isset ( $_COOKIE ['username'] ))

			setcookie ( 'username', '', time () - 1, $path );

		if (isset ( $_COOKIE ['password'] ))

			setcookie ( 'password', '', time () - 1, $path );

		if (isset ( $_COOKIE ['autologin'] ))

			setcookie ( 'autologin', '', time () - 1, $path );

		return false;

	}

	// Проверяем переменные cookie на недопустимые символы

	$name = preg_replace ( "#[^- _0-9a-zА-Яа-я]#i", '', $_COOKIE ['username'] );

	// Т.к. пароль зашифрован с помощью md5, то он представляет собой

	// 32-значное шестнадцатеричное число

	$password = substr ( $_COOKIE ['password'], 0, 32 );

	$password = preg_replace ( "#[^0-9a-f]#i", '', $password );



	// Выполняем запрос на получение данных пользователя из БД

	$query = "SELECT *, UNIX_TIMESTAMP(last_visit) as unix_last_visit

            FROM " . TABLE_USERS . "

            WHERE name='" . mysql_real_escape_string ( $name ) . "'

			AND passw='" . mysql_real_escape_string ( $password ) . "'

			LIMIT 1";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при авторизации пользователя';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	// Если пользователь с таким логином и паролем не найден -

	// значит данные неверные и надо их удалить

	if (mysql_num_rows ( $res ) == 0) {

		$tmppos = strrpos ( $_SERVER ['PHP_SELF'], '/' ) + 1;

		$path = substr ( $_SERVER ['PHP_SELF'], 0, $tmppos );

		setcookie ( 'autologin', '', time () - 1, $path );

		setcookie ( 'username', '', time () - 1, $path );

		setcookie ( 'password', '', time () - 1, $path );

		return false;

	}



	$user = mysql_fetch_assoc ( $res );

	if (! empty ( $user ['activation'] )) {

		$tmppos = strrpos ( $_SERVER ['PHP_SELF'], '/' ) + 1;

		$path = substr ( $_SERVER ['PHP_SELF'], 0, $tmppos );

		setcookie ( 'autologin', '', time () - 1, $path );

		setcookie ( 'username', '', time () - 1, $path );

		setcookie ( 'password', '', time () - 1, $path );

		return showInfoMessage ( 'Ваша учетная запись не активирована', '' );

	}



	// Если пользователь заблокирован

	if (($user ['locked']) or ($user ['lock_admin'])) {

		$tmppos = strrpos ( $_SERVER ['PHP_SELF'], '/' ) + 1;

		$path = substr ( $_SERVER ['PHP_SELF'], 0, $tmppos );

		setcookie ( 'autologin', '', time () - 1, $path );

		setcookie ( 'username', '', time () - 1, $path );

		setcookie ( 'password', '', time () - 1, $path );

		return showInfoMessage ( 'Ваша учетная запись заблокирована. Обратитесь к администратору.', '' );

	}



	$_SESSION ['user'] = $user;



	if ($user ['status'] == 'autosaloon') {

		define ( "EW_PROJECT_NAME", "saloon_control", TRUE ); // Project Name

		define ( "EW_RANDOM_KEY", 'A%Ysd4eceH3NG3No', TRUE ); // Random key for encryption





	// Функция getNewThemes() помещает в массив $_SESSION['newThemes'] ID тем,

	// в которых были новые сообщения со времени последнего посещения пользователя

	//getNewThemes();

	} else {

		define ( "EW_PROJECT_NAME", "user_control", TRUE ); // Project Name

		define ( "EW_RANDOM_KEY", 'C2$Ozz77J2Eu4yTk', TRUE ); // Random key for encryption

	}



	define ( "EW_SESSION_STATUS", EW_PROJECT_NAME . "_status", TRUE ); // Login Status

	define ( "EW_SESSION_USER_CITY", EW_SESSION_STATUS . "_city", TRUE ); // Login Status

	define ( "EW_SESSION_USER_REGION", EW_SESSION_STATUS . "_region", TRUE ); // User Name

	define ( "EW_SESSION_USER_NAME", EW_SESSION_STATUS . "_UserName", TRUE ); // User Name

	define ( "EW_SESSION_USER_ID", EW_SESSION_STATUS . "_UserID", TRUE ); // User ID

	$_SESSION [EW_SESSION_STATUS] = "login";

	$_SESSION [EW_SESSION_SYS_ADMIN] = 0; // Non System Administrator

	$_SESSION [EW_SESSION_USER_NAME] = $_SESSION ['user'] ['email']; // Load user name

	$_SESSION [EW_SESSION_USER_ID] = $_SESSION ['user'] ['id_author']; // Load User ID

	$_SESSION [EW_SESSION_USER_CITY] = $_SESSION ['user'] ['city']; // Load user name

	$_SESSION [EW_SESSION_USER_REGION] = $_SESSION ['user'] ['region']; // Load User ID





	// Функция getNewThemes() помещает в массив $_SESSION['newThemes'] ID тем,

	// в которых были новые сообщения со времени последнего посещения пользователя

	//getNewThemes();





	return true;

}



// Выход из системы

function logout() {

	unset ( $_SESSION ['searchForm'] );

	unset ( $_SESSION ['url'] );

	unset ( $_SESSION ['action_url'] );

	unset ( $_SESSION ['user'] );

	if (isset ( $_SESSION ['newThemes'] ))

		unset ( $_SESSION ['newThemes'] );

	$tmppos = strrpos ( $_SERVER ['PHP_SELF'], '/' ) + 1;

	$path = substr ( $_SERVER ['PHP_SELF'], 0, $tmppos );

	if (isset ( $_COOKIE ['autologin'] ))

		setcookie ( 'autologin', '', time () - 1, $path );

	if (isset ( $_COOKIE ['username'] ))

		setcookie ( 'username', '', time () - 1, $path );

	if (isset ( $_COOKIE ['password'] ))

		setcookie ( 'password', '', time () - 1, $path );

	header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

	die ();

}



// Функция возвращает html формы для поиска по форуму

function searchForm() {



	$html = '';



	$query = "SELECT id_forum, name FROM " . TABLE_FORUMS . " WHERE 1 ORDER BY pos";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при формировании формы для поиска';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		$options = '<option value="0">Все имеющиеся</option>' . "\n";

		while ( $forum = mysql_fetch_row ( $res ) ) {

			$options = $options . '<option value="' . $forum [0] . '">' . $forum [1] . '</option>' . "\n";

		}

		$html = file_get_contents ( './templates/searchForm.html' );

		$action = $_SERVER ['PHP_SELF'] . '?action=searchResult';

		$html = str_replace ( '{options}', $options, $html );

		$html = str_replace ( '{action}', $action, $html );

	}



	return $html;

}



// Функция возвращает html меню для постраничной навигации

function pageIterator($page, $cntPages, $url) {



	$html = '<div class="pagesDiv">&nbsp;Страницы: ';

	// Проверяем нужна ли стрелка "В начало"

	if ($page > 3)

		$startpage = '<a class="pages" href="' . $url . '&page=1"><<</a> ... ';

	else

		$startpage = '';

		// Проверяем нужна ли стрелка "В конец"

	if ($page < ($cntPages - 2))

		$endpage = ' ... <a class="pages" href="' . $url . '&page=' . $cntPages . '">>></a>';

	else

		$endpage = '';



	// Находим две ближайшие станицы с обоих краев, если они есть

	if ($page - 2 > 0)

		$page2left = ' <a class="pages" href="' . $url . '&page=' . ($page - 2) . '">' . ($page - 2) . '</a> | ';

	else

		$page2left = '';

	if ($page - 1 > 0)

		$page1left = ' <a class="pages" href="' . $url . '&page=' . ($page - 1) . '">' . ($page - 1) . '</a> | ';

	else

		$page1left = '';

	if ($page + 2 <= $cntPages)

		$page2right = ' | <a class="pages" href="' . $url . '&page=' . ($page + 2) . '">' . ($page + 2) . '</a>';

	else

		$page2right = '';

	if ($page + 1 <= $cntPages)

		$page1right = ' | <a class="pages" href="' . $url . '&page=' . ($page + 1) . '">' . ($page + 1) . '</a>';

	else

		$page1right = '';



	// Выводим меню

	$html = $html . $startpage . $page2left . $page1left . '<strong>' . $page . '</strong>' . $page1right . $page2right . $endpage . "\n";



	$html = $html . '</div>' . "\n";



	return $html;

}



// Статистика форума

function getStat() {

	$html = '<table class="showTable">' . "\n";

	$html = $html . '<tr><th>Статистика</th></tr>' . "\n";

	$html = $html . '<tr>' . "\n";

	$html = $html . '<td>' . "\n";

	$html = $html . '<div class="details">' . "\n";

	$query = 'SELECT COUNT(*) FROM ' . TABLE_POSTS;

	$res = mysql_query ( $query );

	if (! $res)

		return '';

	$html = $html . 'Наши пользователи оставили сообщений: ' . mysql_result ( $res, 0, 0 ) . '<br/>' . "\n";

	$query = 'SELECT COUNT(*) FROM ' . TABLE_USERS;

	$res = mysql_query ( $query );

	if (! $res)

		return '';

	$html = $html . 'Всего зарегистрированных пользователей: ' . mysql_result ( $res, 0, 0 ) . '<br/>' . "\n";

	$query = 'SELECT id_author, name FROM ' . TABLE_USERS . ' ORDER BY id_author DESC LIMIT 1';

	$res = mysql_query ( $query );

	if (! $res)

		return '';

	list ( $id_user, $name ) = mysql_fetch_array ( $res );

	$html = $html . 'Последний зарегистрированный пользователь: ' . '<a href="' . $_SERVER ['PHP_SELF'] . '?action=showUserInfo&idUser=' . $id_user . '">' . $name . '</a><br/>' . "\n";

	// Пользователи on-line

	if (isset ( $_SESSION ['usersOnLine'] )) {

		$cnt = count ( $_SESSION ['usersOnLine'] );

		$onLine = '';

		if ($cnt > 0) {

			$onLine = $onLine . 'Сейчас на форуме: ';

			foreach ( $_SESSION ['usersOnLine'] as $id => $name ) {

				$onLine = $onLine . '<a href="' . $_SERVER ['PHP_SELF'] . '?action=showUserInfo&idUser=' . $id . '">' . $name . '</a>, ';

			}

			$onLine = substr ( $onLine, 0, (strlen ( $onLine ) - 2) );

		}

		$html = $html . $onLine . "\n";

	}

	$html = $html . '</div>' . "\n";

	$html = $html . '</td>' . "\n";

	$html = $html . '</tr>' . "\n";

	$html = $html . '</table>' . "\n";



	return $html;

}



// Функция помещает в массив $_SESSION['usersOnLine'] список зарегистрированных

// пользователей, которые в настоящий момент просматривают форум

function getUsersOnLine() {

	$query = "SELECT id_author, name

            FROM " . TABLE_USERS . "

			WHERE UNIX_TIMESTAMP(last_visit)>" . (time () - 60 * TIME_ON_LINE) . "

			ORDER BY status DESC";

	$res = mysql_query ( $query );

	if ($res) {

		if (isset ( $_SESSION ['usersOnLine'] ))

			unset ( $_SESSION ['usersOnLine'] );

		$cnt = mysql_num_rows ( $res );

		if ($cnt > 0) {

			for($i = 0; $on = mysql_fetch_array ( $res ); $i ++) {

				$_SESSION ['usersOnLine'] [$on ['id_author']] = $on ['name'];

			}

		}

	}

	return;

}



// Функция возвращает форму для быстрого ответа в тему

function getQuickReplyForm($id_theme) {

	$html = file_get_contents ( './templates/quickReplyForm.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=quickReply&idForum=' . $_GET ['idForum'] . '&id_theme=' . $id_theme;

	$html = str_replace ( '{action}', $action, $html );

	return $html;

}



// Возвращает размер файла в Кб

function getFileSize($file) {

	return number_format ( (filesize ( $file ) / 1024), 2, '.', '' );

}

function bannerList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormbannerList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Баннеры";

if (empty($_REQUEST['ch'])) {

	$html .= showbannerList ();



	$html2 = file_get_contents ( './templates/bannerList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=bannerList">Баннеры</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

			$query = "SELECT BANNER FROM AUTO_BANNER WHERE ID IN ". $q;

			$res = mysql_query ( $query );

			if (! $res) {

				$msg = 'Ошибка при получении списка марок1';

				$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $err, true, '' );

			}

			if (mysql_num_rows ( $res ) > 0) {

				for($ph[]=array();$row=mysql_fetch_assoc($res);$ph[]=$row);

		}
	for ($i=0;$i<count($ph);$i++)
		if ($ph[$i]['BANNER']) unlink($_SERVER['DOCUMENT_ROOT'].'/banner/'.$ph[$i]['BANNER']);

	$query = "DELETE FROM AUTO_BANNER WHERE ID IN ". $q;

} else
if ($_REQUEST['mode'] === "2" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "UPDATE AUTO_BANNER SET VISIBLE='1' WHERE ID IN ". $q;

} else
if ($_REQUEST['mode'] === "3" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "UPDATE AUTO_BANNER SET VISIBLE='0' WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным баннерам было успешно применено!<br><a href="?action=bannerAdd">Добавить баннер</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=bannerList">Баннеры</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showbannerList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=bannerList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormbannerList'] ['sql'] )) {

		$html = str_replace ( '{FOUND}', ShowTableBanner ( $_SESSION ['searchFormbannerList'] ['sql'], 'bannerList', 'bannerEdit' ), $html );

	} else {

		$html = str_replace ( '{FOUND}', ShowTableBanner ( $_SESSION ['searchFormbannerList'] ['sql'], 'bannerList', 'bannerEdit' ), $html );

	}
//тттм
	return $html;

}

function ShowTableBanner($where, $action, $ShowCarInfo) {

//туда-сюда

	$column = $_GET ['order'];

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];

	//echo $where;



	$query = "SELECT COUNT(*) FROM AUTO_BANNER";


	$query .= ($where != "") ? " and " . $where : "";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );



	if ($total == 0) {


		$html .= file_get_contents ( './templates/searchnoBanner.html' );

		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	$table .= "<table width=\"100%\"><tr><td>";

		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_banner.gif); width: 113px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=bannerAdd\"></a>" . "</td></tr></table>";

	$table .= '<form name="chform" method="post">';

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete





	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";




	$table .= "<td valign=\"top\" width=\"30\" align=\"center\">";

	$table .= "<input type=\"checkbox\" title=\"Отметить все\" onclick=\"checkAll(this);\" name=\"chAll\"/></td>";




	$table .= "<td valign=\"middle\" width=\"30\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'id' ) . "&ordertype=";

	$table .= SortOrder ( 'id' );

	$table .= " \">№";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'link' ) . "&ordertype=";

	$table .= SortOrder ( 'link' );

	$table .= " \">Ссылка";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'place' ) . "&ordertype=";

	$table .= SortOrder ( 'place' );

	$table .= " \">Место";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

		$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'data_beg' ) . "&ordertype=";

	$table .= SortOrder ( 'data_beg' );

	$table .= " \">Начало показа";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

		$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'data_end' ) . "&ordertype=";

	$table .= SortOrder ( 'data_end' );

	$table .= " \">Окончание показа";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'active' ) . "&ordertype=";

	$table .= SortOrder ( 'active' );

	$table .= " \">Видимость";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";






	$table .= "</tr>";



	$query = "SELECT b.ID, b.LINK, p.DESCR, UNIX_TIMESTAMP(b.DATA_BEG) as DATA_BEG, UNIX_TIMESTAMP(b.DATA_END) as DATA_END, b.VISIBLE FROM AUTO_BANNER b, AUTO_BAN_PLACE p WHERE b.PLACE=p.ID ";






	$query .= (($where) != "") ? " and " . $where : "";

	// $query.= $_SESSION['searchForm']['sql'];

	if (isset ( $_GET ['order'] ))

		$query .= " ORDER BY " . $_GET ['order'] . " " . $_GET ['ordertype'];
/*
	else

		$query .= " ORDER BY a.DATE_VVOD desc";
*/
	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;



	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;

	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {



			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}








			$table .= "<tr class=$CssClass>";





			//
			$table .= '<td align="center"><input type="checkbox" value="'. $autolist ['ID'] . '" name="ch[]"/></a>';

			$table .= "</td>";
			//



			//
			$table .= '<td align="center">'. $autolist ['ID'];

			$table .= "</td>";
			//



			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=bannerEdit&id=" . $autolist ['ID'] . ">" . $autolist ['LINK'];

			$table .= "</td>";


			$table .= "<td class='phpmakerlist'>";

			$table .= $autolist ['DESCR'];

			$table .= "</td>";


			$table .= "<td class='phpmakerlist'>";

			$table .= date("d.m.Y",$autolist ['DATA_BEG']);

			$table .= "</td>";


			$table .= "<td class='phpmakerlist'>";

			$table .= date("d.m.Y",$autolist ['DATA_END']);

			$table .= "</td>";


			$table .= "<td class='phpmakerlist'>";

			$table .= ($autolist ['VISIBLE'] ? 'Отображается' : 'Скрыта');

			$table .= "</td>";






			$table .= "</tr>";



		}

		$table .= "</td></tr></table>";

	}




$table .= "<table width=\"100%\"><tr><td widht=\"100\"><table><tr><td>С отмеченным:
<select id=\"mode\" name=\"mode\">
<option value=\"1\">Удалить</option>
<option value=\"2\">Показать</option>
<option value=\"3\">Скрыть</option>
</select></td><td><a href=\"#\" style=\"background-image:url(img/btn_apply.gif); width: 84px; height: 21px;\"  onclick=\"this.blur(); javascript: document.searchForm.submit();\" class=\"button_apply\"></a></td></tr></table></form></td><td align=\"right\">" . $pages . "</td></tr></table>";




	$table .= "</td></tr></table>";



	return $table;

}



function bannerAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Добавление баннера";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/addBannerForm.html' );





	$action = $_SERVER ['PHP_SELF'] . '?action=bannerAddSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{LINK}', $_SESSION ['carbase'] ['link'], $html );


		$query = "SELECT * FROM AUTO_BAN_PLACE";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}
//тут..
	$place = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $placelist = mysql_fetch_array ( $res ) ) {



			$selwrk = (intval($placelist ['ID']) === intval($_SESSION ['carbase'] ['place'])) ? " selected" : "";

			$place .= "<option value='" . $placelist ['ID'] . "' " . $selwrk . " >" . $placelist ['DESCR']  . "</option>";

		}

	}

	$html = str_replace ( '{PLACE}', $place, $html );

	$html = str_replace ( '{DATA_BEG}', $_SESSION ['carbase'] ['begin'], $html );

	$html = str_replace ( '{DATA_END}', $_SESSION ['carbase'] ['end'], $html );


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=bannerList">Баннеры</a></span> / <span class="und"><a href="">Добавление баннера</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function bannerAddSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$place = abs (intval ( $_POST ['place'] ));

	$link = substr ( $_POST ['x_LINK'], 0, 255 );

	$begin = $_POST ['DATA_BEG'];

	$end = $_POST ['DATA_END'];


	// Обрезаем лишние пробелы

	$link = trim ( $link );

	$begin = trim ( $begin );

	$end = trim ( $end );



	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $link ) > 255)

		$error = $error . '<li>длина ссылки более 255 символов</li>' . "\n";

	if ( $place <= 0 )

		$error = $error . '<li>не указано место, где будет размещаться баннер</li>' . "\n";

	if ( !$_FILES['BANNER']['name'] )

		$error = $error . '<li>не указан баннер</li>' . "\n";










	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['carbase'] ['link'] = $link;

		$_SESSION ['carbase'] ['place'] = $place;

		$_SESSION ['carbase'] ['begin'] = $begin;

		$_SESSION ['carbase'] ['end'] = $end;


		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=bannerAdd' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {

	$d1 = substr($begin,0,2);
	$m1 = substr($begin,3,2);
	$y1 = substr($begin,6,4);

	$d2 = substr($end,0,2);
	$m2 = substr($end,3,2);
	$y2 = substr($end,6,4);

	$data_beg = $y1.'-'.$m1.'-'.$d1;
	$data_end = $y2.'-'.$m2.'-'.$d2;

	$type = 0;

	$ban = '';

	if ($_FILES['BANNER']['name']) {

		$f=pathinfo($_FILES['BANNER']['name']);
		list($frec,$sec) = explode(" ", microtime());
		if ($f['extension'] === strtolower("swf"))
			$type = 1;

		$ban = $sec.$frec.'.'.$f['extension'];

		copy($_FILES['BANNER']['tmp_name'], 'banner/' . $ban);
	}

	if (!$link) $link = '/';


		if ($_POST ['a_add'] == "A") {


//car
			$query = "INSERT INTO AUTO_BANNER

		SET
		`BANNER`='" . mysql_escape_string ( $ban ) . "',
		`TYPE`='" .  $type  . "',
		`LINK`='" . mysql_escape_string ( $link ) . "',
		`PLACE`='" . $place . "',
		`DATA_BEG`='" . $data_beg . "',
		`DATA_END`='" . $data_end . "'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении состояния';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addBanner.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}



function bannerEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение баннера";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/editBannerForm.html' );





	$action = $_SERVER ['PHP_SELF'] . '?action=bannerEditSubmit';

	$html = str_replace ( '{action}', $action, $html );



	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT *, UNIX_TIMESTAMP(DATA_BEG) as DATA_BEG, UNIX_TIMESTAMP(DATA_END) as DATA_END FROM AUTO_BANNER WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}


	$html = str_replace ( '{LINK}', $data ['LINK'], $html );


		$query = "SELECT * FROM AUTO_BAN_PLACE";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}
//тут..
	$place = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $placelist = mysql_fetch_array ( $res ) ) {



			$selwrk = (intval($placelist ['ID']) === intval($data ['PLACE'])) ? " selected" : "";

			$place .= "<option value='" . $placelist ['ID'] . "' " . $selwrk . " >" . $placelist ['DESCR']  . "</option>";

		}

	}

	$html = str_replace ( '{PLACE}', $place, $html );

	$html = str_replace ( '{DATA_BEG}', ( $data ['DATA_BEG'] ? date("d.m.Y",$data ['DATA_BEG']) : ''), $html );

	$html = str_replace ( '{DATA_END}', ( $data ['DATA_BEG'] ? date("d.m.Y",$data ['DATA_END']) : ''), $html );


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=bannerList">Баннеры</a></span> / <span class="und"><a href="">Изменение баннера</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function bannerEditSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$place = abs (intval ( $_POST ['place'] ));

	$link = substr ( $_POST ['x_LINK'], 0, 255 );

	$begin = $_POST ['DATA_BEG'];

	$end = $_POST ['DATA_END'];


	// Обрезаем лишние пробелы

	$link = trim ( $link );

	$begin = trim ( $begin );

	$end = trim ( $end );



	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $link ) > 255)

		$error = $error . '<li>длина ссылки более 255 символов</li>' . "\n";

	if ( $place <= 0 )

		$error = $error . '<li>не указано место, где будет размещаться баннер</li>' . "\n";



	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['carbase'] ['link'] = $link;

		$_SESSION ['carbase'] ['place'] = $place;

		$_SESSION ['carbase'] ['begin'] = $begin;

		$_SESSION ['carbase'] ['end'] = $end;


		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=bannerEdit' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {

	$d1 = substr($begin,0,2);
	$m1 = substr($begin,3,2);
	$y1 = substr($begin,6,4);

	$d2 = substr($end,0,2);
	$m2 = substr($end,3,2);
	$y2 = substr($end,6,4);

	$data_beg = $y1.'-'.$m1.'-'.$d1;
	$data_end = $y2.'-'.$m2.'-'.$d2;

	$type = '';

	$ban = '';

if ($_FILES['BANNER']['name']) {


			$query = "SELECT BANNER FROM AUTO_BANNER WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}

	unlink('banner/' . $data['BANNER']);

		$f=pathinfo($_FILES['BANNER']['name']);
		list($frec,$sec) = explode(" ", microtime());
		if ($f['extension'] === strtolower("swf"))
			$type = 1;
		else
			$type = 0;

		$ban = $sec.$frec.'.'.$f['extension'];

		copy($_FILES['BANNER']['tmp_name'], 'banner/' . $ban);

}

	if (!$link) $link = '/';


		if ($_POST ['a_add'] == "A") {


//car
			$query = "UPDATE AUTO_BANNER

		SET
		".($_FILES['BANNER']['name'] ? "`BANNER`='" . mysql_escape_string ( $ban ) . "', `TYPE`='" .  $type  . "', " : "")."
		`LINK`='" . mysql_escape_string ( $link ) . "',
		`PLACE`='" . $place . "',
		`DATA_BEG`='" . $data_beg . "',
		`DATA_END`='" . $data_end . "' WHERE ID='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении состояния';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/editBanner.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

/////
function regionList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormregionList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Регионы";

if (empty($_REQUEST['ch'])) {

	$html .= showregionList ();



	$html2 = file_get_contents ( './templates/regionList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=regionList">Регионы</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "DELETE FROM AUTO_REGION WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным регионам было успешно применено!<br><a href="?action=regionAdd">Добавить регион</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=regionList">Регионы</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showregionList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=regionList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormregionList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableRegionCity ( $_SESSION ['searchFormregionList'] ['sql'], 'regionList', 'regionEdit' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableRegionCity ( $_SESSION ['searchFormregionList'] ['sql'], 'regionList', 'regionEdit' ), $html );

	}
//тттм
	return $html;

}


/////
function cityList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormcityList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Города";

if (empty($_REQUEST['ch'])) {

	$html .= showcityList ();



	$html2 = file_get_contents ( './templates/cityList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=cityList">Города</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "DELETE FROM AUTO_CITY WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным городам было успешно применено!<br><a href="?action=cityAdd">Добавить город</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=cityList">Города</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}

/////
function markList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormmarkList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Марки авто";

if (empty($_REQUEST['ch'])) {

	$html .= showmarkList ();



	$html2 = file_get_contents ( './templates/markList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=markList">Марки авто</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "DELETE FROM AUTO_TRADEMARK WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным маркам атво было успешно применено!<br><a href="?action=markAdd">Добавить марку авто</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=markList">Марки авто</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showmarkList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=markList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormmarkList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableMarkModel ( $_SESSION ['searchFormmarkList'] ['sql'], 'markList', 'markEdit' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableMarkModel ( $_SESSION ['searchFormmarkList'] ['sql'], 'markList', 'markEdit' ), $html );

	}
//тттм
	return $html;

}

/////
function modelList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormmodelList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Модели авто";

if (empty($_REQUEST['ch'])) {

	$html .= showmodelList ();



	$html2 = file_get_contents ( './templates/modelList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=modelList">Модели авто</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "DELETE FROM AUTO_MODEL WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным моделям атво было успешно применено!<br><a href="?action=modelAdd">Добавить модель авто</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=modelList">Модель авто</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showmodelList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=modelList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormmodelList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableMarkModel ( $_SESSION ['searchFormmodelList'] ['sql'], 'modelList', 'modelEdit' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableMarkModel ( $_SESSION ['searchFormmodelList'] ['sql'], 'modelList', 'modelEdit' ), $html );

	}
//тттм
	return $html;

}


function ShowTableMarkModel($where, $action, $ShowCarInfo) {

//туда-сюда

	$column = $_GET ['order'];

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];

	//echo $where;


if ($_REQUEST['action'] === "markList") {
	$query = "SELECT COUNT(*) FROM AUTO_TRADEMARK";
} else {
	$query = "SELECT COUNT(*) FROM AUTO_MODEL";
}

	$query .= ($where != "") ? " and " . $where : "";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );



	if ($total == 0) {

		if ($_REQUEST['action'] === "marktList") {
		$html .= file_get_contents ( './templates/searchnoMark.html' );
		} else {
		$html .= file_get_contents ( './templates/searchnoModel.html' );
		}
		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	$table .= "<table width=\"100%\"><tr><td>";

	if ($_REQUEST['action'] === "markList")
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_mark.gif); width: 135px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=markAdd\"></a>" . "</td></tr></table>";
	else
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_model.gif); width: 144px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=modelAdd\"></a>" . "</td></tr></table>";

	$table .= '<form name="chform" method="post">';

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete





	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";




	$table .= "<td valign=\"top\" width=\"30\" align=\"center\">";

	$table .= "<input type=\"checkbox\" title=\"Отметить все\" onclick=\"checkAll(this);\" name=\"chAll\"/></td>";




	$table .= "<td valign=\"middle\" width=\"30\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'id' ) . "&ordertype=";

	$table .= SortOrder ( 'id' );

	$table .= " \">№";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

if ($_REQUEST['action'] === "markList") {

	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'trademark' ) . "&ordertype=";

	$table .= SortOrder ( 'trademark' );

	$table .= " \">Марка авто";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"middle\" width=\"180\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'car_type' ) . "&ordertype=";

	$table .= SortOrder ( 'car_type' );

	$table .= " \">Тип автомобиля";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

} else {

	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'model' ) . "&ordertype=";

	$table .= SortOrder ( 'model' );

	$table .= " \">Модель авто";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"middle\" width=\"180\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'trademark' ) . "&ordertype=";

	$table .= SortOrder ( 'trademark' );

	$table .= " \">Марка авто";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"middle\" width=\"180\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'car_type' ) . "&ordertype=";

	$table .= SortOrder ( 'car_type' );

	$table .= " \">Тип автомобиля";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

}




	$table .= "</tr>";


if ($_REQUEST['action'] === "markList") {
	$query = "SELECT mark.ID, mark.TRADEMARK, type.CAR_TYPE FROM AUTO_TRADEMARK mark, AUTO_CAR_TYPE type WHERE type.ID=mark.CAR_TYPE ";
} else {
	$query = "SELECT model.ID, model.MODEL, mark.TRADEMARK, mark.ID as TRADEMARK_ID, type.CAR_TYPE FROM AUTO_MODEL model, AUTO_TRADEMARK mark, AUTO_CAR_TYPE type WHERE type.ID=mark.CAR_TYPE AND model.TRADEMARK=mark.ID ";
}





	$query .= (($where) != "") ? " and " . $where : "";

	// $query.= $_SESSION['searchForm']['sql'];

	if (isset ( $_GET ['order'] ))

		$query .= " ORDER BY " . $_GET ['order'] . " " . $_GET ['ordertype'];

	else if ($_REQUEST['action'] === "markList") {

		$query .= " ORDER BY mark.TRADEMARK";

	} else {

		$query .= " ORDER BY model.MODEL";

	}

	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;



	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;

	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {



			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}








			$table .= "<tr class=$CssClass>";





			//
			$table .= '<td align="center"><input type="checkbox" value="'. $autolist ['ID'] . '" name="ch[]"/></a>';

			$table .= "</td>";
			//



			//
			$table .= '<td align="center">'. $autolist ['ID'];

			$table .= "</td>";
			//




		if ($_REQUEST['action'] === "markList") {

			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=markEdit&id=" . $autolist ['ID'] . ">" . $autolist ['TRADEMARK'] . "</a>";

			$table .= "</td>";


			$table .= "<td class='phpmakerlist'>";

			$table .= $autolist ['CAR_TYPE'];

			$table .= "</td>";

		} else {

			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=modelEdit&id=" . $autolist ['ID'] . ">" . $autolist ['MODEL'] . "</a>";

			$table .= "</td>";


			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=markEdit&id=" . $autolist ['TRADEMARK_ID'] . ">" . $autolist ['TRADEMARK'] . "</a>";

			$table .= "</td>";


			$table .= "<td class='phpmakerlist'>";

			$table .= $autolist ['CAR_TYPE'];

			$table .= "</td>";

		}









			$table .= "</tr>";



		}

		$table .= "</td></tr></table>";

	}




$table .= "<table width=\"100%\"><tr><td widht=\"100\"><table><tr><td>С отмеченным:
<select id=\"mode\" name=\"mode\">
<option value=\"1\">Удалить</option>
</select></td><td><a href=\"#\" style=\"background-image:url(img/btn_apply.gif); width: 84px; height: 21px;\"  onclick=\"this.blur(); javascript: document.searchForm.submit();\" class=\"button_apply\"></a></td></tr></table></form></td><td align=\"right\">" . $pages . "</td></tr></table>";




	$table .= "</td></tr></table>";



	return $table;

}



function showcityList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=cityList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormcityList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableRegionCity ( $_SESSION ['searchFormcityList'] ['sql'], 'cityList', 'cityEdit' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableRegionCity ( $_SESSION ['searchFormcityList'] ['sql'], 'cityList', 'cityEdit' ), $html );

	}
//тттм
	return $html;

}

/////
function rubList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormrubtList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Рубрики сообщений";

if (empty($_REQUEST['ch'])) {

	$html .= showrubList ();



	$html2 = file_get_contents ( './templates/rubList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=rubList">Рубрики сообщений</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "DELETE FROM AUTO_BLOG_TYPE WHERE ID IN ". $q;

} else
if ($_REQUEST['mode'] === "2" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "UPDATE AUTO_BLOG_TYPE SET ACTIVE='1' WHERE ID IN ". $q;

} else
if ($_REQUEST['mode'] === "3" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "UPDATE AUTO_BLOG_TYPE SET ACTIVE='0' WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным рубрикам было успешно применено!<br><a href="?action=rubAdd">Добавить рубрику</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=rubList">Рубрики сообщений</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showrubList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=rubList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormrubList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableBlog ( $_SESSION ['searchFormregionList'] ['sql'], 'rubList', 'rubEdit' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableBlog ( $_SESSION ['searchFormsostList'] ['sql'], 'rubList', 'rubEdit' ), $html );

	}
//тттм
	return $html;

}



function rubAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Добавление рубрику";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/addRubForm.html' );





	$action = $_SERVER ['PHP_SELF'] . '?action=rubAddSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{RUB}', $_SESSION ['carbase'] ['rub'], $html );







	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=rubList">Рубрики сообщений</a></span> / <span class="und"><a href="">Добавление рубрики</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function rubAddSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$rub = substr ( $_POST ['x_RUB'], 0, 128 );






	// Обрезаем лишние пробелы

	$rub = trim ( $rub );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $rub ) > 128)

		$error = $error . '<li>длина названия более 128 символов</li>' . "\n";

	if (empty ( $rub ))

		$error = $error . '<li>не указано название рубрики</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['rub'] = $rub;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=rubAdd' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "INSERT INTO AUTO_BLOG_TYPE

		SET
		`NAME`='" . mysql_escape_string ( $rub ) . "'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении состояния';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addRub.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////
//////////////////////
function rubEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение рубрики";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/editRub.html' );

	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_BLOG_TYPE WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}



	$action = $_SERVER ['PHP_SELF'] . '?action=rubEditSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{RUB}', $data ['NAME'], $html );




	//photo


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=rubList">Рубрки сообщений</a></span> / <span class="und"><a href="">Изменение рубрики</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function rubEditSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

	{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$rub = substr ( $_POST ['x_RUB'], 0, 128 );






	// Обрезаем лишние пробелы

	$rub = trim ( $rub );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $rub ) > 128)

		$error = $error . '<li>длина названия более 128 символов</li>' . "\n";

	if (empty ( $rub ))

		$error = $error . '<li>не указано название рубрики</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['rub'] = $rub;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=rubEdit&id='.$_SESSION['edID'] );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "UPDATE AUTO_BLOG_TYPE

		SET
		`NAME`='" . mysql_escape_string ( $rub ) . "' WHERE ID='". $_SESSION['edID'] ."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении рубрики';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/changeRub.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

function blogList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormblogList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Сообщения";

if (empty($_REQUEST['ch'])) {

	$html .= showblogList ();



	$html2 = file_get_contents ( './templates/blogList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=blogList">Сообщения</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

			$query = "SELECT PICTURE FROM AUTO_BLOG WHERE ID IN ". $q;

			$res = mysql_query ( $query );

			if (! $res) {

				$msg = 'Ошибка при получении списка марок1';

				$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $err, true, '' );

			}

			if (mysql_num_rows ( $res ) > 0) {

				for($ph[]=array();$row=mysql_fetch_assoc($res);$ph[]=$row);

		}
	for ($i=0;$i<count($ph);$i++)
		if ($ph[$i]['PICTURE']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph[$i]['PICTURE']);


	$query = "DELETE FROM AUTO_BLOG WHERE ID IN ". $q;

} else
if ($_REQUEST['mode'] === "2" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "UPDATE AUTO_BLOG SET ACTIVE='1' WHERE ID IN ". $q;

} else
if ($_REQUEST['mode'] === "3" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "UPDATE AUTO_BLOG SET ACTIVE='0' WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным сообщениям было успешно применено!<br><a href="?action=blogAdd">Добавить сообщение</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=blogList">Сообщения</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}

//???//
function showblogList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=blogList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormblogList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableBlog ( $_SESSION ['searchFormregionList'] ['sql'], 'blogList', 'blogEdit' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableBlog ( $_SESSION ['searchFormsostList'] ['sql'], 'blogList', 'blogEdit' ), $html );

	}
//тттм
	return $html;

}

function blogAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle']="Добавление сообщения";



	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

		$zag = htmlspecialchars ( $_SESSION ['carbase'] ['zag'] );
		$about = htmlspecialchars ( $_SESSION ['carbase'] ['about'] );
		$rubric = htmlspecialchars ( $_SESSION ['carbase'] ['rubric'] );
		$descr = htmlspecialchars ( $_SESSION ['carbase'] ['descr'] );

		unset ( $_SESSION ['carbase'] );

	} else {

		$zag = '';
		$rubric = '';
		$descr = '';
		$about = '';
	}

	$date = empty( $_SESSION['carbase']['date'] ) ? date('Y-m-d H:i:s') : $_SESSION['carbase']['date'];
	$show_in_anons = empty( $_SESSION['carbase']['show_in_anons'] ) ? '' : ' checked="checked" ';

	// Считываем в переменную файл шаблона, содержащего

	// форму для добавления нового пользователя



	$action = $_SERVER ['PHP_SELF'] . '?action=blogAddSubmit';



	$tpl = file_get_contents ( './templates/blogAddForm.html' );

	$tpl = str_replace ( '{action}', $action, $tpl );
	$tpl = str_replace ( '{ZAGOL}', $zag, $tpl );
	$tpl = str_replace ( '{DATE}', $date, $tpl );
	$tpl = str_replace ( '{SHOW_IN_ANONS}', $show_in_anons, $tpl );
	$tpl = str_replace ( '{about}', $data['about'], $tpl );
	$tpl = str_replace ( '{descr}', $data['descr'], $tpl );

	$query = "SELECT * FROM AUTO_BLOG_TYPE ORDER BY NAME";
	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';
		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';
		return showErrorMessage ( $msg, $err, true, '' );
	}
//тут..
	$rub = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $rublist = mysql_fetch_array ( $res ) ) {

			$selwrk = (intval($rublist ['ID']) === intval($rubric)) ? " selected" : "";

			$rub .= "<option value='" . $rublist ['ID'] . "' " . $selwrk . " >" . $rublist ['NAME'] . "</option>";

		}

	}

	$tpl = str_replace ( '{RUB}', $rub, $tpl );
	$html = $html . $tpl;
	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=blogAdd">Добавление сообщения</a></span>';
	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;
}



function blogAddSubmit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	// Если не переданы данные формы - значит функция была вызвана по ошибке

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );
		$_POST = stripslashes_array ( $_POST );
		$_COOKIE = stripslashes_array ( $_COOKIE );
	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$zag = substr ( $_POST ['zag'], 0, 255 );
	$rub = abs( intval( $_POST ['rub'] ) );
	$descr = substr ( $_POST ['descr'], 0, 30000 );
	$about = substr ( $_POST ['about'], 0, 255 );

	// Обрезаем лишние пробелы
	$zag = trim ( $zag );
	$about = trim ( $about );
	$descr = trim ( $descr );

	if ( empty( $_POST['date'] ) ) $date = date('Y-m-d H:i:s');
	else $date = date( 'Y-m-d H:i:s', strtotime( $_POST['date'] ) );

	$show_in_anons = isset( $_POST['show_in_anons'] ) ? intval( $_POST['show_in_anons'] ) : 0;

	$zag = RemoveXSS ( $zag );
	$about = RemoveXSS ( $about );
	$descr = RemoveXSS ( $descr );

	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (empty ( $zag ))
		$error = $error . '<li>не заполнен заголовок</li>' . "\n";

	if ($rub <= 0)
		$error = $error . '<li>не выбрана рубрика</li>' . "\n";

	if (empty ( $about ))
		$error = $error . '<li>не заполнено краткое содержание</li>' . "\n";

	if (empty ( $descr ))
		$error = $error . '<li>не заполнено полное содержание</li>' . "\n";

	if (strlen ( $about ) > 255)
		$error = $error . '<li>длина краткого содержания более 255 символов</li>' . "\n";

	if (strlen ( $descr ) > 30000)
		$error = $error . '<li>длина полное содержания более 30000 символов</li>' . "\n";

		if (! empty ( $_FILES ['x_PHOTO'] ['name'] )) {

			unset($imgS);

			$imgS = getimagesize($_FILES ['x_PHOTO']['tmp_name']);

			$extensions = array ("image/jpg", "image/png", "image/jpeg", "image/gif" );

			if (! in_array ( strtolower($imgS['mime']), $extensions ))

				$error = $error . '<li>Недопустимый формат изображения' . '</li>' . "\n";

			if ($_FILES ['x_PHOTO'] ['size'] > MAX_PHOTO_SIZE)

				$error = $error . '<li>Размер изображения больше ' . (MAX_PHOTO_SIZE / 1024) . ' Кб</li>' . "\n";
		}
	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {

		$_SESSION ['carbase'] = array ();
		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";
		$_SESSION ['carbase'] ['name'] = $name;
		$_SESSION ['carbase'] ['zag'] = $zag;
		$_SESSION ['carbase'] ['date'] = $date;
		$_SESSION ['carbase'] ['show_in_anons'] = $show_in_anons;
		$_SESSION ['carbase'] ['about'] = $about;
		$_SESSION ['carbase'] ['rubric'] = $rub;
		$_SESSION ['carbase'] ['descr'] = $descr;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=blogAdd' );
		die ();
	}

	    $img="";

        if (! empty ( $_FILES ['x_PHOTO'] ['tmp_name'] )) {
			$img = water2 ($_FILES ['x_PHOTO'] );
		}

	// Все поля заполнены правильно - продолжаем регистрацию

	//$date .= date(' H:i:s');
	$query = "INSERT INTO AUTO_BLOG

		    SET ZAGOL='" .  mysql_real_escape_string($zag) . "',

			DATE='$date',
			SHOW_IN_ANONS = $show_in_anons,

		    TYPE='" . mysql_real_escape_string ( $rub ) . "',

			PICTURE='" . mysql_real_escape_string ( $img ) . "',

		    SMALL_TEXT= '" . mysql_real_escape_string ( $about ) . "',

			TEXT= '" . mysql_real_escape_string ( $descr ) . "'";


	$res = mysql_query ( $query );

	//echo $query;

	if (! $res) {

		$msg = 'Ошибка при изменении контактной информации';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$msg = '<br/>

      <center><p><strong>Сообщение было успешно добавлено!</strong></p></center>

    <br/>';

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $msg, $html );

	return $html;
}
/////////////

function blogEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }
	$_SESSION ['pageTitle']="Изменение сообщения";
	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );
		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );
		$html = $html . $info . "\n";
		unset ( $_SESSION ['carbase'] );
	}

	$_SESSION['edID'] = abs(intval($_REQUEST['id']));
	$query = "SELECT * FROM AUTO_BLOG WHERE ID='".$_SESSION['edID']."'";
	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';
		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';
		return showErrorMessage ( $msg, $err, true, '' );
	}

	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);
	}

	$action = $_SERVER ['PHP_SELF'] . '?action=blogEditSubmit';
	$tpl = file_get_contents ( './templates/blogEditForm.html' );

	$tpl = str_replace ( '{action}', $action, $tpl );
	$tpl = str_replace ( '{ZAGOL}', $data['ZAGOL'], $tpl );
	$tpl = str_replace ( '{about}', $data['SMALL_TEXT'], $tpl );
	$tpl = str_replace ( '{descr}', $data['TEXT'], $tpl );
	$tpl = str_replace ( '{DATE}', $data['DATE'], $tpl );
	$tpl = str_replace ( '{SHOW_IN_ANONS}', $data['SHOW_IN_ANONS'] ? ' checked="checked" ' : '', $tpl );

	$query = "SELECT * FROM AUTO_BLOG_TYPE ORDER BY NAME";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';
		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';
		return showErrorMessage ( $msg, $err, true, '' );

	}
//тут..
	$rub = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $rublist = mysql_fetch_array ( $res ) ) {

			$selwrk = (intval($rublist ['ID']) === intval($data['TYPE'])) ? " selected" : "";

			$rub .= "<option value='" . $rublist ['ID'] . "' " . $selwrk . " >" . $rublist ['NAME'] . "</option>";

		}

	}

	$tpl = str_replace ( '{RUB}', $rub, $tpl );


	$DEL_PHOTO = '';

	if ($data['PICTURE'])

	$DEL_PHOTO = $DEL_PHOTO . '


            <td>

              <img src="show_image.php?filename=photo/'.$data['PICTURE'].'&width=80"/><br/><input type="checkbox" name="x_DEL_PHOTO" value="1"/> <span class="style7">Удалить</span>

            </td>

          ';

	$tpl = str_replace ( '{DEL_PHOTO}', $DEL_PHOTO, $tpl );


	$html = $html . $tpl;

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=blogEdit">Изменение сообщения</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

}



function blogEditSubmit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	// Если не переданы данные формы - значит функция была вызвана по ошибке

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input

	$zag = substr ( $_POST ['zag'], 0, 255 );

	$rub = abs( intval( $_POST ['rub'] ) );

	$descr = substr ( $_POST ['descr'], 0, 30000 );

	$about = substr ( $_POST ['about'], 0, 255 );


	// Обрезаем лишние пробелы

	$zag = trim ( $zag );

	$about = trim ( $about );

	$descr = trim ( $descr );

	if ( empty( $_POST['date'] ) ) $date = date('Y-m-d H:i:s');
	else $date = date( 'Y-m-d H:i:s', strtotime( $_POST['date'] ) );

	$show_in_anons = isset( $_POST['show_in_anons'] ) ? intval( $_POST['show_in_anons'] ) : 0;


	$zag = RemoveXSS ( $zag );

	$about = RemoveXSS ( $about );

	$descr = RemoveXSS ( $descr );

	// Проверяем, заполнены ли обязательные поля

	$error = '';



	if (empty ( $zag ))

		$error = $error . '<li>не заполнен заголовок</li>' . "\n";

	if ($rub <= 0)

		$error = $error . '<li>не выбрана рубрика</li>' . "\n";

	if (empty ( $about ))

		$error = $error . '<li>не заполнено краткое содержание</li>' . "\n";

	if (empty ( $descr ))

		$error = $error . '<li>не заполнено полное содержание</li>' . "\n";

	if (strlen ( $about ) > 255)

		$error = $error . '<li>длина краткого содержания более 255 символов</li>' . "\n";

	if (strlen ( $descr ) > 30000)

		$error = $error . '<li>длина полное содержания более 30000 символов</li>' . "\n";





		if (! empty ( $_FILES ['x_PHOTO'] ['name'] )) {

			unset($imgS);

			$imgS = getimagesize($_FILES ['x_PHOTO']['tmp_name']);

			$extensions = array ("image/jpg", "image/png", "image/jpeg", "image/gif" );



			if (! in_array ( strtolower($imgS['mime']), $extensions ))

				$error = $error . '<li>Недопустимый формат изображения' . '</li>' . "\n";



			if ($_FILES ['x_PHOTO'] ['size'] > MAX_PHOTO_SIZE)

				$error = $error . '<li>Размер изображения больше ' . (MAX_PHOTO_SIZE / 1024) . ' Кб</li>' . "\n";

		}







	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {

		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['carbase'] ['name'] = $name;

		$_SESSION ['carbase'] ['date'] = $date;
		$_SESSION ['carbase'] ['show_in_anons'] = $show_in_anons;

		$_SESSION ['carbase'] ['zag'] = $zag;

		$_SESSION ['carbase'] ['about'] = $about;

		$_SESSION ['carbase'] ['rubric'] = $rub;

		$_SESSION ['carbase'] ['descr'] = $descr;

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=blogEdit&id='. $_SESSION['edID']);

		die ();

	}


/*
	    $img="";

        if (! empty ( $_FILES ['x_PHOTO'] ['tmp_name'] )) {

			$img = water2 ($_FILES ['x_PHOTO'] );

		}
*/

///111111111
		if ($_REQUEST['x_DEL_PHOTO']) {

	$query = "SELECT PICTURE FROM AUTO_BLOG WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	if (mysql_num_rows ( $res ) > 0) {

		for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

	}

	unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.	$ph['PICTURE']);
	//

			$query = "UPDATE AUTO_BLOG

				SET PICTURE='' WHERE ID='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );

		}

	    $img="";

        if (! empty ( $_FILES ['x_PHOTO'] ['tmp_name'] )) {

				$query = "SELECT PICTURE FROM AUTO_BLOG WHERE ID='".$_SESSION['edID']."'";

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph=array();$row=mysql_fetch_assoc($res);$ph=$row);

				}

			if ($ph['PICTURE']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph['PICTURE']);

			$query = "UPDATE AUTO_BLOG

				SET PICTURE='' WHERE ID='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );

			$img = water2 ($_FILES ['x_PHOTO'] );


			$query = "UPDATE AUTO_BLOG

			SET PICTURE='" .  $img . "' WHERE ID='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );

		}







	// Все поля заполнены правильно - продолжаем регистрацию

	//$date .= date(' H:i:s');
	$query = "UPDATE AUTO_BLOG

		    SET ZAGOL='" .  mysql_real_escape_string($zag) . "',
		    DATE='$date',
		    SHOW_IN_ANONS='$show_in_anons',

		    TYPE='" . mysql_real_escape_string ( $rub ) . "',

		    SMALL_TEXT= '" . mysql_real_escape_string ( $about ) . "',

			TEXT= '" . mysql_real_escape_string ( $descr ) . "' WHERE ID='".$_SESSION['edID']."'";


	$res = mysql_query ( $query );

	//echo $query;

	if (! $res) {

		$msg = 'Ошибка при изменении контактной информации';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}




	$msg = '<br/>

      <center><p><strong>Сообщение было успешно изменено!</strong></p></center>

    <br/>';

	$html = file_get_contents ( './templates/infoMessage.html' );

	$html = str_replace ( '{infoMessage}', $msg, $html );



	return $html;

}
/////////////



function ShowTableBlog($where, $action, $ShowCarInfo) {

//туда-сюда

	$column = $_GET ['order'];

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];

	//echo $where;


if ($_REQUEST['action'] === "rubList") {
	$query = "SELECT COUNT(*) FROM AUTO_BLOG_TYPE";
} else {
	$query = "SELECT COUNT(*) FROM AUTO_BLOG WHERE TYPE<>'0'";
}

	$query .= ($where != "") ? " and " . $where : "";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );



	if ($total == 0) {

		if ($_REQUEST['action'] === "rubList") {
		$html .= file_get_contents ( './templates/searchnoRub.html' );
		} else {
		$html .= file_get_contents ( './templates/searchnoBlog.html' );
		}
		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	$table .= "<table width=\"100%\"><tr><td>";

	if ($_REQUEST['action'] === "rubList")
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_rub.gif); width: 118px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=rubAdd\"></a>" . "</td></tr></table>";
	else
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_blog.gif); width: 139px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=blogAdd\"></a>" . "</td></tr></table>";

	$table .= '<form name="chform" method="post">';

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete





	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";




	$table .= "<td valign=\"top\" width=\"30\" align=\"center\">";

	$table .= "<input type=\"checkbox\" title=\"Отметить все\" onclick=\"checkAll(this);\" name=\"chAll\"/></td>";




	$table .= "<td valign=\"middle\" width=\"30\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'id' ) . "&ordertype=";

	$table .= SortOrder ( 'id' );

	$table .= " \">№";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

if ($_REQUEST['action'] === "rubList") {

	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'name' ) . "&ordertype=";

	$table .= SortOrder ( 'name' );

	$table .= " \">Название рубрики";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'active' ) . "&ordertype=";

	$table .= SortOrder ( 'active' );

	$table .= " \">Видимость";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

} else {

	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'zagol' ) . "&ordertype=";

	$table .= SortOrder ( 'zagol' );

	$table .= " \">Заголовок";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'name' ) . "&ordertype=";

	$table .= SortOrder ( 'name' );

	$table .= " \">Рубрика";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'date' ) . "&ordertype=";

	$table .= SortOrder ( 'date' );

	$table .= " \">Дата";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";


	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'active' ) . "&ordertype=";

	$table .= SortOrder ( 'active' );

	$table .= " \">Видимость";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

}




	$table .= "</tr>";


if ($_REQUEST['action'] === "rubList") {
	$query = "SELECT t.* FROM AUTO_BLOG_TYPE t ";
} else {
	$query = "SELECT t.ID as TYPE_ID, b.ID, b.ZAGOL, UNIX_TIMESTAMP(b.DATE) as DATE, b.ACTIVE, t.NAME FROM AUTO_BLOG b,AUTO_BLOG_TYPE t WHERE b.TYPE=t.ID AND b.TYPE <> '0' ";
}





	$query .= (($where) != "") ? " and " . $where : "";

	// $query.= $_SESSION['searchForm']['sql'];

	if (isset ( $_GET ['order'] ))

		$query .= " ORDER BY " . $_GET ['order'] . " " . $_GET ['ordertype'];

	else if ($_REQUEST['action'] === "blogList")

		$query .= " ORDER BY b.DATE desc";

	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;



	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;

	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {



			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}








			$table .= "<tr class=$CssClass>";





			//
			$table .= '<td align="center"><input type="checkbox" value="'. $autolist ['ID'] . '" name="ch[]"/></a>';

			$table .= "</td>";
			//



			//
			$table .= '<td align="center">'. $autolist ['ID'];

			$table .= "</td>";
			//




		if ($_REQUEST['action'] === "rubList") {

			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=rubEdit&id=" . $autolist ['ID'] . ">" . $autolist ['NAME'];

			$table .= "</td>";


		} else {

			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=blogEdit&id=" . $autolist ['ID'] . ">" . $autolist ['ZAGOL'];

			$table .= "</td>";


			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=rubEdit&id=" . $autolist ['TYPE_ID'] . ">" . $autolist ['NAME'];

			$table .= "</td>";


			$table .= "<td class='phpmakerlist'>";

			$table .= date("d.m.Y",$autolist ['DATE']);

			$table .= "</td>";

		}


			$table .= "<td class='phpmakerlist'>";

			$table .= ($autolist ['ACTIVE'] ? 'Отображается' : 'Скрыта');

			$table .= "</td>";






			$table .= "</tr>";



		}

		$table .= "</td></tr></table>";

	}




$table .= "<table width=\"100%\"><tr><td widht=\"100\"><table><tr><td>С отмеченным:
<select id=\"mode\" name=\"mode\">
<option value=\"1\">Удалить</option>
<option value=\"2\">Показать</option>
<option value=\"3\">Скрыть</option>
</select></td><td><a href=\"#\" style=\"background-image:url(img/btn_apply.gif); width: 84px; height: 21px;\"  onclick=\"this.blur(); javascript: document.searchForm.submit();\" class=\"button_apply\"></a></td></tr></table></form></td><td align=\"right\">" . $pages . "</td></tr></table>";




	$table .= "</td></tr></table>";



	return $table;

}





/////
function sostList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormsostList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Состояния авто";

if (empty($_REQUEST['ch'])) {

	$html .= showsostList ();



	$html2 = file_get_contents ( './templates/sostList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=sostList">Состояния авто</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "DELETE FROM AUTO_SOST WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным состояниям атво было успешно применено!<br><a href="?action=sostAdd">Добавить состояние авто</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=sostList">Состояния авто</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showsostList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=sostList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormsostList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableSost ( $_SESSION ['searchFormregionList'] ['sql'], 'sostList', 'sostEdit' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableSost ( $_SESSION ['searchFormsostList'] ['sql'], 'sostList', 'sostEdit' ), $html );

	}
//тттм
	return $html;

}


/////
function colorList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormcolorList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Цвета авто";

if (empty($_REQUEST['ch'])) {

	$html .= showcolorList ();



	$html2 = file_get_contents ( './templates/colorList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=colorList">Цвета авто</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "DELETE FROM AUTO_COLOR WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным цветам атво было успешно применено!<br><a href="?action=colorAdd">Добавить цвет авто</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=colorList">Цвета авто</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showcolorList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=colorList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormcolorList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableSost ( $_SESSION ['searchFormcolorList'] ['sql'], 'colorList', 'colorEdit' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableSost ( $_SESSION ['searchFormcolorList'] ['sql'], 'colorList', 'colorEdit' ), $html );

	}
//тттм
	return $html;

}

/////
function dvigList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormdvigList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Типы двигателей";

if (empty($_REQUEST['ch'])) {

	$html .= showdvigList ();



	$html2 = file_get_contents ( './templates/dvigList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=dvigList">Типы двигателей</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "DELETE FROM AUTO_TYPE_DVIG WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным типам двигателей было успешно применено!<br><a href="?action=dvigAdd">Добавить тип двигателя</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=dvigList">Типы двигателей</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showdvigList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=dvigList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormdvigList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableSost ( $_SESSION ['searchFormdvigList'] ['sql'], 'dvigList', 'dvigEdit' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableSost ( $_SESSION ['searchFormdvigList'] ['sql'], 'dvigList', 'dvigEdit' ), $html );

	}
//тттм
	return $html;

}

/////
function kuzList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormkuzList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Типы кузова";

if (empty($_REQUEST['ch'])) {

	$html .= showkuzList ();



	$html2 = file_get_contents ( './templates/kuzList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=kuzList">Типы кузова</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "DELETE FROM AUTO_TYPE_KUZ WHERE ID IN ". $q;

}

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным типам кузова было успешно применено!<br><a href="?action=kuzAdd">Добавить тип кузова</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=kuzList">Типы кузова</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showkuzList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=kuzList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormcolorList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableSost ( $_SESSION ['searchFormcolorList'] ['sql'], 'kuzList', 'kuzEdit' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableSost ( $_SESSION ['searchFormcolorList'] ['sql'], 'kuzList', 'kuzEdit' ), $html );

	}
//тттм
	return $html;

}


function ShowTableSost($where, $action, $ShowCarInfo) {

//туда-сюда

	$column = $_GET ['order'];

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];

	//echo $where;


if ($_REQUEST['action'] === "sostList") {
	$query = "SELECT COUNT(*) FROM AUTO_SOST";
} else if ($_REQUEST['action'] === "colorList") {
	$query = "SELECT COUNT(*) FROM AUTO_COLOR";
} else if ($_REQUEST['action'] === "dvigList") {
	$query = "SELECT COUNT(*) FROM AUTO_TYPE_DVIG";
} else if ($_REQUEST['action'] === "kuzList") {
	$query = "SELECT COUNT(*) FROM AUTO_TYPE_KUZ";
}

	$query .= ($where != "") ? " and " . $where : "";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );



	if ($total == 0) {

		if ($_REQUEST['action'] === "sostList") {
		$html .= file_get_contents ( './templates/searchnoSost.html' );
		} else if ($_REQUEST['action'] === "colorList") {
		$html .= file_get_contents ( './templates/searchnoColor.html' );
		} else if ($_REQUEST['action'] === "dvigList") {
		$html .= file_get_contents ( './templates/searchnoDvig.html' );
		} else if ($_REQUEST['action'] === "kuzList") {
		$html .= file_get_contents ( './templates/searchnoKuz.html' );
		}
		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	$table .= "<table width=\"100%\"><tr><td>";

	if ($_REQUEST['action'] === "sostList")
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_sost.gif); width: 165px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=sostAdd\"></a>" . "</td></tr></table>";
	else if ($_REQUEST['action'] === "colorList")
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_color.gif); width: 129px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=colorAdd\"></a>" . "</td></tr></table>";
	else if ($_REQUEST['action'] === "dvigList")
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_dvig.gif); width: 155px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=dvigAdd\"></a>" . "</td></tr></table>";
	else if ($_REQUEST['action'] === "kuzList")
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_kuz.gif); width: 135px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=kuzAdd\"></a>" . "</td></tr></table>";

	$table .= '<form name="chform" method="post">';

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete





	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";




	$table .= "<td valign=\"top\" width=\"30\" align=\"center\">";

	$table .= "<input type=\"checkbox\" title=\"Отметить все\" onclick=\"checkAll(this);\" name=\"chAll\"/></td>";




	$table .= "<td valign=\"middle\" width=\"30\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'id' ) . "&ordertype=";

	$table .= SortOrder ( 'id' );

	$table .= " \">№";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

if ($_REQUEST['action'] === "sostList") {

	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'sost' ) . "&ordertype=";

	$table .= SortOrder ( 'sost' );

	$table .= " \">Состояние авто";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

} else if ($_REQUEST['action'] === "colorList") {

	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'color' ) . "&ordertype=";

	$table .= SortOrder ( 'color' );

	$table .= " \">Цвета авто";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

} else if ($_REQUEST['action'] === "dvigList") {

	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'type_dvig' ) . "&ordertype=";

	$table .= SortOrder ( 'type_dvig' );

	$table .= " \">Типы двигателей";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

} else if ($_REQUEST['action'] === "kuzList") {

	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'type_kuz' ) . "&ordertype=";

	$table .= SortOrder ( 'type_kuz' );

	$table .= " \">Типы кузова";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

}




	$table .= "</tr>";


if ($_REQUEST['action'] === "sostList") {
	$query = "SELECT s.* FROM AUTO_SOST s";
} else if ($_REQUEST['action'] === "colorList") {
	$query = "SELECT c.* FROM AUTO_COLOR c";
} else if ($_REQUEST['action'] === "dvigList") {
	$query = "SELECT d.* FROM AUTO_TYPE_DVIG d";
} else if ($_REQUEST['action'] === "kuzList") {
	$query = "SELECT k.* FROM AUTO_TYPE_KUZ k";
}





	$query .= (($where) != "") ? " and " . $where : "";

	// $query.= $_SESSION['searchForm']['sql'];

	if (isset ( $_GET ['order'] ))

		$query .= " ORDER BY " . $_GET ['order'] . " " . $_GET ['ordertype'];

else if ($_REQUEST['action'] === "sostList") {
	//$query .= " ORDER BY s.SOST";
} else if ($_REQUEST['action'] === "colorList") {
	$query .= " ORDER BY c.COLOR";
} else if ($_REQUEST['action'] === "dvigList") {
	$query .= " ORDER BY d.TYPE_DVIG";
} else if ($_REQUEST['action'] === "kuzList") {
	$query .= " ORDER BY k.TYPE_KUZ";
}



	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;



	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;

	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {



			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}








			$table .= "<tr class=$CssClass>";





			//
			$table .= '<td align="center"><input type="checkbox" value="'. $autolist ['ID'] . '" name="ch[]"/></a>';

			$table .= "</td>";
			//



			//
			$table .= '<td align="center">'. $autolist ['ID'];

			$table .= "</td>";
			//




		if ($_REQUEST['action'] === "sostList") {

			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=sostEdit&id=" . $autolist ['ID'] . ">" . $autolist ['SOST'];

			$table .= "</td>";

		} else if ($_REQUEST['action'] === "colorList") {

			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=colorEdit&id=" . $autolist ['ID'] . ">" . $autolist ['COLOR'];

			$table .= "</td>";

		} else if ($_REQUEST['action'] === "dvigList") {

			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=dvigEdit&id=" . $autolist ['ID'] . ">" . $autolist ['TYPE_DVIG'];

			$table .= "</td>";

		} else if ($_REQUEST['action'] === "kuzList") {

			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=kuzEdit&id=" . $autolist ['ID'] . ">" . $autolist ['TYPE_KUZ'];

			$table .= "</td>";

		}









			$table .= "</tr>";



		}

		$table .= "</td></tr></table>";

	}




$table .= "<table width=\"100%\"><tr><td widht=\"100\"><table><tr><td>С отмеченным:
<select id=\"mode\" name=\"mode\">
<option value=\"1\">Удалить</option>
</select></td><td><a href=\"#\" style=\"background-image:url(img/btn_apply.gif); width: 84px; height: 21px;\"  onclick=\"this.blur(); javascript: document.searchForm.submit();\" class=\"button_apply\"></a></td></tr></table></form></td><td align=\"right\">" . $pages . "</td></tr></table>";




	$table .= "</td></tr></table>";



	return $table;

}




function sostAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Добавление состояния авто";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/addSostForm.html' );





	$action = $_SERVER ['PHP_SELF'] . '?action=sostAddSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{SOST}', $_SESSION ['carbase'] ['sost'], $html );







	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=sostList">Состояния авто</a></span> / <span class="und"><a href="">Добавление состояния авто</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function sostAddSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$sost = substr ( $_POST ['x_SOST'], 0, 255 );






	// Обрезаем лишние пробелы

	$region = trim ( $region );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $sost ) > 255)

		$error = $error . '<li>длина названия более 200 символов</li>' . "\n";

	if (empty ( $sost ))

		$error = $error . '<li>не указано состояние авто</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['sost'] = $sost;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=sostAdd' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "INSERT INTO AUTO_SOST

		SET
		`SOST`='" . mysql_escape_string ( $sost ) . "'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении состояния';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addSost.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////



function colorAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Добавление цвета авто";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/addColorForm.html' );





	$action = $_SERVER ['PHP_SELF'] . '?action=colorAddSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{COLOR}', $_SESSION ['carbase'] ['color'], $html );







	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=colorList">Цвета авто</a></span> / <span class="und"><a href="">Добавление цвета авто</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function colorAddSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$color = substr ( $_POST ['x_COLOR'], 0, 255 );






	// Обрезаем лишние пробелы

	$color = trim ( $color );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $color ) > 255)

		$error = $error . '<li>длина цвета более 200 символов</li>' . "\n";

	if (empty ( $color ))

		$error = $error . '<li>не указан цвет авто</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['color'] = $color;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=colorAdd' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "INSERT INTO AUTO_COLOR

		SET
		`COLOR`='" . mysql_escape_string ( $color ) . "'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении цвета';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addColor.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////

function dvigAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Добавление типа двигателя";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/addDvigForm.html' );





	$action = $_SERVER ['PHP_SELF'] . '?action=dvigAddSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{DVIG}', $_SESSION ['carbase'] ['dvig'], $html );







	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=dvigList">Типы двигателей</a></span> / <span class="und"><a href="">Добавление типа двигателя</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function dvigAddSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$dvig = substr ( $_POST ['x_DVIG'], 0, 64 );






	// Обрезаем лишние пробелы

	$dvig = trim ( $dvig );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $dvig ) > 64)

		$error = $error . '<li>длина типа более 60 символов</li>' . "\n";

	if (empty ( $dvig ))

		$error = $error . '<li>не указан тип двигателя</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['dvig'] = $dvig;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=dvigAdd' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "INSERT INTO AUTO_TYPE_DVIG

		SET
		`TYPE_DVIG`='" . mysql_escape_string ( $dvig ) . "'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении цвета';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addDvig.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////

function kuzAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Добавление типа кузова";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/addKuzForm.html' );





	$action = $_SERVER ['PHP_SELF'] . '?action=kuzAddSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{KUZ}', $_SESSION ['carbase'] ['kuz'], $html );







	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=kuzList">Типы кузова</a></span> / <span class="und"><a href="">Добавление типа кузова</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function kuzAddSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$kuz = substr ( $_POST ['x_KUZ'], 0, 255 );






	// Обрезаем лишние пробелы

	$kuz = trim ( $kuz );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $kuz ) > 200)

		$error = $error . '<li>длина типа более 60 символов</li>' . "\n";

	if (empty ( $kuz ))

		$error = $error . '<li>не указан тип кузова</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['kuz'] = $kuz;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=kuzAdd' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "INSERT INTO AUTO_TYPE_KUZ

		SET
		`TYPE_KUZ`='" . mysql_escape_string ( $kuz ) . "'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении цвета';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addKuz.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////
function sostEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение состояния авто";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/editSost.html' );

	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_SOST WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}



	$action = $_SERVER ['PHP_SELF'] . '?action=sostEditSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{SOST}', $data ['SOST'], $html );




	//photo


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=sostList">Состояния авто</a></span> / <span class="und"><a href="">Изменение состояния авто</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function sostEditSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$sost = substr ( $_POST ['x_SOST'], 0, 255 );






	// Обрезаем лишние пробелы

	$sost = trim ( $sost );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $sost ) > 255)

		$error = $error . '<li>длина состояния более 200 символов</li>' . "\n";

	if (empty ( $sost ))

		$error = $error . '<li>не указано состояние авто</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['sost'] = $sost;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=sostEdit&id='.$_SESSION['edID'] );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "UPDATE AUTO_SOST

		SET
		`SOST`='" . mysql_escape_string ( $sost ) . "' WHERE ID='". $_SESSION['edID'] ."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении состояния';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/changeSost.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////
//////////////////////
function colorEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение цвета авто";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/editColor.html' );

	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_COLOR WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}



	$action = $_SERVER ['PHP_SELF'] . '?action=colorEditSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{COLOR}', $data ['COLOR'], $html );




	//photo


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=colorList">Цвета авто</a></span> / <span class="und"><a href="">Изменение цвета авто</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function colorEditSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$color = substr ( $_POST ['x_COLOR'], 0, 255 );






	// Обрезаем лишние пробелы

	$color = trim ( $color );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $color ) > 255)

		$error = $error . '<li>длина цвета более 200 символов</li>' . "\n";

	if (empty ( $color ))

		$error = $error . '<li>не указан цвет авто</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['color'] = $color;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=colorEdit&id='.$_SESSION['edID'] );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "UPDATE AUTO_COLOR

		SET
		`COLOR`='" . mysql_escape_string ( $color ) . "' WHERE ID='". $_SESSION['edID'] ."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении состояния';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/changeColor.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////
//////////////////////
function dvigEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение типа двигателя";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/editDvig.html' );

	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_TYPE_DVIG WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}



	$action = $_SERVER ['PHP_SELF'] . '?action=dvigEditSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{DVIG}', $data ['TYPE_DVIG'], $html );




	//photo


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=dvigList">Типы двигателей</a></span> / <span class="und"><a href="">Изменение типа двигателя</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function dvigEditSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$dvig = substr ( $_POST ['x_DVIG'], 0, 64 );






	// Обрезаем лишние пробелы

	$dvig = trim ( $dvig );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $dvig ) > 60)

		$error = $error . '<li>длина типа более 200 символов</li>' . "\n";

	if (empty ( $dvig ))

		$error = $error . '<li>не указан тип двигателя</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['dvig'] = $dvig;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=dvigEdit&id='.$_SESSION['edID'] );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "UPDATE AUTO_TYPE_DVIG

		SET
		`TYPE_DVIG`='" . mysql_escape_string ( $dvig ) . "' WHERE ID='". $_SESSION['edID'] ."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении типа';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/changeDvig.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////
function kuzEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение типа кузова";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/editKuz.html' );

	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_TYPE_KUZ WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}



	$action = $_SERVER ['PHP_SELF'] . '?action=kuzEditSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{KUZ}', $data ['TYPE_KUZ'], $html );




	//photo


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=kuzList">Типы кузова</a></span> / <span class="und"><a href="">Изменение типа кузова</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function kuzEditSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$kuz = substr ( $_POST ['x_KUZ'], 0, 255 );






	// Обрезаем лишние пробелы

	$kuz = trim ( $kuz );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $kuz ) > 255)

		$error = $error . '<li>длина типа более 200 символов</li>' . "\n";

	if (empty ( $kuz ))

		$error = $error . '<li>не указан тип кузова</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['kuz'] = $kuz;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=kuzEdit&id='.$_SESSION['edID'] );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "UPDATE AUTO_TYPE_KUZ

		SET
		`TYPE_KUZ`='" . mysql_escape_string ( $kuz ) . "' WHERE ID='". $_SESSION['edID'] ."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении типа';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/changeKuz.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////

function ShowTableRegionCity($where, $action, $ShowCarInfo) {

//туда-сюда

	$column = $_GET ['order'];

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];

	//echo $where;


if ($_REQUEST['action'] === "regionList") {
	$query = "SELECT COUNT(*) FROM AUTO_REGION";
} else {
	$query = "SELECT COUNT(*) FROM AUTO_CITY";
}

	$query .= ($where != "") ? " and " . $where : "";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );



	if ($total == 0) {

		if ($_REQUEST['action'] === "regionList") {
		$html .= file_get_contents ( './templates/searchnoRegion.html' );
		} else {
		$html .= file_get_contents ( './templates/searchnoCity.html' );
		}
		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	$table .= "<table width=\"100%\"><tr><td>";

	if ($_REQUEST['action'] === "regionList")
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_region.gif); width: 115px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=regionAdd\"></a>" . "</td></tr></table>";
	else
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_city.gif); width: 108px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=cityAdd\"></a>" . "</td></tr></table>";

	$table .= '<form name="chform" method="post">';

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete





	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";




	$table .= "<td valign=\"top\" width=\"30\" align=\"center\">";

	$table .= "<input type=\"checkbox\" title=\"Отметить все\" onclick=\"checkAll(this);\" name=\"chAll\"/></td>";




	$table .= "<td valign=\"middle\" width=\"30\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'id' ) . "&ordertype=";

	$table .= SortOrder ( 'id' );

	$table .= " \">№";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

if ($_REQUEST['action'] === "regionList") {

	$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'region' ) . "&ordertype=";

	$table .= SortOrder ( 'region' );

	$table .= " \">Регион";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

} else {

	$table .= "<td valign=\"middle\" width=\"180\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'city' ) . "&ordertype=";

	$table .= SortOrder ( 'city' );

	$table .= " \">Город";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

		$table .= "<td valign=\"middle\" width=\"100%\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'id_region' ) . "&ordertype=";

	$table .= SortOrder ( 'id_region' );

	$table .= " \">Регион";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";

}




	$table .= "</tr>";


if ($_REQUEST['action'] === "regionList") {
	$query = "SELECT r.* FROM AUTO_REGION r";
} else {
	$query = "SELECT c.* FROM AUTO_CITY c";
}





	$query .= (($where) != "") ? " and " . $where : "";

	// $query.= $_SESSION['searchForm']['sql'];

	if (isset ( $_GET ['order'] ))

		$query .= " ORDER BY " . $_GET ['order'] . " " . $_GET ['ordertype'];
/*
	else

		$query .= " ORDER BY a.DATE_VVOD desc";
*/
	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;



	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;

	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {



			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}
/*
			$photo = (isset ( $autolist ['PHOTO_1'] )) ? $autolist ['PHOTO_1'] : "";

			if ($photo == "") {

				$img = "<img src=\"photo/none" . $autolist ['CAR_TYPE'] . ".jpg\" border=0  alt=\"Подробнее\"/>";

			} else {

				$img = "<img src=\"show_image.php?filename=photo/" . $photo . "&width=75\" border=0  alt=\"Подробнее\"/>";

			}
*/

			if ($_REQUEST['action'] !== "regionList") {

				$query = "SELECT REGION FROM AUTO_REGION WHERE ID='".$autolist ['ID_REGION']."'";

				//echo $query;

				$r = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $r ) > 0) {

					for($reg=array();$row=mysql_fetch_assoc($r);$reg=$row);

				}

			}

				//echo $reg['REGION'];





			$table .= "<tr class=$CssClass>";





			//
			$table .= '<td align="center"><input type="checkbox" value="'. $autolist ['ID'] . '" name="ch[]"/></a>';

			$table .= "</td>";
			//



			//
			$table .= '<td align="center">'. $autolist ['ID'];

			$table .= "</td>";
			//




		if ($_REQUEST['action'] === "regionList") {

			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=regionEdit&id=" . $autolist ['ID'] . ">" . $autolist ['REGION'];

			$table .= "</td>";

		} else {


			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=cityEdit&id=" . $autolist ['ID'] . ">" . $autolist ['CITY'];

			$table .= "</td>";


			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=regionEdit&id=" . $autolist ['ID_REGION'] . ">" . $reg['REGION'];

			$table .= "</td>";

		}









			$table .= "</tr>";



		}

		$table .= "</td></tr></table>";

	}




$table .= "<table width=\"100%\"><tr><td widht=\"100\"><table><tr><td>С отмеченным:
<select id=\"mode\" name=\"mode\">
<option value=\"1\">Удалить</option>
</select></td><td><a href=\"#\" style=\"background-image:url(img/btn_apply.gif); width: 84px; height: 21px;\"  onclick=\"this.blur(); javascript: document.searchForm.submit();\" class=\"button_apply\"></a></td></tr></table></form></td><td align=\"right\">" . $pages . "</td></tr></table>";




	$table .= "</td></tr></table>";



	return $table;

}




function markAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Добавление марки";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/addMarkForm.html' );





	$action = $_SERVER ['PHP_SELF'] . '?action=markAddSubmit';

	$html = str_replace ( '{action}', $action, $html );


	$query = "SELECT * FROM AUTO_CAR_TYPE order by CAR_TYPE ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($typelist ['ID'] == $_SESSION ['carbase'] ['type']) ? " selected" : "";

			//$type.="<option value='".$typelist['ID']."' ".$selwrk." >".$typelist['CAR_TYPE']."</option>";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . ">" . $typelist ['CAR_TYPE'] . "</option>";



		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );


	$html = str_replace ( '{MARK}', $_SESSION ['carbase'] ['mark'], $html );







	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=markList">Марки авто</a></span> / <span class="und"><a href="">Добавление марки</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function markAddSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input


	$type = abs( intval( $_POST ['id_typeCode'] ) );

	$mark = substr ( $_POST ['id_markCode'], 0, 255 );






	// Обрезаем лишние пробелы

	$mark = trim ( $mark );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if ( $type <= 0)

		$error = $error . '<li>не выбран тип автомобиля</li>' . "\n";

	if (strlen ( $mark ) > 255)

		$error = $error . '<li>длина марки более 255 символов</li>' . "\n";

	if (empty ( $mark ))

		$error = $error . '<li>не указана марка автомобиля</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['carbase'] ['type'] = $type;

		$_SESSION ['carbase'] ['mark'] = $mark;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=markAdd' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "INSERT INTO AUTO_TRADEMARK

		SET
		`CAR_TYPE`='" . mysql_escape_string ( $type ) . "',
		`TRADEMARK`='" . mysql_escape_string ( $mark ) . "'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении марки';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addMark.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////

function markEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение марки";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/editMarkForm.html' );


	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_TRADEMARK WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}


	$action = $_SERVER ['PHP_SELF'] . '?action=markEditSubmit';

	$html = str_replace ( '{action}', $action, $html );


	$query = "SELECT * FROM AUTO_CAR_TYPE order by CAR_TYPE ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($typelist ['ID'] == $data ['CAR_TYPE']) ? " selected" : "";

			//$type.="<option value='".$typelist['ID']."' ".$selwrk." >".$typelist['CAR_TYPE']."</option>";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . ">" . $typelist ['CAR_TYPE'] . "</option>";



		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );


	$html = str_replace ( '{MARK}', $data ['TRADEMARK'], $html );







	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=markList">Марки авто</a></span> / <span class="und"><a href="">Изменение марки</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function markEditSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input


	$type = abs( intval( $_POST ['id_typeCode'] ) );

	$mark = substr ( $_POST ['id_markCode'], 0, 255 );






	// Обрезаем лишние пробелы

	$mark = trim ( $mark );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if ( $type <= 0)

		$error = $error . '<li>не выбран тип автомобиля</li>' . "\n";

	if (strlen ( $mark ) > 255)

		$error = $error . '<li>длина марки более 255 символов</li>' . "\n";

	if (empty ( $mark ))

		$error = $error . '<li>не указана марка автомобиля</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['carbase'] ['type'] = $type;

		$_SESSION ['carbase'] ['mark'] = $mark;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=markEdit' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "UPDATE AUTO_TRADEMARK

		SET
		`CAR_TYPE`='" . mysql_escape_string ( $type ) . "',
		`TRADEMARK`='" . mysql_escape_string ( $mark ) . "' WHERE `ID`='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении марки';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/editMark.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}


function modelAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Добавление модели";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/addModelForm.html' );





	$action = $_SERVER ['PHP_SELF'] . '?action=modelAddSubmit';

	$html = str_replace ( '{action}', $action, $html );


	$query = "SELECT * FROM AUTO_CAR_TYPE order by CAR_TYPE ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($typelist ['ID'] == $_SESSION ['carbase'] ['type']) ? " selected" : "";

			//$type.="<option value='".$typelist['ID']."' ".$selwrk." >".$typelist['CAR_TYPE']."</option>";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . ">" . $typelist ['CAR_TYPE'] . "</option>";



		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );

	$html = str_replace ( '{MARK}', $mark, $html );

	$html = str_replace ( '{MODEL}', $_SESSION ['carbase'] ['model'], $html );







	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=modelList">Модели авто</a></span> / <span class="und"><a href="">Добавление модели</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function modelAddSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input


	$type = abs( intval( $_POST ['id_typeCode'] ) );

	$mark = abs( intval( $_POST ['id_markCode'] ) );

	$model = substr ( $_POST ['id_modelCode'], 0, 255 );




	// Обрезаем лишние пробелы

	$model = trim ( $model );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if ( $type <= 0)

		$error = $error . '<li>не выбран тип автомобиля</li>' . "\n";

	if ( $mark <= 0)

		$error = $error . '<li>не выбрана марка автомобиля</li>' . "\n";

	if (strlen ( $model ) > 255)

		$error = $error . '<li>длина модели более 255 символов</li>' . "\n";

	if (empty ( $model ))

		$error = $error . '<li>не указана модель автомобиля</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['carbase'] ['type'] = $type;

		$_SESSION ['carbase'] ['mark'] = $mark;

		$_SESSION ['carbase'] ['model'] = $model;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=markAdd' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "INSERT INTO AUTO_MODEL

		SET
		`TRADEMARK`='" . mysql_escape_string ( $mark ) . "',
		`MODEL`='" . mysql_escape_string ( $model ) . "'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении модели';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addModel.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////


function modelEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение модели";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/editModelForm.html' );


	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT model.MODEL, model.TRADEMARK, type.ID FROM AUTO_MODEL model, AUTO_TRADEMARK mark, AUTO_CAR_TYPE type WHERE model.ID='".$_SESSION['edID']."' AND model.TRADEMARK=mark.ID AND type.ID=mark.CAR_TYPE";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}


	$action = $_SERVER ['PHP_SELF'] . '?action=modelEditSubmit';

	$html = str_replace ( '{action}', $action, $html );


	$query = "SELECT * FROM AUTO_CAR_TYPE order by CAR_TYPE ";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$type = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $typelist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($typelist ['ID'] == $data ['ID']) ? " selected" : "";

			//$type.="<option value='".$typelist['ID']."' ".$selwrk." >".$typelist['CAR_TYPE']."</option>";

			$type .= "<option value='" . $typelist ['ID'] . "' " . $selwrk . ">" . $typelist ['CAR_TYPE'] . "</option>";



		}

	}

	$html = str_replace ( '{TYPE}', $type, $html );


		$query = "SELECT * FROM AUTO_TRADEMARK where CAR_TYPE=" . $data ['ID'] . "  order by TRADEMARK";

		$res = mysql_query ( $query );

		if (! $res) {

			$msg = 'Ошибка при получении списка марок2';

			$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

			return showErrorMessage ( $msg, $err, true, '' );

		}

		$mark = "";

		if (mysql_num_rows ( $res ) > 0) {

			while ( $marklist = mysql_fetch_array ( $res ) ) {

				$selwrk = ($marklist ['ID'] == $data ['TRADEMARK']) ? " selected" : "";

				$mark .= "<option value='" . $marklist ['ID'] . "' " . $selwrk . " >" . $marklist ['TRADEMARK'] . "</option>";

			}

		}

		$html = str_replace ( '{MARK}', $mark, $html );


	$html = str_replace ( '{MODEL}', $data ['MODEL'], $html );







	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=modelList">Модели авто</a></span> / <span class="und"><a href="">Изменение модели</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function modelEditSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input


	$type = abs( intval( $_POST ['id_typeCode'] ) );

	$mark = abs( intval( $_POST ['id_markCode'] ) );

	$model = substr ( $_POST ['id_modelCode'], 0, 255 );




	// Обрезаем лишние пробелы

	$model = trim ( $model );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if ( $type <= 0)

		$error = $error . '<li>не выбран тип автомобиля</li>' . "\n";

	if ( $mark <= 0)

		$error = $error . '<li>не выбрана марка автомобиля</li>' . "\n";

	if (strlen ( $model ) > 255)

		$error = $error . '<li>длина модели более 255 символов</li>' . "\n";

	if (empty ( $model ))

		$error = $error . '<li>не указана модель автомобиля</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['carbase'] ['type'] = $type;

		$_SESSION ['carbase'] ['mark'] = $mark;

		$_SESSION ['carbase'] ['model'] = $model;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=modelEdit' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "UPDATE AUTO_MODEL

		SET
		`TRADEMARK`='" . mysql_escape_string ( $mark ) . "',
		`MODEL`='" . mysql_escape_string ( $model ) . "' WHERE `ID`='".$_SESSION['edID']."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении модели';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/editModel.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}


function regionAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Добавление региона";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/addRegionForm.html' );





	$action = $_SERVER ['PHP_SELF'] . '?action=regionAddSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{REGION}', $_SESSION ['carbase'] ['region'], $html );







	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=regionList">Регионы</a></span> / <span class="und"><a href="">Добавление региона</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function regionAddSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$region = substr ( $_POST ['x_REGION'], 0, 255 );






	// Обрезаем лишние пробелы

	$region = trim ( $region );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $region ) > 255)

		$error = $error . '<li>длина названия более 200 символов</li>' . "\n";

	if (empty ( $region ))

		$error = $error . '<li>не указано название региона</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['region'] = $region;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=regionAdd' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "INSERT INTO AUTO_REGION

		SET
		`REGION`='" . mysql_escape_string ( $region ) . "'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении региона';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addRegion.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////

//////////////////////

function cityAdd() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Добавление города";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/addCityForm.html' );







	$action = $_SERVER ['PHP_SELF'] . '?action=cityAddSubmit';

	$html = str_replace ( '{action}', $action, $html );


		$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}
//тут..
	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] === $_SESSION['carbase']['region']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

		}

	}

	$html = str_replace ( '{REGION}', $region, $html );


	$html = str_replace ( '{CITY}', $_SESSION['carbase'] ['city'], $html );




	//photo


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=cityList">Города</a></span> / <span class="und"><a href="">Добавление города</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function cityAddSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$city = substr ( $_POST ['x_CITY'], 0, 255 );


	$region = abs ( intval ( $_POST ['id_region'] ) );



	// Обрезаем лишние пробелы

	$city = trim ( $city );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $city ) > 255)

		$error = $error . '<li>длина названия более 200 символов</li>' . "\n";

	if (empty ( $city ))

		$error = $error . '<li>не указано название города</li>' . "\n";

	if ($region <= 0)

		$error = $error . '<li>не указан регион</li>' . "\n";












	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['carbase'] ['city'] = $city;

		$_SESSION ['carbase'] ['region'] = $region;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=cityAdd' );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "INSERT INTO AUTO_CITY

		SET
		`CITY`='" . $city . "',
		`ID_REGION`='" . $region . "'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении города';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/addCity.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}
/////



function regionEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение региона";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/editRegion.html' );

	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_REGION WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}



	$action = $_SERVER ['PHP_SELF'] . '?action=regionEditSubmit';

	$html = str_replace ( '{action}', $action, $html );





	$html = str_replace ( '{REGION}', $data ['REGION'], $html );




	//photo


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=regionList">Регионы</a></span> / <span class="und"><a href="">Изменение региона</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function regionEditSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$region = substr ( $_POST ['x_REGION'], 0, 255 );






	// Обрезаем лишние пробелы

	$region = trim ( $region );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $region ) > 255)

		$error = $error . '<li>длина названия более 200 символов</li>' . "\n";

	if (empty ( $region ))

		$error = $error . '<li>не указано название региона</li>' . "\n";














	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";


		$_SESSION ['carbase'] ['region'] = $region;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=regionEdit&id='.$_SESSION['edID'] );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "UPDATE AUTO_REGION

		SET
		`REGION`='" . mysql_escape_string ( $region ) . "' WHERE ID='". $_SESSION['edID'] ."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении региона';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/changeRegion.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}

//////////////////////

//////////////////////

function cityEdit() {

	if ($_SESSION ['user'] ['status'] !== "admin") { die(); }

	$_SESSION ['pageTitle'] = "Изменение города";





	$html = '';

	// Если при заполнении формы были допущены ошибки

	if (isset ( $_SESSION ['carbase'] )) {

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['carbase'] ['error'], $info );

		$html = $html . $info . "\n";

	}

	$html .= file_get_contents ( './templates/editCity.html' );

	$_SESSION['edID'] = abs(intval($_REQUEST['id']));

	$query = "SELECT * FROM AUTO_CITY WHERE ID='".$_SESSION['edID']."'";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок1';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	if (mysql_num_rows ( $res ) > 0) {

		for($data=array();$row=mysql_fetch_assoc($res);$data=$row);

	}



	$action = $_SERVER ['PHP_SELF'] . '?action=cityEditSubmit';

	$html = str_replace ( '{action}', $action, $html );


		$query = "SELECT * FROM AUTO_REGION ORDER BY REGION";

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка марок';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}
//тут..
	$region = "";

	if (mysql_num_rows ( $res ) > 0) {

		while ( $regionlist = mysql_fetch_array ( $res ) ) {

			$selwrk = ($regionlist ['ID'] === $data['ID_REGION']) ? " selected" : "";

			$region .= "<option value='" . $regionlist ['ID'] . "' " . $selwrk . " >" . $regionlist ['REGION'] . "</option>";

		}

	}

	$html = str_replace ( '{REGION}', $region, $html );


	$html = str_replace ( '{CITY}', $data ['CITY'], $html );




	//photo


	$tpl = $html;


	unset ( $_SESSION ['carbase'] );



	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=cityList">Города</a></span> / <span class="und"><a href="">Изменение города</a></span>';

	$tpl = str_replace ( '{path}', $Gpath, $tpl );

	return $tpl;



}
// ".(($_SESSION['user']['status'] !== 'admin') ? 'AND ID_SALOON='.$_SESSION['user']['id_author'] : '');
//////////////////////

function cityEditSubmit() {

	if ($_POST ['a_add'] != "A") // && ($_POST ['a_add'] != "U"))

{

		header ( 'Location: ' . $_SERVER ['PHP_SELF'] );

		die ();

	}

	if (get_magic_quotes_gpc ()) {

		$_GET = stripslashes_array ( $_GET );

		$_POST = stripslashes_array ( $_POST );

		$_COOKIE = stripslashes_array ( $_COOKIE );

	}

	// Обрезаем переменные до длины, указанной в параметре maxlength тега input




	$city = substr ( $_POST ['x_CITY'], 0, 255 );


	$region = abs ( intval ( $_POST ['id_region'] ) );



	// Обрезаем лишние пробелы

	$city = trim ( $city );







	// Проверяем, заполнены ли обязательные поля

	$error = '';

	if (strlen ( $city ) > 255)

		$error = $error . '<li>длина названия более 200 символов</li>' . "\n";

	if (empty ( $city ))

		$error = $error . '<li>не указано название города</li>' . "\n";

	if ($region <= 0)

		$error = $error . '<li>не указан регион</li>' . "\n";












	// Если были допущены ошибки при заполнении формы - перенаправляем посетителя на страницу регистрации

	if (! empty ( $error )) {



		$_SESSION ['carbase'] = array ();

		$_SESSION ['carbase'] ['error'] = '<p class="errorMsg">При заполнениии формы были допущены ошибки:</p>' . "\n" . '<ul class="errorMsg">' . "\n" . $error . '</ul>' . "\n";

		$_SESSION ['carbase'] ['city'] = $city;

		$_SESSION ['carbase'] ['region'] = $region;






		header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=cityEdit&id='.$_SESSION['edID'] );

		die ();

	}



	// Формируем SQL-запрос
if ($_SESSION ['user'] ['status'] === "admin") {


///








		if ($_POST ['a_add'] == "A") {


//car
			$query = "UPDATE AUTO_CITY

		SET
		`CITY`='" . $city . "',
		`ID_REGION`='" . $region . "' WHERE ID='". $_SESSION['edID'] ."'";

			$res = mysql_query ( $query );

			//echo $query;

			if (! $res) {

				$msg = 'Ошибка при добавлении города';

				//$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

				return showErrorMessage ( $msg, $msg, true, '' );

			}

			//$ID_CAR = @mysql_insert_id ();



			$html .= file_get_contents ( './templates/changeCity.html' );

			//$html = str_replace ( '{URL}', '?action=News&id_saloon='.$_SESSION['user']['id_author'].'&id_news='.$ID_CAR, $html );

			//$html = str_replace ( '{edit}', '?action=editNews&id='.$ID_CAR, $html );


		}



}

	return $html;



}
/////

function getuserList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormuserList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Пользователи";

if (empty($_REQUEST['ch'])) {

	$html .= showuserList ();



	$html2 = file_get_contents ( './templates/userList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=userList">Пользователи</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "DELETE FROM AUTO_USERS WHERE id_author IN ". $q;

} else
if ($_REQUEST['mode'] === "2" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "UPDATE AUTO_USERS SET lock_admin='0' WHERE id_author IN ". $q;

} else
if ($_REQUEST['mode'] === "3" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "UPDATE AUTO_USERS SET lock_admin='1' WHERE id_author IN ". $q;

}
	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным пользователям было успешно применено!<br><a href="?action=addUser">Добавить пользователя</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=userList">Пользователи</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showuserList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=userList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormuserList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableUser ( $_SESSION ['searchFormuserList'] ['sql'], 'userList', 'chinfo' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableUser ( $_SESSION ['searchFormuserList'] ['sql'], 'userList', 'chinfo' ), $html );

	}
//тттм
	return $html;

}


/////
function getautosaloonList() {

	if (!($_SESSION ['user'] ['status'] === 'admin')) {

		//header ( 'Location: ' . $_SERVER ['PHP_SELF'] . '?action=chinfo' );

		die ();

	}



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormautosaloonList'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Автосалоны";

if (empty($_REQUEST['ch'])) {

	$html .= showautosaloonList ();



	$html2 = file_get_contents ( './templates/autosaloonList.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=autosaloonList">Автосалоны</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1" && ($_SESSION['user']['status'] === 'admin')) {


				$query = "SELECT PHOTO_1,PHOTO_2,PHOTO_3 FROM AUTO_SALOON_PHOTO WHERE ID_SALOON IN ". $q;

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph[]=array();$row=mysql_fetch_assoc($res);$ph[]=$row);

			}
		for ($i=0;$i<count($ph);$i++) {

			if ($ph[$i]['PHOTO_1']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo_saloon/'.$ph[$i]['PHOTO_1']);
			if ($ph[$i]['PHOTO_2']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo_saloon/'.$ph[$i]['PHOTO_2']);
			if ($ph[$i]['PHOTO_3']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo_saloon/'.$ph[$i]['PHOTO_3']);

			}



	$query = "DELETE FROM AUTO_USERS WHERE id_author IN ". $q;

} else
if ($_REQUEST['mode'] === "2" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "UPDATE AUTO_USERS SET lock_admin='0' WHERE id_author IN ". $q;

} else
if ($_REQUEST['mode'] === "3" && ($_SESSION['user']['status'] === 'admin')) {

	$query = "UPDATE AUTO_USERS SET lock_admin='1' WHERE id_author IN ". $q;

}
	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным автосалонам было успешно применено!<br><a href="?action=addAutosaloon">Добавить автосалон</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=autosaloonList">Автосалоны</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showautosaloonList() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=autosaloonList';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormautosaloonList'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableUser ( $_SESSION ['searchFormautosaloonList'] ['sql'], 'autosaloonList', 'chinfo' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableUser ( $_SESSION ['searchFormautosaloonList'] ['sql'], 'autosaloonList', 'chinfo' ), $html );

	}
//тттм
	return $html;

}


function ShowTableUser($where, $action, $ShowCarInfo) {

//туда-сюда

	$column = $_GET ['order'];

	unset ( $_SESSION ['sort'] );



	$_SESSION ['sort'] [$column] = $_GET ['ordertype'];

	//echo $where;

	$query = "SELECT COUNT(*) FROM AUTO_USERS a";
if ($_REQUEST['action'] === "userList") {
	$query .= " where a.status='user' ";
} else {
	$query .= " where (a.status='autosaloon' OR a.status='admin') ";
}

	$query .= ($where != "") ? " and " . $where : "";

	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}

	$total = mysql_result ( $res, 0, 0 );



	if ($total == 0) {

		if ($_REQUEST['action'] === "userList") {
		$html .= file_get_contents ( './templates/searchnoUser.html' );
		} else {
		$html .= file_get_contents ( './templates/searchnoAutosaloon.html' );
		}
		return $html;

	}



	// Число страниц списка тем форума (постраничная навигация)

	$cntPages = ceil ( $total / THEMES_PER_PAGE );



	// Проверяем передан ли номер текущей страницы (постраничная навигация)

	if (isset ( $_GET ['page'] )) {

		$page = ( int ) $_GET ['page'];

		if ($page < 1)

			$page = 1;

	} else {

		$page = 1;

	}



	if ($page > $cntPages)

		$page = $cntPages;

		// Начальная позиция (постраничная навигация)

	$start = ($page - 1) * THEMES_PER_PAGE;



	// Строим постраничную навигацию, если это необходимо

	// if ( $cntPages > 1 ) {





	// Функция возвращает html меню для постраничной навигации

	$pages = pageIterator ( $page, $cntPages, $_SERVER ['PHP_SELF'] . '?action=' . $action );

	//}

	$table .= "<table class=\"form_main\" align=\"center\" width=\"100%\">";

	$table .= "<tr class=\"title\">";

	$table .= "<td valign=\"top\" width=\"100%\" align=\"left\" >";

	$table .= "<table width=\"100%\"><tr><td>";

	if ($_REQUEST['action'] === "userList")
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_user.gif); width: 154px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=addUser\"></a>" . "</td></tr></table>";
	else
		$table .= "</td><td align=\"right\">" . "<a class=\"button_apply\" style=\"background-image: url(img/btn_saloon.gif); width: 136px; height: 21px; font-size: 13; font-weight: bold;\" href=\"?action=addAutosaloon\"></a>" . "</td></tr></table>";

	$table .= '<form name="chform" method="post">';

	$table .= "<table class=\"ewTable\" align=\"center\" width=\"100%\">";



	$OptionCnt = 0;

	$OptionCnt ++; // view

	$OptionCnt ++; // edit

	$OptionCnt ++; // copy

	$OptionCnt ++; // delete





	//	<!-- Table header -->

	$table .= "<tr class=\"ewTableHeader\">";




	$table .= "<td valign=\"top\" width=\"30\" align=\"center\">";

	$table .= "<input type=\"checkbox\" title=\"Отметить все\" onclick=\"checkAll(this);\" name=\"chAll\"/></td>";




	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'id_author' ) . "&ordertype=";

	$table .= SortOrder ( 'id_author' );

	$table .= " \">№";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	$table .= "<td valign=\"middle\" width=\"180\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'email' ) . "&ordertype=";

	$table .= SortOrder ( 'email' );

	$table .= " \">e-mail";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	$table .= "<td valign=\"middle\" width=\"180\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'name' ) . "&ordertype=";

	$table .= SortOrder ( 'name' );

	$table .= " \">".(($_REQUEST['action'] === "userList") ? 'ФИО' : 'Название');

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}



	$table .= "<td valign=\"middle\" width=\"280\" align=\"center\">";

	$table .= (($_REQUEST['action'] === "userList") ? 'Местоположение' : 'Адрес');

	$table .= "</td>";






	$table .= "<td valign=\"middle\" width=\"80\" align=\"center\">";

	$table .= "<a href=\"index.php?action=$action&order=" . urlencode ( 'lock_admin' ) . "&ordertype=";

	$table .= SortOrder ( 'lock_admin' );

	$table .= " \">Заблокирован";

	if ($_SESSION ['sort'] ['type'] == "ASC") {

		$table .= "<img src=\"images/sortup.gif\" width=\"10\" height=\"9\" border=\"0\">";

	} elseif ($_SESSION ['sort'] ['type'] == "DESC") {

		$table .= "<img src=\"images/sortdown.gif\" width=\"10\" height=\"9\" border=\"0\">";

	}

	$table .= "</a></td>";



	$table .= "</tr>";


	$query = "SELECT c.* FROM AUTO_USERS c where ";

if ($_REQUEST['action'] === "userList")
	$query .= " (c.status='user') ";
else
	$query .= " (c.status='autosaloon' OR c.status='admin') ";





	$query .= (($where) != "") ? " and " . $where : "";

	// $query.= $_SESSION['searchForm']['sql'];

	if (isset ( $_GET ['order'] ))

		$query .= " ORDER BY " . $_GET ['order'] . " " . $_GET ['ordertype'];
/*
	else

		$query .= " ORDER BY a.DATE_VVOD desc";
*/
	$query .= " LIMIT " . $start . ", " . THEMES_PER_PAGE;



	//echo $query;

	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка при получении списка моделй';

		$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	}



	$RowCnt = 0;

	if (mysql_num_rows ( $res ) > 0) {

		while ( $autolist = mysql_fetch_array ( $res ) ) {



			$RowCnt ++;

			$CssClass = "ewTableRow";

			if ($RowCnt % 2 == 0) {

				$CssClass = "ewTableAltRow";

			}
/*
			$photo = (isset ( $autolist ['PHOTO_1'] )) ? $autolist ['PHOTO_1'] : "";

			if ($photo == "") {

				$img = "<img src=\"photo/none" . $autolist ['CAR_TYPE'] . ".jpg\" border=0  alt=\"Подробнее\"/>";

			} else {

				$img = "<img src=\"show_image.php?filename=photo/" . $photo . "&width=75\" border=0  alt=\"Подробнее\"/>";

			}
*/


				$query = "SELECT REGION FROM AUTO_REGION WHERE ID='".$autolist ['region']."'";

				//echo $query;

				$r = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $r ) > 0) {

					for($reg=array();$row=mysql_fetch_assoc($r);$reg=$row);

				}

				//echo $reg['REGION'];

				$query = "SELECT CITY FROM AUTO_CITY WHERE ID='".$autolist ['city']."'";

				//echo $query;

				$r = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $r ) > 0) {

					for($city=array();$row=mysql_fetch_assoc($r);$city=$row);

				}

				//echo $reg['REGION'];





			$table .= "<tr class=$CssClass>";





			//
			$table .= '<td align="center"><input type="checkbox" value="'. $autolist ['id_author'] . '" name="ch[]"/></a>';

			$table .= "</td>";
			//



			//
			$table .= '<td align="center">'. $autolist ['id_author'];

			$table .= "</td>";
			//






			$table .= "<td class='phpmakerlist'>";

			$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=chinfo&id=" . $autolist ['id_author'] . ">" . $autolist ['email'];

			$table .= "</td>";




			if ($autolist ['status'] === "autosaloon" || $autolist ['status'] === "admin") {

				$table .= "<td valign=\"center\" align=\"center\">";

				$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=chinfo&id=" . $autolist ['id_author'] . "><strong>" . $autolist ["name"] . "</strong></a>";

				$table .= "</td>";



			} else {

				$table .= "<td valign=\"center\" align=\"center\">";

				$table .= "<a href=" . $_SERVER ['PHP_SELF'] . "?action=chinfo&id=" . $autolist ['id_author'] . "><strong>" . $autolist ["name"] . "</strong></a>";

				$table .= "</td>";

			}









			$CITY_A = $reg['REGION'] . ", г. " . $city ['CITY'];

			if ($autolist ['status'] === "autosaloon" || $autolist ['status'] === "admin") {

				$table .= "<td valign=\"top\">";

				$table .= $CITY_A . "</br><strong>" . $autolist ["address"] . "</strong>";

				$table .= "</td>";



			} else {

				$table .= "<td valign=\"top\">";

				$table .= $CITY_A;

				$table .= "</td>";

			}




			$table .= '<td align="center">'.($autolist ['lock_admin'] ? 'Да' : 'Нет');
			$table .= "</td>";

			$table .= "</tr>";



		}

		$table .= "</td></tr></table>";

	}




$table .= "<table width=\"100%\"><tr><td widht=\"100\"><table><tr><td>С отмеченным:
<select id=\"mode\" name=\"mode\">
<option value=\"2\">Разблокировать</option>
<option value=\"3\">Заблокировать</option>
<option value=\"1\">Удалить</option>
</select></td><td><a href=\"#\" style=\"background-image:url(img/btn_apply.gif); width: 84px; height: 21px;\"  onclick=\"this.blur(); javascript: document.searchForm.submit();\" class=\"button_apply\"></a></td></tr></table></form></td><td align=\"right\">" . $pages . "</td></tr></table>";




	$table .= "</td></tr></table>";



	return $table;

}




/////
function getMyCar() {



	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchFormMyCar'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Мои автомобили";

if (empty($_REQUEST['ch'])) {

	$html .= showMyCar ();



	$html2 = file_get_contents ( './templates/myCar.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=myCar">Мои автомобили</a></span>';

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

return $html2;


	} else {


	//
$q = "(" ;
foreach($_REQUEST['ch'] as $val) $q.= "$val,";
// Удаляем последнюю запятую, заменяя ее закрывающей скобкой)
$q = substr($q, 0, strlen($q) - 1 ). ")" ;
// Завершаем формирование SQL-запроса на удаление

if ($_REQUEST['mode'] === "1") {


				$query = "SELECT PHOTO_1,PHOTO_2,PHOTO_3,PHOTO_4,PHOTO_5 FROM AUTO_CAR_BASE WHERE ".(($_SESSION['user']['status'] !== 'admin') ? " ID_USER='".$_SESSION['user']['id_author']."' AND" : '')." ID IN ". $q;

				$res = mysql_query ( $query );

				if (! $res) {

					$msg = 'Ошибка при получении списка марок1';

					$err = 'Ошибка при выполнении запроса: <br/>' . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

					return showErrorMessage ( $msg, $err, true, '' );

				}

				if (mysql_num_rows ( $res ) > 0) {

					for($ph[]=array();$row=mysql_fetch_assoc($res);$ph[]=$row);

			}
		for ($i=0;$i<count($ph);$i++) {
			if ($ph[$i]['PHOTO_1']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph[$i]['PHOTO_1']);
			if ($ph[$i]['PHOTO_2']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph[$i]['PHOTO_2']);
			if ($ph[$i]['PHOTO_3']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph[$i]['PHOTO_3']);
			if ($ph[$i]['PHOTO_4']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph[$i]['PHOTO_4']);
			if ($ph[$i]['PHOTO_5']) unlink($_SERVER['DOCUMENT_ROOT'].'/photo/'.$ph[$i]['PHOTO_5']);
			}



	$query = "DELETE FROM AUTO_CAR_BASE WHERE ".(($_SESSION['user']['status'] !== 'admin') ? " ID_USER='".$_SESSION['user']['id_author']."' AND" : '')."  ID IN ". $q;

} else
if ($_REQUEST['mode'] === "2") {

	$query = "UPDATE AUTO_CAR_BASE SET PREDL='1' WHERE ".(($_SESSION['user']['status'] !== 'admin') ? " ID_USER='".$_SESSION['user']['id_author']."' AND" : '')."  ID IN ". $q;

} else
if ($_REQUEST['mode'] === "3") {

	$query = "UPDATE AUTO_CAR_BASE SET PREDL='0' WHERE ".(($_SESSION['user']['status'] !== 'admin') ? " ID_USER='".$_SESSION['user']['id_author']."' AND" : '')."  ID IN ". $q;

}
	$res = mysql_query ( $query );

	if (! $res) {

		$msg = 'Ошибка';

		$err = 'Ошибка при выполнении запроса: <br/>' . $where . $query . '<br/>' . mysql_errno () . ':&nbsp;' . mysql_error () . '<br/>' . '(Файл ' . __FILE__ . ', строка ' . __LINE__ . ')';

		return showErrorMessage ( $msg, $err, true, '' );

	} else {
	//сюда
		$_SESSION ['loginForm'] ['error'] = '<br/><center><p><b>Действие к выбранным объявлениям было успешно применено!<br><a href="?action=addCar">Добавить объявление</a></b></p></center><br/>';

		$info = file_get_contents ( './templates/infoMessage.html' );

		$info = str_replace ( '{infoMessage}', $_SESSION ['loginForm'] ['error'], $info );

		$html = $html . $info . "\n";

		unset ( $_SESSION ['loginForm'] ['error'] );
	}

	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=myCar">Мои автомобили</a></span>';

	$html = str_replace ( '{path}', $Gpath, $html );

	return $html;

	}



}


function showMyCar() {



	$html = file_get_contents ( './templates/showMyCar.html' );

	$action = $_SERVER ['PHP_SELF'] . '?action=myCar';

	$html = str_replace ( '{action}', $action, $html );


	if (isset ( $_SESSION ['searchFormMyCar'] ['sql'] )) {



		$html = str_replace ( '{FOUND}', ShowTableCar ( $_SESSION ['searchFormMyCar'] ['sql'], 'myCar', 'editCar' ), $html );

		//$html.=ShowTableCar($_SESSION['searchForm']['sql'],'searchView','ShowCar');





	} else {

		$html = str_replace ( '{FOUND}', ShowTableCar ( $_SESSION ['searchFormMyCar'] ['sql'], 'myCar', 'editCar' ), $html );

	}
//тттм
	return $html;

}


////

function searchView() {

if ($_REQUEST['date_list'])
	$Gpath = '<span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'">Главная</a></span> / <span class="und"><a href="http://'.$_SERVER['SERVER_NAME'].'?action=searchView&del=1&date_list=1">Выставленные '.date ( "d.m.Y" ).'</a></span>';
else
	$Gpath = '';

	if (($_POST ["del"] == 1) || ($_GET ["del"] == 1)) {

		unset ( $_SESSION ['searchForm'] );



	}

	$html = "";

	$_SESSION ['pageTitle'] = "Поиск";

	//if (isset($_POST['or'])) $where[]="CAR_MODEL=".$_POST['id_modelCode'] ;

	$where = "";

	if ($_POST) {

		if (isset ( $_POST ['id_typeCode'] ))

			$_SESSION ['searchForm'] ['id_typeCode'] = intval ( RemoveXSS ( $_POST ['id_typeCode'] ) );

		if (isset ( $_POST ['id_markCode'] ))

			$_SESSION ['searchForm'] ['id_markCode'] = intval ( RemoveXSS ( $_POST ['id_markCode'] ) );

		if (isset ( $_POST ['id_modelCode'] ))

			$_SESSION ['searchForm'] ['id_modelCode'] = intval ( RemoveXSS ( $_POST ['id_modelCode'] ) );

		if (isset ( $_POST ['id_region'] ))

			$_SESSION ['searchForm'] ['id_region'] = intval ( RemoveXSS ( $_POST ['id_region'] ) );

		if (isset ( $_POST ['cityCode'] ))

			$_SESSION ['searchForm'] ['id_city'] = intval ( RemoveXSS ( $_POST ['cityCode'] ) );

		if (isset ( $_POST ['year1'] ))

			$_SESSION ['searchForm'] ['year1'] = intval ( RemoveXSS ( $_POST ['year1'] ) );

		if (isset ( $_POST ['year2'] ))

			$_SESSION ['searchForm'] ['year2'] = intval ( RemoveXSS ( $_POST ['year2'] ) );

		if (isset ( $_POST ['date_list'] ))

			$_SESSION ['searchForm'] ['date_list'] = intval ( RemoveXSS ( $_POST ['date_list'] ) );



		if (isset ( $_POST ['id_color'] ))

			$_SESSION ['searchForm'] ['id_color'] = intval ( RemoveXSS ( $_POST ['id_color'] ) );

		if (isset ( $_POST ['id_sost'] ))

			$_SESSION ['searchForm'] ['id_sost'] = intval ( RemoveXSS ( $_POST ['id_sost'] ) );

		if (isset ( $_POST ['id_dvig'] ))

			$_SESSION ['searchForm'] ['id_dvig'] = intval ( RemoveXSS ( $_POST ['id_dvig'] ) );

		if (isset ( $_POST ['id_kuzov'] ))

			$_SESSION ['searchForm'] ['id_kuzov'] = intval ( RemoveXSS ( $_POST ['id_kuzov'] ) );

		if (isset ( $_POST ['id_privod'] ))

			$_SESSION ['searchForm'] ['id_privod'] = intval ( RemoveXSS ( $_POST ['id_privod'] ) );

		if (isset ( $_POST ['prav_rul'] ))

			$_SESSION ['searchForm'] ['prav_rul'] = intval ( RemoveXSS ( $_POST ['prav_rul'] ) );

		if (isset ( $_POST ['rastamogen'] ))

			$_SESSION ['searchForm'] ['rastamogen'] = intval ( RemoveXSS ( $_POST ['rastamogen'] ) );

		if (isset ( $_POST ['bez_probega'] ))

			$_SESSION ['searchForm'] ['bez_probega'] = intval ( RemoveXSS ( $_POST ['bez_probega'] ) );

		if (isset ( $_POST ['kpp'] ))

			$_SESSION ['searchForm'] ['kpp'] = intval ( RemoveXSS ( $_POST ['kpp'] ) );



		$_SESSION ['searchForm'] ['new'] = ($_POST ['new_model'] > 0) ? 1 : 0;

		$_SESSION ['searchForm'] ['foto'] = ($_POST ['foto'] > 0) ? 1 : 0;






		if (isset ( $_POST ['id_typeCode'] ) and $_POST ['id_typeCode'] > 0)

			$where .= "a.CAR_TYPE=" . intval ( mysql_escape_string ( $_POST ['id_typeCode'] ) );

		if (isset ( $_POST ['id_markCode'] ) and $_POST ['id_markCode'] > 0)

			$where = ($where != "") ? $where . " and a.CAR_MARK=" . intval ( mysql_escape_string ( $_POST ['id_markCode'] ) ) : "a.CAR_MARK=" . intval ( mysql_escape_string ( $_POST ['id_markCode'] ) );

		if (isset ( $_POST ['id_modelCode'] ) and $_POST ['id_modelCode'] > 0)

			$where = ($where != "") ? $where . " and a.CAR_MODEL=" . intval ( mysql_escape_string ( $_POST ['id_modelCode'] ) ) : "a.CAR_MODEL=" . intval ( mysql_escape_string ( $_POST ['id_modelCode'] ) );

		if (isset ( $_POST ['id_region'] ) and $_POST ['id_region'] > 0)

			$where = ($where != "") ? $where . " and a.REGION=" . intval ( mysql_escape_string ( $_POST ['id_region'] ) ) : "a.REGION=" . intval ( mysql_escape_string ( $_POST ['id_region'] ) );

		if (isset ( $_POST ['cityCode'] ) and $_POST ['cityCode'] > 0)

			$where = ($where != "") ? $where . " and a.CITY=" . intval ( mysql_escape_string ( $_POST ['cityCode'] ) ) : "a.CITY=" . intval ( mysql_escape_string ( $_POST ['cityCode'] ) );



		if (isset ( $_POST ['year1'] ) and $_POST ['year1'] > 0)

			$where = ($where != "") ? $where . " and a.YEAR_VYP>=" . intval ( mysql_escape_string ( $_POST ['year1'] ) ) : "a.YEAR_VYP>=" . intval ( mysql_escape_string ( $_POST ['year1'] ) );

		if (isset ( $_POST ['year2'] ) and $_POST ['year2'] > 0)

			$where = ($where != "") ? $where . " and a.YEAR_VYP<=" . intval ( mysql_escape_string ( $_POST ['year2'] ) ) : "a.YEAR_VYP<=" . intval ( mysql_escape_string ( $_POST ['year2'] ) );



		if (isset ( $_POST ['id_color'] ) and $_POST ['id_color'] > 0)

			$where = ($where != "") ? $where . " and a.COLOR=" . intval ( mysql_escape_string ( $_POST ['id_color'] ) ) : "a.COLOR=" . intval ( mysql_escape_string ( $_POST ['id_color'] ) );

		if (isset ( $_POST ['id_sost'] ) and $_POST ['id_sost'] > 0)

			$where = ($where != "") ? $where . " and a.SOST=" . intval ( mysql_escape_string ( $_POST ['id_sost'] ) ) : "a.SOST=" . intval ( mysql_escape_string ( $_POST ['id_sost'] ) );

		if (isset ( $_POST ['id_dvig'] ) and $_POST ['id_dvig'] > 0)

			$where = ($where != "") ? $where . " and a.TYPE_DVIG=" . intval ( mysql_escape_string ( $_POST ['id_dvig'] ) ) : "a.TYPE_DVIG=" . intval ( mysql_escape_string ( $_POST ['id_dvig'] ) );

		if (isset ( $_POST ['id_kuzov'] ) and $_POST ['id_kuzov'] > 0)

			$where = ($where != "") ? $where . " and a.TYPE_KUZ=" . intval ( mysql_escape_string ( $_POST ['id_kuzov'] ) ) : "a.TYPE_KUZ=" . intval ( mysql_escape_string ( $_POST ['id_kuzov'] ) );

		if (isset ( $_POST ['id_privod'] ) and $_POST ['id_privod'] > 0)

			$where = ($where != "") ? $where . " and a.TYPE_PRIV=" . intval ( mysql_escape_string ( $_POST ['id_privod'] ) ) : "a.TYPE_PRIV=" . intval ( mysql_escape_string ( $_POST ['id_privod'] ) );

		if (isset ( $_POST ['prav_rul'] ) and $_POST ['prav_rul'] >= 0)

			$where = ($where != "") ? $where . " and a.PRAV_RUL=" . intval ( mysql_escape_string ( $_POST ['prav_rul'] ) ) : "a.PRAV_RUL=" . intval ( mysql_escape_string ( $_POST ['prav_rul'] ) );

		if (isset ( $_POST ['rastamogen'] ) and $_POST ['rastamogen'] >= 0)

			$where = ($where != "") ? $where . " and a.NE_RASTAM=" . intval ( mysql_escape_string ( $_POST ['rastamogen'] ) ) : "a.NE_RASTAM=" . intval ( mysql_escape_string ( $_POST ['rastamogen'] ) );

		if (isset ( $_POST ['bez_probega'] ) and $_POST ['bez_probega'] >= 0)

			$where = ($where != "") ? $where . " and a.BEZ_PROB=" . intval ( mysql_escape_string ( $_POST ['bez_probega'] ) ) : "a.BEZ_PROB=" . intval ( mysql_escape_string ( $_POST ['bez_probega'] ) );

		if (isset ( $_POST ['kpp'] ) and $_POST ['kpp'] >= 0)

			$where = ($where != "") ? $where . " and a.AKPP=" . intval ( mysql_escape_string ( $_POST ['kpp'] ) ) : "a.AKPP=" . intval ( mysql_escape_string ( $_POST ['kpp'] ) );

		if ($_POST ['new_model'] > 0)

			$where = ($where != "") ? $where . " and a.NEW=" . intval ( mysql_escape_string ( $_POST ['new_model'] ) ) : "a.NEW=" . intval ( mysql_escape_string ( $_POST ['new_model'] ) );

		if ($_POST ['foto'] > 0)

			$where = ($where != "") ? $where . " and (a.PHOTO_1 <> '' or a.PHOTO_2 <> '' or a.PHOTO_3 <> '' or a.PHOTO_4 <> '' or a.PHOTO_5 <> '') " : " (a.PHOTO_1 <> '' or a.PHOTO_2 <> '' or a.PHOTO_3 <> '' or a.PHOTO_4 <> '' or a.PHOTO_5 <> '')  ";



		if (isset ( $_POST ['price1'] ))

			$_SESSION ['searchForm'] ['price1'] = abs ( mysql_escape_string ( $_POST ['price1'] ) );

		if (isset ( $_POST ['price2'] ))

			$_SESSION ['searchForm'] ['price2'] = abs ( mysql_escape_string ( $_POST ['price2'] ) );



	//$price1=$_POST['price1'];

	//$price2=$_POST['price2'];





	}



	if ($_GET) {



		if (isset ( $_GET ['id_typeCode'] ))

			$_SESSION ['searchForm'] ['id_typeCode'] = intval ( RemoveXSS ( $_GET ['id_typeCode'] ) );

		if (isset ( $_GET ['id_markCode'] ))

			$_SESSION ['searchForm'] ['id_markCode'] = intval ( RemoveXSS ( $_GET ['id_markCode'] ) );

		if (isset ( $_GET ['id_modelCode'] ))

			$_SESSION ['searchForm'] ['id_modelCode'] = intval ( RemoveXSS ( $_GET ['id_modelCode'] ) );

		if (isset ( $_GET ['id_region'] ))

			$_SESSION ['searchForm'] ['id_region'] = intval ( RemoveXSS ( $_GET ['id_region'] ) );

		if (isset ( $_GET ['cityCode'] ))

			$_SESSION ['searchForm'] ['id_city'] = intval ( RemoveXSS ( $_GET ['cityCode'] ) );

		if (isset ( $_GET ['year1'] ))

			$_SESSION ['searchForm'] ['year1'] = intval ( RemoveXSS ( $_GET ['year1'] ) );

		if (isset ( $_GET ['year2'] ))

			$_SESSION ['searchForm'] ['year2'] = intval ( RemoveXSS ( $_GET ['year2'] ) );

		if (isset ( $_GET ['price1'] ))

			$_SESSION ['searchForm'] ['price1'] = abs ( RemoveXSS ( $_GET ['price1'] ) );

		if (isset ( $_GET ['price2'] ))

			$_SESSION ['searchForm'] ['price2'] = abs ( RemoveXSS ( $_GET ['price2'] ) );

		if (isset ( $_GET ['date_list'] ))

			$_SESSION ['searchForm'] ['date_list'] = intval ( RemoveXSS ( $_GET ['date_list'] ) );

		if (isset ( $_GET ['id_color'] ))

			$_SESSION ['searchForm'] ['id_color'] = intval ( RemoveXSS ( $_GET ['id_color'] ) );

		if (isset ( $_GET ['id_sost'] ))

			$_SESSION ['searchForm'] ['id_sost'] = intval ( RemoveXSS ( $_GET ['id_sost'] ) );

		if (isset ( $_GET ['id_dvig'] ))

			$_SESSION ['searchForm'] ['id_dvig'] = intval ( RemoveXSS ( $_GET ['id_dvig'] ) );

		if (isset ( $_GET ['id_kuzov'] ))

			$_SESSION ['searchForm'] ['id_kuzov'] = intval ( RemoveXSS ( $_GET ['id_kuzov'] ) );

		if (isset ( $_GET ['id_privod'] ))

			$_SESSION ['searchForm'] ['id_privod'] = intval ( RemoveXSS ( $_GET ['id_privod'] ) );

		if (isset ( $_GET ['prav_rul'] ))

			$_SESSION ['searchForm'] ['prav_rul'] = intval ( RemoveXSS ( $_GET ['prav_rul'] ) );

		if (isset ( $_GET ['rastamogen'] ))

			$_SESSION ['searchForm'] ['rastamogen'] = intval ( RemoveXSS ( $_GET ['rastamogen'] ) );

		if (isset ( $_GET ['bez_probega'] ))

			$_SESSION ['searchForm'] ['bez_probega'] = intval ( RemoveXSS ( $_GET ['bez_probega'] ) );

		if (isset ( $_GET ['kpp'] ))

			$_SESSION ['searchForm'] ['kpp'] = intval ( RemoveXSS ( $_GET ['kpp'] ) );



		if ($_GET ['new_model'] > 0) {

			$_SESSION ['searchForm'] ['new'] = intval ( RemoveXSS ( $_GET ['new_model'] ) );

		} else {

			//unset($_SESSION['searchForm']['new']);

		}

		if ($_GET ['foto'] > 0) {

			$_SESSION ['searchForm'] ['foto'] = intval ( RemoveXSS ( $_GET ['foto'] ) );

		} else {

			//unset($_SESSION['searchForm']['foto']);

		}

		//$_SESSION['searchForm']['new']=intval($_GET['new_model']);





		if (isset ( $_GET ['id_typeCode'] ) and $_GET ['id_typeCode'] > 0)

			$where .= "a.CAR_TYPE=" . intval ( mysql_escape_string ( $_GET ['id_typeCode'] ) );

		if (isset ( $_GET ['id_markCode'] ) and $_GET ['id_markCode'] > 0)

			$where = ($where != "") ? $where . " and a.CAR_MARK=" . intval ( mysql_escape_string ( $_GET ['id_markCode'] ) ) : "CAR_MARK=" . intval ( mysql_escape_string ( $_GET ['id_markCode'] ) );

		if (isset ( $_GET ['id_modelCode'] ) and $_GET ['id_modelCode'] > 0)

			$where = ($where != "") ? $where . " and a.CAR_MODEL=" . intval ( mysql_escape_string ( $_GET ['id_modelCode'] ) ) : "CAR_MODEL=" . intval ( mysql_escape_string ( $_GET ['id_modelCode'] ) );

		if (isset ( $_GET ['id_region'] ) and $_GET ['id_region'] > 0)

			$where = ($where != "") ? $where . " and a.REGION=" . intval ( mysql_escape_string ( $_GET ['id_region'] ) ) : "a.REGION=" . intval ( mysql_escape_string ( $_GET ['id_region'] ) );

		if (isset ( $_GET ['cityCode'] ) and $_GET ['cityCode'] > 0)

			$where = ($where != "") ? $where . " and a.CITY=" . intval ( mysql_escape_string ( $_GET ['cityCode'] ) ) : "a.CITY=" . intval ( mysql_escape_string ( $_GET ['cityCode'] ) );



		if (isset ( $_GET ['year1'] ) and $_GET ['year1'] > 0)

			$where = ($where != "") ? $where . " and a.YEAR_VYP>=" . intval ( mysql_escape_string ( $_GET ['year1'] ) ) : "a.YEAR_VYP>=" . intval ( mysql_escape_string ( $_GET ['year1'] ) );

		if (isset ( $_GET ['year2'] ) and $_GET ['year2'] > 0)

			$where = ($where != "") ? $where . " and a.YEAR_VYP<=" . intval ( mysql_escape_string ( $_GET ['year2'] ) ) : "a.YEAR_VYP<=" . intval ( mysql_escape_string ( $_GET ['year2'] ) );

			//$datetoday=mktime(0,0,0,date("m"),date("d"),date("Y"));

		if ($_GET ['date'] == date ( "Y-m-d" ))

			$where = ($where != "") ? $where . " and a.DATE_VVOD='" . date ( "Y-m-d" ) . "'" : " a.DATE_VVOD='" . date ( "Y-m-d" ) . "'";

			//$price1=$_GET['price1'];

		//$price2=$_GET['price2'];





		if (isset ( $_GET ['id_color'] ) and $_GET ['id_color'] > 0)

			$where = ($where != "") ? $where . " and a.COLOR=" . intval ( mysql_escape_string ( $_GET ['id_color'] ) ) : "a.COLOR=" . intval ( mysql_escape_string ( $_GET ['id_color'] ) );

		if (isset ( $_GET ['id_sost'] ) and $_GET ['id_sost'] > 0)

			$where = ($where != "") ? $where . " and a.SOST=" . intval ( mysql_escape_string ( $_GET ['id_sost'] ) ) : "a.SOST=" . intval ( mysql_escape_string ( $_GET ['id_sost'] ) );

		if (isset ( $_GET ['id_dvig'] ) and $_GET ['id_dvig'] > 0)

			$where = ($where != "") ? $where . " and a.TYPE_DVIG=" . intval ( mysql_escape_string ( $_GET ['id_dvig'] ) ) : "a.TYPE_DVIG=" . intval ( mysql_escape_string ( $_GET ['id_dvig'] ) );

		if (isset ( $_GET ['id_kuzov'] ) and $_GET ['id_kuzov'] > 0)

			$where = ($where != "") ? $where . " and a.TYPE_KUZ=" . intval ( mysql_escape_string ( $_GET ['id_kuzov'] ) ) : "a.TYPE_KUZ=" . intval ( mysql_escape_string ( $_GET ['id_kuzov'] ) );

		if (isset ( $_GET ['id_privod'] ) and $_GET ['id_privod'] > 0)

			$where = ($where != "") ? $where . " and a.TYPE_PRIV=" . intval ( mysql_escape_string ( $_GET ['id_privod'] ) ) : "a.TYPE_PRIV=" . intval ( mysql_escape_string ( $_GET ['id_privod'] ) );

		if (isset ( $_GET ['prav_rul'] ) and $_GET ['prav_rul'] >= 0)

			$where = ($where != "") ? $where . " and a.PRAV_RUL=" . intval ( mysql_escape_string ( $_GET ['prav_rul'] ) ) : "a.PRAV_RUL=" . intval ( mysql_escape_string ( $_GET ['prav_rul'] ) );

		if (isset ( $_GET ['rastamogen'] ) and $_GET ['rastamogen'] >= 0)

			$where = ($where != "") ? $where . " and a.NE_RASTAM=" . intval ( mysql_escape_string ( $_GET ['rastamogen'] ) ) : "a.NE_RASTAM=" . intval ( mysql_escape_string ( $_GET ['rastamogen'] ) );

		if (isset ( $_GET ['bez_probega'] ) and $_GET ['bez_probega'] >= 0)

			$where = ($where != "") ? $where . " and a.BEZ_PROB=" . intval ( mysql_escape_string ( $_GET ['bez_probega'] ) ) : "a.BEZ_PROB=" . intval ( mysql_escape_string ( $_GET ['bez_probega'] ) );

		if (isset ( $_GET ['kpp'] ) and $_GET ['kpp'] >= 0)

			$where = ($where != "") ? $where . " and a.AKPP=" . intval ( mysql_escape_string ( $_GET ['kpp'] ) ) : "a.AKPP=" . intval ( mysql_escape_string ( $_GET ['kpp'] ) );

		if (isset ( $_GET ['new_model'] ) and $_GET ['new_model'] > 0)

			$where = ($where != "") ? $where . " and a.NEW=" . intval ( mysql_escape_string ( $_GET ['new_model'] ) ) : "a.NEW=" . intval ( mysql_escape_string ( $_GET ['new_model'] ) );

		if ($_GET ['foto'] > 0)

			$where = ($where != "") ? $where . " and (a.PHOTO_1 <> '' or a.PHOTO_2 <> '' or a.PHOTO_3 <> '' or a.PHOTO_4 <> '' or a.PHOTO_5 <> '') " : " (a.PHOTO_1 <> '' or a.PHOTO_2 <> '' or a.PHOTO_3 <> '' or a.PHOTO_4 <> '' or a.PHOTO_5 <> '')  ";





	}






	$price1 = $_SESSION ['searchForm'] ['price1'];

	$price2 = $_SESSION ['searchForm'] ['price2'];

	settype ( $price1, "float" );

	settype ( $price2, "float" );



	if (($price2 < $price1) && ($price2 != 0)) {

		$price3 = $price1;

		$price1 = $price2;

		$price2 = $price3;

	}

	$_SESSION ['searchForm'] ['price1'] = $price1;

	$_SESSION ['searchForm'] ['price2'] = $price2;



	if ($price1 > 0)

		$where = ($where != "") ? $where . " and PRICE>=" . $price1 : "PRICE>=" . $price1;

	if ($price2 > 0)

		$where = ($where != "") ? $where . " and PRICE<=" . $price2 : "PRICE<=" . $price2;



	if ($_SESSION ['searchForm'] ['date_list'] > 0) {

		$datelist = time () - 86400 * ($_SESSION ['searchForm'] ['date_list'] - 1);

		$vdate = strftime ( '%Y-%m-%d', $datelist );

		$where = ($where != "") ? $where . " and DATE_VVOD>='" . $vdate . "' " : " DATE_VVOD>='" . $vdate . "' ";

	}



	$_SESSION ['searchForm'] ['sql'] = $where;



	$html .= showSearchForm ();



	$html2 = file_get_contents ( './templates/foundForm.html' );

	$html2 = str_replace ( '{found}', $html, $html2 );

	$html2 = str_replace ( '{path}', $Gpath, $html2 );

	return $html2;

}



function SortOrder($column) {

	if (! isset ( $_SESSION ['sort'] [$column] )) {

		return 'DESC';

	}

	if ($_SESSION ['sort'] [$column] == 'ASC') {

		$_SESSION ['sort'] [$column] = 'DESC';

	} else {

		$_SESSION ['sort'] [$column] = 'ASC';

	}

	return $_SESSION ['sort'] [$column];

}



function show_banner($place_banner) {



	//global $link;

	//  if (($id_cat>2) or ($id_cat==1) or ($id_cat==-1)) $id_cat=0;

	//  if ($id_cat==2) $id_banner_page=0;

	$sql = "SELECT CB.* FROM `AUTO_BANNER` CB, `AUTO_BAN_PLACE` BP WHERE CB.PLACE=BP.ID and BP.PLACE_NAME='" . $place_banner . "' and ".time()." >= UNIX_TIMESTAMP(CB.DATA_BEG) and UNIX_TIMESTAMP(" . date ( "Y-m-d" ) . ") <= UNIX_TIMESTAMP(CB.DATA_END) and CB.VISIBLE=1";

	//echo $sql;

	$result = mysql_query ( $sql );

	//echo $sql;

	//$numrows=;



	if (@mysql_num_rows ( $result ) > 0) {

		while ( $row = @mysql_fetch_array ( $result ) ) {

			//print_r($row ["TYPE"]);

			if (substr ( $place_banner, 0, 5 ) == "RIGHT") {

				$height = " height='280' width='180' ";

				$otst = "<div style=\"height: 7px;\" ></div>";//$otst = "</br>";

			} elseif (substr ( $place_banner, 0, 3 ) == "TOP") {

				$height = " height='60' width='468' ";

				$otst = "";

			} else {

				$height = " height='90' width='600' ";

				$otst = "";

			}



			if (intval($row ["TYPE"]) === 0) {

				$banner = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td $height valign=\"middle\">" . $otst . "<a href=" . $row ["LINK"] . " target=_blank><img src=\"banner/" . $row ["BANNER"] . "\" border=\"0\"" . $height . "/></a>" . $otst . $otst . "</td></tr></table>";

			} else {



				$banner = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td $height valign=\"middle\">" . $otst . "<a href=" . $row ["LINK"] . " target=_blank><object> <param name=\"bgcolor\" value=\"#ffffff\" /><embed src=\"banner/" . $row ["BANNER"] . "\" " . $height . " quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"/></object></a>" . $otst . "</td></tr></table>";

			}



		}

		return $banner;

	}



	else {



		if (substr ( $place_banner, 0, 5 ) == "RIGHT") {

			//$banner="<img src=img/none.jpg border=0 width=140/>";

			$banner = "";

		} else //$banner="<a href=?id_z=".$id_banner_page."&place=".$place_banner." alt='Подать заявку'><img src=img/none.gif border=0 /></a>";

{

			//$banner="<img src=img/none.jpg border=0 height=\"60\" />";

			$banner = "";



		}



		return $banner;



	}

}

function RemoveXSS($val) {



	// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed

	// this prevents some character re-spacing such as <java\0script>

	// note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs





	$val = preg_replace ( '/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val );



	// straight replacements, the user should never need these since they're normal characters

	// this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>





	$search = 'abcdefghijklmnopqrstuvwxyz';

	$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	$search .= '1234567890!@#$%^&*()';

	$search .= '~`";:?+/={}[]-_|\'\\';

	for($i = 0; $i < strlen ( $search ); $i ++) {



		// ;? matches the ;, which is optional

		// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

		// &#x0040 @ search for the hex values





		$val = preg_replace ( '/(&#[x|X]0{0,8}' . dechex ( ord ( $search [$i] ) ) . ';?)/i', $search [$i], $val ); // with a ;





		// &#00064 @ 0{0,7} matches '0' zero to seven times

		$val = preg_replace ( '/(&#0{0,8}' . ord ( $search [$i] ) . ';?)/', $search [$i], $val ); // with a ;

	}



	// now the only remaining whitespace attacks are \t, \n, and \r

	//$ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');





	$ra1 = Array ('javascript', 'vbscript', 'expression', '<applet', '<meta', '<xml', '<blink', '<link', '<style', '<script', '<embed', '<object', '<iframe', '<frame', '<frameset', '<ilayer', '<layer', '<bgsound', '<title', '<base' ); // less strict

	$ra2 = Array ('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload' );

	$ra = array_merge ( $ra1, $ra2 );

	$found = true; // keep replacing as long as the previous round replaced something

	while ( $found == true ) {

		$val_before = $val;

		for($i = 0; $i < sizeof ( $ra ); $i ++) {

			$pattern = '/';

			for($j = 0; $j < strlen ( $ra [$i] ); $j ++) {

				if ($j > 0) {

					$pattern .= '(';

					$pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';

					$pattern .= '|(&#0{0,8}([9][10][13]);?)?';

					$pattern .= ')?';

				}

				$pattern .= $ra [$i] [$j];

			}

			$pattern .= '/i';

			$replacement = substr ( $ra [$i], 0, 2 ) . '<x>' . substr ( $ra [$i], 2 ); // add in <> to nerf the tag

			$val = preg_replace ( $pattern, $replacement, $val ); // filter out the hex tags

			if ($val_before == $val) {



				// no replacements were made, so exit the loop

				$found = false;

			}

		}

	}

	return $val;

}

function stripslashes_array($array) {

	return is_array ( $array ) ? array_map ( 'stripslashes_array', $array ) : stripslashes ( $array );

}
//print_r($_SESSION['user']);
?>





