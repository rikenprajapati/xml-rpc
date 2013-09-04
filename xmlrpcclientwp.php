<?php


class XMLRPClientWordPress
{

	var $XMLRPCURL = "http://qknols.com/ontimeaccounting.ca/xmlrpc.php";
	var $UserName  = "admin";
	var $PassWord = "Grs34dss";
	
	// constructor
    public function __construct($xmlrpcurl, $username, $password) 
	{
        $this->XMLRPCURL = $xmlrpcurl;
		$this->UserName  = $username;
		$this->PassWord = $password;
       
    }
	function send_request($requestname, $params) 
	{
		$request = xmlrpc_encode_request($requestname, $params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_URL, $this->XMLRPCURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		$results = curl_exec($ch);
		curl_close($ch);
		return $results;
	}
	
	function create_post($title,$body,$category,$keywords='',$encoding='UTF-8')
	{
		$title = htmlentities($title,ENT_NOQUOTES,$encoding);
		$keywords = htmlentities($keywords,ENT_NOQUOTES,$encoding);
	 
		$content = array(
			'title'=>$title,
			'description'=>$body,
			'mt_allow_comments'=>0,  // 1 to allow comments
			'mt_allow_pings'=>0,  // 1 to allow trackbacks
			'post_type'=>'post',
			'mt_keywords'=>$keywords,
			'categories'=>array($category)
		);
		$params = array(0,$this->UserName,$this->PassWord,$content,true);
		
		return $this->send_request('metaWeblog.newPost',$params);
		
	}
	
	function create_page($title,$body,$encoding='UTF-8')
	{
		$title = htmlentities($title,ENT_NOQUOTES,$encoding);

		$content = array(
			'title'=>$title,
			'description'=>$body
		);
		$params = array(0,$this->UserName,$this->PassWord,$content,true);

		return $this->send_request('wp.newPage',$params);
	}

	function display_authors()
	{
		$params = array(0,$this->UserName,$this->PassWord);
		return $this->send_request('wp.getAuthors',$params);
	}
	
	function sayHello()
	{
		$params = array();
		return $this->send_request('demo.sayHello',$params);
	}

}

$objXMLRPClientWordPress = new XMLRPClientWordPress("http://qknols.com/xml-rpc/xmlrpc.php" , "admin" , "Admin@123");

echo '<table border="1" cellpadding="10">';

echo '<tr>';
echo '<td>Request Name</td>';
echo '<td>Result</td>';
echo '</tr>';

/*echo '<tr>';
echo '<td>demo.sayHello</td>';
echo '<td>'.$objXMLRPClientWordPress->sayHello().'</td>';
echo '</tr>';*/

echo '<tr>';
echo '<td>metaWeblog.newPost</td>';
echo '<td>'.$objXMLRPClientWordPress->create_post('Hello WordPress XML-RPC', 'This is the content of post done via XML-RPC','').'</td>';
echo '</tr>';

/*echo '<tr>';
echo '<td>wp.newPage</td>';
echo '<td>'.$objXMLRPClientWordPress->create_page('WordPress XML-RPC page', 'This is the content of First Page done via XML-RPC').'</td>';
echo '</tr>';


echo '<tr>';
echo '<td>wp.getAuthors</td>';
echo '<td>'. $objXMLRPClientWordPress->display_authors().'</td>';
echo '</tr>';
*/
echo '</table>';



?>