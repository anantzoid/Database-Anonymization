<?php
//This clusters the data according to quasi-identifier attribute($k) and produces a k-anonimized clusters of the data
//set T of n records; the value k for k-anonymity and the value l for l-diversity.
include 'declare.php';
require_once('distance.php');
$con = mysql_connect("localhost", "root", "");
mysql_select_db("anon",$con);


retreive();
//sort by quasi-identifier(education)
mysql_query("select * from table1 order by Education asc");
  


for($i=1;$i<=$K;$i++)
{
	mysql_query("CREATE TABLE `clus".$i."` (
	  `id` int(4) NOT NULL AUTO_INCREMENT,
	  `Education` varchar(20) NOT NULL,
	  `Sex` varchar(1) NOT NULL,
	  `Work` int(3) NOT NULL,
	  `Disease` varchar(20) NOT NULL,
	  `Salary` int(6) NOT NULL,
	  PRIMARY KEY (`id`)
	)");
		$p[$i-1] = $i;
		echo 'Creating cluster'.$i.'<br/>';
}

//select K records randomly and remove it from the table
for($i=1;$i<=$K;$i++)
{
	//Allot each record to a P ds
	mysql_query("insert into clus".$i." select * from table1 order by RAND() limit 1");
	$set_r = mysql_query("select id from clus".$i);
	$pk = mysql_fetch_array($set_r);
	mysql_query("delete from table1 where sl=".$pk['id']);
}

do
{
	//retrieve record r
	$set = mysql_query("select * from table1 LIMIT 1");
	$r=mysql_fetch_array($set);
	//order Ps accdn to distance from r
	array_multisort(dist($r,$p),$p);

	$j=0;
	$i=$p[$j];
	$flag=0;
		while($i<$K && $flag==0)
		{
			$ar = array();$new = array();
			
			//allot distinct senstive values of Pi to s(Pi)
			$sP = mysql_query("select Disease from clus".$i);
			while($SP=mysql_fetch_array($sP))
				$ar[] = $SP;
			foreach($ar as $x){
				array_push($new,$x["Disease"]);
			}
			
			//	allot senstive values of r to s(r)
			$sR = $r['Disease'];
			$NP = mysql_query("select COUNT(*) as num from clus".$i);
			$nP = mysql_fetch_assoc($NP);

			if(($nP['num'] < $k) || (!in_array($sR,$new) && sizeof($new) < $l))
			{	//add r to Pi
				mysql_query("insert into clus".$i." select * from table1 limit 1");
				//update centroid of Pi
				echo 'entry added to clus'.$i.'<br/>';
				$Cent = mysql_query("select COUNT(*) as no from clus".$i);
				$CEnt = mysql_fetch_assoc($Cent);
				$cent = floor($CEnt['no']/2);
				$flag=1;
			}	
			else 
				$i=$p[$j++];
		}
		if($flag==0)
		{
			//ADD R TO NEAREST CLUSTER
				mysql_query("insert into clus".$p[0]." select * from table1 limit 1");	
		}
	//remove r from table	
	mysql_query("delete from table1 where sl=".$r['sl']);	
	$se = mysql_query("select * from table1 LIMIT 1");
	$s=mysql_fetch_array($se);
}while(!is_null($s['sl']));

?>	
