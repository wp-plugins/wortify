<?php

define('PROTECTOR_POSTCOMMON_POST_REGISTER_MORATORIUM' , 60 ) ; // minutes

class protector_postcommon_post_register_moratorium extends ProtectorFilterAbstract {

	function execute()
	{
		global $wortifyUser ;

		if( ! is_object( $wortifyUser ) ) {
			return true ;
		}

		$moratorium_result = intval( ( $wortifyUser->getVar('user_regdate') + PROTECTOR_POSTCOMMON_POST_REGISTER_MORATORIUM * 60 - time() ) / 60 ) ;
		if( $moratorium_result > 0 ) {
			if( preg_match( '#(https?\:|\[\/url\]|www\.)#' , serialize( $_POST ) ) ) {
				printf( _MD_PROTECTOR_FMT_REGISTER_MORATORIUM , $moratorium_result ) ;
				exit ;
			}
		}
	}

}

?>