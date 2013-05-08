<?php
//This stage makes up the size of the cluster to have minimum k elements each
//input:clusters
//op:new set of clusters
include 'declare.php';
$con = mysql_connect("localhost", "root", "");
mysql_select_db("anon",$con);


$S=$U=$V=array();
$a=$b=$c=-1;
for($i=1;$i<=$K;$i++)
{
  $C = mysql_query("select COUNT(*) as numC from clus".$i);
	$cC = mysql_fetch_assoc($C);
	if($cC['numC'] <= $k/2)
		{$a=$a+1;$S[$a] = $i;}
	else if($cC['numC'] > $k/2 && $cC['numC'] < $k)
		{$b=$b+1;$U[$b] = $i;}
	else if($cC['numC'] >= $k)
		{$c=$c+1;$V[$c] = $i;}
		
				
}

$flag = 0;
$emp = !empty($S); 
$i = -1;
while($emp==1 && $flag==0)
{	
	//while($S[++$i]==""){$P = $S[$i];}
	$P = $S[current(array_keys($S))];
	$PC = mysql_query("select * from clus".$P." LIMIT 1");
	$pc=mysql_fetch_array($PC);
	$em = !empty($U); 	
	$j = 0;
	while($em==1 && (!is_null($pc['id'])))
	{
		
		//select Q CLOSEST to P
		array_multisort(dist($pc,$U),$U);
		$Q = $U[0];
		$CQ = mysql_query("select COUNT(*) as numQ from clus".$Q);
		$cQ = mysql_fetch_assoc($CQ);
//		var_dump($CQ);
		$CP = mysql_query("select COUNT(*) as numP from clus".$P);
		$cP = mysql_fetch_assoc($CP);
	//	var_dump($CP);
		if($k-$cQ['numQ'] > $cP['numP'])
			mysql_query("insert into clus".$Q." select * from clus".$P." LIMIT ".$cP['numP']);
		else{	
			mysql_query("insert into clus".$Q." select * from clus".$P." LIMIT ".$k-$cQ['numQ']);
			unset($U[$j]);
			$j++;
			$V[$c['numC']++]=$Q;
			mysql_query("delete from clus".$P." where id in (select id from clus".$P." LIMIT".$k-$cQ['numQ'].")");
		}
		$em = !empty($U);
	}
	if(empty($U)){
		$flag = 1;
	}
	//remove P form S
	unset($S[current(array_keys($S))]);
	$emp = !empty($S);
}
if($flag==0)
{

	mysql_query("create table newS like clus".$i);
	mysql_query("insert into newS select * fron clus".$i);
	$nS = mysql_query("select * from newS limit 1");
	$ns = mysql_fetch_array($nS);
	/*for($i=0;$i<=$a;$i++)
	{
		$sS = mysql_query("select * from clus".$i);
		array_push($Ss,mysql_fetch_array($sS));
	}	
	$f = !empty($Ss);*/
	while(!is_null($ns['sl']))
	{
		//--------add r to closest cluster in V
		mysql_query("insert into clus".$V[current(array_keys($V))]." values select * from newS limit 1");	
		$ns = mysql_fetch_array(mysql_query("select * from newS limit 1"));
		}
}else{

	$g = !empty($U);
	while($g==1)
	{
		$Q = $U[current(array_keys($U))];
		$CQ = mysql_query("select COUNT(*) as numQ from clus".$Q);
		$cQ = mysql_fetch_assoc($CQ);
		while($cQ['numQ'] < $k)
		{
			$P = 0;
			for($j=0;$j<=$c;$j++)
			{
				$CV = mysql_query("select COUNT(*) as numV from clus".$j);
				$cV = mysql_fetch_assoc($CV);
				if($cV['numV'] > $k )
				{	$P=$V[$j];//LOOKOUT FOR SCOPE HERE
					break;
				}
			}
			/*
			//----------sort records in P by distance from centroid
			$new = array();
			for($i = 0;$i < count($P);$i++)
			{
				$arr = array();
				$ce = mysql_query("select Work from clus".$P[$i]);
				while($Ce = mysql_fetch_assoc($ce))
					$arr = $Ce;
					
				foreach($arr as $ar)
					array_push($new,$ar['Work']);
			}
					
		//finding centroid
		$cnt = count($new);
			if($cnt%2==0)
				$cent = ($new[$cnt/2]+($new[($cnt/2)+1]))/2;
			else
				$cent = $new[floor($cnt/2)+1];
				
				*/
			
			do{
				//--------r is the row farthest from centroid
				//remove r from P and add to Q
				mysql_query("insert into clus".$Q." select * from clus".$P." LIMIT 1");
				mysql_query("delete from clus".$P." LIMIT 1");
				
				$cP = mysql_query("select COUNT(*) as numP from clus".$P);
				$cp = nysql_fetch_assoc($cP);
			}while($cp['numP'] > $k);	
		}
		//remove Q from U and add to V
		$c=$c+1;
		$V[$c]=$Q;
		unset($U[current(array_keys($U))]);
		$g = !empty($U);
	}
}
echo 'Set of new clusters<br/>';
var_dump($S);
echo '<br/>';
var_dump($U);
echo '<br/>';
var_dump($V);
echo '<br/>';
