<?php
  # PMS Core Functions 
  # CURRENT : Codebase V1.5( MySql Enterprise 5.n & PHP 5.n & CSS formatting )
  # File    : MyPlus_xhtml-1.5.inc.php - standard xtendable html functions              
  # ----------------------------------------------
  # Author    : John Bunyan
  #
  # Copyright : escapefromuk 2016. All Rights Reserved
  #
  # Code Versions : Date        Versions                      Description
  # ------------- : ----        --------                      -----------
  # 	            : Jul 2003    V1.0 Codebase V1.0.0-initial  : First version MySql V2.0.10 PHP 2.0 VI
  #               : Aug 2004    V1.2 Codebase V1.3.0-17       : MySql 3.0 & PHP V3.0.17
  #               : Jan 2007    V1.4 Codebase V1.5.0-4.4.4    : MySql 5.0 & PHP 4.4.4
  #               : Apr 2009    V1.5 Codebase V1.5.1-5.1.2    : MySql 5.1 & PHP 5.1.2
  #               : Oct 2009    V1.5 Codebase V1.5.1-5.1.2C   : Mysql 5.1 & PHP 5.1.2 & CSS formatting
  #               : Aug 2011    V1.5 Codebase V1.5.1-5.1.2C   : Cosmetic changes
  #               : Apr 2012    V1.5 Codebase V1.5.1-5.1.2D   : Info prompts
  #               : May 2012    V1.5 Codebase V1.5.1-5.1.2D   : Massive re-write of the HTML - remove PHP wrapping
  #               : Dec 2012    V1.5 Codebase V1.5.1-5.1.2E   : Various fixes
  #               : Jan 2016    V1.5 Codebase V1.5.1-5.1.2F   : PMS changes - YAML - errors - db 
  #              
  # Facility	  : PMS
  #
  # Notes:   
  #
  #		1. all functions that need it assume the use of a css sheet defined in the ../styles/ directory
  #             named pms_admin_style.css
  #
  #     The globals defined in Myplus_globals.inc.php are used as defaults. Most are overridden in code.
  #
  #		2. section 1 contains core functions.
  #
  #		3. section 2 contains functions for php and MySql database calls.
  #
  #		4. section 3 contains formatting functions for tables, cells, dates, errors.
  #
  #		5. section 4 contains deprecated functions, included for compatibility.
  #
  #		6. Refer to the supplied documentation for detailed descriptions of use and modifying
  #             the functions to suit if you are so inclined.
  #
  #		7. We recommend you do not change the basic functions but rather copy and paste and modify
  #             since future updates may negate your changes. Licenced copies of PMS are eligible for free updates.
  #
  # ---------------------------------------------
  #
  # Section 1 - core routines
  #
  # ---------------------------------------------
  #
  # Functionname :     mpGetInitialT
  #
  # Description :      Get initial top client details
  #                    Gets and displays an initial client selection / input screen
  #                  
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  # @caller    string    name of calling script for display
  #
  function mpGetInitialT( $caller )
  {
  global $link;
  #
  idHeadN( $caller );
  #
  echo "<!-- mpGetInitialT -->\n";
  echo '<div class="containerUL">' ."\n";
  echo '<div class="ainput">'."\n";
  #
  # page
  #
  echo '<div id="page_margins">'."\n";
  printf ( "<form method=\"post\" action=\"%s\" name=\"form1\">\n", $_SERVER['SCRIPT_NAME']  );
  echo '<div id="page">'."\n";
  echo '<div id="nav">'."\n";
  echo '<a id="navigation" name="navigation"></a>'."\n";
  echo '<div class="hlist">'."\n";
  echo '<ul><li><a href="../../mpAdmin.php">back</a></li></ul></div></div></div>'."\n";
  #
  # get client id
  #
  printf ( "<input type=\"hidden\" name=\"script_action\" value=\"%d\">\n", MYx_VALID_ID );
  echo '<div class="center">'."\n";
  echo '<p>Enter the client ID</p>';
  print ( "<p><input type=\"text\" name=\"client_id\" size=\"5\" maxlength=\"5\"></p>\n" );
  print ( "<input type=\"hidden\" name=\"submitted\" value=\"TRUE\">\n" );
  echo '<div class="type-button"><input type="submit" value="Continue" id="submit" name="submit" /></div>';
  echo '</form>';
  #
  # get email
  #
  printf ( "<form method=\"post\" action=\"%s\" name=\"form2\">\n", $_SERVER['SCRIPT_NAME']  );
  printf ( "<input type=\"hidden\" name=\"script_action\" value=\"%d\">\n", MYx_VALID_EMAIL );
  #
  echo '<div class="center">'."\n";
  echo '<p>or select a client email address</p>';
  printf ( "<p><select name=\"email\" size=\"1\"><option selected>%s</option>\n", "- select -" );
  #
  $query = "SELECT client_id,email FROM mp_client where client_id >= 10000 ORDER BY email ASC";
  $result=mysqli_query($link, $query)
  	or die( "$mp_script - cannot read client table" );
  #
  # read results and format for display
  while ($row = mysqli_fetch_array( $result ) )
  {
    printf ("<option>%s</option>\n", $row[ "email" ] );
  }
  print ( "</select></p>\n" );
  print ( "<input type=\"hidden\" name=\"submitted\" value=\"TRUE\">\n" );
  echo '<div class="type-button"><input type="submit" value="Continue" id="submit" name="submit" /></div>';
  echo '</form>';
  #
  # get client names
  #
  printf ( "<form method=\"post\" action=\"%s\" name=\"form3\">\n", $_SERVER['SCRIPT_NAME'] );
  printf ( "<input type=\"hidden\" name=\"script_action\" value=\"%d\">\n", MYx_VALID_MNAME );
  print ( "<div class=\"center\">\n" );
  print ( "<p>or select a client name</p>\n" );
  printf ( "<p><select name=\"mname\" size=\"1\"><option selected>%s</option>\n", "- select -" );
  #
  $query = "SELECT DISTINCT client_id,first_name,surname FROM mp_client WHERE client_id >= 10000 ORDER BY surname";
  $result=mysqli_query($link, $query)
   or die( "$mp_script - cannot read clients " );
  #
  while ($row = mysqli_fetch_array( $result ) )
  {
   $name_parts = array ( $row[ "first_name" ], $row[ "surname" ] );
   $fullname = implode( $name_parts, " " );
   printf ("<option>%s</option>\n", $fullname );
  }
  print ( "</select></p>\n" );
  print ( "<input type=\"hidden\" name=\"submitted\" value=\"TRUE\">\n" );
  echo '<div class="type-button"><input type="submit" value="Continue" id="submit" name="submit" /></div>';
  echo '</form>';
  #
  # display alpha select bar
  #
  printf ( "<form method=\"post\" action=\"%s\" name=\"form4\">\n", $_SERVER['SCRIPT_NAME'] );
  printf ( "<input type=\"hidden\" name=\"script_action\" value=\"%d\">\n", MYx_VALID_SINDEX );
  print ( "<div class=\"center\">\n" );
  #
  # set up indicator for what we can search on
  #
  $search_str = "surname";
  mpAlphaIndex( $search_str, "EN", MYx_VALID_SINDEX );
  #
  print ( "<input type=\"hidden\" name=\"submitted\" value=\"TRUE\">\n" );
  print ( "</div></form><br />\n" );
  print ( "</div>\n" );
  echo '</div></div>'."\n";
 #
 } // end: mpGetInitialT
 #
 # ================================================================
 #
 # Functionname :     mpGetInitialE
 #
 # Description :      Get initial Estate details
 #                    Gets and displays an Estate selection / input screen
 #                  
 #
 # Name	     Type      Description
 # ----	     ----      ----------- 
 # @caller    string    name of calling script for display
 #
 function mpGetInitialE( $caller )
 {
  global $link;
  #
  idHeadN( $caller );
  #
  echo "<!-- mpGetInitialE -->\n";
  echo '<div class="containerUL">' ."\n";
  echo '<div class="ainput">'."\n";
  #
  # page
  #
  echo '<div id="page_margins">'."\n";
  printf ( "<form method=\"post\" action=\"%s\" name=\"form1\">\n", $_SERVER['SCRIPT_NAME']  );
  echo '<div id="page">'."\n";
  echo '<div id="nav">'."\n";
  echo '<a id="navigation" name="navigation"></a>'."\n";
  echo '<div class="hlist">'."\n";
  echo '<ul><li><a href="../../mpAdmin.php">back</a></li></ul></div></div></div>'."\n";
  #
  # get estate id
  #
  printf ( "<input type=\"hidden\" name=\"script_action\" value=\"%d\">\n", MYx_VALID_ID );
  echo '<div class="center">'."\n";
  echo '<p>Enter the Estate ID</p>';
  print ( "<p><input type=\"text\" name=\"g_id\" size=\"5\" maxlength=\"5\"></p>\n" );
  print ( "<input type=\"hidden\" name=\"submitted\" value=\"TRUE\">\n" );
  echo '<div class="type-button"><input type="submit" value="Continue" id="submit" name="submit" /></div>';
  echo '</form>';
  #
  # get primary email
  #
  printf ( "<form method=\"post\" action=\"%s\" name=\"form2\">\n", $_SERVER['SCRIPT_NAME']  );
  printf ( "<input type=\"hidden\" name=\"script_action\" value=\"%d\">\n", MYx_VALID_EMAIL );
  #
  echo '<div class="center">'."\n";
  echo '<p>or select Estate primary email address</p>';
  printf ( "<p><select name=\"g_email1\" size=\"1\"><option selected>%s</option>\n", "- select -" );
  #
  $query = "SELECT DISTINCT g_id,g_email1 FROM mp_org where g_id > 10001 ORDER BY g_email1 ASC";
  $result=mysqli_query($link,  $query)
  	or die( "$mp_script - cannot read estate table" );
  #
  # read results and format for display
  while ($row = mysqli_fetch_array( $result ) )
  {
    printf ("<option>%s</option>\n", $row[ "g_email1" ] );
  }
  print ( "</select></p>\n" );
  print ( "<input type=\"hidden\" name=\"submitted\" value=\"TRUE\">\n" );
  echo '<div class="type-button"><input type="submit" value="Continue" id="submit" name="submit" /></div>';
  echo '</form>';
  #
  # get names
  #
  printf ( "<form method=\"post\" action=\"%s\" name=\"form3\">\n", $_SERVER['SCRIPT_NAME'] );
  printf ( "<input type=\"hidden\" name=\"script_action\" value=\"%d\">\n", MYx_VALID_MNAME );
  print ( "<div class=\"center\">\n" );
  print ( "<p>or select an Estate primary contact name</p>\n" );
  printf ( "<p><select name=\"g_cont_name\" size=\"1\"><option selected>%s</option>\n", "- select -" );
  #
  $query = "SELECT DISTINCT g_id,g_cont_name FROM mp_org WHERE g_id > 10001 ORDER BY g_name";
  $result=mysqli_query($link, $query)
   or die( "$mp_script - cannot read estate table" );
  #
  while ($row = mysqli_fetch_array( $result ) )
  {
   printf ("<option>%s</option>\n", $row[ "g_cont_name" ] );
  }
  print ( "</select></p>\n" );
  print ( "<input type=\"hidden\" name=\"submitted\" value=\"TRUE\">\n" );
  echo '<div class="type-button"><input type="submit" value="Continue" id="submit" name="submit" /></div>';
  echo '</form>';
  #
  # display alpha select bar
  #
  printf ( "<form method=\"post\" action=\"%s\" name=\"form4\">\n", $_SERVER['SCRIPT_NAME'] );
  printf ( "<input type=\"hidden\" name=\"script_action\" value=\"%d\">\n", MYx_VALID_SINDEX );
  print ( "<div class=\"center\">\n" );
  #
  # set up indicator for what we can search on
  #
  $search_str = "Estate name";
  mpAlphaIndex( $search_str, "EN", MYx_VALID_SINDEX );
  #
  print ( "<input type=\"hidden\" name=\"submitted\" value=\"TRUE\">\n" );
  print ( "</div></form><br />\n" );
  print ( "</div>\n" );
  echo '</div></div>'."\n";
 #
 } // end: mpGetInitialE 
 #
 # ================================================================
 #
 # Functionname : idHeadA
 #
 # Description : identifier header line for Admin pages
 #
 # Parameters
 # ----------
 #
 # Name	    Type      Description
 # ----	    ----      -----------
 # @caller  string    caller
 #
 function idHeadA( $caller )
 {
  #
  # globals
  #
  global $infoMess;  
  #
  echo "<!-- idHeadA -->\n";
  print ( "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n" );
  print ( "<tr><td>\n" );
  require_once ( "../../../includes/short_stat.inc.php" );
  print ( "</td><td align=\"top\">\n" );
  #
  print ( "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n" );
  print ( "<tr><td valign=\"top\">\n" );
  if ($_SESSION["sel"] == "LIV") {
   require_once ( "../../../includes/liv_search.inc.php" );
  }
  echo '</td></tr>' ."\n";
  #
  echo '<tr><td align="left">' ."\n";
  echo '<table border="0" cellspacing="4" cellpadding="8">' ."\n";
  echo '<tr>' ."\n";
  print ( "<td align=\"center\"><a href=\"mpLogout.php\" onMouseOver=\"return overlib('Logout - back to login page',BGCOLOR,'#ffffff',FGCOLOR,'#ff6633',TEXTCOLOR,'#ffffff')\" onMouseOut=\"nd();\"><img src=\"../buttons/logout.png\" width=\"32\" height=\"32\" border=\"0\"></a></td>\n" );
  print ( "<td align=\"center\"><a href=\"mpChangePwd.php\" onMouseOver=\"return overlib('Change your login password',BGCOLOR,'#ffffff',FGCOLOR,'#ff6633',TEXTCOLOR,'#ffffff')\" onMouseOut=\"nd();\"><img src=\"../buttons/change_password.png\" width=\"32\" height=\"32\" border=\"0\"></a></td>\n" );
  print ( "<td align=\"center\"><a href=\"../code/v15/displays/mpUpdsd.php\" onMouseOver=\"return overlib('Update system data',BGCOLOR,'#ffffff',FGCOLOR,'#ff6633',TEXTCOLOR,'#ffffff')\" onMouseOut=\"nd();\"><img src=\"../buttons/sys_edit.png\" width=\"32\" height=\"32\" border=\"0\"></a></td>\n" );
  print ( "<td align=\"center\"><a href=\"mpUpdsp.php\" onMouseOver=\"return overlib('Update Parameter Settings',BGCOLOR,'#ffffff',FGCOLOR,'#ff6633',TEXTCOLOR,'#ffffff')\" onMouseOut=\"nd();\"><img src=\"../buttons/params.png\" width=\"32\" height=\"32\" border=\"0\"></a></td>\n" );
  if( $_SESSION['account_type'] < 3 ) {
   print ( "<td align=\"center\"><a href=\"../code/v15/01_layouts_basics/mpIncome.php\" onMouseOver=\"return overlib('View Contract Income',BGCOLOR,'#ffffff',FGCOLOR,'#ff6633',TEXTCOLOR,'#ffffff')\" onMouseOut=\"nd();\"><img src=\"../buttons/curr_add.png\" width=\"32\" height=\"32\" border=\"0\"></a></td>\n" );
  }
  #
  # this should NEVER be seen after initial configuration!
  if ($_SESSION['config'] == "N" ) {
   echo '<td align="center"><a href="mpDBinstall.php"><img src="../buttons/big-database.png" width="32" height="32" border="0" alt="Install Database" title="Install Database"></a></td>' ."\n";
  }
  #
  # test data loaded indicator
  if ($_SESSION['tdi'] == "Y" ) {
   print ( " <td align=\"center\"><a href=\"mpUpdateCust.php\" onMouseOver=\"return overlib('Remove Test Data and reset',BGCOLOR,'#ffffff',FGCOLOR,'#ff6633',TEXTCOLOR,'#ffffff')\" onMouseOut=\"nd();\"><img src=\"../buttons/del_db.png\" width=\"32\" height=\"32\" border=\"0\"></a></td>\n" );
  }
  echo '</tr></table></td>' ."\n";
  #
  # display any info messages
  #
  print ( "</tr>\n");
  if( !empty($infoMess) ) {  
   printf ( "<tr><td align=\"center\" valign=\"middle\"colspan=\"3\"><p style=\"color:#FF0000\" class=\"info\"><strong>%s</strong></p></td></tr>\n", $infoMess );
   } else {
    $infoMess = "No tasks due";
    printf ( "<tr><td align=\"center\" valign=\"middle\"colspan=\"3\"><p style=\"color:black\" class=\"info\"><strong>%s</strong></p></td></tr>\n", $infoMess );
  }
  print ( "</table>\n");
  print ( "</td>\n" );
  print ( "<td style='padding-left: 0em;'>\n" );
  require_once ( "../../../includes/short_flags.inc.php" );
  # image over
  print ( "</td><td><p class=\"info\"><a href=\"javascript:void(0);\" onMouseOver=\"return overlib('PMS - Property Management System. (c) escapefromuk 2016. Version 1.5',BGCOLOR,'#ffffff',FGCOLOR,'#ff6633',TEXTCOLOR,'#ffffff')\" onMouseOut=\"nd();\"><img src=\"../images/sitelogo.jpg\" width=\"700\" height=\"140\"></p></td>\n" );
  print ( "</tr></table>\n");
  #
  } // end:idHeadA
  #
  # ================================================================
  #
  # Functionname :     idHeadN
  #
  # Description :      identifier header line for all normal pages
  #                    display current script name and any active modes
  #                    from session data.
  #
  #                    Only diffence is the <a href tag targets for displayed images
  #
  # Parameters
  # ----------
  #
  # Name       Type      Description
  # ----       ----      -----------
  # @caller    string    caller
  #
  function idHeadN( $caller )
  {
  #
  # globals
  global $mp_script;
  global $infoMess;
  #
  echo "<!-- idHeadN -->\n";
  print ( "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n" );
  print ( "<tr><td>\n" );
  require_once ( "../../../includes/short_stat.inc.php" );
  print ( "</td><td align=\"top\">\n" );
  #
  print ( "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n" );
  print ( "<tr><td align=\"left\">\n" );
  if ($_SESSION["sel"] == "LIV") {
   require_once ( "../../../includes/liv_search.inc.php" );
  }
  echo '</td></tr>' ."\n";
  #
  #
  echo '<tr><td align="left">' ."\n";
  echo '<table border="0" cellspacing="4" cellpadding="8">' ."\n";
  echo '<tr>' ."\n";
  echo '<td align="center"><a href="../../../code/mpLogout.php"><img src="../../../buttons/logout.png" width="32" height="32" border="0" alt="logout" title="logout"></a></td>' ."\n";
  echo '<td align="center"><a href="../../../code/mpChangePwd.php"><img src="../../../buttons/change_password.png" width="32" height="32" border="0" alt="change password" title="change password"></a></td>' ."\n";
  echo '<td align="center"><a href="../../../code/v15/displays/mpUpdsd.php"><img src="../../../buttons/sys_edit.png" width="32" height="32" border="0" alt="Update System Data" title="Update System Data"></a></td>' ."\n";
  echo '<td align="center"><a href="../../../code/mpUpdsp.php"><img src="../../../buttons/params.png" width="32" height="32" border="0" alt="Change Parameters" title="Change Parameters"></a></td>' ."\n";
  echo '<td align="center"><a href="../../../code/v15/01_layouts_basics/mpIncome.php"><img src="../../../buttons/curr_add.png" width="32" height="32" border="0" alt="View Income" title="View Income"></a></td>' ."\n";
  echo '</tr></table></td></tr>' ."\n";
  #
  # any info or debug messages ?
  #
  print ( "</tr>\n");
  printf ( "<tr><td align=\"left\" colspan=\"3\"><p class=\"infomess\">%s</p></td></tr>\n", $infoMess );
  print ( "</table>\n");
  print ( "</td>\n" );
  print ( "<td style='padding-left: 1em;'>\n" );
  print ( "</td>\n" );
  print ( "</tr></table>\n");
  #
  } // end:idHeadN
 #
 # ================================================================
 #
 # Functionname :     idHeadP
 #
 # Description :      identifier header line for prints / lists
 #                    display current script name and head string
 #                    
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 # @caller   string    calling script
 #
 function idHeadP( $caller )
 {
  #
  # globals
  #
  global $infoMess;
  #
  echo "<!-- idHeadP -->\n";
  print ( "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n" );
  print ( "<tr><td>" );
  require_once ( "../../../includes/short_stat_n.inc.php" );
  print ( "</td>" );
  if( !empty($infoMess) ) {  
   printf ( "<td align=\"center\" valign=\"middle\"colspan=\"3\"><p style=\"color:#FF0000\" class=\"info\"><strong>%s</strong></p></td>\n", $infoMess );
  } 
  print ( "</table>");
  #
 } // end:idHeadP  
 #
 # ================================================================
 #
 # Functionname :     mpBackPage
 #
 # Description :      Display back button. Sends browser back to named page
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 # @page      string    name of page
 # @lang      string    language
 #
 function mpBackPage( $page, $lang )
 {
   #
   # globals
   #
   if ( empty ($lang) )
    $lang=$_SESSION['mp_lang'];
   #
   switch( $lang )
   {
   case "EN":
    echo "<!-- mpBackPage -->\n";
    print ( "<div id=\"idBack\">\n" );
    printf ( "<td align=\"center\"><a href=\"%s\" onmouseout=\"turn_off('go_back_B1')\" onmouseover=\"turn_over('go_back_B1')\" ><img name=\"go_back_B1\" src=\"../buttons/go_back_B1.png\" alt=\"go back\" title=\"go back\" width=\"32\" height=\"32\" border=\"0\"></a></td>", $page );
    print ( "</div>\n" );
    break;
   case "DE":
    break;
   case "ES":
    break;
   }
  } //end: mpBackPage
 #
 # ================================================================
 #
 # Functionname :     mpBackCode
 #
 # Description :      javascript for go back button. Sends browser back 
 #
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 # none
 #
 function mpBackCode()
 {
 #
  echo "<!-- mpBackCode -->\n";
  print ( "<script language=\"JavaScript\" type=\"text/javascript\">\n" );
  print ( "<!-- mpBackCode Dummy comment to hide code from non-JavaScript browsers.\n" );
  print ( "if (document.images) {\n" );
  print ( "go_back_B1_off = new Image(); go_back_B1_off.src = \"../buttons/go_back_B1.png\"\n" );
  print ( "go_back_B1_over = new Image(); go_back_B1_over.src = \"../buttons/go_back_B1_over.png\"\n" );
  print ( "}\n" );
  print ( "function turn_off_back(ImageName) {\n" );
  print ( " if (document.images != null) {\n" );
  print ( "  document[ImageName].src = eval(ImageName + \"_off.src\");\n" );
  print ( "}\n" );
  print ( "}\n" );
  print ( "function turn_over_back(ImageName) {\n" );
  print ( " if (document.images != null) {\n" );
  print ( "  document[ImageName].src = eval(ImageName + \"_over.src\");\n" );
  print ( "}\n" );
  print ( "}\n" );
  print ( "// End of mpBackCode -->\n" );
  print ( "</script>\n" );
  } // end: mpbackCode
  #
  # ================================================================
  #
  # Functionname :     mpListNames
  #
  # Description :      List clients added but not completed
  #                   
  # ----------
  #
  # Name	Type      Description
  # ----	----      -----------
  #    
  function mpListNames()       
  {
  #
  # globals
  #
  global $link;
  global $mp_script;
  #
  if( !empty( $_POST[ 'parFname' ] ) )
  {
    $parFname = mpEscapeData( $_POST[ "parFname" ] );
  }
  if( !empty( $_POST[ 'parSname' ] ) )
  {
    $parSname = mpEscapeData( $_POST[ "parSname" ] );
  }
  #
  # display list of all added but not completed
  #
  $str = "Click on client id to select";
  idHeadN( $mp_script, $str );
  #
  # divs
   echo "<!-- mpListNames -->\n";
   echo '<div id="page_margins">';
   echo '<div id="page">'."\n";           
   echo '<div id="nav">'."\n";           
   echo '<!-- skiplink anchor: navigation -->'."\n";
   echo '<a id="navigation" name="navigation"></a>'."\n";
   echo '<div class="hlist">'."\n";
   echo '<!-- main navigation: #nav_main -->'."\n";
   echo '<ul><li><a href="../../mpAdmin.php">back</a></li></ul></div></div>'."\n";
   echo '</div>'."\n";
   #
   #  Number of records to show per page:
   #
   $display = "MPx_DISPLAY";
   #
   # Determine how many pages there are or need to determine
   #
   if (isset($_GET['np'])) {
    $num_pages = $_GET['np'];
   } else {
    #
    # Count the number of records
    #
    $query = "SELECT COUNT(*) FROM mp_client where first_f = 'Y' ORDER BY surname ASC";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result, MYSQL_NUM);
    $num_records = $row[0];
    #
    # Calculate the number of pages
    #
    if ($num_records > $display) { // More than 1 page.
      $num_pages = ceil($num_records/$display);
    } else {
      $num_pages = 1;
    }
    #
    } // End of np IF.
    # Determine where in the database to start returning results
    if (isset($_GET['s'])) {
     $start = $_GET['s'];
    } else {
     $start = 0;
    }
    #
    $query = "SELECT * FROM mp_client where first_f = 'Y' ORDER BY surname ASC LIMIT $start, $display";
    #
    $result = mysqli_query($link, $query)
     or die ( "$mp_script - cannot locate any uncompleted clients" );
    #
    # info box
    #
    echo '<div class="center">' ."\n";
    echo '<div class="einfo">' ."\n";
    echo '<p class="infoI"><img class="middle" src="../../../buttons/info_warning.png" width="32" height="32" border="0" vertical-align:text-top;>&nbsp;Info:</p>' ."\n";
    echo '<p class="infoL">These clients have been added but details are not yet completed. To complete registration, click on a client ID.</p>' ."\n";
    echo '</div>' ."\n";
    echo '<br />' ."\n";
    #
    # display client records for update (if any)
    #
    print ( "<br />\n" );
    print ( "<div align=\"center\">\n" );
    print ( "<table border=\"1\" bordercolor=\"#000080\" bgcolor=\"#ffcc00\" cellspacing=\"0\" cellpadding=\"0\" width=\"95%\">\n" );
    print ( "<tr>\n" );
    mpDisplayHeadCell ( "td", "datop", "Client ID", 1 );
    mpDisplayHeadCell ( "td", "datop", "First name", 1 );
    mpDisplayHeadCell ( "td", "datop", "Surname", 1 );
    print  ( "</tr>\n" );
    #
    $bg = '#eeeeee'; // background color.
    #
    # associate each with a link to display detail
    #
    $num_recs = 0;
    while ( $row = mysqli_fetch_array( $result ) )
    {
     #
     $bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // background color.
     $num_recs++;
     printf ( "<tr bgcolor=\"%s\">\n", $bg );
     $url = sprintf ( "%s?script_action=%s&client_id=%d", $_SERVER['SCRIPT_NAME'], MYx_GET_MEMBER_DET, $row[ "client_id" ] );
     mpDisplayCell ( "td", "DispNC", "<a href=\"$url\">" . $row[ "client_id" ] ."</a>", 0);
    #
    # names
    #
    $name_parts = array( $row[ "first_name" ], $row[ "surname" ] );
    $full_name = implode( $name_parts, " " );
    mpDisplayCell ( "td", "DispNC", $row[ "first_name" ], 1 );
    mpDisplayCell ( "td", "DispNC", $row[ "surname" ], 1 );
    #
    #
    } // end: while
    #  Make the links to other pages, if necessary
    print ( "<tr><td colspan=\"3\" align=\"center\" valign=\"middle\" bgcolor=\"#eeeeee\">\n" );
    if ($num_pages > 1) {
     #  Determine what page the script is on.
     $current_page = ($start/$display) + 1;
     #
     # If it's not the first page, make a Previous button.
     if ($current_page != 1) {
        $url = sprintf ( "%s?script_action=%s&s=%s&np=%d", $_SERVER['SCRIPT_NAME'], MYx_LIST_NAMES, ($start - $display), $num_pages  );
    	print ("<a href=\"$url\">&nbsp;Previous&nbsp;</a>\n" );
     }
     #
     #  Make all the numbered pages
     for ($i = 1; $i <= $num_pages; $i++) {
       if ($i != $current_page) {
        $url = sprintf ( "%s?script_action=%s&s=%s&np=%d", $_SERVER['SCRIPT_NAME'], MYx_LIST_NAMES, (($display * ($i - 1))), $num_pages );
        printf ( "<a href=\"$url\">&nbsp;%d&nbsp;</a>\n", $i  );
     } else {
       echo $i . ' ';
     }
    } // end: if loop
    #
    # If it's not the last page, make a Next button
    if ($current_page != $num_pages) {
      $url = sprintf ( "%s?script_action=%s&s=%s&np=%d", $_SERVER['SCRIPT_NAME'], MYx_LIST_NAMES, ($start + $display), $num_pages );
    print ( "<a href=\"$url\">&nbsp;Next&nbsp;</a>\n" );
    }
     #
    } // end: for loop
   #
   # End of links section
   print ( "</td></tr>\n" );
   print ( "<tr>\n" );
   print ( "<td colspan=\"3\" align=\"center\" valign=\"middle\" bgcolor=\"#eeeeee\">" );
   printf ( "<font face=\"Arial\" size=\"1\" color=\"#000000\">%s clients found</font></td></tr>\n", $num_records );
   print ( "</table>\n" );
  #
  } //end: function mpListnames     
  #
  # ================================================================
  #
  # Functionname :     mpBegin
  #
  # Description :      page header & html head
  #
  # Parameters
  # ----------
  #
  # Name	Type      Description
  # ----	----      -----------
  # @s_title   string     title for browser header - used by spiders to spider the site.
  #		          		  The string $stitle in ../xhtml/MyPlus_globals.inc.php is the default.
  #		          		  If the parameter $s_title is blank we use the default title in ../xhtml/MyPlus_globals.inc.php.
  #
  # @lang 	 string   language
  #
  function mpBegin( $s_title, $lang )
  {
   #
   # globals
   #
   global $MP_stitle;
   #
   if ( empty ($lang) )
    $lang=$MP_lang;
    #
    switch( $lang )
    {
    case "EN":
     echo '<?xml version="1.0" encoding="utf-8"?>' ."\n";
     print ( "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/2000/REC-xhtml/DTD/xhtml1-transitional.dtd\">\n" );
     echo '<html xml:lang="en-br" lang="en-br">' ."\n";
     if(isset($_SESSION['mp_copy'])) {
      if ( $_SESSION['mp_copy'] == "Y" ) {
       echo '<!-- Copyright (C) 2003-2016 escapefromuk. All rights reserved -->';
      } else {
       echo '<!-- Copyright (C) 2016 escapefromuk.  All rights reserved -->';
      }
     }
     print ( "\n<head>\n" );
     if ( $s_title ) {
      #
      # print the passed title
      #
      print ( "<title>$s_title</title>\n" );
     } else {
      #
      # use the default title 
      #
      printf ( "<title>%s</title>\n", $MP_stitle );
     }
     break;
    case "DE":
     break;
    case "ES":
     break;
   }
  } // end:mpBegin
  #
  # ================================================================
  #
  # Functionname : mpTags
  #
  # Description : metatags - used by some spiders to spider the site - not needed until 
  #				  web hosted
  # Parameters
  # ----------
  #
  # Name	Type	 Description
  # ----	----	 -----------
  # @ssite      string	 name of site ( URL ). Defined in Myplus_globals.inc.php
  # @ssitedesc  string	 text description of site
  # @tagsarray  stringar  text array of meta tags for search engines / spiders
  # @lang       string    default language
  #
  function mpTags( $ssite, $ssitedesc, $tagarray, $lang )
  {
   #
   # globals
   #
   if ( empty ($lang) )
     $lang=$_SESSION['mp_lang'];
   #
   switch( $lang )
   {
   case "EN":
?>
<!-- mpTags -->
<meta name="generator" content="MyPlus Core V1.5">
<?php
    printf ( "<meta name=\"Author\" content=\"%s\">\n", $ssite );
    printf ( "<meta name=\"description\" content=\"%s\">\n", $ssitedesc );
?>
<meta name="robots" content="nofollow, noindex">
<?php
    printf ( "<meta name=\"keywords\" content=\"%s\">\n", $tagarray );
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
    #
    # fades are deprecated
    #
    if (isset($_SESSION['mp_fades'])) {
     if ( $_SESSION[ 'mp_fades' ] == "Y" )
     {
      print ( "<meta http-equiv=\"Page-Exit\" content=\"revealTrans(Duration=1.5,Transition=33)\">\n" );
     }
    }
    #
?>
<meta name="language" content="english">
<?php
    break;
   case "DE":
    break;
   case "ES":
    break;
   }
  } // end: mpTags
  #
  # ================================================================
  #
  # Functionname :     mpCss
  #
  # Description :      define a style sheet(s) for site
  #
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  # @ssheet          string    filename of css sheet. See ../xhtml/Myplus_globals.inc.php
  #		               and ../styles/myplus_style.css - the default css file
  #
  function mpCss( $ssheet )
  {
  #
  # globals
  #
  global $MP_ssheet;
  #
   echo "<!-- mpCss -->\n";
   if ( !empty( $ssheet ) )
   {
    printf ( "<link href=\"%s\" rel=\"stylesheet\" type=\"text/css\"/>\n", $ssheet );
   } else {
    printf ( "<link href=\"%s\" rel=\"stylesheet\" type=\"text/css\"/>\n", $MP_ssheet );
   }
  } // end: mpCss
  #
  # ================================================================
  #
  # Functionname :     mpOverLIB
  #
  # Description :      add overLIB functionality & a fast javascript button swap.
  #		       		   This function assumes the directory ../buttons/ exists under
  #		       		   site root and uses this location to locate images.
  #
  # Copyright   :      overLIB functionality is the copyright material of Erik Bosrup.
  #                    rogue software, Nanoweb Ltd, its employees and agents make no claim on the ownership
  #                    or creation of the overLIB library.
  #
  #                    OverLIB is included in MyPlus Core under section Licence (Artistic) 2.5
  #                    of the overLIB licence and we will endeavour to display a link directing you,
  #                    the MyPlus user, to the overLIB site to download your own version.
  #                    This link is served via function mpFooter.
  #
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  # none
  #
  function mpOverLIB()
  {
  #
  echo "<!-- mpOverLIB -->\n";
  print ( "<script language=\"javascript\" src=\"../js/overlib.js\"><!--- overLIB (c) Erik Bosrup ---></script>\n" );
  #
  # button loader support for your main navigation bar
  #
  print ( "<script language=\"javaScript\" type=\"text/javascript\">\n" );
  print ( "<!-- Dummy comment to hide code from non-JavaScript browsers.\n" );
  print ( "if (document.images) {\n" );
  #
  print ( "nav_B1_off = new Image(); nav_B1_off.src = \"../buttons/nav/nav_B1.jpg\"\n" );
  print ( "nav_B1_over = new Image(); nav_B1_over.src = \"../buttons/nav/nav_B1_over.jpg\"\n" );
  print ( "nav_B2_off = new Image(); nav_B2_off.src = \"../buttons/nav/nav_B2.jpg\"\n" );
  print ( "nav_B2_over = new Image(); nav_B2_over.src = \"../buttons/nav/nav_B2_over.jpg\"\n" );
  print ( "nav_B3_off = new Image(); nav_B3_off.src = \"../buttons/nav/nav_B3.jpg\"\n" );
  print ( "nav_B3_over = new Image(); nav_B3_over.src = \"../buttons/nav/nav_B3_over.jpg\"\n" );
  print ( "nav_B4_off = new Image(); nav_B4_off.src = \"../buttons/nav/nav_B4.jpg\"\n" );
  print ( "nav_B4_over = new Image(); nav_B4_over.src = \"../buttons/nav/nav_B4_over.jpg\"\n" );
  print ( "nav_B5_off = new Image(); nav_B5_off.src = \"../buttons/nav/nav_B5.jpg\"\n" );
  print ( "nav_B5_over = new Image(); nav_B5_over.src = \"../buttons/nav/nav_B5_over.jpg\"\n" );
  print ( "nav_B6_off = new Image(); nav_B6_off.src = \"../buttons/nav/nav_B6.jpg\"\n" );
  print ( "nav_B6_over = new Image(); nav_B6_over.src = \"../buttons/nav/nav_B6_over.jpg\"\n" );
  print ( "nav_B7_off = new Image(); nav_B7_off.src = \"../buttons/nav/nav_B7.jpg\"\n" );
  print ( "nav_B7_over = new Image(); nav_B7_over.src = \"../buttons/nav/nav_B7_over.jpg\"\n" );
  print ( "nav_B8_off = new Image(); nav_B8_off.src = \"../buttons/nav/nav_B8.jpg\"\n" );
  print ( "nav_B8_over = new Image(); nav_B8_over.src = \"../buttons/nav/nav_B8_over.jpg\"\n" );
  print ( "nav_B9_off = new Image(); nav_B9_off.src = \"../buttons/nav/nav_B9.jpg\"\n" );
  print ( "nav_B9_over = new Image(); nav_B9_over.src = \"../buttons/nav/nav_B9_over.jpg\"\n" );
  print ( "nav_B10_off = new Image(); nav_B10_off.src = \"../buttons/nav/nav_B10.jpg\"\n" );
  print ( "nav_B10_over = new Image(); nav_B10_over.src = \"../buttons/nav/nav_B10_over.jpg\"\n" );
  #
  # you need the following 2 lines for using backbuttons on pages
  #
  print ( "go_back_B1_off = new Image(); go_back_B1_off.src = \"../../../buttons/go_back_B1.jpg\"\n" );
  print ( "go_back_B1_over = new Image(); go_back_B1_over.src = \"../../../buttons/go_back_B1_over.jpg\"\n" );
  print ( "}\n" );
  #
  # this javascript code snippet swaps the required image on mouseover & mouseout events
  #
  print ( "function turn_off(ImageName) {\n" );
  print ( "	 if (document.images != null) {\n" );
  print ( "		 document[ImageName].src = eval(ImageName + \"_off.src\");\n" );
  print ( "	 }\n" );
  print ( "}\n" );
  print ( "function turn_over(ImageName) {\n" );
  print ( "	 if (document.images != null) {\n" );
  print ( "		 document[ImageName].src = eval(ImageName + \"_over.src\");\n" );
  print ( "	 }\n" );
  print ( "}\n" );
  print ( "// -->\n" );
  print ( "</script>\n" );
  #
  } // end: mpOverlib
  #
  # ================================================================
  #
  # Functionname :     mpEndHead
  #
  # Description :      function to insert the end of the head block.
  #		               This routine is called seperately to terminate the <head> block since each page may
  #		               require variable code in its <head> .. </head> section.
  #
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  # none
  function mpEndHead()
  {
  #
  #
  # End <head> block
  #
   echo "<!-- mpEndHead -->\n";
   print ( "</head>\n" );
  #
  } // end: mpEndHead
  #
  # ================================================================
  #
  # Functionname :     mpPageBody
  #
  # Description :      start page body section, define page background colour, declare
  #                    overLIB necessary DIV container.
  #
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  # $ptype     string    value for required page background type.
  #                      Can be
  #                      a = commonAdmin - we override any layouts
  #                      c = common
  #                      f = forms input
  #                      d = database
  #                      y = we default to a multi column layout
  #
  # $pdef_col            hex value for the default page colour
  #
  function mpPageBody( $ptype, $pdef_col )
  {
  #
  # globals
  #
  global $MP_pdef_col;
  #
  if ( empty( $pdef_col ) )  {
   $pdef_col = $MP_pdef_col;
  }
  #
  if ( !empty( $ptype ) ) {
  switch ( $ptype )
  {
  case "a":
   # echo " -- page type ADMIN --";
?>
<!-- mpPageBody -->
<body class="commonAdmin">
<?php
   break;
  case "c":
   # echo " -- page type COMMON --";
?>
<!-- mpPageBody -->
<body class="common">
<?php
   break;
  case "f":
   # echo " -- page type FORMS --";
?>
<!-- mpPageBody -->
<body class="forms">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<?php
   break;
  case "d":
   # echo " -- page type DATABASE --";
?>
<!-- mpPageBody -->
<body class="database">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<?php
   break;
   case "y":
   # echo " -- page type Multi column - load the MCL layout --";
?>
<!-- mpPageBody -->
<body class="mcl">
<?php
   break;
  }
  } else {
   #
   # nothing passed - default to common body 
   #
?>
<!-- mpPageBody -->
<body>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<?php
  }
  } // end: mpPageBody
  #
  # ================================================================
  #
  # Functionname :     mpHeader
  #
  # Description :      function to insert a header line across a page
  #
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  # @header        string    text of header to insert
  #
  function mpHeader( $header )
  {
  if ( !empty( $header ) )
  {
?>
    <div class="center">
<?php
    printf ( "<h3 class=\"blue\">%s</h3>\n", $header );
?>
    </div>
    <br />
<?php
  }
  } // end: mpHeader
  #
  # ================================================================
  #
  # Functionname :     mpEscapeData
  #
  # Description :      escape the data for weird inputs
  #
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  # @data          string   data to escape
  #
  function mpEscapeData( $data )
  {
  #
   if ( ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
   }
   return ($data);
  #
  } // end: mpEscapeData
  #
  # ================================================================
  #
  # Functionname :     mpValidateEmail
  #
  # Description :      validate an email
  #
  # Name	Type      Description
  # ----	----      -----------
  #
  function mpValidateEmail( $qstr )
  {
   #
   # globals
   #
   global $link;
   global $mp_script;
   #
   $email = mpEscapeData( $_POST['email'] );
   #
   # check email address is not blank
   $email = trim ( $email );
   if ( empty ($email) )
    die ( "$mp_script - no email address specified - enter a valid e-mail address for client" );
   #
   # check email address looks real
   $amper = strpos ( $email, "@" );
   if ( $amper == 0 )
    die ( "$mp_script - invalidly formatted email address specified - please enter a valid e-mail address" );
   #
   $query_str = "SELECT client_id, first_name, surname, first_f from mp_client WHERE email = '$email'";
   $result = mysqli_query($link, $query_str);
   if (!$result) {
     $errors[] = "Query: " . $query . "";
     $errors[] = "Errno: " . mysqli_errno($link);
     $errors[] = "Error: " . mysqli_error($link);
     $errors[] = $mp_script ." error occurred reading client table";
  }
  #
  # if record exists, proceed
  $num = mysqli_num_rows($result);
  #
  if ( $num>0 )
  {
  $row = mysqli_fetch_row($result);
  $client_id = $row[0];
  $first_name = $row[2];
  $surname = $row[3];
  #
  $name_parts = array( $row[ 2 ], $row[ 3 ] );
  $full_name = implode( $name_parts, " " );
  #
  # record found, get confirm and continue
  #
  } else {
   $errors[] = 'Please check the entered email address - it is not registered!';
  }
   mysqli_free_result( $result );
   return $email;
  #
  } // end: mpValidateEmail
  #
  # ================================================================
  #
  # Functionname :     mpValidateMname
  #
  # Description :      validate client name
  #
  # Name	Type      Description
  # ----	----      -----------
  #
  function mpValidateMname()
  {
  #
  # globals
  #
  global $link;
  global $mp_script;
  #
  # get POST variables
  $mname = mpEscapeData( $_POST['surname'] );
  $mname = trim ( $surname );
  if ( empty ($surname) ) {
      $errors[] = $mp_script ."no surname specified - select a valid surname";
   }
  #
  # check for existance of this surname
  $query_str = "SELECT * FROM mp_client WHERE surname = '$surname'";
  $result = mysqli_query($link, $query_str);
   if (!$result) {
    print ( "Query: " . $query );
    print ( "<br />errno: " . mysqli_errno($link));
    print ( "<br />error: " . mysqli_error($link));
     die( "<br />$mp_script - error occurred reading clients on surname" );
   }
   while ($row = mysqli_fetch_array( $result ) )
   {
    $this_client =	$row[ "client_id" ];
   }
   #
   # if user exists, proceed
   $query_str = "SELECT * FROM mp_client WHERE client_id = $this_client";
   $result = mysqli_query($link, $query_str);
   if (!$result)
   {
    print ( "Query: " . $query );
    print ( "<br />errno: " . mysqli_errno($link));
    print ( "<br />error: " . mysqli_error($link));
     die( "<br />$mp_script - error occurred reading clients on cient id" );
   }
   while ($row = mysqli_fetch_array( $result ) )
   {
    $email = $row[ "email" ];
   }
   #
   $num = mysqli_num_rows($result);
   if ( $num>0 ) {
   #
   # record found, get confirm and continue
    printf ( "<form method=\"post\" action=\"%s\" name=\"form5\">\n", $_SERVER['SCRIPT_NAME'] );
    printf ( "<input type=\"hidden\" name=\"script_action\" value=\"%s\">\n", MYx_INITIAL_PAGE );
    printf ( "<input type=\"hidden\" name=\"email\" value=\"%s\">\n", $email );
    print ( "<div align=\"center\"><table border=\"0\" cols=\"2\" width=\"50%\">\n" );
    print ( "<tr>\n" );
    printf ( "<td colspan=\"2\"><div align=\"center\"><b><font size=\"1\"><p>%s located - click Continue to view details</p></font></b></div></td></tr>\n", $surname );
    print ( "<tr>\n" );
    print ( "<input type=\"hidden\" name=\"submitted\" value=\"TRUE\">\n" );
    print ( "<td align=\"center\" valign=\"middle\" colspan=\"2\"><div align=\"center\"><input type=\"submit\" value=\"Continue\"><div></td></tr></table></form></div>\n" );
   } else {
    $errors[] = 'Please check the entered client surname - it is not found!';
    mpFooter( $_SESSION['mp_lang'] );
   }
 # 
 } // end: mpValidateMname 
 #
 # ================================================================
 #
 # Functionname :     mpValidateSindex
 #
 # Description :      validate alpha index search on surname
 #
 # Name	Type      Description
 # ----	----      -----------
 #
 # validate passed alpha index for search on surname
 #
 function mpValidateSindex()
 {
  #
  # globals
  #
  global $link;
  global $mp_script;
  global $errors;
  #
  #  Number of records to show per page: from /xhtml/myplus_globals.inc.php
  $display = MAX_DISPLAY;
  #
  if (empty($_GET['skey'])) {
   $errors[] = $mp_script .'- No key selected';
   } else {
   $skey = mpEscapeData($_GET['skey']);
  }
  #  have we got a result
  #
  $Iquery = "SELECT COUNT(*) FROM mp_client where surname LIKE '%$skey%' ORDER BY surname ASC";
  $Iresult = mysqli_query($link, $Iquery);
  $Irow = mysqli_fetch_array($Iresult, MYSQL_NUM);
  if ( $Irow[0] >= 1 ) {
  #
  # Determine how many pages there are or we need to determine
  #
   if (isset($_GET['np'])) {
    $num_pages = $_GET['np'];
    #
    } else {
    #
    # Count the number of records
    $query = "SELECT COUNT(*) FROM mp_client where surname LIKE '%$skey%' ORDER BY surname ASC";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result, MYSQL_NUM);
    $num_records = $row[0];
    #
    # Calculate the number of pages
    if ( $num_records > $display ) { // More than 1 page.
     $num_pages = ceil( $num_records/$display );
     } else {
     $num_pages = 1;
     }
   #
   } // end: np if
  # Determine where in the database to start returning results
  if (isset($_GET['s'])) {
   $start = $_GET['s'];
   } else {
   $start = 0;
   }
  #
  # read matches on passed key
  $Gquery  = "SELECT * FROM mp_client WHERE surname LIKE '%$skey%' ORDER BY surname ASC LIMIT $start, $display";
  $Gresult = mysqli_query($link, $Gquery );
  if (!$Gresult)
  {
   $errors[] = "Query: " . $Gquery . "";
   $errors[] = "Errno: " . mysqli_errno($link);
   $errors[] = "Error: " . mysqli_error($link);
   $errors[] = $mp_script . "validate_sname - cannot read clients on surname index passed in UpdClient";
  }
  #
  idHeadN( $mp_script );
  #
  # divs
?>
 <!-- mpValidateSindex -->
 <div id="page_margins">
 <div id="page">
 <div id="nav">
 <a id="navigation" name="navigation"></a>
 <div class="hlist">
<?php
 printf ( "<ul><li><a href=\"%s\">back</a></li></ul></div></div>\n", $_SERVER['SCRIPT_NAME'] );
?>
 </div></div>
<?php
 #
 echo '<div class="centeri" style="width: 55%;">' ."\n"; 
 print ( "<table class=\"centeri\" border=\"1\" bordercolor=\"#000080\">\n" );
 print ( "<tr>\n" );
 mpDisplayHeadCell ( "td", "datop", "Client ID", 1 );
 mpDisplayHeadCell ( "td", "datop", "Status", 1 );
 mpDisplayHeadCell ( "td", "datop", "Client Name", 1 );
 mpDisplayHeadCell ( "td", "datop", "Primary Email", 1 );
 mpDisplayHeadCell ( "td", "datop", "Address", 1 );
 mpDisplayHeadCell ( "td", "datop", "Main Phone", 1 );
 mpDisplayHeadCell ( "td", "datop", "Mobile", 1 );
 $num_recs = 0;
 while ( $Grow = mysqli_fetch_array( $Gresult ) )
 {
  $num_recs++;
  print  ( "<tr>\n" );
  if( $Grow[ "gender" ] === "M" ) {
   $gimg = "he.png";
   $atxt = "Male";
  } else  {
   $gimg = "she.png";
   $atxt = "Female";
  } // end:if
  $disp_parts = array( $Grow[ "title" ], $Grow[ "first_name" ], $Grow[ "surname" ] );
  $disp_name = implode( $disp_parts, " " );
 #} // end: while
 #
 $mem_comments = $Grow[ "comments" ];
 $mem_status = $Grow[ "m_stat_id" ];
 #
 $addr_parts = array( $Grow[ "address_1" ], $Grow[ "address_2" ], $Grow[ "address_3"] );
 $addr_name = implode( $addr_parts, ", " );
 #
 # display for selection
 #
 printf ( "<th scope=\"row\" class=\"sub\">%d&nbsp;<img class=\"middle\" src=\"../../../buttons/%s\" width=\"32\" height=\"32\" border=\"0\" title=\"%s\">", $Grow[ "client_id" ], $gimg, $atxt );
 #
 # client contract status
 $mem_status = getMStat( $Grow[ "m_stat_id" ] );
 $scode =  $Grow[ "m_stat_id" ];
 $squery = "SELECT m_stat_img, m_stat_txt from mp_stat_type WHERE m_stat_id='$scode'";
 $sresult=mysqli_query($link, $squery);
 $srow=mysqli_fetch_array( $sresult );
 printf ( "<td><img class=\"middle\" src=\"../../../images/status/%d.png\" width=\"32\" height=\"32\" border=\"0\" title=\"%s\"></td>", $Grow[ "m_stat_id" ], $srow[ "m_stat_txt" ]  );
 mysqli_free_result( $sresult );
 #
 #  display as selectable client <a href=\"mailto:%s\" subject=\"PMS - Property Management\">&nbsp;%s&nbsp;</a>
 #
 $url = sprintf ( "%s?script_action=%s&client_id=%s", $_SERVER['SCRIPT_NAME'], MYx_INITIAL_PAGE, $Grow[ "client_id" ] );
 mpDisplayCell ( "td", "dacellsel", "<a href=\"$url\">" . $disp_name ."</a>", 0 );
 $eurl = sprintf ( "<a href=\"mailto:%s\" subject=\"PMS\">&nbsp;%s&nbsp;</a>", $Grow[ "email" ], $Grow[ "email" ] );
 mpDisplayCell ( "td", "dacell", $eurl, 0 );
 mpDisplayCell ( "td", "dacell", $addr_name, 1 );
 mpDisplayCell ( "td", "dacell", $Grow[ "office_phone" ], 1 );
 mpDisplayCell ( "td", "dacell", $Grow[ "mob_phone" ], 1 );
 #
 } // end: while
 #
 #  Make the links to other pages, if necessary
 #
 print ( "<tr><th colspan=\"7\" align=\"center\" valign=\"middle\" bgcolor=\"#eeeeee\">\n" );
 if ($num_pages > 1) {
  #  Determine what page the script is on.
  $current_page = ($start/$display) + 1;
  #
  # If it's not the first page, make a Previous button.
  if ($current_page != 1) {
   $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&skey=%s", $_SERVER['SCRIPT_NAME'], MYx_VALID_SINDEX, ($start - $display), $num_pages, $skey  );
   print ("<a href=\"$url\">&nbsp;Previous&nbsp;</a>\n" );
  } // end: previous button
  #
  #  Make all the numbered pages
  for ($i = 1; $i <= $num_pages; $i++) {
   if ($i != $current_page) {
    $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&skey=%s", $_SERVER['SCRIPT_NAME'], MYx_VALID_SINDEX, (($display * ($i - 1))), $num_pages, $skey );
    printf ( "<a href=\"$url\">&nbsp;%d&nbsp;</a>\n", $i  );
   } else {
    echo $i . ' ';
   } // end: current page
  } // end: for - num pages
  #
  # If it's not the last page, make a Next button
  if ($current_page != $num_pages) {
   $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&skey=%s", $_SERVER['SCRIPT_NAME'], MYx_VALID_SINDEX, ($start + $display), $num_pages, $skey );
   print ( "<a href=\"$url\">&nbsp;Next&nbsp;</a>\n" );
  } // end: next button
  #
 } // end:links to other pages section 
 #
 #
 #
 print ( "</th></tr>\n" ); // end text table
 #
 printf ( "<tr><th colspan=\"7\">%s clients displayed for surname beginning or containing %s</th></tr></table>\n", $num_recs, $skey );
 print ( "</div><br />\n" );
 # no matching records
 } else {
  #
  idHeadN( $mp_script );
  #
  # divs
?>
 <div id="page_margins">
 <div id="page">
 <div id="nav">
 <a id="navigation" name="navigation"></a>
 <div class="hlist">
<?php
 printf ( "<ul><li><a href=\"%s\">back</a></li></ul></div></div>\n", $_SERVER['SCRIPT_NAME'] );
?>
 </div></div>
 <table class="full">
 <thead><tr><th scope="col" colspan="7">No Matched clients</th></tr></thead>
 <tbody><tr>
 <th scope="col">Client ID</th>
 <th scope="col">Status</th>
 <th scope="col">Name</th>
 <th scope="col">Email</th>
 <th scope="col">Address</th>
 <th scope="col">Main Phone</th>
 <th scope="col">Mob Phone</th>
 </tr>
<?php
 printf ( "<tr><th colspan=\"7\">No clients for surname beginning or containing %s found</th></tr></table>\n",  $skey );
 print ( "</div><br />" );
  } // end: num pages loop
 #
 } // end: validateSindex 
 #
 # ================================================================
 #
 # Functionname :     mpValidateRindex
 #                      usage : diary
 #
 # Description :      validate alpha index search on all client residents for an estate
 #
 # Name	Type      Description
 # ----	----      -----------
 #
 # validate passed alpha index for search 
 #
 function mpValidateRindex($skey)
 {
  #
  # globals
  #
  global $link;
  global $mp_script;
  global $errors;
  global $infoMess;
  #
  #  Number of records to show per page: 
  $display = MAX_DISPLAY;
  #
  #  have we got a result
  #
  $Iquery = "SELECT COUNT(*) FROM mp_client WHERE g_id = '$skey' ORDER BY surname ASC";
  $Iresult = mysqli_query($link, $Iquery);
  $Irow = mysqli_fetch_array($Iresult, MYSQL_NUM);
  if ( $Irow[0] >= 1 ) {
  #
  # Determine how many pages there are or we need to determine
  #
   if (isset($_GET['np'])) {
    $num_pages = $_GET['np'];
    #
    } else {
    #
    # Count the number of records
    $query = "SELECT COUNT(*) FROM mp_client WHERE g_id = '$skey' ORDER BY surname ASC";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result, MYSQL_NUM);
    $num_records = $row[0];
    #
    # Calculate the number of pages
    if ( $num_records > $display ) { // More than 1 page.
     $num_pages = ceil( $num_records/$display );
     } else {
     $num_pages = 1;
     }
   #
   } // end: np if
  # Determine where in the database to start returning results
  if (isset($_GET['s'])) {
   $start = $_GET['s'];
   } else {
   $start = 0;
   }
  #
  # read estate / contract / type matches on passed key
  #
  $equery  = "SELECT a.*,b.c_contract_type_id,b.per_id,c.c_contract_type_desc FROM mp_org a ";
  $equery  .= "LEFT JOIN mp_contract b USING (g_id) ";
  $equery  .= "LEFT JOIN mp_contract_type c USING (c_contract_type_id) ";  
  $equery  .= "WHERE g_id = '$skey'";
  $eresult = mysqli_query($link, $equery );
  if (!$eresult)
  {
   $errors[] = "Query: " . $equery . "";
   $errors[] = "Errno: " . mysqli_errno($link);
   $errors[] = "Error: " . mysqli_error($link);
   $errors[] = $mp_script . " validate_rname - cannot read estate on estate index passed";
  }
  if ( $errors ) {
    mpErrors( $errors, $_SESSION['mp_lang'] );
    $errors = NULL;
  } // end: errors
  #
  # residents count
  #
  $cquery = "mp_client WHERE g_id = '$skey'";
  $num_res = getRows( $cquery );
  #
  # read client matches on passed key
  #
  $Gquery  = "SELECT * FROM mp_client WHERE g_id = '$skey' ORDER BY surname ASC LIMIT $start, $display";
  $Gresult = mysqli_query($link, $Gquery );
  if (!$Gresult)
  {
   $errors[] = "Query: " . $Gquery . "";
   $errors[] = "Errno: " . mysqli_errno($link);
   $errors[] = "Error: " . mysqli_error($link);
   $errors[] = $mp_script . "validate_rname - cannot read clients on estate index passed";
  }
  if ( $errors ) {
    mpErrors( $errors, $_SESSION['mp_lang'] );
    $errors = NULL;
  } // end: errors
  #
  $infoMess = "Client Diary - Select Client to add diary entry for";
  idHeadN( $mp_script );
  #
  # nav divs
  echo '<div id="page_margins">'."\n";
  echo '<div id="page">'."\n";
  echo '<div id="nav">'."\n";
  echo '<!-- skiplink anchor: navigation -->'."\n";
  echo '<a id="navigation" name="navigation"></a>'."\n";
  echo '<div class="hlist">'."\n";
  echo '<!-- main navigation: #nav_main -->'."\n";
  echo '<ul><li><a href="../../../code/mpAdmin.php">back</a></li></ul></div></div>'."\n";
  echo '</div></div>';
  #
  echo '<div class="page_margins">';
  echo '<div class="page_wid">';
 #
 # div class for display of estate and residents
 #
 echo '<div class="centeri" style="width: 95%;">' ."\n"; 
 #
 # show estate details
 #
 print ( "<table class=\"full\" border=\"1\" bordercolor=\"#000080\" width=\"95%\">\n" );
 print ( "<tr>\n" );
 mpDisplayHeadCell ( "td", "datop", "Estate Name", 1 );
 mpDisplayHeadCell ( "td", "datop", "Trading Name", 1 );
 mpDisplayHeadCell ( "td", "datop", "Organisation type", 1 );
 mpDisplayHeadCell ( "td", "datop", "Primary Contact", 1 );
 mpDisplayHeadCell ( "td", "datop", "Main Address", 1 );
 mpDisplayHeadCell ( "td", "datop", "No of Residents", 1 );
 mpDisplayHeadCell ( "td", "datop", "Service Contract", 1 );
 
 print ( "</tr><tr>\n" );
 while ( $erow = mysqli_fetch_array( $eresult ) )
 {
  mpDisplayCell ( "td", "dacell", $erow[ "g_name" ], 1 );
  mpDisplayCell ( "td", "dacell", $erow[ "g_trading_name" ], 1 );
  mpDisplayCell ( "td", "dacell", getOrgtypeDesc($erow[ "g_orgtype_id" ]), 1 );
  mpDisplayCell ( "td", "dacell", $erow[ "g_cont_name" ], 1 );
  mpDisplayCell ( "td", "dacell", $erow[ "g_address_1" ].", ".$erow[ "g_address_2" ].", ".$erow[ "g_city" ], 1 );
  mpDisplayCell ( "td", "dacent", $num_res, 1 );
  mpDisplayCell ( "td", "dacell", $erow[ "c_contract_type_desc" ], 1 );
  $g_name = $erow[ "g_name" ];
 }
 mysqli_free_result( $eresult );
 print ( "</tr></table><br />\n" );
 #
 # client details ( selectable )
 #
 print ( "<table class=\"full\" border=\"1\" bordercolor=\"#000080\" width=\"95%\">\n" );
 print ( "<tr>\n" );
 mpDisplayHeadCell ( "td", "datop", "Client ID", 1 );
 mpDisplayHeadCell ( "td", "datop", "Status", 1 );
 mpDisplayHeadCell ( "td", "datop", "Client Name", 1 );
 mpDisplayHeadCell ( "td", "datop", "Primary Email", 1 );
 mpDisplayHeadCell ( "td", "datop", "Address", 1 );
 mpDisplayHeadCell ( "td", "datop", "Main Phone", 1 );
 mpDisplayHeadCell ( "td", "datop", "Mobile", 1 );
 $num_recs = 0;
 while ( $Grow = mysqli_fetch_array( $Gresult ) )
 {
  $num_recs++;
  print  ( "<tr>\n" );
  if( $Grow[ "gender" ] === "M" ) {
   $gimg = "he.png";
   $atxt = "Male";
  } else  {
   $gimg = "she.png";
   $atxt = "Female";
  } // end:if
  $disp_parts = array( $Grow[ "title" ], $Grow[ "first_name" ], $Grow[ "surname" ] );
  $disp_name = implode( $disp_parts, " " );
 #} // end: while
 #
 $mem_comments = $Grow[ "comments" ];
 $mem_status = $Grow[ "m_stat_id" ];
 #
 $addr_parts = array( $Grow[ "address_1" ], $Grow[ "address_2" ], $Grow[ "address_3"] );
 $addr_name = implode( $addr_parts, ", " );
 #
 # display for selection
 #
 printf ( "<th scope=\"row\" class=\"sub\">%d&nbsp;<img class=\"middle\" src=\"../../../buttons/%s\" width=\"32\" height=\"32\" border=\"0\" title=\"%s\">", $Grow[ "client_id" ], $gimg, $atxt );
 #
 # client contract status
 $mem_status = getMStat( $Grow[ "m_stat_id" ] );
 $scode =  $Grow[ "m_stat_id" ];
 $squery = "SELECT m_stat_img, m_stat_txt from mp_stat_type WHERE m_stat_id='$scode'";
 $sresult=mysqli_query($link, $squery);
 $srow=mysqli_fetch_array( $sresult );
 printf ( "<td><img class=\"middle\" src=\"../../../images/status/%d.png\" width=\"32\" height=\"32\" border=\"0\" title=\"%s\"></td>", $Grow[ "m_stat_id" ], $srow[ "m_stat_txt" ]  );
 mysqli_free_result( $sresult );
 #
 #  display as selectable client
 #
 $url = sprintf ( "%s?script_action=%s&client_id=%s&g_id=%d", $_SERVER['SCRIPT_NAME'], MYx_GET_DIARY_DETAILS, $Grow[ "client_id" ], $skey );
 mpDisplayCell ( "td", "dacellsel", "<a href=\"$url\">" . $disp_name ."</a>", 0 );
 #
 $eurl = sprintf ( "<a href=\"mailto:%s\" subject=\"PMS\">&nbsp;%s&nbsp;</a>", $Grow[ "email" ], $Grow[ "email" ] );
 mpDisplayCell ( "td", "dacell", $eurl, 0 );
 mpDisplayCell ( "td", "dacell", $addr_name, 1 );
 mpDisplayCell ( "td", "dacell", $Grow[ "office_phone" ], 1 );
 mpDisplayCell ( "td", "dacell", $Grow[ "mob_phone" ], 1 );
 #
 } // end: while
 #
 #  Make the links to other pages, if necessary
 #
 print ( "<tr><th colspan=\"7\" align=\"center\" valign=\"middle\" bgcolor=\"#eeeeee\">\n" );
 if ($num_pages > 1) {
  #  Determine what page the script is on.
  $current_page = ($start/$display) + 1;
  #
  # If it's not the first page, make a Previous button.
  if ($current_page != 1) {
   $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&skey=%s", $_SERVER['SCRIPT_NAME'], MYx_GET_CLIENTS, ($start - $display), $num_pages, $skey  );
   print ("<a href=\"$url\">&nbsp;Previous&nbsp;</a>\n" );
  } // end: previous button
  #
  #  Make all the numbered pages
  for ($i = 1; $i <= $num_pages; $i++) {
   if ($i != $current_page) {
    $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&skey=%s", $_SERVER['SCRIPT_NAME'], MYx_GET_CLIENTS, (($display * ($i - 1))), $num_pages, $skey );
    printf ( "<a href=\"$url\">&nbsp;%d&nbsp;</a>\n", $i  );
   } else {
    echo $i . ' ';
   } // end: current page
  } // end: for - num pages
  #
  # If it's not the last page, make a Next button
  if ($current_page != $num_pages) {
   $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&skey=%s", $_SERVER['SCRIPT_NAME'], MYx_GET_CLIENTS, ($start + $display), $num_pages, $skey );
   print ( "<a href=\"$url\">&nbsp;Next&nbsp;</a>\n" );
  } // end: next button
  #
 } // end:links to other pages section 
 #
 #
 #
 print ( "</th></tr>\n" ); // end text table
 #
 printf ( "<tr><th colspan=\"7\">%s clients displayed for Estate : %s</th></tr></table>\n", $num_recs, $g_name );
 print ( "</div></div></div><br />\n" );
 # no matching records
 } else {
  #
  idHeadN( $mp_script );
  #
  # divs
?>
 <div id="page_margins">
 <div id="page_wid">
 <div id="nav">
 <a id="navigation" name="navigation"></a>
 <div class="hlist">
<?php
 printf ( "<ul><li><a href=\"%s\">back</a></li></ul></div></div>\n", $_SERVER['SCRIPT_NAME'] );
?>
 </div></div>
 <table class="full" width="95%">
 <thead><tr><th scope="col" colspan="7">No Matched clients</th></tr></thead>
 <tbody><tr>
 <th scope="col">Client ID</th>
 <th scope="col">Status</th>
 <th scope="col">Name</th>
 <th scope="col">Email</th>
 <th scope="col">Address</th>
 <th scope="col">Main Phone</th>
 <th scope="col">Mob Phone</th>
 </tr>
<?php
 printf ( "<tr><th colspan=\"7\">No clients for Estate : %s found</th></tr></table>\n",  $g_name );
 print ( "</div></div><br />" );
  } // end: num pages loop
 #
 } // end: validateRindex
 #
 # ================================================================
 #
 # Functionname :     mpValidateEindex
 #
 # Description :      validate alpha index search on Estate
 #
 # Name	Type      Description
 # ----	----      -----------
 #
 # validate passed alpha index for search on Estate
 #
 function mpValidateEindex()
 {
  #
  # globals
  #
  global $link;
  global $mp_script;
  global $errors;
  #
  #  Number of records to show per page: from /xhtml/myplus_globals.inc.php
  $display = MAX_DISPLAY;
  #
  if (empty($_GET['skey'])) {
   $errors[] = $mp_script .'- No key selected';
   } else {
   $skey = mpEscapeData($_GET['skey']);
  }
  #  have we got a result
  #
  $Iquery = "SELECT COUNT(*) FROM mp_org where g_name LIKE '%$skey%' AND g_id > 10001 ORDER BY g_name ASC";
  $Iresult = mysqli_query($link, $Iquery);
  $Irow = mysqli_fetch_array($Iresult, MYSQL_NUM);
  if ( $Irow[0] >= 1 ) {
  #
  # Determine how many pages there are or we need to determine
  #
   if (isset($_GET['np'])) {
    $num_pages = $_GET['np'];
    #
    } else {
    #
    # Count the number of records
    $query = "SELECT COUNT(*) FROM mp_org where g_name LIKE '%$skey%' AND g_id > 10001 ORDER BY g_name ASC";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result, MYSQL_NUM);
    $num_records = $row[0];
    #
    # Calculate the number of pages
    if ( $num_records > $display ) { // More than 1 page.
     $num_pages = ceil( $num_records/$display );
     } else {
     $num_pages = 1;
     }
   #
   } // end: np if
  # Determine where in the database to start returning results
  if (isset($_GET['s'])) {
   $start = $_GET['s'];
   } else {
   $start = 0;
   }
  #
  # read matches on passed key
  $Gquery  = "SELECT DISTINCT a.*,b.c_contract_type_id,c.c_contract_type_desc FROM mp_org a ";
  $Gquery .= "LEFT JOIN mp_contract b USING (g_id) ";
  $Gquery .= "LEFT JOIN mp_contract_type c USING (c_contract_type_id) ";
  $Gquery .= "WHERE g_name LIKE '%$skey%' AND g_id > 10001 ORDER BY g_name ASC LIMIT $start, $display";
  $Gresult = mysqli_query($link, $Gquery);
  if (!$Gresult)
  {
   $errors[] = "Query: " . $Gquery . "";
   $errors[] = "Errno: " . mysqli_errno($link);
   $errors[] = "Error: " . mysqli_error($link);
   $errors[] = $mp_script . "mpValidateEindex - cannot read Estates on Estate name index passed ";
  }
  #
  #
  idHeadN( $mp_script );
  #
  # divs
?>
 <!-- mpValidateEindex -->
 <div id="page_margins">
 <div id="page">
 <div id="nav">
 <a id="navigation" name="navigation"></a>
 <div class="hlist">
<?php
 printf ( "<ul><li><a href=\"%s\">back</a></li></ul></div></div>\n", $_SERVER['SCRIPT_NAME'] );
?>
 </div></div>
<?php
 #
 echo '<div class="center" style="width: 65%;">' ."\n"; 
 print ( "<table border=\"1\" bordercolor=\"#000080\">\n" );
 print ( "<tr>\n" );
 mpDisplayHeadCell ( "td", "datop", "Estate ID", 1 );
 mpDisplayHeadCell ( "td", "datop", "Estate Name", 1 ); 
 mpDisplayHeadCell ( "td", "datop", "Trading Name", 1 ); 
 mpDisplayHeadCell ( "td", "datop", "Contract Type", 1 ); 
 mpDisplayHeadCell ( "td", "datop", "Estate Address", 1 );
 mpDisplayHeadCell ( "td", "datop", "Residents", 1 );
 mpDisplayHeadCell ( "td", "datop", "Primary Contact", 1 );
 mpDisplayHeadCell ( "td", "datop", "Primary Email", 1 );
 mpDisplayHeadCell ( "td", "datop", "Main Phone", 1 );
 mpDisplayHeadCell ( "td", "datop", "Mobile", 1 );
 $num_recs = 0;
 while ( $Grow = mysqli_fetch_array($Gresult) )
 {
  $num_recs++;
  print  ( "<tr>\n" );
  $disp_parts = $Grow[ "g_cont_name" ];
 #
 # 
 $g_id = $Grow[ "g_id" ];
 $g_name = $Grow[ "g_name" ];
 $trading = $Grow[ "g_trading_name" ];
 $cont = $Grow[ "g_cont_name" ];
 $p_email = $Grow[ "g_email1" ];
 $p_phone = $Grow[ "g_phone1" ];
 $p_mob = $Grow[ "g_mob" ];
 $ctype = $Grow[ "c_contract_type_desc" ];
 # count residents for current estate
 $Cquery = "mp_client WHERE g_id = '$g_id'";
 $residents = getRows($Cquery);
 #
 #  address
 #
 $addr_parts = array( $Grow[ "g_address_1" ], $Grow[ "g_address_2" ], $Grow[ "g_address_3"] );
 $addr_name = implode( $addr_parts, ", " );
 #
 # display for selection
 #
 printf ( "<th scope=\"row\" class=\"sub\">%d&nbsp;", $Grow[ "g_id" ] );
 #
 #  display as selectable
 #
 $url = sprintf ( "%s?script_action=%s&g_id=%s", $_SERVER['SCRIPT_NAME'], MYx_INITIAL_PAGE, $g_id );
 mpDisplayCell ( "td", "dacell", "<a href=\"$url\">" . $g_name ."</a>", 0 );
 #
 mpDisplayCell ( "td", "dacell", $trading, 1 );
 mpDisplayCell ( "td", "dacell", $ctype, 1 );
 mpDisplayCell ( "td", "dacell", $addr_name, 1 );
 mpDisplayCell ( "td", "dacent", $residents, 1 );
 mpDisplayCell ( "td", "dacell", $disp_parts, 1 );
 $eurl = sprintf ( "<a href=\"mailto:%s\" subject=\"PMS\">&nbsp;%s&nbsp;</a>", $p_email, $p_email );
 mpDisplayCell ( "td", "dacell", $eurl, 0 );
 mpDisplayCell ( "td", "dacell", $p_phone, 1 );
 mpDisplayCell ( "td", "dacell", $p_mob, 1 );
 #
 } // end: while
 #
 #  Make the links to other pages, if necessary
 #
 print ( "<tr><th colspan=\"10\" align=\"center\" valign=\"middle\" bgcolor=\"#eeeeee\">\n" );
 if ($num_pages > 1) {
  #  Determine what page the script is on.
  $current_page = ($start/$display) + 1;
  #
  # If it's not the first page, make a Previous button.
  if ($current_page != 1) {
   $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&skey=%s", $_SERVER['SCRIPT_NAME'], MYx_VALID_SINDEX, ($start - $display), $num_pages, $skey  );
   print ("<a href=\"$url\">&nbsp;Previous&nbsp;</a>\n" );
  } // end: previous button
  #
  #  Make all the numbered pages
  for ($i = 1; $i <= $num_pages; $i++) {
   if ($i != $current_page) {
    $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&skey=%s", $_SERVER['SCRIPT_NAME'], MYx_VALID_SINDEX, (($display * ($i - 1))), $num_pages, $skey );
    printf ( "<a href=\"$url\">&nbsp;%d&nbsp;</a>\n", $i  );
   } else {
    echo $i . ' ';
   } // end: current page
  } // end: for - num pages
  #
  # If it's not the last page, make a Next button
  if ($current_page != $num_pages) {
   $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&skey=%s", $_SERVER['SCRIPT_NAME'], MYx_VALID_SINDEX, ($start + $display), $num_pages, $skey );
   print ( "<a href=\"$url\">&nbsp;Next&nbsp;</a>\n" );
  } // end: next button
  #
 } // end:links to other pages section 
 #
 #
 #
 print ( "</th></tr>\n" ); // end text table
 #
 printf ( "<tr><th colspan=\"10\">%s Estates displayed for Estate name beginning or containing %s</th></tr></table>\n", $num_recs, $skey );
 print ( "</div><br />\n" );
 # no matching records
 } else {
  #
  idHeadN( $mp_script );
  #
  # divs
?>
 <div id="page_margins">
 <div id="page">
 <div id="nav">
 <a id="navigation" name="navigation"></a>
 <div class="hlist">
<?php
 printf ( "<ul><li><a href=\"%s\">back</a></li></ul></div></div>\n", $_SERVER['SCRIPT_NAME'] );
?>
 </div></div>
 <table class="full">
 <thead><tr><th scope="col" colspan="7">No Matched Estates</th></tr></thead>
 <tbody><tr>
 <th scope="col">Estate ID</th>
 <th scope="col">Estate Name</th>
 <th scope="col">Primary Contact</th>
 <th scope="col">Primary Email</th>
 <th scope="col">Address</th>
 <th scope="col">Main Phone</th>
 <th scope="col">Mobile</th>
 </tr>
<?php
 printf ( "<tr><th colspan=\"7\">No Estates for Estate name beginning or containing %s found</th></tr></table>\n",  $skey );
 print ( "</div><br />" );
  } // end: num pages
 #
 } // end: validateEindex 
 #
 # ================================================================
 #
 # Functionname :     getBankAccount
 #
 # Description :      back accounts for selection
 #
 # Name		Type      Description
 # ----		----      -----------
 # @qKey	str		  estate key
 #
 # bank accounts for selection
 #
 function getBankAccount( $qKey )
 {
  #
  # globals
  #
  global $link;
  global $mp_script;
  global $infoMess;
  global $errors;
  #
  #  Number of records to show per page
  $display = MAX_DISPLAY;
  $g_id = $qKey;
  #  have we got a result
  #
  $Iquery = "SELECT a.b_id,a.b_branch,a.b_acc_no,a.b_sort,b.b_name FROM mp_estate_bank a ";
  $Iquery .= "LEFT JOIN mp_bank b USING (b_id) ";
  $Iquery .= "WHERE g_id = '$g_id'";
  $Iresult = mysqli_query($link, $Iquery); 
  if (!$Iresult) {
   $errors[] = "Query: " . $Iquery . "";
   $errors[] = "Errno: " . mysqli_errno($link);
   $errors[] = "Error: " . mysqli_error($link);
   $errors[] = $mp_script ." 001 getBankAccount. Cannot read Estate Accounts";
  }
  if ( $errors ) {                             
    mpErrors( $errors, $_SESSION['mp_lang'] );
    $errors = NULL;
  } // end: errors  
  $Iresult = mysqli_query($link, $Iquery);
  $Irow = mysqli_fetch_array($Iresult, MYSQL_NUM);
  if ( $Irow[0] >= 1 ) {
  #
  # Determine how many pages there are or we need to determine
  #
   if (isset($_GET['np'])) {
    $num_pages = $_GET['np'];
    #
    } else {
    #
    # Count the number of records
    $query = "SELECT COUNT(*) FROM mp_estate_bank where g_id > '$g_id' ORDER BY b_branch ASC";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result, MYSQL_NUM);
    $num_records = $row[0];
    #
    # Calculate the number of pages
    if ( $num_records > $display ) { // More than 1 page.
     $num_pages = ceil( $num_records/$display );
     } else {
     $num_pages = 1;
     }
   #
   } // end: np if
  # Determine where in the database to start returning results
  if (isset($_GET['s'])) {
   $start = $_GET['s'];
   } else {
   $start = 0;
   }
  #
  # read matches on passed key
  $Gquery = "SELECT a.b_id,a.g_id,a.b_branch,a.b_acc_no,a.b_sort,b.b_name,c.g_name FROM mp_estate_bank a ";
  $Gquery .= "LEFT JOIN mp_bank b USING (b_id) ";
  $Gquery .= "LEFT JOIN mp_org c USING (g_id) ";
  $Gquery .= "WHERE g_id = '$g_id' ORDER BY b_branch ASC";
  $Gresult = mysqli_query($link, $Gquery);
  if (!$Gresult)
  {
   $errors[] = "Query: " . $Gquery . "";
   $errors[] = "Errno: " . mysqli_errno($link);
   $errors[] = "Error: " . mysqli_error($link);
   $errors[] = $mp_script . "getBankAccount - cannot read Estate banks on Estate name index passed ";
  }
  #
  if(empty($infoMess) ) {
   $infoMess = "Take payments - click on Branch to Select";
  }
  idHeadN( $mp_script );
  #
  # divs
?>
 <!-- getBankAccount -->
 <div id="page_margins_wid">
 <div id="page_wid">
 <div id="nav">
 <a id="navigation" name="navigation"></a>
 <div class="hlist">
<?php
 printf ( "<ul><li><a href=\"%s\">back</a></li></ul></div></div>\n", $_SERVER['SCRIPT_NAME'] );
?>
 </div></div>
<?php
 #
 echo '<div class="centeri" style="width: 95%;">' ."\n"; 
 print ( "<table class=\"full\" border=\"1\" bordercolor=\"#000080\" width=\"95%\">\n" );
 print ( "<tr>\n" );
 mpDisplayHeadCell ( "td", "datop", "Bank Name", 1 );
 mpDisplayHeadCell ( "td", "datop", "Branch", 1 ); 
 mpDisplayHeadCell ( "td", "datop", "Account no", 1 ); 
 mpDisplayHeadCell ( "td", "datop", "Sort Code", 1 ); 
 mpDisplayHeadCell ( "td", "datop", "Balance", 1 );
 $num_recs = 0;
 while ( $Grow = mysqli_fetch_array( $Gresult ) )
 {
  $num_recs++;
  print  ( "<tr>\n" );
 #
 # 
 $b_name = $Grow["b_name"];
 $g_name = $Grow["g_name"];
 $g_id = $Grow[ "g_id" ];
 $b_id = $Grow[ "b_id" ];
 $b_branch = $Grow[ "b_branch" ];
 $acc_no = $Grow[ "b_acc_no" ];
 $sort = $Grow[ "b_sort" ];
 $b_balance = getCurr($_SESSION["curr"]) ." 0";
 #
 # display for selection
 #
 printf ( "<th scope=\"row\" class=\"sub\">%s&nbsp;", $b_name );
 #
 #  display as selectable
 #
 $url = sprintf ( "%s?script_action=%s&g_id=%d&b_id=%d", $_SERVER['SCRIPT_NAME'], MYx_GET_DETAILS, $g_id, $b_id );
 mpDisplayCell ( "td", "dacellsel", "<a href=\"$url\">" . $b_branch ."</a>", 0 );
 #
 mpDisplayCell ( "td", "dacell", $acc_no, 1 );
 mpDisplayCell ( "td", "dacell", $sort, 1 );
 mpDisplayCell ( "td", "dacell", $b_balance, 0 );
 #
 } // end: while
 #
 #  Make the links to other pages, if necessary
 #
 print ( "<tr><th colspan=\"5\" align=\"center\" valign=\"middle\" bgcolor=\"#eeeeee\">\n" );
 if ($num_pages > 1) {
  #  Determine what page the script is on.
  $current_page = ($start/$display) + 1;
  #
  # If it's not the first page, make a Previous button.
  if ($current_page != 1) {
   $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&g_id=%s", $_SERVER['SCRIPT_NAME'], MYx_GET_BANK($start - $display), $num_pages, $g_id  );
   print ("<a href=\"$url\">&nbsp;Previous&nbsp;</a>\n" );
  } // end: previous button
  #
  #  Make all the numbered pages
  for ($i = 1; $i <= $num_pages; $i++) {
   if ($i != $current_page) {
    $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&g_id=%s", $_SERVER['SCRIPT_NAME'], MYx_GET_BANK, (($display * ($i - 1))), $num_pages, $g_id );
    printf ( "<a href=\"$url\">&nbsp;%d&nbsp;</a>\n", $i  );
   } else {
    echo $i . ' ';
   } // end: current page
  } // end: for - num pages
  #
  # If it's not the last page, make a Next button
  if ($current_page != $num_pages) {
   $url = sprintf ( "%s?script_action=%s&s=%s&np=%d&g_id=%s", $_SERVER['SCRIPT_NAME'], MYx_GET_BANK ($start + $display), $num_pages, $g_id );
   print ( "<a href=\"$url\">&nbsp;Next&nbsp;</a>\n" );
  } // end: next button
  #
 } // end:links to other pages section 
 #
 #
 #
 print ( "</th></tr>\n" ); // end text table
 #
 printf ( "<tr><th colspan=\"10\">%s Bank Accounts displayed for Estate : %s</th></tr></table>\n", $num_recs, $g_name );
 print ( "</div><br />\n" );
 # no matching records
 } else {
  #
  idHeadN( $mp_script );
  #
  # divs
?>
 <div id="page_margins">
 <div id="page">
 <div id="nav">
 <a id="navigation" name="navigation"></a>
 <div class="hlist">
<?php
 printf ( "<ul><li><a href=\"%s\">back</a></li></ul></div></div>\n", $_SERVER['SCRIPT_NAME'] );
?>
 </div></div>
 <table class="full">
 <thead><tr><th scope="col" colspan="5">No Matched Bank Accounts</th></tr></thead>
 <tbody><tr>
 <th scope="col">Bank Name</th>
 <th scope="col">Branch</th>
 <th scope="col">Account No</th>
 <th scope="col">Sort Code</th>
 <th scope="col">Balance</th>
 </tr>
<?php
 printf ( "<tr><th colspan=\"5\">No Bank accounts for Estate : %s found</th></tr></table>\n",  $g_name );
 print ( "</div><br />" );
  } // end: num pag
 #
 } // end: getBankAccount
 #
 # ===================================
 # Functionname :     mpContractSubPart
 #
 # Description :      select the contract sub part for update
 #
 # Name	Type      Description
 # ----	----      -----------
 #
 # 
 #
 function mpContractSubPart()
 {
  if( $_SESSION[ "mp_debug" ] == "Y" ) {
   echo 'DEBUG:In mpContractSubPart';
  }
 } //end: mpContractSubPart
 #
 # ================================================================
 # 
  #
  # Functionname :     mpFooter
  #
  # Description :      footer for end of php pages with links
  #
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  # @lang       string       language
  #
  function mpFooter( $lang )
  {
  #
  # globals
  #
  if ( empty ($lang) )
    $lang=$_SESSION['mp_lang'];
  #
  switch( $lang )
  {
  case "EN":
  ?>
  <!-- mpFooter -->
   <!-- div id="centered">Layout based on <a target="_new" href="www.yaml.de">YAML</a>&nbsp;&ndash;&nbsp;Popups based on OverLib&nbsp;&ndash;&nbsp;Graphs based on Bar_Graph</div -->
   <div id="centered"><br /><b>escapefromuk&nbsp;&copy;&nbsp;2016</b><br /></div>
   </body></html>
<?php
    break;
   }
  } // end : mpFooter
  #
  # ================================================================
  #
  #
  # Functionname :     mpFooterNL
  #
  # Description :      footer for end of php pages ( with no links )
  #                    mainly for terms and conditions pages with close window
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  # @ssite      string       site name or description to be displayed in footers
  # @lang       string       language
  #
  function mpFooterNL( $lang )
  {
  #
  # globals
  #
  if ( empty ($lang) )
    $lang=$_SESSION['mp_lang'];
  #
  switch( $lang )
  {
  case "EN":
?>

<!-- mpFooterNL -->
<div id="idCommit">
<p id="idFoot"><b> escapefromuk&nbsp;&copy;&nbsp;2016&nbsp;</b></p>
<p id="idFoot"><nobr>[<a href="javascript:window.close();">close this window</a>]</nobr></p>
</div>
</body></html>

<?php
    break;
  case "DE":
    break;
  case "ES":
    break;
	}
  #
  } // end: mpFooterNL
  #
  # ================================================================
  #
  # Functionname :     mpFoot
  #
  # Description :      footer ( with no links )
  #
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  #
  function mpFoot()
  {
  #
?> 

<!-- mpFoot -->
<div id="centered">escapefromuk&nbsp;&copy;&nbsp;2016</div>
</body></html>

<?php
 #
 } // end: mpFoot
 #
 # ================================================================
 #
 # Functionname :     mpAlphaIndex
 #
 # Description :      displays a search bar with all alpha chars on a page
 #		      		  Allows use to select an alphabetic index that is used to search
 #		      		  the underlying database
 #
 # Parameters
 # ----------
 #
 # Name	   Type      Description
 # ----	   ----      -----------
 #
 # @category      string    search category. Ie, surname, name
 # @code          string    extra data - usually an index key to a table
 # @action        string    loop control action constant
 #
 function mpAlphaIndex( $category, $code, $action )
 {
 #
 # globals
 #
 global $mp_script;
 # 
 if ( empty ($lang) )
  $lang=$_SESSION['mp_lang'];
 #
  switch( $lang )
  {
  case "EN":
   #
   echo "<!-- mpAlphaIndex -->\n";
   printf ( "<p><br />or choose the first letter of %s<br /></p>\n", $category );
   print ( "<p>" );
   #
   # iterative display for all alphas  ( asc 65 to asc 90 )
   #
   $alpha_idx = 64;
   $last_idx = 90;
   while ( $alpha_idx <> $last_idx )
   {
    $alpha_idx == $alpha_idx++;
    $str = chr( $alpha_idx );
    #
    # routine calls itself iterativly with an ACTION and a KEY value
    #
    printf ( "<a href=\"%s?script_action=%d&skey=%s\">&nbsp;%s&nbsp;</a>", $_SERVER['SCRIPT_NAME'], $action, $str, $str );
   } // end while
   print ( "</p>\n" );
   break;
  default:
        die ( "$mp_script - unknown action code in mpAlphaIndex" );       
  } // end switch
 #
 } // end: mpAlphaIndex
 #
 # ---------------------------------------------
 #
 # Section 3 - functions for page formatting, date formats, cells
 #
 # ---------------------------------------------
 #
 # Functionname :  mpDivider
 #		   		   display a dividing line across a page or table cell
 #
 #		  		   look at the css file for <hr> classes
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 # $alg	     string    alignment. Right, Center or Left
 #
 function mpDivider( $alg )
 {
 printf ( "<div class=\"divider\">\n", $alg );
 print ( "<hr class=\"grey\" noshade=\"noshade\"></div>\n" );  
 } // end: mpDivider
 #
 # ================================================================
 #
 ###################################################
 # Following group of functions display data in cells
 ###################################################
 #
 # Functionname :  mpDisplayHeadCell
 #		         displays a heading inside a table cell layout
 #
 # Parameters
 # ----------
 #
 # Name	   Type      Description
 # ----	   ----      -----------
 # @tag	   string    html element tag ( )
 # @class    string    css class ID for element
 # @value    string    value to display
 # @encode   int       flag for special character treatment
 #
 function mpDisplayHeadCell( $tag, $class, $value, $encode )
 {
   #
   # Headings
   # tableless constructor - set headers text font, colour, background colour </$tag>
   #
   if ( $encode )
    $value = htmlspecialchars( $value );
   #
   if ( empty($value) )
    $value = "&nbsp;";
   #
    print ( "<$tag class=\"$class\" style=\"white-space: nowrap;\">&nbsp;$value&nbsp;</$tag>" );
   #
 } // end: mpDisplayHeadCell
 #
 # ================================================================
 #
 # Functionname :  mpDisplayHeadCellC
 #		         displays a heading inside a tableless layout
 #
 # Parameters
 # ----------
 #
 # Name     Type      Description
 # ----     ----      -----------
 # @value   string    value to display
 # @encode  int       flag for special character treatment
 #
 function mpDisplayHeadCellC(  $value, $encode )
 {
  #
  # Headings
  # tableless constructor - set headers text font, colour, background colour done in css
  #
  if ( $encode )
     $value = htmlspecialchars( $value );
  #
  if ( empty($value) )
    $value = "&nbsp;";
  #
  print ( "&nbsp;$value&nbsp;" );
 #
 } // end: mpDisplayHeadCellC
 #
 # ================================================================
 #
 # Functionname :  mpDisplayCell
 #		            displays a column value inside page layout
 #
 # Parameters
 # ----------
 #
 # Name     Type      Description
 # ----     ----      -----------
 # @tag     string    css element tag
 # @class   string    css class identifier
 # @value   string    value to display
 # @encode  int       flag for special character treatment
 #
 function mpDisplayCell( $tag, $class, $value, $encode )
 {
   #
   # data cell
   # tableless constructor - set cell text font, colour, background colour
   #
   if ( $encode )
    $value = htmlspecialchars( $value );
   #
   if ( empty($value) )
     $value = "&nbsp;";
   printf ( "<$tag class=\"%s\">$value</$tag>", $class );
 #
 } // end: mpDisplayCell
 #
 # ================================================================
 #
 # Functionname :  mpDisplayEmail
 #		            displays a selectable email inside table layout
 #
 # Parameters
 # ----------
 #
 # Name     Type      Description
 # ----     ----      -----------
 # @tag     string    css element tag
 # @class   string    css class identifier
 # @value   string    email value to display
 # @encode  int       flag for special character treatment
 #
 function mpDisplayEmail( $tag, $class, $value, $encode )
 {
   #
   # data cell
   # tableless constructor - set cell text font, colour, background colour
   #
   if ( $encode )
    $value = htmlspecialchars( $value );
   #
   if ( empty($value) )
     $value = "&nbsp;";
   $str =  "<a target=\"new\" href=\"mailto:%s\" subject=\"PMS - Property Management\">&nbsp;%value&nbsp;</a>";     
   printf ( "<$tag class=\"%s\">$str</$tag>", $class );
   #
 } // end: mpDisplayEmail
 #   
 #
 # ================================================================
 #
    # Functionname :  mpDisplayCellNW
    #		          displays a column value heading inside a table cell  ( nowrap variant )
    #
    # Parameters
    # ----------
    #
    # Name    Type      Description
    # ----    ----      -----------
    # $tag    string    html table element tag ( usually td )
    # $class  string    css class identifier
    # $value  string    value to display
    # $encode int       flag for special character treatment
    #
    function mpDisplayCellNW( $tag, $class, $value, $encode )
    {
     #
     # Table cell. Normal display
     # table constructor - set cell text font, colour, background colour
     #
     if ( $encode )
      $value = htmlspecialchars( $value );
      #
      if ( $value == "")
	    $value = "&nbsp;";
      printf ( "<$tag class=\"%s\">&nbsp;$value&nbsp;</$tag>\n", $class );
      #
    } // end: mpDisplayCellNW
 #
 # ================================================================
 #
    #Functionname :  mpDisplayPcell
    #		  		 displays a numeric accountancy value inside a table cell
    #		  		 Used to display monetery values
    #
    # Parameters
    # ----------
    #
    # Name	     Type      Description
    # ----	     ----      -----------
    # $tag	     string    html table element tag ( usually td )
    # $class     string    css class identifier
    # $value     string    value to display
    # $encode    int       flag for special character treatment
    #
    function mpDisplayPcell( $tag, $class, $value, $encode )
    {
     #
     # Table cell. Display numeric with pound sign
     # table constructor - set cell text font, colour, background colour
     #
     if ( $encode )
      $value = htmlspecialchars( $value );
      #
      if ( $value == "")
	   $value = "&nbsp;";
      #
      printf ( "<$tag class=\"%s\">&nbsp;&pound;&nbsp;$value&nbsp;</$tag>\n", $class );
    #
    } // end: mpDisplayPcell
 #
 # ================================================================
 #
    # Functionname :  mpDisplayCellRCL
    #		  		  displays a column value Right, Centre or Left inside a table cell
    #
    # Parameters
    # ----------
    #
    # Name     Type      Description
    # ----     ----      -----------
    # $RCL     string    alignment ( right, left or center )
    # $tag     string    html table element tag ( usually td )
    # $class   string    css class id
    # $value   string    value to display
    # $encode  int       flag for special character treatment
    #
    function mpDisplayCellRCL( $RCL, $tag, $class, $value, $encode )
    {
	#
	# Table cell. Right, Centered,Left
	# table constructor for text - set cell text font, colour, background colour
	#
	if ( $encode )
	 $value = htmlspecialchars( $value );
	#
	if ( $value == "")
	 $value = "&nbsp;";
	 #
	printf ( "<$tag class=\"%s\" text-align=\"$RCL\">&nbsp;$value&nbsp;</$tag>\n", $class );
	#
    } // end: mpDisplayCellRCL
 #
 # ================================================================
 #
    # Functionname :  mpDisplayCellURL
    #		  		  displays a selectable URL inside a table cell
    #
    # Parameters
    # ----------
    #
    # Name    Type      Description
    # ----    ----      -----------
    # $tag    string    html table element tag ( usually td )
    # $class  string    css class identifier
    # $value  string    URL value
    # $link   string    link text to display
    # $encode int       flag for special character treatment
    #
    function mpDisplayCellURL( $tag, $class, $value, $link, $encode )
    {
		#
		# Table cell.  Display selectable link
		# table constructor - set cell text font, colour, background colour
		#
		if ( $encode )
			$value = htmlspecialchars( $value );
		#
		if ( $value == "")
			$value = "&nbsp;";
		printf ( "<$tag class=\"%s\" \"white-space: nowrap;\"><a href=\"$value\">$link</a></$tag>\n", $class );
	#
    } // end: mpDisplayCellURL
 #
 # ================================================================
 #
    # Functionname :  mpDisplayCellImageURL
    #		      	  displays a selectable URL inside a table cell with image
    #
    # Parameters
    # ----------
    #
    # Name	     Type      Description
    # ----	     ----      -----------
    # $tag	     string    html table element tag ( usually td )
    # $value     string    URL value
    # $encode    int       flag for special character treatment
    # $img_N     string    image name to display
    # $img_H     int       image height in pixels
    # $img_W     int       image width in pixels
    # $img_alt   string    alt text
    #
    function mpDisplayCellImageURL( $tag, $value, $encode, $img_N, $img_H, $img_W, $img_alt )
    {
	#
	# Table cell.  Display selectable link
	# table constructor - set cell text font, colour, background colour
	#
	if ( $encode )
    {
	 $value = htmlspecialchars( $value );
	 $img_alt = htmlspecialchars( $img_alt );
    }
    # 
	if ( $value == "")
			$value = "&nbsp;";
	if ( $img_alt == "")
			$img_alt = "&nbsp;";
	printf ( "<$tag class=\"dispC\" \"white-space: nowrap;\"><a href=\"$value\"><image src=\"../images/%s\" height=\"%d\" width=\"%d\" border=\"0\" alt=\"$img_alt\"></a></$tag>\n", $img_N, $img_H, $img_W );
  #
  } // end: mpDisplayCellImageURL
  #    
    #
    # ==================================
    #
    # Functionname :  mpDisplayThumb
    #		      displays a thumbnail mugshot in a table cell
    #
    # Parameters
    # ----------
    #
    # Name	     Type      Description
    # ----	     ----      -----------
    # $tag	     string    html table element tag ( usually td )
    # $img_N     string    image name to display
    # $encode    int       flag for special character treatment
    #
    function mpDisplayThumb( $tag, $img_N, $encode )
    {
		#
		# Table cell.  Display selectable link
		# table constructor - set cell text font, colour, background colour
		#
		if ( $encode )
		   {
			$value = htmlspecialchars( $value );
			$img_alt = htmlspecialchars( $img_alt );
		   }
		#
		if ( $value == "")
			$value = "&nbsp;";
		if ( $img_alt == "")
			$img_alt = "&nbsp;";
		  printf ( "<$tag class=\"dispC\" \"white-space: nowrap;\"><image src=\"%s\" height=\"92\" width=\"100\" border=\"0\"></$tag>\n", $img_N );
    } // end: mpDisplayThumb
 #
 # ================================================================
 #
    # Functionname :  mpRandomImage
    #
    #  Display a random selected image from a directory full of image files
    #  Selected image is displayed in a table cell - so call this routine from inside the table def
    #
    #  Store all the images that you want to display in the same folder.
    #  DO NOT store anything you do not want to display such as files.
    #  This script reads the entire folder you specify and displays the contents at random
    #  on each page load.
    #
    # Parameters
    # ----------
    #
    # Name	     Type      Description
    # ----	     ----      -----------
    # $Iw        decimal   image width
    # $Ih        decimal   image height
    # $Id        decimal   image subdir name
    #
    function mpRandomImage( $Iw, $Ih, $Id )
    {
    #
     $IPath = "../";
     $IFolder = "images/" .$Id;
     srand((double) microtime() * 10000000);
     $imgdirpath = opendir("$IPath/$IFolder");
     while (false !== ($imgfile = readdir($imgdirpath))) {
      if ($imgfile != "." && $imgfile != "..") {
       $imgpath = "$IPath/$IFolder/$imgfile";
       $imageimg[$imgfile] = basename($imgpath);
      }
     }
     closedir($imgdirpath);
     shuffle($imageimg);
     list( ,$img_value) = each($imageimg);
     printf ( "<a href=\"javascript:void(0);\" onmouseover=\"return overlib('Popups by overLIB.');\" onmouseout=\"return nd();\"><img src=\"../$IFolder/$img_value\" width=\"%d\" height=\"%d\" alt=\"\"></a>\n", $Iw, $Ih );
    } // end: mpRandomImage
 #
 # ================================================================
 #
    # <a href=\"javascript:void(0);\" onmouseover=\"return overlib('Test popup.');\" onmouseout=\"return nd();\">here</a>
    # Functionname :  mpFormatAddressCell
    #		  		  displays an addressline
    #
    # Parameters
    # ----------
    # 
    # Name	     Type      Description
    # ----	     ----      -----------
    # $text      string    text to be displayed
    # $name      string    formfield name to hold input value
    # $value     string    address value to display
    # $encode    int       flag for special character treatment
    #
    function mpFormatAddressCell ( $text, $name, $value, $encode )
    {
		#
		# cell constructor	- set cell display characteristics
		#
		if ( $encode )
			$value = htmlspecialchars( $value );
		#
		if ( $value == "")
		       $value = "&nbsp;";
		printf ( "<p><span class=\"dtext\">$text:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"20\" name=\"$name\" value=\"%s\"></span></p>\n", $value );
    } //end: mpFormatAddressCell
 #
 # ================================================================
 #
    # Functionname :  mpFormatBaddressCell
    #		  		  displays an addressline inside a table cell with blank heading
    #
    # Parameters
    # ----------
    #
    # Name	     Type      Description
    # ----	     ----      -----------
    # $name      string    formfield name to hold input value
    # $value     string    address value to display
    # $encode    int       flag for special character treatment
    #
    function mpFormatBaddressCell ( $name, $value, $encode )
    {
		#
		# cell constructor	- set cell display characteristics
		#
		if ( $encode )
		       $value = htmlspecialchars( $value );
		#
		if ( $value == "")
			$value = "&nbsp;";
		printf ( "<p><span class=\"dtext\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"20\" name=\"$name\" onchange=\"disableSameFlag()\" value=\"%s\"></span></p>\n", $value );
    } // end: mpFormatBaddressCell
 #
 # ================================================================
 #
 # Functionname :  mpFormatStateCell
 #		  		  displays a state or county name inside a table cell
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 # $text      string    text to be displayed
 # $name      string    formfield name to hold input value
 # $value     string    value to display
 # $encode    int       flag for special character treatment
 #
function mpFormatStateCell ( $text, $name, $value, $encode )
{
#
# cell constructor	- set cell display characteristics
#
if ( $encode )
	$value = htmlspecialchars( $value );
	#
	if ( $value == "")
		$value = "&nbsp;";
	printf ( "<p><span class=\"dtext\">$text:&nbsp;<input type=\"text\" size=\"20\" name=\"$name\" value=\"%s\"></span></p>\n", $value );
} // end: mpFormatStateCell
#
# ================================================================
#
# Functionname :  mpFormatDateDisp
#		  		  displays a selectable date field inside a table cell
#
# Parameters
# ----------
#
# Name	     Type      Description
# ----	     ----      -----------
# $class     string    css class name
# $name      string    formfield name to hold input value
# $encode    int       flag for special character treatment
# $value     string    existing ( db) value
#
function mpFormatDateDisp ( $class, $name, $encode, $value )
{
#
# cell constructor	- set cell display characteristics
#
if ( $encode )
	$value = htmlspecialchars( $value );
#
if ( $value == "")
	$value = "&nbsp;";
        #
	if ( $value == "Y" ) {
	 print ( "<span class=\"$class\"><input type=\"checkbox\" size=\"1\" name=\"$name\" value=\"$value\" checked>$value</span>\n" );
        } else {
         print ( "<span class=\"$class\"><input type=\"checkbox\" size=\"1\" name=\"$name\" value=\"$value\">$value</span>\n" );
        }
 #
 }
 # ===============================
 # Functionname :     mpDatePicker
 #
 # Description :      js date input selector
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 #
 function mpDatePicker( )
 {
     echo '<script language="javascript" type="text/javascript" src="../../../js/datetimepicker.js"></script>'."\n";
 } // end: mpDatePicker
 #
 # ================================================================
 #
 # Functionname :     mpPMS
 #
 # Description :      ancilliary js module
 #
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 #
 function mpPMS( )
 {
     echo '<script language="javascript" type="text/javascript" src="../../../js/pms.js"></script>'."\n";
 } // end: mpPMS
 #
 ###################################################
 # Following group of functions manipulate dates
 ###################################################mpUpdateNextDueDate
 # Functionname :     mpDateMath
 #
 # Description :      date additions, subtractions
 #
 #
 # Parameters
 # ----------
 #
 # Name	     Type          Description
 # ----	     ----          -----------
 # @string    sourcedate   date string yyyy-mm-dd
 # @string    doMath       action [+/-][Qty][Unit]
 #                         example '+6m' = Add 6 Months
 #                         example '-180d' = Subtract 180 Days
 #                         example '+1Y' = Add 1 Year
 # @string    retFmt       format
 #                         formatting uses standard date() formats
 #
 function mpDateMath( $sourceDate, $doMath, $retFmt )
 {
    $sourceDate = strtotime($sourceDate);
    if( !$sourceDate ) {
     return(0);
    }
    if( $retFmt == '' ) {
     $retFmt = 'm/d/Y';
    }
    $m = date('m',$sourceDate);
    $d = date('d',$sourceDate);
    $Y = date('Y',$sourceDate);
    $mathFunc = substr($doMath,0,1);
    $Unit = substr($doMath,-1,1);
    $Qty = substr($doMath, 1,strlen($doMath)-2);
    if( $mathFunc == '-' ) {
        switch( $Unit ) {
            case "m":
                $newday = mktime(0,0,0,($m-$Qty),$d,$Y);
            break;
            case "d":
                $newday = mktime(0,0,0,$m,($d-$Qty),$Y);
            break;
            case "Y":
                $newday = mktime(0,0,0,$m,$d,($Y-$Qty));
            break;
        }
    } elseif( $mathFunc == '+' ) {
        switch( $Unit ) {
           case "m":
                $newday = mktime(0,0,0,($m+$Qty),$d,$Y);
            break;
            case "d":
                $newday = mktime(0,0,0,$m,($d+$Qty),$Y);
            break;
            case "Y":
               $newday = mktime(0,0,0,$m,$d,($Y+$Qty));
            break;
        }
    } elseif( $doMath =='' ) {
        $newday = mktime(0,0,0,$m,$d,$Y);
    }
        $newday = date( $retFmt,$newday );
        return( $newday );
 } // end: function mpDateMath
 #
 # ================================================================
 #
 # Functionname :     mpValidateDate
 #
 # Description :      js to validate an input date
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 #
 function mpValidateDate( )
 {
                 print ( "<script type=\"text/javascript\">\n" );
                 print ( "var monthtext=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];\n" );
                 print ( "function populatedropdown(dayfield, monthfield, yearfield){\n" );
                 print ( "var today=new Date()\n" );
                 print ( "var dayfield=document.getElementById(dayfield)\n" );
                 print ( "var monthfield=document.getElementById(monthfield)\n" );
                 print ( "var yearfield=document.getElementById(yearfield)\n" );
                 print ( "for (var i=0; i<31; i++)\n" );
                 print ( "dayfield.options[i]=new Option(i+1, i+1)\n" );
                 print ( "dayfield.options[today.getDate()]=new Option(today.getDate(), today.getDate(), true, true)\n" );
                 print ( "for (var m=0; m<12; m++)\n" );
                 print ( "monthfield.options[m]=new Option(monthtext[m], monthtext[m])\n" );
                 print ( "monthfield.options[today.getMonth()]=new Option(monthtext[today.getMonth()], monthtext[today.getMonth()], true, true)\n" );
                 print ( "var thisyear=today.getFullYear()\n" );
                 print ( "for (var y=0; y<20; y++){\n" );
                 print ( "yearfield.options[y]=new Option(thisyear, thisyear)\n" );
                 print ( "thisyear+=1\n" );
                 print ( "}\n" );
                 print ( "yearfield.options[0]=new Option(today.getFullYear(), today.getFullYear(), true, true)\n" );
                 print ( "}\n" );
                 print ( "</script>\n" );
} // end: 
#
# ================================================================
#
#
#Functionname :     mpMonthAsText
#
# Description :      return month as text
#
# Parameters
# ----------
#
# Name	     Type      Description
# ----	     ----      -----------
# $par1      string    month number
#
function mpMonthAsText( $par1 )
{
         if ( $par1 == "01" )  { $month_part = "January"; }
         if ( $par1 == "02" )  { $month_part = "February"; }
         if ( $par1 == "03" )  { $month_part = "March"; }
         if ( $par1 == "04" )  { $month_part = "April"; }
         if ( $par1 == "05" )  { $month_part = "May"; }
         if ( $par1 == "06" )  { $month_part = "June"; }
         if ( $par1 == "07" )  { $month_part = "July"; }
         if ( $par1 == "08" )  { $month_part = "August"; }
         if ( $par1 == "09" )  { $month_part = "September"; }
         if ( $par1 == "10" )  { $month_part = "October"; }
         if ( $par1 == "11" )  { $month_part = "November"; }
         if ( $par1 == "12" )  { $month_part = "December"; }
         #
         return $month_part;                              
} // end:mpMonthAsText
#
# ================================================================
#
# Functionname :     mpDefExpDate
#
# Description :      get a date for exploded table in format for compares
#
# Parameters
# ----------
#
# Name	     Type      Description
# ----	     ----      -----------
# $par1      string    month
# $par2      string    day
# $par3      string    year
 # $num_days  int
#
function mpDefExpDate( $par1, $par2, $par3, $num_days )
{
         if ( $par1 == "January" ) { $month_part = "01"; }
         if ( $par1 == "February" )  { $month_part = "02"; }
         if ( $par1 == "March" )  { $month_part = "03"; }
         if ( $par1 == "April" )  { $month_part = "04"; }
         if ( $par1 == "May" )  { $month_part = "05"; }
         if ( $par1 == "June" )  { $month_part = "06"; }
         if ( $par1 == "July" )  { $month_part = "07"; }
         if ( $par1 == "August" )  { $month_part = "08"; }
         if ( $par1 == "September" )  { $month_part = "09"; }
         if ( $par1 == "October" )  { $month_part = "10"; }
         if ( $par1 == "November" )  { $month_part = "11"; }
         if ( $par1 == "December" )  { $month_part = "12"; }
         #
         if ( $par1 == "Jan" )  { $month_part = "01"; }
         if ( $par1 == "Feb" )  { $month_part = "02"; }
         if ( $par1 == "Mar" )  { $month_part = "03"; }
         if ( $par1 == "Apr" )  { $month_part = "04"; }
         if ( $par1 == "May" )  { $month_part = "05"; }
         if ( $par1 == "Jun" )  { $month_part = "06"; }
         if ( $par1 == "Jul" )  { $month_part = "07"; }
         if ( $par1 == "Aug" )  { $month_part = "08"; }
         if ( $par1 == "Sep" )  { $month_part = "09"; }
         if ( $par1 == "Oct" )  { $month_part = "10"; }
         if ( $par1 == "Nov" )  { $month_part = "11"; }
         if ( $par1 == "Dec" )  { $month_part = "12"; }
         #
         if ( $par1 == "01" )  { $month_part = "01"; }
         if ( $par1 == "02" )  { $month_part = "02"; }
         if ( $par1 == "03" )  { $month_part = "03"; }
         if ( $par1 == "04" )  { $month_part = "04"; }
         if ( $par1 == "05" )  { $month_part = "05"; }
         if ( $par1 == "06" )  { $month_part = "06"; }
         if ( $par1 == "07" )  { $month_part = "07"; }
         if ( $par1 == "08" )  { $month_part = "08"; }
         if ( $par1 == "09" )  { $month_part = "09"; }
         if ( $par1 == "10" )  { $month_part = "10"; }
         if ( $par1 == "11" )  { $month_part = "11"; }
         if ( $par1 == "12" )  { $month_part = "12"; }
         #
         if ( $par1 == "1" )  { $month_part = "01"; }
         if ( $par1 == "2" )  { $month_part = "02"; }
         if ( $par1 == "3" )  { $month_part = "03"; }
         if ( $par1 == "4" )  { $month_part = "04"; }
         if ( $par1 == "5" )  { $month_part = "05"; }
         if ( $par1 == "6" )  { $month_part = "06"; }
         if ( $par1 == "7" )  { $month_part = "07"; }
         if ( $par1 == "8" )  { $month_part = "08"; }
         if ( $par1 == "9" )  { $month_part = "09"; }
          #
          $this_date=array();
          $this_date[]=$par3;                               // year
          $this_date[]="/";
          $this_date[]=$month_part;                         // month
          $this_date[]="/";
          $this_date[]=$par2;                               // day
          #
          $this_date=implode("",$this_date);
          $my_time = strtotime ($this_date);                // converts date string to UNIX timestamp
          $timestamp = $my_time + ($num_days * 86400);      // calculates # of days passed ($num_days) * # seconds in a day (86400)
          $return_date = date("d-m-Y",$timestamp);          // puts the UNIX timestamp back into string format
          #
          return $return_date;                              
  } // end: mpEndDefExpDate
  #
  # ================================================================
  # 
  # Functionname :    mpValidateExpDate
  #
  # Description :     display date input form
  #                   needs mpValidateDate in HEAD section 
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  #
  function mpValidateExpDate()
  {
   print ( "<form action=\"\" name=\"ExpDateForm\">\n" );
   print ( "<select id=\"daydropdown\"></select>\n" );
   print ( "<select id=\"monthdropdown\"></select>\n" );
   print ( "<select id=\"yeardropdown\"></select>\n" );
   print ( "</form>\n" );
   print ( "<script type=\"text/javascript\">\n" );
   print ( "//populatedropdown(id_of_day_select, id_of_month_select, id_of_year_select)\n" );
   print ( "window.onload=function(){\n" );
   print ( "populatedropdown(\"daydropdown\", \"monthdropdown\", \"yeardropdown\")\n" );
   print ( "}\n" );
   print ( "</script>\n" );
  } // end: mpValidateExpDate
  #
  # ================================================================
  #
  # Functionname :     mpDateAsDBDate
  #
  # Description :      return a date formatted ready for input to DB as a timestamp
  #                    defaults to 12:00:00 time.
  #
  #                    Takes a date in form :
  #                    d-mm-yyy or dd-m-yyyy or
  #                    d-m-yyyy or dd-mm-yyyy or
  #					   yyyy-mm-dd 
  #                    Returns yyyy-mm-dd hh:mm:ss
  #
  # Parameters
  # ----------
  #
  # Name	     Type      Description
  # ----	     ----      -----------
  # $jDate     string    date string
  #
  function mpDateAsDBDate( $jDate )
  {
  #
		if ( substr( $jDate, 1, 1 ) == "-" ) {
                    if ( substr( $jDate, 3, 1 ) == "-" ) {
                        # day - month - year
                        $day_p1 = "0" .substr(  $jDate, 0, 1 );
                        $month_p1 = "0" . substr( $jDate, 2, 1 );
                        $year_p1 = substr( $jDate, 4, 4 );
                      } else {
                        # day - month - year
                        $day_p1 = "0" .substr(  $jDate, 0, 1 );
                        $month_p1 = substr( $jDate, 2, 2 );
                        $year_p1 = substr( $jDate, 5, 4 );
                      }
        } else {
                     if ( substr( $jDate, 4, 1 ) == "-" ) {
                        # day - month - year
                        $day_p1 = substr(  $jDate, 0, 2 );
                        $month_p1 = "0" . substr( $jDate, 3, 1 );
                        $year_p1 = substr( $jDate, 5, 4 );
                     } else {
                        # day - month - year
                        $day_p1 = substr(  $jDate, 0, 2 );
                        $month_p1 = substr( $jDate, 3, 2 );
                        $year_p1 = substr( $jDate, 6, 4 );
                     }

        }
        if ( substr( $jDate, 7, 1 ) == "-" && substr( $jDate, 4, 1 ) == "-") {    
                     # year - month - day
                        $year_p1 = substr( $jDate, 0, 4 );
                        $month_p1 = substr( $jDate, 5, 2 );
                        $day_p1 = substr( $jDate, 8, 2 );
                     }
        #
        $finished_date = ( $year_p1 ."-" .$month_p1 ."-" .$day_p1 ." 12:00:00" );
        return $finished_date;
} // end: function mpDateAsDBDate
#
# ================================================================
#
# Functionname :     mpITdate
#
# Description :      convert DB date timestamp in IT displayable format
#
# Parameters
# ----------
#
# Name	     Type      Description
# ----	     ----      -----------
# $timestamp string    date timestamp
#
function mpITdate($timestamp)
{
   $m_par = substr($timestamp, 5, 2);
   $cm_par = mpMonthAsText( $m_par );
   #
   return( $cm_par . ' ' . substr($timestamp, 8, 2) . ' ' . substr($timestamp, 0, 4) );
 } //end: function mpITdate
 #
 # ================================================================
 #
 # Functionname :     mpTextDate
 #
 # Description :      display DB timestamp in displayable text format
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 # $timestamp string    date timestamp
 #
 function mpTextDate($timestamp)
 {
  $datetime = strtotime($timestamp);
  $sdate = date("l, F jS, Y",$datetime);
  #
  return $sdate;
  #
 } //end: mpTextDate
 #
 # ================================================================
 # 
 # Functionname :     mpDateDiff
 #
 # Description :      calculate difference in days between 2 dates
 #                    t1 and t2 are DB formatted timestamps
 # Parameters
 # ----------
 #
 # Name	  Type      Description
 # ----	  ----      -----------
 # $tE     string    date timestamp  (Earlier)
 # $tL     string    date timestamp  (Later)
 #
 function mpDateDiff( $tE, $tL )
 {
  $tED = strtotime( $tE );
  $tLD = strtotime( $tL );
  $tDD = round(abs($tLD-$tED)/60/60/24);
  #
  # return date diff ( in days )
  return $tDD;
  #
 } // end mpDateDiff
 # ================================================================
 #
	#
	# Functionname :     mpShowDate
	#
	# Description :      js to display todays date on page
	#
	# Parameters
	# ----------
	#
	# Name	     Type      Description
	# ----	     ----      -----------
	# $alg	     string    alignment ( right, center or left ).
	#		       If not empty, aligns date display to that requested
	# $lang      string    language
	#
	function mpShowDate( $alg, $lang  )
	{
        #
        # globals
        #
        global $MP_lang;
        #
	  #
	  if ( empty ($lang) )
		  $lang=$MP_lang;
		#
	  switch( $lang )
	  {
	  case "EN":
	   if ( !$alg ) {
	       #
	       # $alg is not defined so no alignment sought
	       #
	       print ( "<script language=\"JavaScript\">\n<!--\n" );
	       print ( "<!-- Begin\n" );
	       print ( "d = new Array(\n" );
	       print ( "\" Sunday\",\n" );
	       print ( "\" Monday\",\n" );
	       print ( "\" Tuesday\",\n" );
	       print ( "\" Wednesday\",\n" );
	       print ( "\" Thursday\",\n" );
	       print ( "\" Friday\",\n" );
	       print ( "\" Saturday\"\n" );
	       print ( ");\n" );
	       print ( "m = new Array(\n" );
	       print ( "\"January\",\n" );
	       print ( "\"February\",\n" );
	       print ( "\"March\",\n" );
	       print ( "\"April\",\n" );
	       print ( "\"May\",\n" );
	       print ( "\"June\",\n" );
	       print ( "\"July\",\n" );
	       print ( "\"August\",\n" );
	       print ( "\"September\",\n" );
	       print ( "\"October\",\n" );
	       print ( "\"November\",\n" );
	       print ( "\"December\"\n" );
	       print ( ");\n" );
	       #
	       print ( "today = new Date();\n" );
	       print ( "day = today.getDate();\n" );
	       print ( "end = \"th\";\n" );
	       print ( "if (day==1 || day==21 || day==31) end=\"st\";\n" );
	       print ( "if (day==2 || day==22) end=\"nd\";\n" );
	       print ( "if (day==3 || day==23) end=\"rd\";\n" );
	       print ( "day+=end;\n" );
	       print ( "document.write(\"<right>\");\n" );
	       print ( "document.write(d[today.getDay()]+\", \"+m[today.getMonth()]+\" \");\n" );
	       print ( "document.write(day+\", \"+(000+today.getYear()));\n" );
	       print ( "document.write(\" </right>\");\n" );
	       print ( "// End\n" );
	       print ( "// -->\n" );
	       print ( "</script>\n" );
	} else {
	       #
	       # $alg is defined as either right, left or center
	       #
	       printf ( "<div align=\"%s\">\n", $alg );
	       print ( "<font class=\"olf8\">\n" );
	       print ( "<script language=\"JavaScript\">\n\n" );
	       print ( "<!-- Begin\n" );
	       print ( "d = new Array(\n" );
	       print ( "\" Sunday\",\n" );
	       print ( "\" Monday\",\n" );
	       print ( "\" Tuesday\",\n" );
	       print ( "\" Wednesday\",\n" );
	       print ( "\" Thursday\",\n" );
	       print ( "\" Friday\",\n" );
	       print ( "\" Saturday\"\n" );
	       print ( ");\n" );
	       print ( "m = new Array(\n" );
	       print ( "\"January\",\n" );
	       print ( "\"February\",\n" );
	       print ( "\"March\",\n" );
	       print ( "\"April\",\n" );
	       print ( "\"May\",\n" );
	       print ( "\"June\",\n" );
	       print ( "\"July\",\n" );
	       print ( "\"August\",\n" );
	       print ( "\"September\",\n" );
	       print ( "\"October\",\n" );
	       print ( "\"November\",\n" );
	       print ( "\"December\"\n" );
	       print ( ");\n" );
	       #
	       print ( "today = new Date();\n" );
	       print ( "day = today.getDate();\n" );
	       print ( "end = \"th\";\n" );
	       print ( "if (day==1 || day==21 || day==31) end=\"st\";\n" );
	       print ( "if (day==2 || day==22) end=\"nd\";\n" );
	       print ( "if (day==3 || day==23) end=\"rd\";\n" );
	       print ( "day+=end;\n" );
	       print ( "document.write(\"<right>\");\n" );
	       print ( "document.write(d[today.getDay()]+\", \"+m[today.getMonth()]+\" \");\n" );
	       print ( "document.write(day+\", \"+(000+today.getYear()));\n" );
	       print ( "document.write(\" </right>\");\n" );
	       print ( "// End\n" );
	       print ( "// -->\n" );
	       print ( "</script></font></div>\n" );
	       }
	     break;
	     case "DE":
	     if ( !$alg ) {
	       #
	       # $alg wird nicht definiert
	       #
	       print ( "<script language=\"JavaScript\">\n<!--\n" );
	       print ( "<!-- Beginn\n" );
	       print ( "d = new Array(\n" );
	       print ( "\" Sonntag\",\n" );
	       print ( "\" Montag\",\n" );
	       print ( "\" Dienstag\",\n" );
	       print ( "\" Mittwoch\",\n" );
	       print ( "\" Donnerstag\",\n" );
	       print ( "\" Freitag\",\n" );
	       print ( "\" Samstag\"\n" );
	       print ( ");\n" );
	       print ( "m = new Array(\n" );
	       print ( "\"Januar\",\n" );
	       print ( "\"Februar\",\n" );
	       print ( "\"M�rz\",\n" );
	       print ( "\"April\",\n" );
	       print ( "\"Mai\",\n" );
	       print ( "\"Juni\",\n" );
	       print ( "\"Juli\",\n" );
	       print ( "\"August\",\n" );
	       print ( "\"September\",\n" );
	       print ( "\"Oktober\",\n" );
	       print ( "\"November\",\n" );
	       print ( "\"Dezember\"\n" );
	       print ( ");\n" );
	       #
	       print ( "today = new Date();\n" );
	       print ( "day = today.getDate();\n" );
	       print ( "end = \"th\";\n" );
	       print ( "if (day==1 || day==21 || day==31) end=\"st\";\n" );
	       print ( "if (day==2 || day==22) end=\"nd\";\n" );
	       print ( "if (day==3 || day==23) end=\"rd\";\n" );
	       print ( "day+=end;\n" );
	       print ( "document.write(\"<right>\");\n" );
	       print ( "document.write(d[today.getDay()]+\", \"+m[today.getMonth()]+\" \");\n" );
	       print ( "document.write(day+\", \"+(000+today.getYear()));\n" );
	       print ( "document.write(\" </right>\");\n" );
	       print ( "// End\n" );
	       print ( "// -->\n" );
	       print ( "</script>\n" );
	} else {
	       #
	       # $alg wird nicht definiert
	       #
	       printf ( "<div align=\"%s\">\n", $alg );
	       print ( "<font class=\"olf8\">\n" );
	       print ( "<script language=\"JavaScript\">\n<!--\n" );
	       print ( "<!-- Beginn\n" );
	       print ( "d = new Array(\n" );
	       print ( "\" Sonntag\",\n" );
	       print ( "\" Montag\",\n" );
	       print ( "\" Dienstag\",\n" );
	       print ( "\" Mittwoch\",\n" );
	       print ( "\" Donnerstag\",\n" );
	       print ( "\" Freitag\",\n" );
	       print ( "\" Samstag\"\n" );
	       print ( ");\n" );
	       print ( "m = new Array(\n" );
	       print ( "\"Januar\",\n" );
	       print ( "\"Februar\",\n" );
	       print ( "\"M�rz\",\n" );
	       print ( "\"April\",\n" );
	       print ( "\"Mai\",\n" );
	       print ( "\"Juni\",\n" );
	       print ( "\"Juli\",\n" );
	       print ( "\"August\",\n" );
	       print ( "\"September\",\n" );
	       print ( "\"Oktober\",\n" );
	       print ( "\"November\",\n" );
	       print ( "\"Dezember\"\n" );
	       print ( ");\n" );
	       #
	       print ( "today = new Date();\n" );
	       print ( "day = today.getDate();\n" );
	       print ( "end = \"th\";\n" );
	       print ( "if (day==1 || day==21 || day==31) end=\"st\";\n" );
	       print ( "if (day==2 || day==22) end=\"nd\";\n" );
	       print ( "if (day==3 || day==23) end=\"rd\";\n" );
	       print ( "day+=end;\n" );
	       print ( "document.write(\"<right>\");\n" );
	       print ( "document.write(d[today.getDay()]+\", \"+m[today.getMonth()]+\" \");\n" );
	       print ( "document.write(day+\", \"+(000+today.getYear()));\n" );
	       print ( "document.write(\" </right>\");\n" );
	       print ( "// End\n" );
	       print ( "// -->\n" );
	       print ( "</script></font></div>\n" );
	       }
	     break;
             case "ES":
	     if ( !$alg ) {
	       #
	       # $alg -- se define
	       #
	       print ( "<script language=\"JavaScript\">\n<!--\n" );
	       print ( "<!-- Beginn\n" );
	       print ( "d = new Array(\n" );
	       print ( "\" Domingo\",\n" );
	       print ( "\" Lunes\",\n" );
	       print ( "\" Martes\",\n" );
	       print ( "\" Miercoles\",\n" );
	       print ( "\" Jueves\",\n" );
	       print ( "\" Viernes\",\n" );
	       print ( "\" Sabado\"\n" );
	       print ( ");\n" );
	       print ( "m = new Array(\n" );
	       print ( "\"Enero\",\n" );
	       print ( "\"Febrero\",\n" );
	       print ( "\"Marzo\",\n" );
	       print ( "\"Abril\",\n" );
	       print ( "\"Mayo\",\n" );
	       print ( "\"Junio\",\n" );
	       print ( "\"Julio\",\n" );
	       print ( "\"Agosto\",\n" );
	       print ( "\"Septiembre\",\n" );
	       print ( "\"Octubre\",\n" );
	       print ( "\"Noviembre\",\n" );
	       print ( "\"Diciembre\"\n" );
	       print ( ");\n" );
	       #
	       print ( "today = new Date();\n" );
	       print ( "day = today.getDate();\n" );
	       print ( "end = \"th\";\n" );
	       print ( "if (day==1 || day==21 || day==31) end=\"st\";\n" );
	       print ( "if (day==2 || day==22) end=\"nd\";\n" );
	       print ( "if (day==3 || day==23) end=\"rd\";\n" );
	       print ( "day+=end;\n" );
	       print ( "document.write(\"<right>\");\n" );
	       print ( "document.write(d[today.getDay()]+\", \"+m[today.getMonth()]+\" \");\n" );
	       print ( "document.write(day+\", \"+(000+today.getYear()));\n" );
	       print ( "document.write(\" </right>\");\n" );
	       print ( "// End\n" );
	       print ( "// -->\n" );
	       print ( "</script>\n" );
	} else {
	       #
	       # $alg se no define
	       #
	       printf ( "<div align=\"%s\">\n", $alg );
	       print ( "<font class=\"olf8\">\n" );
	       print ( "<script language=\"JavaScript\">\n<!--\n" );
	       print ( "<!-- Beginn\n" );
	       print ( "d = new Array(\n" );
	       print ( "\" Domingo\",\n" );
	       print ( "\" Lunes\",\n" );
	       print ( "\" Martes\",\n" );
	       print ( "\" Miercoles\",\n" );
	       print ( "\" Jueves\",\n" );
	       print ( "\" Viernes\",\n" );
	       print ( "\" Sabado\"\n" );
	       print ( ");\n" );
	       print ( "m = new Array(\n" );
	       print ( "\"Enero\",\n" );
	       print ( "\"Febrero\",\n" );
	       print ( "\"Marzo\",\n" );
	       print ( "\"Abril\",\n" );
	       print ( "\"Mayo\",\n" );
	       print ( "\"Junio\",\n" );
	       print ( "\"Julio\",\n" );
	       print ( "\"Agosto\",\n" );
	       print ( "\"Septiembre\",\n" );
	       print ( "\"Octubre\",\n" );
	       print ( "\"Noviembre\",\n" );
	       print ( "\"Diciembre\"\n" );
	       print ( ");\n" );
	       #
	       print ( "today = new Date();\n" );
	       print ( "day = today.getDate();\n" );
	       print ( "end = \"th\";\n" );
	       print ( "if (day==1 || day==21 || day==31) end=\"st\";\n" );
	       print ( "if (day==2 || day==22) end=\"nd\";\n" );
	       print ( "if (day==3 || day==23) end=\"rd\";\n" );
	       print ( "day+=end;\n" );
	       print ( "document.write(\"<right>\");\n" );
	       print ( "document.write(d[today.getDay()]+\", \"+m[today.getMonth()]+\" \");\n" );
	       print ( "document.write(day+\", \"+(000+today.getYear()));\n" );
	       print ( "document.write(\" </right>\");\n" );
	       print ( "// End\n" );
	       print ( "// -->\n" );
	       print ( "</script></font></div>\n" );
	       }
             break;
	     }
    } // end: function mpSHowDate
    #
    ###################################################
    # Following group of functions handle errors
    ###################################################
    #
    # Functionname :     mpErrors
    #
    # Description :      display standard error box
    #
    # Parameters
    # ----------
    #
    # Name	 Type           Description
    # ----	 ----           -----------
    # @errors    array          errors array for display
    # @lang      string         language
    #
    function mpErrors( $errors, $lang )
    {
    #
    # globals
    #
    if ( empty ($lang) )
     $lang=$_SESSION['mp_lang'];
    #
    switch( $lang )
    {
     case "EN":
     if (!empty($errors)) {
      print ( "<div class=\"errors\">\n" );
      print ( "<span class=\"error\"><p class=\"errL\">&nbsp;&nbsp;<img class=\"middle\" src=\"../../../buttons/error.png\" width=\"32\" height=\"32\" border=\"0\" vertical-align:text-top;>&nbsp;Error!</p></span>\n" );
      print ( "<span class=\"error\"><p class=\"errL\">&nbsp;&nbsp;The following error(s) occurred:</p></span>\n" );
      foreach ($errors as $msg) {
       print ( "<span><p class=\"errC\">$msg<br /></p></span>\n" );
     }
     print ( "<span><p>&nbsp;</p></span>\n" );
     print ( "<span class=\"error\"><p class=\"errL\">&nbsp;&nbsp;&nbsp;Please click on back to try again.</p></span>\n" );
     print ( "</div>\n" );
     }
      die;
      break;
     case "DE":
     if (!empty($errors)) {
      print ( "<div class=\"errors\">\n" );
	  print ( "<span class=\"error\"><p class=\"errL\">&nbsp;&nbsp;<img class=\"middle\" src=\"../buttons/error.png\" width=\"32\" height=\"32\" border=\"0\" vertical-align:text-top;>Storung!</p></span>\n" );
	  print ( "<span class=\"error\"><p class=\"error\">&nbsp;&nbsp;Die folgelden Storung(en) traten auf:</p><br /></span>\n" );
	  foreach ($errors as $msg) {
		print ( "<span><p class=\"errC\">$msg<br /></p></span>\n" );
	  }
	  print ( "<span><p>&nbsp;</p></span>\n" );
	  print ( "<span class=\"error\"><p class=\"errL\">&nbsp;Versuchen Sie bitte noch einmal.</p></span>\n" );
      print ( "</div>\n" );
      }
       break;
     case "ES":
     if (!empty($errors)) {
      print ( "<div class=\"errors\">\n" );
	  print ( "<span class=\"error\"><p class=\"errL\">&nbsp;&nbsp;<img class=\"middle\" src=\"../buttons/error.png\" width=\"32\" height=\"32\" border=\"0\" vertical-align:text-top;>Error!</p></span>\n" );
	  print ( "<span class=\"error\"><p class=\"error\">&nbsp;&nbsp;Los errores siguient(es) ocurrieron:</p><br /></span>\n" );
	  foreach ($errors as $msg) {
	  print ( "<span><p class=\"errC\">$msg<br /></p></span>\n" );
	  }
	  print ( "<span><p>&nbsp;</p></span>\n" );
	  print ( "<span class=\"error\"><p class=\"errL\">&nbsp;Intente por favor otra vez./p></span>\n" );
	  print ( "</div>\n" );
      }
       break;
     }
    # need logfile
 } // end: function mpErrors
 #
 ###################################################
 # Following group of functions gets keys and descriptors
 ###################################################
 # 
 # Functionname :     getNextDueDate( $table, $target )
 #
 # Description :      get next payment cycle date from contract
 #					  for accepting payments from clients
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 # $table    string    table to read
 # $target   string    contract key
 #
 function getNextDueDate($table,$target)
 {
  global $link;
  global $errors;
  $nd_query = "SELECT next_due FROM $table where g_id = '$target'";
  $result = mysqli_query($link, $nd_query);
  if(!$result)
  {
   $errors[] = "Query: " . $nd_query . "";
   $errors[] = "Errno: " . mysqli_errno($link);
   $errors[] = "Error: " . mysqli_error($link);
   $errors[] = "getNextDueDate - error occurred reading estate " .$target;
  }
  if ( $errors ) {
   mpErrors( $errors, $_SESSION['mp_lang'] );
   $errors = NULL;
  } // end: errors
  #
  while ($row = mysqli_fetch_array( $result ) )
  {
   $nddate = $row[ "next_due" ];
  }
  mysqli_free_result( $result );
  return $nddate;
  #
 } //end: getNextDueDate()
 #
 # ================================================================
 # 
 # Functionname :     mpUpdateNextDueDate( $date, $target )
 #
 # Description :      update next payment cycle date in contract
 #                    for accepting payments from clients
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 # $this_dat date 	   date to insert
 # $target   string    contract key
 #
 function mpUpdateNextDueDate($this_date,$target)
 {
  global $link;
  global $errors;
  echo '<br />Next Due Date - '.$this_date;
  echo '<br />Contract ID - '.$target;
  $nd_query = "UPDATE mp_contract SET next_due = '$this_date' WHERE contract_id = '$target'";
  $result = mysqli_query($link, $nd_query);
  #
  if(!$result) 
  {
   $errors[] = "Query: " . $nd_query . "";
   $errors[] = "Errno: " . mysqli_errno($link);
   $errors[] = "Error: " . mysqli_error($link);
   $errors[] = "mpUpdateNextDueDate - error occurred updating ".$target." ";
  }
  if ( $errors ) {
    mpErrors( $errors, $_SESSION['mp_lang'] );
    $errors = NULL;
  } // end: errors 
  return $result;
  #
 } //end: mpUpdateNextDueDate()
 #
 # ====================================================
 # 
 # Functionname :     getNextDueDateComp( $table, $target )
 #
 # Description :      get this payment cycle date from contract
 #                    component for paying sub-contractors
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 # $table    string    table to read
 # $target   string    component key
 #
 function getNextDueDateComp($table,$target)
 {
  global $link;
  global $errors;
  $nd_query = "SELECT * FROM $table where c_contract_comp_id = '$target'";
  $result = mysqli_query($link, $nd_query);
  if(!$result)
  {
   $errors[] = "Query: " . $nd_query . "";
   $errors[] = "Errno: " . mysqli_errno($link);
   $errors[] = "Error: " . mysqli_error($link);
   $errors[] = "getNextDueDateComp - error occurred reading contract_components " .$target;
  }
  if ( $errors ) {
   mpErrors( $errors, $_SESSION['mp_lang'] );
   $errors = NULL;
  } // end: errors
  #
  while ($row = mysqli_fetch_array( $result ) )
  {
   $nddate = $row[ "next_due" ];
  }
  mysqli_free_result( $result );
  return $nddate;
  #
 } //end: getNextDueDateComp()
 #
 # ================================================================
 # 
 # Functionname :     mpUpdateNextDueDateComp( $date, $target )
 #
 # Description :      update next payment cycle date in contract
 #                    for making payments for sub-contractors
 #
 # Parameters
 # ----------
 #
 # Name	     Type      Description
 # ----	     ----      -----------
 # $this_dat date 	   date to insert
 # $target   string    contract key
 #
 function mpUpdateNextDueDateComp($this_date,$target)
 {
  global $link;
  global $errors;
  $nd_query = "UPDATE mp_contract_component SET next_due = '$this_date' WHERE c_contract_comp_id = '$target'";
  $result = mysqli_query($link, $nd_query);
  #
  if(!$result) 
  {
   $errors[] = "Query: " . $nd_query . "";
   $errors[] = "Errno: " . mysqli_errno($link);
   $errors[] = "Error: " . mysqli_error($link);
   $errors[] = "mpUpdateNextDueDateComp - error occurred updating ".$target." ";
  }
  if ( $errors ) {
    mpErrors( $errors, $_SESSION['mp_lang'] );
    $errors = NULL;
  } // end: errors 
  return $result;
  #
 } //end: mpUpdateNextDueDateComp()
 #
 # ================================================================
 # 
    # Functionname :     getMemberID( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       member email
    #
    function getMemberID( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_client on client email and return client_id
      #
      $Qquery = "SELECT * FROM mp_client WHERE email = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read clients on email." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "client_id" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getMemberID
    #
    # ================================================================
    # 
    # Functionname :     getClientIDReal( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       client real name
    #
    function getClientIDReal( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_client on real name and return id
      #
      $names = explode( " ", $qKey );
      $Qquery = "SELECT * FROM mp_client WHERE surname = '$names[1]'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getClientIDReal : cannot read clients on surname." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "client_id" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getClientIDReal
    #
    # ================================================================
    # 
    # Functionname :     getMemberEmail( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int      member id
    #
    function getMemberEmail( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_client on client_id member and return email
      #
      $Qquery = "SELECT * FROM mp_client WHERE client_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read clients on client ID." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "email" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getMemberEmail
    #
    # ================================================================
    # 
    # Functionname :     getMemberName( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int      member id
    #
    function getMemberName( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_client on client_id and return name
      #
      $Qquery = "SELECT * FROM mp_client WHERE client_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read clients on client ID." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "first_name" ]." ".$Qrow[ "surname" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getMembername
    #
    # ================================================================
    # 
    # Functionname :     getGroupDiscKey( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       Estate discount desc
    #
    function getGroupDiscKey( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_discounts on passed text string ( desc )  and return discount key
      #
      $Qquery = "SELECT * FROM mp_discount WHERE g_type_desc = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read group discounts on description" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "g_disc_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end : getGroupDiscKey
    #
    # ================================================================
    # 
    # Functionname :     getPropDesc( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       get property desc on key
    #
    function getPropDesc( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_prop_type on passed key and return desc
      #
      $Qquery = "SELECT * FROM mp_prop_type WHERE p_proptype_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read property types on key" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "p_proptype_desc" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getPropDesc
    #
    # ================================================================
    # 
    # Functionname :     getPropKey( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       get property key on desc
    #
    function getPropKey( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_prop_type on passed desc and return key
      #
      $Qquery = "SELECT * FROM mp_prop_type WHERE p_proptype_desc = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read property types on description" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "p_proptype_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getPropKey
    #
    # ================================================================ 
    # 
    # Functionname :     getGroupDisc( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       get discount desc
    #
    function getGroupDisc( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_discount on passed key and return text string ( desc )
      #
      $Qquery = "SELECT * FROM mp_discount WHERE g_disc_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read discounts on key" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "g_type_desc" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getGroupDisc
    #
    # ================================================================
    # 
    # Functionname :     getGroupTypeKey( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       org type desc
    #
    function getTypeKey( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_group_type on passed text string ( desc )  and return group type key
      #
      $Qquery = "SELECT * FROM mp_org_type WHERE g_gtype_desc = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read group types on description" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array ($Qresult) )
      {
         $qualstr = $Qrow[ "g_gtype_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getTypeKey
    #
    # ================================================================
    # 
    # Functionname :     getGroupTypeDesc( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       org type key
    #
    function getGroupTypeDesc( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_group_type on passed key and return text string ( desc )
      #
      $Qquery = "SELECT * FROM mp_org_type WHERE g_gtype_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read group types on key" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "g_gtype_desc" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getGroupTypeDesc
    #
    # ================================================================
    #
    # Functionname :     getGroup( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int      get org name from key
    #
    function getGroup( $qKey )
    {
      global $link;
      global $mp_script;
      global $errors;
      #
      #  read mp_org on passed key and return text
      #
      $Qquery = "SELECT * FROM mp_org WHERE g_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery);
      if (!$Qresult)
      {
       $errors[] = "Query: " . $Qquery . "";
       $errors[] = "Errno: " . mysqli_errno($link);
       $errors[] = "Error: " . mysqli_error($link);
       $errors[] = $mp_script . " getGroup - cannot read groups on key ";
      }
      if ( $errors ) {
       mpErrors( $errors, $_SESSION['mp_lang'] );
       $errors = NULL;
      } // end: errors
      $total=mysqli_num_rows($Qresult);
      if($total > 0) {
       #
       # read results of query and return
       #
       while ($Qrow = mysqli_fetch_array($Qresult) )
       {
         $qualstr = $Qrow[ "g_name" ];
       }
      } else {
         $qualstr = "";
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getGroup
    #
    # ================================================================
    # 
    # Functionname :     getGroupKey( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str      get org key from name
    #
    function getGroupKey( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_org on passed text and return key
      #
      $Qquery = "SELECT * FROM mp_org WHERE g_name = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getGroupKey cannot read groups on name" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "g_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getGroupKey
    #
    # ================================================================   
    #
    # Functionname :     getBankName( $qKey )
    #                    usage: banks
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int      get bank name from key
    #
    function getBankName( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_org on passed key and return text
      #
      $Qquery = "SELECT * FROM mp_bank WHERE b_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getBank cannot read banks on key" );
      $total = mysqli_num_rows($Qresult);
      if($total>0) {
       #
       # read results of query and return
       #
       while ($Qrow = mysqli_fetch_array($Qresult) )
       {
         $qualstr = $Qrow[ "b_name" ];
         if(is_null($qualstr)) {
             $qualstr = "";
         }
       }
      } else {
          $qualstr = "-none-";
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getBankName
    #
    # ================================================================
    # 
    # Functionname :     getBankKey( $qKey )
    #                    usage: banks
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str      get bank key from name
    #
    function getBankKey( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_bank on passed text and return key
      #
      $Qquery = "SELECT * FROM mp_bank WHERE b_name = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getBankKey cannot read banks on bank name" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "b_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getBankKey
    # 
    # ================================================================
    # 
    # Functionname :     getBankBal( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str      get estate bank balance for bank
    #
    function getBankBal( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_bank on passed key and return balance
      #
      $Qquery = "SELECT * FROM mp_estate_bank WHERE b_bank_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getBankBal cannot read banks on bank key" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "b_balance" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getBankKey
    # 
    # ================================================================
    # 
    # Functionname :     getPayCode( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       pay code key
    #
    function getPayCode( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_pay_types on passed key and return text
      #
      $Qquery = "SELECT * FROM mp_pay_type WHERE g_pay_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read pay codes" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "g_pay_desc" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getPayCode
    #
    # ================================================================
    # 
    # Functionname :     getPayKey( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       pay code desc
    #
    function getPayKey( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_pay_type on passed desc and return key
      #
      $Qquery = "SELECT * FROM mp_pay_type WHERE g_pay_desc = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getPayKey cannot read pay codes using description" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "g_pay_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getPayKey
    #
    # ================================================================
    #
    # Functionname :     getCurr( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       currency name
    #
    function getCurr( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_curr on currency name & return symbol
      #
      $Qquery = "SELECT * FROM mp_curr WHERE cur_name = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getCurr cannot read currancies" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "cur_symbol" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getCurr
    #
    # ================================================================
    # 
    # Functionname :     getPayKey2( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       pay type desc
    #
    function getPayKey2( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_pay_type on passed string and return key
      #
      $Qquery = "SELECT * FROM mp_pay_type WHERE g_pay_desc = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getPayKey2 cannot read on pay desc" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "g_pay_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getPayKey2
    #
    # ================================================================
    #
    # Functionname :     getCert( $qKey )
    #                    usage: 
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       certification key
    #
    function getCert( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_c_type on passed key and return text cert
      #
      $Qquery = "SELECT * FROM mp_c_type WHERE c_type_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read on certifications key" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "c_desc" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getCert
    #
    # ================================================================
    # 
    # Functionname :     getCertKey( $qstr )
    #                    usage: 
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qstr       str       certification string
    #
    function getCertKey( $qstr )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_c_type on passed string and return resultant key
      #
      $Qquery = "SELECT * FROM mp_c_type WHERE c_desc = '$qstr'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read on certification" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "c_type_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getCertKey
    #
    # ================================================================
    #  
    # Functionname :     getResDesc( $qKey )
    #                    usage: all
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       residence status key
    #
    function getResDesc( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_resident on passed key and return description
      #
      $Qquery = "SELECT * FROM mp_resident WHERE res_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read resident status on resident key" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "res_desc" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getResDesc
    #
    # ================================================================
    # 
    # Functionname :     getResKey( $qstr )
    #                    usage: all
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qstr       str       residence type description
    #
    function getResKey( $qstr )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_resident on passed description and return resultant key
      #
      $Qquery = "SELECT * FROM mp_resident WHERE res_desc = '$qstr'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read client resident status on description" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "res_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getResKey
    #
    # ================================================================
    # 
    # Functionname :     getPeriodTerm( $qKey )
    #                    usage: non specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       period desc
    #
    function getPeriodTerm( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_period on per_desc and return per_id
      #
      $Qquery = "SELECT per_id FROM mp_period WHERE per_desc = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getPeriodTerm cannot read periods on description" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "per_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getPeriodTerm
    #
    # ================================================================
    # 
    # Functionname :     getLoyKey( $qKey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       loyalty key
    #
    function getLoyKey( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_loyalty_code on passed key and return desc
      #
      $Qquery = "SELECT * FROM mp_loyalty_code WHERE mpLid = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script getLoyKey - cannot read loyalty codes" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "mpLdesc" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getLoyKey
    #
    # ================================================================
    # 
    # Functionname :     getLoydesc( $qKey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       loyalty desc
    #
    function getLoydesc( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_loyalty_codes on passed desc and return key
      #
      $Qquery = "SELECT * FROM mp_loyalty_code WHERE mpLdesc = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script getLoydesc - cannot read loyalty codes on description" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "mpLid" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getLoyDesc
    #
    # ================================================================
    #
    # Functionname :     getInitialLoy()
    #                    usage: non - specific
    #                    get initial loyalty bonus value
    #                    on adding a new client record
    #
    # Parameters  type      description
    # ----------  ----      -----------
    # none
    #
    function getInitialLoy()
    {
      global $link;
      global $mp_script;
      #
      #  read mp_loyalty_codes and get initial loyalty points
      #  // default joining bonus code
      $Qquery = "SELECT * FROM mp_loyalty_code WHERE mpLid = '10000'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getInitialLoy cannot read loyalty codes on key" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $initialVal = $Qrow[ "mpLval" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $initialVal;
    } // end: getInitialLoy
    #
    # ================================================================
    #
    # Functionname :     getGranted()
    #                    usage: 
    #                    get loyalty points awarded for client
    #
    # Parameters  type      description
    # ----------  ----      -----------
    # none
    #
    function getGranted( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_loyalty and get loyalty points
      #  
      $Qquery = "SELECT * FROM mp_loyalty WHERE client_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - getGranted cannot read granted loyalty for client" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $grantedVal = $Qrow[ "mpLpoints" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $grantedVal;
    } // end: getGranted
    #
    # ================================================================
    #
    # Functionname :     getMStat( $qKey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       get status desc
    #
    function getMStat( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_stat_type on key and get current status
      #
      $Qquery = "SELECT * FROM mp_stat_type WHERE m_stat_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read property status codes" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "m_stat_desc" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end : getMStat
    #
    # ================================================================
    # 
    # Functionname :     getMStatKey( $qKey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       get status key
    #
    function getMStatKey( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_stat_type on desc and get key
      #
      $Qquery = "SELECT * FROM mp_stat_type WHERE m_stat_desc = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read status codes" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "m_stat_id" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end; getMStatKey  
    #
    # ================================================================
    # 
    # Functionname :     getOrdStat( $qKey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       get work order status desc
    #
    function getOrdStat( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_wo_status on key and get status
      #
      $Qquery = "SELECT * FROM mp_wo_status WHERE wos_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script : getOrdStat - cannot read work order status codes" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "wos_desc" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end : getOrdStat
    #
    # ================================================================
    # 
    # Functionname :     getOrdStatKey( $qKey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       get work order status key
    #
    function getOrdStatKey( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_wo_status on desc and get key
      #
      $Qquery = "SELECT * FROM mp_wo_status WHERE wos_desc = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script : getOrdStatKey - cannot read work order status codes" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "wos_id" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end; getMStatKey   
    #
    # ================================================================
    # 
    # Functionname :     getWOQStat( $qKey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       get work order quote status desc
    #
    function getWOQStat( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_wq_status on key and get status
      #
      $Qquery = "SELECT * FROM mp_wq_status WHERE wqs_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script : getWOQStat - cannot read work order quote status codes" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "wqs_desc" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end : getWOQStat   
    #
    # ================================================================
    # 
    # Functionname :     mpUpdateWOStat( $qKey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       work package ID
    #
    function mpUpdateWOStat( $qKey )
    {
      global $link;
      global $mp_script;
      global $errors;
      #
      #  update mp_work_order status
      #
      $wos = 103;
      $Qquery = "UPDATE mp_work_order SET wos_id = '$wos' WHERE worder_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery);
      if(!$Qresult)
      {
        $errors[] = "Work Package: " . $qkey . "";
        $errors[] = "Errno: " . mysqli_errno($link);
	$errors[] = "Error: " . mysqli_error($link);
        $errors[] = $mp_script . "mpUpdateWOStat : cannot update status to quoted for " .$qkey. "";
      } //end:if
      #
      # return
      #
      return $Qresult;
    } // end : mpUpdateWOStat
    #
    # ================================================================
    # 
    # Functionname :     getMStatTxt( $qKey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       get status text
    #
    function getMStatTxt( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_stat_type on key and get descriptive text
      #
      $Qquery = "SELECT * FROM mp_stat_type WHERE m_stat_id = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read status codes for text" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "m_stat_txt" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getMStatTxt
    #
    # ================================================================
    # 
    # Functionname :     getJobKey( $qkey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       job desc
    #
    function getJobKey( $qKey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_job on job description and get job key
      #
      $Qquery = "SELECT * FROM mp_job WHERE job_desc = '$qKey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read job code" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array ( $Qresult ) )
      {
         $qualstr = $Qrow[ "job_id" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getJobKey
    #
    # ================================================================
    # 
    # Functionname :     getJobDesc( $qkey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       job key
    #
    function getJobDesc( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_job on job key and get job desc
      #
      $Qquery = "SELECT * FROM mp_job WHERE job_id = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read job code" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "job_desc" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getJobDesc
    #
    # ================================================================
    # 
    # Functionname :     getCountyKey( $qkey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       county name
    #
    function getCountyKey( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_counties on county name and get county key
      #
      $Qquery = "SELECT * FROM mp_county WHERE county_name = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read counties on name" );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "county_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getCountyKey
    #
    # ================================================================
    # 
    # Functionname :     getCountyName( $qkey )
    #                    usage: non - specific
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int       country key
    #
    function getCountyName( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_counties on county key and get county name
      #
      $Qquery = "SELECT * FROM mp_county WHERE county_id = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read counties on key" );
      $total = mysqli_num_rows($Qresult);
      if($total>0) {
       #
       # read results of query and return
       #
       while ($Qrow = mysqli_fetch_array($Qresult) )
       {
         $qualstr = $Qrow[ "county_name" ];
       }
      } else {
          $qualstr = "";
      } 
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getCountyName
    #
    # ================================================================
    # 
    # Functionname :     getWorkKey( $qkey )
    #                    usage: 
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       event name
    #
    function getWorkKey( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_events on event name - get key
      #
      $Qquery = "SELECT * FROM mp_events WHERE event_name = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read events using name." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "event_id" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getWorkKey
    #
    # ================================================================
    # 
    # Functionname :     getWorkDesc( $qkey )
    #                    usage: BOA
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       int      event key
    #
    function getWorkDesc( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_events on event key - get name
      #
      $Qquery = "SELECT * FROM mp_events WHERE event_id = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read events using key." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "event_name" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end getWorkDesc
    #
    # ================================================================
    # 
    # Functionname :     getWOStatDesc( $qkey )
    #                    usage: 
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       work order status key
    #
    function getWOStatDesc( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_wo_status on key - get desc
      #
      $Qquery = "SELECT * FROM mp_wo_status WHERE wos_id = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read work order status using key." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "wos_desc" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getWOStatDesc
    #
    # ================================================================
    # 
    # Functionname :     getWOStatKey( $qkey )
    #                    usage: 
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       work order status desc
    #
    function getWOStatKey( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_wo_status on desc - get key
      #
      $Qquery = "SELECT * FROM mp_wo_status WHERE wos_desc = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read work order status using name." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "wos_id" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getWOStatKey   
    #
    # ================================================================
    # 
    # Functionname :     getQuoteStat( $qkey )
    #                    usage: 
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       work quote status key
    #
    function getQuoteStat( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_wo_status on key - get desc
      #
      $Qquery = "SELECT * FROM mp_wq_status WHERE wqs_id = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read work quote status using key." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "wqs_desc" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getQuoteStat
    #
    # ================================================================
    # 
    # Functionname :     getStaffKey( $qkey )
    #
    # Parameters  type      description
    # ----------  ----      -----------
    #
    # @qKey       str       staff name
    #
    function getStaffKey( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_staff on name - get key
      #
      $Qquery = "SELECT * FROM mp_staff WHERE st_name = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read staff using name." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "st_id" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getStaffKey
    #
    # ================================================================
    # 
    # Functionname :     getStaffName( $qkey )
    #
    # Parameters  type     description
    # ----------  ----     -----------
    #
    # @qKey       int      staff key
    #
    function getStaffName( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_staff on staff key - get name
      #
      $Qquery = "SELECT * FROM mp_staff WHERE st_id = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read staff using key." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "st_name" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getStaffName
    #
    # ================================================================
    #
    # Functionname :     getOrgTypeKey( $qkey )
    #
    # Parameters  type     description
    # ----------  ----     -----------
    #
    # @qKey       int      Org account type desc
    #
    function getOrgTypeKey( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_org_type on desc - get type key
      #
      $Qquery = "SELECT * from mp_org_type WHERE g_orgtype_desc = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read account types using description." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "g_orgtype_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getOrgTypeKey
    #
    # ================================================================
    #
    # Functionname :     getOrgTypeDesc( $qkey )
    #
    # Parameters  type     description
    # ----------  ----     -----------
    #
    # @qKey       int      get Org. account type key
    #
    function getOrgTypeDesc( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_org_type on key - get type description
      #
      $Qquery = "SELECT * from mp_org_type WHERE g_orgtype_id = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read account types using key." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "g_orgtype_desc" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getOrgTypeDesc
    #
    # ================================================================
    #
    # Functionname :     getAccType( $qkey )
    #
    # Parameters  type     description
    # ----------  ----     -----------
    #
    # @qKey       int      Account type key
    #
    function getAccType( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_acc_types on key - get type
      #
      $Qquery = "SELECT a.user_id,a.account_type,b.acc_type_id,b.acc_type_desc FROM mp_password a LEFT JOIN mp_acc_type b ON(b.acc_type_id=a.account_type) WHERE user_id = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read account types using key." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array( $Qresult ) )
      {
         $qualstr = $Qrow[ "acc_type_desc" ];
      }
      #
      # return
      #
      mysqli_free_result( $Qresult );
      return $qualstr;
    } // end: getAccType
    #
   # ================================================================
   #
   # Functionname :     getContractTypeDesc( $qkey )
   #
   # Parameters  type     description
   # ----------  ----     -----------
   #
    # @qKey       int      get service contract type desc
    #
    function getContractTypeDesc( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_contract_type on key - get description
      #
      $Qquery = "SELECT * from mp_contract_type WHERE c_contract_type_id = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read contract type using key." );
      $total = mysqli_num_rows($Qresult);
      if($total >0){
       #
       # read results of query and return
       #
       while ($Qrow = mysqli_fetch_array($Qresult) )
       {
         $qualstr = $Qrow[ "c_contract_type_desc" ];
       }
      } else {
          $qualstr ="";
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getContractTypeDesc
    #
   # ================================================================
   #
   # Functionname :     getContractTypeKey( $qkey )
   #
   # Parameters  type     description
   # ----------  ----     -----------
   #
    # @qKey       int      get service contract type key
    #
    function getContractTypeKey( $qkey )
    {
     global $link;
      global $mp_script;
      #
      #  read mp_contract_type on desc - get key
      #
      $Qquery = "SELECT * from mp_contract_type WHERE c_contract_type_desc = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read contract type using desc." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "c_contract_type_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getContractTypeKey
    #
   # ================================================================
   # 
   # Functionname :     getChargeStatusDesc( $qkey )
   #
   # Parameters  type     description
   # ----------  ----     -----------
   #
   # @qKey       int      get service contract charge desc
   #
    function getChargeStatusDesc( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_contract_type on key - get description
      #
      $Qquery = "SELECT * from mp_charge_status WHERE cs_id = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read service charge status using key." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "cs_desc" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getChargeStatusDesc
    #
   # ================================================================
   #
   # Functionname :     getChargeStatusKey( $qkey )
   #
   # Parameters  type     description
   # ----------  ----     -----------
   #
   # @qKey       int      get service contract charge key
   #
    function getChargeStatusKey( $qkey )
    {
      global $link;
      global $mp_script;
      #
      #  read mp_contract_type on key - get description
      #
      $Qquery = "SELECT * from mp_charge_status WHERE cs_desc = '$qkey'";
      $Qresult=mysqli_query($link, $Qquery)
          or die( "$mp_script - cannot read service charge status using desc." );
      #
      # read results of query and return
      #
      while ($Qrow = mysqli_fetch_array($Qresult) )
      {
         $qualstr = $Qrow[ "cs_id" ];
      }
      #
      # return
      #
      mysqli_free_result($Qresult);
      return $qualstr;
    } // end: getChargeStatusKey
   #
   # ================================================================   
   # 
   # Functionname :     getContractDetDesc( $qkey )
   #
   # Parameters  type     description
   # ----------  ----     -----------
   #
   # @qKey       int      get service contract detail desc
   #
   function getContractDetDesc( $qkey )
   {
    global $link;
    global $mp_script;
    #
    #  read mp_contract_detail on key - get description
    #
    $Qquery = "SELECT * from mp_contract_detail WHERE c_comp_detail_id = '$qkey'";
    $Qresult=mysqli_query($link, $Qquery)
     or die( "$mp_script - cannot read contract detail component using key." );
    #
    # read results of query and return
    #
    while ($Qrow = mysqli_fetch_array($Qresult) )
    {
      $qualstr = $Qrow[ "c_comp_desc" ];
    }
    #
    # return
    #
    mysqli_free_result($Qresult);
    return $qualstr;
   } // end: getCOntractDetDesc
   #
   # ================================================================
   # Functionname :     getContractDetKey( $qkey )
   #
   # Parameters  type     description
   # ----------  ----     -----------
   #
   # @qKey       int      get service contract detail key
   #
   function getContractDetKey( $qkey )
   {
    global $link;
    global $mp_script;
    #
    #  read mp_contract_detail on desc - get key
    #
    $Qquery = "SELECT * from mp_contract_detail WHERE c_comp_desc = '$qkey'";
    $Qresult=mysqli_query($link, $Qquery)
     or die( "$mp_script - cannot read contract detail component using detail desc." );
    #
    # read results of query and return
    #
    while ($Qrow = mysqli_fetch_array($Qresult) )
    {
      $qualstr = $Qrow[ "c_comp_detail_id" ];
    }
    #
    # return
    #
    mysqli_free_result($Qresult);
    return $qualstr;
   } // end: getContractDetKey
   #
   # ================================================================
   # 
   # Functionname :     getChargePerKey( $qkey )
   #
   # Parameters  type     description
   # ----------  ----     -----------
   #
   # @qKey       int      get charge period key
   #
   function getChargePerKey( $qkey )
   {
    global $link;
    global $mp_script;
    #
    #  read mp_charge_period on desc - get key
    #
    $Qquery = "SELECT * from mp_charge_period WHERE per_desc = '$qkey'";
    $Qresult=mysqli_query($link, $Qquery)
     or die( "$mp_script - cannot read charge periods using desc." );
    #
    # read results of query and return
    #
    while ($Qrow = mysqli_fetch_array($Qresult) )
    {
      $qualstr = $Qrow[ "per_id" ];
    }
    #
    # return
    #
    mysqli_free_result($Qresult);
    return $qualstr;
   } // end: getChargePerKey
   #
   # ================================================================
   # 
   # Functionname :     getChargePerDesc( $qkey )
   #
   # Parameters  type     description
   # ----------  ----     -----------
   #
   # @qKey       int      get charge period desc
   #
   function getChargePerDesc( $qkey )
   {
    global $link;
    global $mp_script;
    #
    #  read mp_charge_period on key - get desc
    #
    $Qquery = "SELECT * from mp_charge_period WHERE per_id = '$qkey'";
    $Qresult=mysqli_query($link, $Qquery)
     or die( "$mp_script - cannot read charge periods using key." );
    #
    # read results of query and return
    #
    while ($Qrow = mysqli_fetch_array($Qresult) )
    {
      $qualstr = $Qrow[ "per_desc" ];
    }
    #
    # return
    #
    mysqli_free_result($Qresult);
    return $qualstr;
   } // end: getChargePerDesc
   #
   # ================================================================
   # 
   # Functionname :     getSupplierKey( $qkey )
   #
   # Parameters  type     description
   # ----------  ----     -----------
   #
   # @qKey       int      get supplier key
   #
   function getSupplierKey( $qkey )
   {
    global $link;
    global $mp_script;
    #
    #  read mp_supplier on desc - get key
    #
    $Qquery = "SELECT * from mp_supplier WHERE s_name = '$qkey'";
    $Qresult=mysqli_query($link, $Qquery)
     or die( "$mp_script - cannot read supplier using desc." );
    #
    # read results of query and return
    #
    while ($Qrow = mysqli_fetch_array($Qresult) )
    {
      $qualstr = $Qrow[ "s_id" ];
    }
    #
    # return
    #
    mysqli_free_result($Qresult);
    return $qualstr;
   } // end: getSupplierKey
   #
   # ================================================================
   # 
   # Functionname :     getSupplierName( $qkey )
   #
   # Parameters  type     description
   # ----------  ----     -----------
   #
   # @qKey       int      get supplier name
   #
   function getSupplierName( $qkey )
   {
    global $link;
    global $mp_script;
    #
    #  read mp_supplier on key - get name
    #
    $Qquery = "SELECT * from mp_supplier WHERE s_id = '$qkey'";
    $Qresult=mysqli_query($link, $Qquery)
     or die( "$mp_script - cannot read supplier using key." );
    #
    # read results of query and return
    #
    while ($Qrow = mysqli_fetch_array($Qresult) )
    {
      $qualstr = $Qrow[ "s_name" ];
    }
    #
    # return
    #
    mysqli_free_result($Qresult);
    return $qualstr;
   } // end: getSupplierName
   #
   # ================================================================ 
   #
   # Functionname :     deleteFile( $data_file_to_delete )
   #                    usage: non - specific
   #
   # Parameters  type      description
   # ----------  ----      -----------
   #
   # @dfile      str       file to delete
   #
   function deleteFile( $data_file_to_delete )
   {
    while( is_file( $data_file_to_delete ) == TRUE )
    {
     #
     # show progress message
     echo '<div id="success-infill">Working ...</div>';
     chmod( $data_file_to_delete, 0666 );
     sleep(1);
     unlink( $data_file_to_delete );
     sleep(1);
     echo '<div id="success-infill">Completed...</div>';
     sleep(1);
    }
   } // end: deleteFile
   #
   # ================================================================
   # Functionname :     getRows( $table_and_$query )
   #                    usage: non - specific
   #
   # Parameters  type      description
   # ----------  ----      -----------
   #
   # @table      str       table to check for any returned rows
   #
   function getRows( $table_and_query)
   {
    global $link;
    $total = mysqli_query($link, "SELECT COUNT(*) FROM $table_and_query" );
    $total = mysqli_fetch_array($total);
    return $total[0];
   } // end: getRows
   #
   # ================================================================
   #
   function testScreen( )
   {
    echo '<div id="centered"><b>TEST TEST TEST TEST</b></div>';
   } // end: testscreen
   #
   # ================================================================
   #
   function using_ie()
   {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $ub = False;
    if(preg_match('/MSIE/i',$u_agent))
    {
     $ub = True;
    }
   #
   return $ub;
   } // end: using_ie
   #
   # ================================================================
   #
   # Functionname :     ie_box( )
   #                    usage: non - specific
   #
   # Parameters  type      description
   # ----------  ----      -----------
   #
   # @table      str       check for IE browser - non 3C compliant to send message
   #
   function ie_box() {
   if (using_ie()) {
   echo '<div class="center" style="text-align: center;">' ."\n";
   echo '<span id="einfo" style="text-align: center; color: #FF0000;">' ."\n";
   echo 'PMS is not designed for Internet Explorer. If you want to use this system as intended please use a standards compliant browser, such as <a href="http://www.google.com/chrome">Google Chrome</a>.' ."\n";
   echo '</span>' ."\n";
   echo '</div>' ."\n";
   #
   return;
   }
  } // end: ie_box
 #
 # ================================================================
 #
 # Functionname :     getPrinter( $name )
 #                    usage: non - specific
 #
 # Parameters  type      description
 # ----------  ----      -----------
 #
 # @name       str       printer name
 #
function getPrinter( $SharedPrinterName ) 
{
 global $REMOTE_ADDR;
 $host  =  getHostByAddr( $REMOTE_ADDR );
 return "\\\\".$host."\\".$SharedPrinterName;
} // end: getPrinter
#
# ================================================================
# Code Versions : Date        Versions                      Description
# ------------- : ----        --------                      ----------- 
# 	        : Apr 2016    V1.5 Codebase                 CSS2 version - YAML
#
##############################################################################
#
# Functionname :     getFormInput( $parLabel, $disPrompt, $parName, $inpSize, $disHint, $chkDiv )
#                    usage: validated form input field with check div and char count display
#
# Parameters  type      description
# ----------  ----      -----------
#
# @parLabel   str       label for datum
# @disPrompt  str       displayed prompt
# @parName    str       parameter name
# @inpSize    dec       input size
# @disHint    str       hint for input
# @chkDiv     str       div name for char count display
#
function getFormInput( $parLabel, $disPrompt, $parName, $inpSize, $disHint, $chkDiv )
{
 printf ( "<div class=\"type-text columnar\"><label for=\"%s\">%s</label><input type=\"text\" name=\"%s\" id=\"%s\" size=\"%d\" title=\"%s\" onKeyDown=textCounter(this,\"%s\",%d) onKeyUp=textCounter(this,\"%s\",%d) onFocus=textCounter(this,\"%s\",%d)></div>\n", $parLabel,$disPrompt,$chkDiv,$chkDiv,$inpSize,$disHint,$chkDiv,$inpSize,$chkDiv,$inpSize,$chkDiv,$inpSize );
 printf ( "<div id=\"%s\" class=\"progress\"><script>textCounter(document.getElementById(\"%s\"),\"%s\",%d)</script></div>\n", $chkDiv,$chkDiv,$chkDiv,$inpSize );
}
#
# snippet for checking a field character count
#
#echo '<div class="type-text columnar"><label for="First name">Enter first name</label><input type="text" name="parFname" id="parFname" size="35" title="Enter first name." onKeyDown=textCounter(this,"parfname",35) onKeyUp=textCounter(this,"parfname",35) onFocus=textCounter(this,"parfname",35)></div>';
#echo '<div id="parfname" class="progress"><script>textCounter(document.getElementById("parfname"),"parfname",35)</script></div>';
#
#
#
?>