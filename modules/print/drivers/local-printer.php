<?php

/*©lgpl*************************************************************************
*                                                                              *
* This file is part of FRIEND UNIFYING PLATFORM.                               *
* Copyright (c) Friend Software Labs AS. All rights reserved.                  *
*                                                                              *
* Licensed under the Source EULA. Please refer to the copy of the GNU Lesser   *
* General Public License, found in the file license_lgpl.txt.                  *
*                                                                              *
*****************************************************************************©*/

// TODO: Support file conversions
if( substr( $args->args->file, 0, 7 ) == 'http://' || substr( $args->args->file, 0, 8 ) == 'https://' )
{
	$fileContent = file_get_contents( $args->args->file );
	$result = true;
}
else
{
	$file = new File( $args->args->file );
	$result = $file->Load();
	$fileContent =& $file->_content;
}

if( $result )
{
	$ftpl = 'friend_print_file';
	$fn = $ftpl;
	$num = 1;
	while( file_exists( '/tmp/' . $fn ) )
	{
		$fn = $ftpl . '_' . ( $num++ );
	}
	if( $f = fopen( '/tmp/' . $fn, 'w+' ) )
	{
		fwrite( $f, $fileContent );
		fclose( $f );
		
		$response = shell_exec( 'lp /tmp/' . $fn );
		
		// Clean up
		unlink( '/tmp/' . $fn );
		
		die( 'ok<!--separate-->{"response":1,"message":"Document was sent to printer."}' );
	}
	die( 'fail<!--separate-->{"response":-1,"message":"Missing parameters."}' );
}

die( 'fail<!--separate-->{"response":-1,"message":"Could not load print file."}' );

?>
