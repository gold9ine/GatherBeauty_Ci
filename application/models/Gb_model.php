<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gb_model extends MY_Model{
	function __construct(){
		parent::__construct();
	}

	public function gets(){
		return $this->db->query('select * from topic')->result();
	}

	public function get($topic_id){
		$this->db->select('id');
		$this->db->select('title');
		$this->db->select('description');
		$this->db->select('UNIX_TIMESTAMP(created) AS created');
		return $this->db->get_where('topic', array('id'=>$topic_id))->row();
		// return $this->db->query('select * from topic where id='.$topic_id)->result();
	}
	function delete($topic_id){
        return $this->db->delete('topic', array('id'=>$topic_id));
    }
	function add($title, $description){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('topic', array(
			'title'=>$title,
			'description'=>$description
			));
		// echo $this->db->last_query();
		return $this->db->insert_id();
	}
}
?>