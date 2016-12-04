<?php
  # ------------------------------
  # variant:  pms
  # file:     MyPlus_db_connect.inc.php
  # ver:      MyPlus Core V1.5
  # extract:  core database connect functions
  #           the user will have logged in via mpLogin.php
  #
  # author:    John Bunyan - all rights reserved
  # Copyright: escapefromuk
  # Date:      Nov 2014
  # ------------------------------
  #
  function mpSiteConnect()
  {
  #
  global $MP_production_site;
  #
  # persistant connect  - for production site
  #
  if ( $MP_production_site ) {
    $link = mysqli_connect( "localhost", "root", "KrYpToNiTe" );
    if ( $link && mysqli_select_db( $link, "pms" ) ) {
     return ( $link );
    } else {
     return (FALSE);
    }
  } else {
   #
   #  pre-prod
   #
   $link = mysqli_connect( "localhost", "root", "KrYpToNiTe" );
   if ( $link && mysqli_select_db ( $link, "pms-preprod" ) ) {
    return ( $link );
   } else {
    return (FALSE);
   }
  }
 }
?>