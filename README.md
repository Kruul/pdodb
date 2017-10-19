Pdodb - simple PDO wrapper

sample:

$db=new Pdodb($dbconf,array(PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_CASE=> PDO::CASE_UPPER));
$f=$db->query($sql);
while($row=$f->Fetch()){
    $dataArray[]=$row;
}

print_r($dataArray);
