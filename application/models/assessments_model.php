<?php if ( ! defined('BASEPATH')) die('No direct script access allowed');

class Assessments_model extends CI_Model {
	
	function getAllRecords($condition=FALSE)
	{
		if ($condition != FALSE)
		{
			$this->db->where($condition);	
		}
		
		$data = $this->db->get('assessments');
		
		return $data;
	}

	function getSomeRecords($fields,$condition=FALSE)
	{
		foreach ($fields as $field)
		{
			$this->db->select($field);
		}
		if ($condition != FALSE)
		{
			$this->db->where($condition);	
		}
		
		$data = $this->db->get('assessments');
		
		return $data;
	}
    
    function insertRecords($data)
    {
        
        return $this->db->insert('assessments',$data);
    }
	
	function updateRecord($data, $assessmentlang)
	{
        $query = "UPDATE ".$this->db->dbprefix('assessments')."
	      SET scope=?,
	      gid=?,
	      minimum=?,
	      maximum=?,
	      name=?,
	      message=?
	      WHERE language='$assessmentlang' and id=".sanitize_int($data['id']);
		$fields=array($data['scope'], $data['gid'], sanitize_signedint($data['minimum']),
					sanitize_signedint($data['maximum']), $data['name_'.$assessmentlang],
					$data['assessmentmessage_'.$assessmentlang]);
		return $this->db->query($query,$fields);
	}
	
	function dropRecord($id)
	{
		$query = "DELETE FROM ".$this->db->dbprefix('assessments')." WHERE id=".sanitize_int($id);
		return $this->db->query($query);
	}

	function getAssessments($surveyid)
	{
	    $query = "SELECT id, sid, scope, gid, minimum, maximum, name, message
				  FROM ".$this->db->dbprefix('assessments')."
				  WHERE sid=? and language=?
				  ORDER BY scope, gid";
	    $result=$this->db->query($query,array($surveyid,$this->config->item("baselang")));
	    $output=array();
	    foreach($result->result_array() as $row) {
	        $output[]=$row;
	    }
	    return $output;
	}

}