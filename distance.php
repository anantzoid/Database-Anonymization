<?php
//Certain parts require calculation of distance between two recored or a record and a cluster.This is deon over here using a given formula.
ini_set('memory_limit', '512M');
$con = mysql_connect("localhost", "root", "");
mysql_select_db("anon",$con);

//This is given in the sample data
//********Need to get automated
$nattr = array('Work');
//This is a herirachial structure of the attributes in a binary tree form.
$cattr = array("Education"=>array(0=>'Any',1=>'Secondary',2=>'University',3=>'Junior',4=>'Senior',5=>'Bachelors',6=>'Grad School',7=>'9th',8=>'10th',9=>'11th',10=>'12th',13=>'Masters',14=>'Doctorate'),"Sex"=>array(0=>'Any',1=>'Male',2=>'Female'));


$maxt =  array();
$mint = array();
$height = array();
  	
function retreive()
{
	global $nattr,$cattr,$maxt,$mint,$height;
	
	foreach($nattr as $na)
	{
		$m = mysql_query("select max(".$na.") from table1");
		$max = mysql_fetch_array($m);
		$maxt[$na] = $max["max(".$na.")"];
		$min = mysql_query("select min(".$na.") from table1");
		$min = mysql_fetch_array($min);
		$mint[$na] = $min["min(".$na.")"];
		
	}	

	//calculate height of tree

	foreach($cattr as $c=>$b)
	{	
		end($cattr[$c]);
		$height[$c] = calcHeight(key($cattr[$c]),0);		
	}

}
function dist($r,$p)
{
	global $maxt,$mint,$K,$nattr,$cattr,$height;
	$Dist = array();
	for($i = 0;$i < count($p); $i++)
	{
		$first = 0;
		$second = 0;
		
		mysql_query("CREATE TABLE `dist` (
	  `id` int(4) NOT NULL AUTO_INCREMENT,
	  `Education` varchar(20) NOT NULL,
	  `Sex` varchar(1) NOT NULL,
	  `Work` int(3) NOT NULL,
	  `Disease` varchar(20) NOT NULL,
	  `Salary` int(6) NOT NULL,
	  PRIMARY KEY (`id`)
		)");
		
		mysql_query("insert into dist select * from clus".$p[$i]);
		mysql_query("insert into dist values(".$r['sl'].",".$r['Education'].",".$r['Sex'].",".$r['Work'].",".$r['Disease'].",".$r['Salary'].")");
		
		foreach($nattr as $na)
		{
			$max = mysql_query("select max(".$na.") from dist");
			$nmax = mysql_fetch_array($max);
			
			$min = mysql_query("select min(".$na.") from dist");
			$nmin = mysql_fetch_array($min);
			$first = $first+(($nmax[0]-$nmin[0])/($maxt[$na]-$mint[$na]));
			
		}
		mysql_query("delete * from dist");
		mysql_query("drop table dist");
		foreach($cattr as $c=>$ca)
		{
			$new = array();$ar = array();
			
			$sP = mysql_query("select distinct(".$c.") from clus".$p[$i]);
			while($SP=mysql_fetch_array($sP))
				$ar[] = $SP;
			
			if(count($new)==1 && strcmp($new[0],$x[$c])==0)
				$second = 0;
			else
			{
				foreach($ar as $x){
					array_push($new,$x[$c]);
				}
				
				//r is an array
				
				$ances = recur($new,array_search($r[$c],$ca),$ca);
						//find height till parent node = root
				$subheight = calcHeight(array_search($r[$c],$ca),$ances);
				$second = $second+($subheight/$height[$c]);	
			}
		}
		$Dist[$p[$i]] = $first + $second;
		
	}
	
	return $Dist;
	
}
function calcHeight($ind,$root)
{
global $cattr;

		//end($cattr[$c]);
		$arr = array();
		$arr = parents($ind,$root);
		$ht = count($arr);
		return $ht;
}

function recur($new,$nxt,$ca)
{
	for($i = 0;$i<count($new);$i++)
		$nxt=lca(array_search($new[$i],$ca),$nxt);//index on tree
	
	return $nxt;
}

//to calculate the all the parents
function parents($l,$root)
{

$arr=array();
	while($l !=$root){
	if($l % 2 ==0)
		$l = ($l-2)/2;
	else 			
		$l = ($l-1)/2;
	
	array_push($arr,$l);		
		}
	return $arr; 
}
//to calculate the least common ancestor
function lca($n,$m)
{

	$LCA = 0;
	$arr1 = array();
	$arr2 = array();
	 
	$arr1 = parents($n,0);
	
	$arr2 = parents($m,0);
	
	if(count($arr1) > count($arr2))
		$limit = count($arr2);
	else
		$limit = count($arr1);
		
	for($i =0;$i<$limit;$i++)
	{
		if($arr1[$i] == $arr2[$i])
		{
			$LCA = $arr1[$i];
			break;
		}
	}
	return $LCA;//this is the index of the element in the tree
	
}


?>
