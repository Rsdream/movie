<?php

class Index extends Controller
{
	public function __construct()
	{
		$this->model = new Model('movie');
		$this->morder = new Model('morder');
	}

	public function indexs()
	{
		$sql = 'select m.* from movie m inner join relss r on m.id = r.m_id group by id';
		$this->indexInfo = $this->model->query($sql);
		$this->display('index');
	}
	public function detail()
	{
		$sql = 'select id,h_id,start_time,end_time,m_price,seating from relss where m_id='.$_GET['id'];
		$this->movieInfo = $this->model->query($sql);
		$sql = 'select m_name,m_type,country_area,m_time,m_director,actor,content,picurl from movie where id='.$_GET['id'];
		$this->indexInfo = $this->model->query($sql);
		$this->display('detail');
	}

	public function seat()
	{
		$sql = 'select m_id,time,m_name,m_time,id,h_id,start_time,end_time,m_price,seating from relss where id='.$_GET['id'];
		$this->movieInfo = $this->model->query($sql);
		$sql = 'select * from hall where id='.$_GET['hid'];
		$this->seatInfo = $this->model->query($sql);
		$this->display('seat');
	}

	public function addOrder()
	{
		var_dump($_POST);
		print_r($this->morder->select());
	}
}