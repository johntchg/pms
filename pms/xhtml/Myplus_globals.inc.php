<?php
# ------------------------------
  # variant:  pms
  # File: 	  MyPlus_globals.inc.php
  # Ver:	    V1.5 Core 
  # Extract:  core global definitions
  #
  # Author:	    John Bunyan  - all rights reserved
  # Copyright:  escapefromuk
  # Date:	    Nov 2014
  # -------------------------------
  #
  global $link;                     # db connection
  global $infoMess;                 # screen info line
  $errors = array();                # Initialize error array.
  global $MP_production_site;	      # dev or production
  global $MP_lang;	                # default natural language
  global $MP_ssite;	                # site name 
  global $MP_ssitedesc;             # site description
  global $MP_scontact;              # contact email ie, info@yoursite.com
  global $MP_stitle;                # site title
  global $MP_sheader;               # site header
  global $MP_ssheet;                # site CSS style sheet
  global $MP_ptype;	                # default page type
  global $MP_pdef_col;              # default page background colour
  global $MP_col1;                  # alternating display row colour 1
  global $MP_col2;                  # alternating display row colour 2
  global $MP_fades;                 # page transition fading
  global $MP_displayCP;             # toggle copyright notices
  global $MP_complex_button;        # toggle 3 part or single part back button
  global $MP_DefInfo;               # default site info - demo pages
  global $MP_DefInfo2;              # 2nd line site info
  global $MP_ver;                   # distribution version
  global $MP_debug;                 # set debug messages on/off
  global $MP_safe;                  # safe mode on/off
  global $mp_overL;                 # overlib copyright on/off
  global $MP_curr;                  # default currancy
  #
  define ( "MP_TRUE", 1 );          # set TRUE as 1
  define ( "MP_FALSE", 0 );         # set FALSE as 0
  #
  # define the globals here
  #
  $MP_production_site	      = MP_TRUE;                                # we are always production 
  $MP_lang                  = "EN";	                                  # $lang EN by default in live version
  $MP_ssite                 = "localhost";                            # website name
  $MP_ssitedesc             = "PMS";                                  # site description
  $MP_scontact              = "info@localhost.com";                   # site contact email
  $MP_stitle                = "PMS";                                  # site title
  $MP_sheader               = "localhost";                            # site header
  $MP_ssheet                = "../../../styles/pms_admin_style.css";  # site default style sheet
  $MP_ptype                 = "c";                                    # page type - site default = common
  $MP_pdef_col              = "#ffffff";                              # default background colour - white
  $MP_col1                  = "#eeeeee";                              # first row colour
  $MP_col2                  = "#ffffff";                              # alternating colour
  $MP_fades                 = 0;                                      # set to false = no fades on transition
  $MP_displayCP             = 1;                                      # set display copyright to true
  $MP_complex_button        = 0;                                      # display complex button - set to false for a single back button image
  $MP_DefInfo               = "PMS";                                  # set demo info
  $MP_ver                   = "PMS";                                  # set distribution version
  $MP_debug                 = 1;                                      # set debug messages on
  $MP_safe                  = 1;                                      # set safemode ( no deletes ) on ( default )
  $MP_overL                 = 0;                                      # set overlib copyright off
  $MP_curr                  = "GBP";                                  # set default currency code
  #
  define ( "MPx_DISPLAY", 10 );                                       # number of records to display per page
  #
?>