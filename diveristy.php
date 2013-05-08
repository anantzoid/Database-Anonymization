<?php
//This produces the l-diversified database taking $l as the p-sensitive attribute
require_once('distance.php');
function ldiv($ii,$jj)
{
$con = mysql_connect("localhost","root","");
mysql_select_db("anon",$con);
global $P,$q,$l,$at,$attr;
  foreach($ii as $i)
	{
		if((count(array_unique(range($P["d"][0],$P["d"][$q]))) == 1) && $P["d"][0]==$l)
			break;
		   
		foreach($jj as $j)
		{
			$F = array();
			foreach($at as $edu)
			{
				if($P[$edu][$i]==0 && $P[$edu][$j]>1)
					array_push($F,$edu);
			}
			//mi:div required
			$mi = min($l-$P["d"][$i],sizeof($F));
			//----CLOSEST to tuples?
		
			if($mi)
			{
				$co=0;
				foreach($F as $f)
				{
					if($mi>=$co)
					{
						$P[$f][$j]--;
						$P[$f][$i]++;					
						$co++;
						$res =mysql_query("select * from clus".$P["i"][$j]." where ".$attr."='".$f."' LIMIT 1");
						$Res = mysql_fetch_array($res);
						mysql_query("insert into clus".$P["i"][$i]." select * from clus".$P["i"][$j]." where id=".$Res['id']);					
						mysql_query("delete from clus".$P["i"][$j]." where id=".$Res['id']);


					}
				}
				$co=0;
				foreach($at as $edu)
				{
					if($P[$edu][$i]>1 && $co<=$mi)
					{
						$P[$edu][$i]--;
						$P[$edu][$j]++;
						$co++;
						$res =mysql_query("select * from clus".$P["i"][$i]." where ".$attr."='".$edu."' LIMIT 1");
						$Res = mysql_fetch_array($res);
						mysql_query("insert into clus".$P["i"][$j]." select * from clus".$P["i"][$i]." where id=".$Res['id']);					
						mysql_query("delete from clus".$P["i"][$i]." where id=".$Res['id']);					
					}
				}
				$P["d"][$i]+=$mi;
				$di = mysql_query("select count(distinct(".$attr.")) as nu from clus".$P["i"][$j]);
				$dd = mysql_fetch_assoc($di);
				$P["d"][$j] = $dd["nu"];
			}
		}
	}
		
}	

function somefunc()
{

global $K,$P,$l;
$S = array();
for($i=0;$i<$K;$i++)
{
	if($P["d"][$i] < $l)
		array_push($S,$i);
}

if(count($S)>1)
{
	$c = array();
	array_push($c,$S[0]);
	unset($S[0]);
	ldiv($c,$S);
	
	 
}
return count($S); 

}
?>
<?php

$con = mysql_connect("localhost","root","");
mysql_select_db("anon",$con);

include 'declare.php';
$P = array();
$attr = 'Disease';
$at = array('Flu','Bronchitis','Cholera');

//initializing matrix P
foreach($at as $dt)
	$P[$dt] = array_fill(1,$K,0);

$P["i"] = array_fill(1,$K,0);
$P["d"] = array_fill(1,$K,0);	

for($i = 1;$i <= $K;$i++)
{
//-------check for null cluster

	$di = mysql_query("select count(distinct(".$attr.")) as nu from clus".$i);
	$dd = mysql_fetch_assoc($di);
	$P["i"][$i] = $i;
	$P["d"][$i] = $dd["nu"];
	
	
	$ar=array();$neo=array();
	$h = mysql_query("select ".$attr." from clus".$i);
	while($H = mysql_fetch_array($h))
		$ar[] = $H;
	
	foreach($ar as $x)
		array_push($neo,$x[$attr]);
	
	foreach($neo as $r)
	{		
		foreach($at as $ed)
		{
			if($r==$ed)
				$P[$ed][$i]++;
		}
	}
}	

//---------ORDER: This can be automated
array_multisort($P["d"],$P["i"],$P[$at[0]],$P[$at[1]],$P[$at[2]]);//index goes to 0


$q=current(array_keys($P["d"],min($P["d"])));
for($i=0;$i<$K;$i++)//fix this after sort index:0
{
	if($i > $q && ($P["d"][$i] < $l))
		$q = $i;
}

$ii = array();$jj = array();
for($i = 0;$i <= $q; $i++)
	array_push($ii,$i);

for($i = $q+1;$i < $K; $i++)
	array_push($jj,$i);

ldiv($ii,$jj);
echo "<br/>";


foreach($P as $p)
{
	print_r($p);
	echo "<br/>";
}

$val = 0;
do{
$val = somefunc();
var_dump($val);
}while($val>1);

if($val==1)
{	$d1 = 0;
	for($i=0;$i<$K;$i++)
	{
	if($P["d"][$i] < $l)
		$d1 = $i;
	}
	$arr = array();
	for($i=0;$i<$K;$i++)
	{
	//CLOSEST CLUSTER
		if($P["d"][$i] >= $l)
		{
			mysql_query("insert into clus".$P["i"][$i]." select * from clus".$P["i"][$d1]);
			mysql_query("drop table clus".$P["i"][$d1]);
		}
	}
	
}



echo "<br/>Final<br/>";
foreach($P as $o=>$p)
{
	print_r($p);
	echo "<br/>";
	}

?>
