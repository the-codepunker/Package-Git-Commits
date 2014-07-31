<?php

echo "<pre>";
$res = array();
//get details from last commit
exec("git log --name-only --max-count=4", $res);

//keep only files names, not commit details
foreach($res as $k=>$string)
{
	if(!is_file($string))
		unset($res[$k]);
}

//recount array
array_values($res);

echo "<h1>Files found in git log result:</h1>";
print_r($res);
echo "<hr />";

//specify the folder where your packages are stored
$packagesfolder = "packages";
//specify the folder name for this particular package
$folder = "package.".date("m.d.Y.h.i", time());

// if the packages folder doesn't exist create one
if(!is_dir($packagesfolder))
{
	mkdir($packagesfolder);
	echo "<h1>Packages folder not found - created a new one</h1>";
	echo "<hr />";
}

// if create the folder for this particular package
if(!is_dir($packagesfolder.'/'.$folder))
{
	mkdir($packagesfolder.'/'.$folder);
	echo "<h1>Current package folder not found - created a new one</h1>";
	echo "<hr />";
}

//copy the files to the package folder and keep the entire folder structure 
//(eg: if a file is in ... sub_folder/sub_sub_folder/file.php ... it will be copied to packagefolder/sub_sub_folder/sub_sub_folder/file.php )

foreach($res as $file)
	exec('cp --parents '.$file.' '.$packagesfolder.'/'.$folder.'/' );

	echo "<h1>Files copied</h1>";
	echo "<hr />";

//navigate to the packagefolder
chdir($packagesfolder.'/'.$folder);
//archive now


exec( 'tar -cf '.$folder.'.tar *' );

	echo "<h1>Files archived</h1>";
	echo "<hr />";
	
exec( 'mv '.$folder.'.tar ../' );

	echo "<h1>tar file moved to the packages folder</h1>";
	echo "<hr />";

//return to the packages folder
chdir( '..' );
exec('rm -rf '.$folder.'/');

	echo "<h1>package folder removed</h1>";
	echo "<hr />";

die("done");