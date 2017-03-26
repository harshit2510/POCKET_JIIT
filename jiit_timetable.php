 <?php
       
function connect($cookie,$username,$password){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://webkiosk.jiit.ac.in/CommonFiles/UserAction.jsp');
  curl_setopt($ch, CURLOPT_NOBODY, true);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "x=&txtInst=Institute&InstCode=JIIT&txtuType=Member+Type&UserType=S&txtCode=Enrollment+No&MemberCode=$username&DOB=DOB&DATE1=01%2F01%2F1999&txtPin=Password%2FPin&Password=$password&BTNSubmit=Submit");
  $output = curl_exec($ch);
  curl_close($ch);
//echo "momo".$output->plaintext;
  return $output;
}

function getStudenttimetable($cookie){
	$ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://webkiosk.jiit.ac.in/StudentFiles/Academic/StudSubjectTaken.jsp');
 curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $output = curl_exec($ch);
//echo "lolo".$output->plaintext;
  $start = stripos($output, "<body");
     $end = stripos($output, "</body");
	 $output = substr($output,$start,$end-$start);
  curl_close($ch);
//echo "hoho".$output;
  return $output;
}
 
		session_start();
        if(isset($_SESSION['user']) && false){
//echo "papa";
            $cookie = $_SESSION['user'];
        }
        else{
//echo "mummy";
            $username ="14103120";
            $password ="075120RA";

            $cookie = $username;
            $_SESSION['user'] = $username;

            connect($cookie,$username,$password);
        }
		
		$attendance =  getStudenttimetable($cookie); 
		 $response=array();
        include_once("simple_html_dom.php");
        $html = str_get_html($attendance);
		//echo $html;
		 $head = $html->find('table');
		 $rows = $head[2]->find('tr');
		 $count = count($rows);
		 for($i=1;$i<$count-1;$i++){
            $cols = $rows[$i]->find('td');
			array_push($response,array("Name" => $cols[1]->plaintext));
 
		 }
		 echo json_encode(array("server_response"=>$response));
		 
		 
		 
		 
		 
		?>