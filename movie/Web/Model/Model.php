<?php
//自定义Model类，封装了PDO

class Model
{
	protected $pdo = null;
	protected $tabName; //表名
	protected $pk = "id"; //主键字段名
	protected $fields = array(); //当前表字段名信息
	protected $limit = null;//分页信息
	protected $where = array();//封装搜索条件信息
	protected $order = null;//封装排序条件信息
	//构造方法
	public function __construct($tabName)
	{
		$this->tabName = $tabName;
		$this->pdo = new PDO(DSN,USER,PASS);
		//加载字段信息
		$this->loadFields();
	}
	
	//私有方法获取当前表的字段信息
	private function loadFields()
	{
		$sql  = "desc {$this->tabName}";
		$stmt = $this->pdo->query($sql);
		$list = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($list as $vo){
			//封装字段信息
			$this->fields[] = $vo['Field'];
			//判断是否是主键
			if($vo['Key']=="PRI"){
				$this->pk = $vo['Field']; 
			}
		}
	}
	
	//获取信息
	public function select()
	{
		$sql = "select * from {$this->tabName}";
	  if(count($this->where)>0){
			$sql .= " where ".implode(" and ",$this->where);
		}
		//判断并封装排序条件
		if(count($this->order)>0){
			$sql .= " order by ".$this->order;
		}
		//判断并封装分页语句
		if(!empty($this->limit)){
			$sql .= " limit ".$this->limit;
		}
		// echo $sql;
		$stmt = $this->pdo->query($sql);
		//清空搜索,分页等语句
		$this->where = array();
		$this->order = null;
		$this->limit = null;
		return $stmt->fetchAll(PDO::FETCH_ASSOC);

	}
	
	//获取传递sql执行语句
	public function query($sql)
	{
		//$sql = "select * from {$this->tabName}";
		$stmt = $this->pdo->query($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//获取单条信息
	public function find($id)
	{
		$sql = "select * from {$this->tabName} where {$this->pk}={$id}";
		$stmt = $this->pdo->query($sql);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	//执行删除
	public function del($id)
	{
		$sql = "delete from {$this->tabName} where {$this->pk}={$id}";
		return $this->pdo->exec($sql);
	}
	//按条件删除
	public function dels($a)
	{
		$sql = "delete from {$this->tabName} where orderid = '{$a}'";
		return $this->pdo->exec($sql);
	}
	
	//执行添加
	public function insert($data=array())
	{
		//判断参数若没有值则尝试采用POST中获取
		if(empty($data)){
			$data = $_POST;
		}
		$fieldlist = array(); //定义用于封装字段的变量
		$valuelist = array(); //定义用于封装值的变量
		//遍历要添加的信息并封装
		foreach($data as $k=>$v){
			//判断k是否为有效字段
			if(in_array($k,$this->fields)){
				$fieldlist[] = $k;
				$valuelist[] = "'".$v."'";
			} 
		}
		//拼装添加sql语句
		$sql = "insert into {$this->tabName}(".implode(",",$fieldlist).") values(".implode(",",$valuelist).")";
		//执行返回影响行数
		//echo $sql;
		return $this->pdo->exec($sql);
	}
	
	//执行修改
	public function update($data=array())
	{
		//判断参数若没有值则尝试采用POST中获取
		if(empty($data)){
			$data = $_POST;
		}
		$fieldlist = array(); //定义用于存储修改信息
		//遍历要修改的信息并封装
		foreach($data as $k=>$v){
			//判断k是否为有效字段,并且不为主键
			if(in_array($k,$this->fields) && $k!=$this->pk){
				$fieldlist[] = "{$k}='{$v}'";
			} 
		}
		//拼装修改sql语句
		$sql = "update {$this->tabName} set ".implode(",",$fieldlist)." where {$this->pk}={$data[$this->pk]}";
		//执行返回影响行数
		//echo $sql;
		return $this->pdo->exec($sql);
	}
	
	public function uptime($a)
	{
		$sql = "update users set lasttime='".time()."' where username='{$a}'";
		$this->pdo->exec($sql);
	}
	
 	public function count()
	{
		$sql = "select count(*) as num from {$this->tabName}";
		//判断并封装搜索语句
		if(count($this->where)>0){
			$sql .= " where ".implode(" and ",$this->where);
		}
		$stmt = $this->pdo->query($sql);
		$vo = $stmt->fetch(PDO::FETCH_ASSOC);
		return $vo['num'];//返回结果
	}
	//封装where条件
	public function where($where)
	{
		$this->where[] = $where;
		return $this;
	}
	//封装order by排序
	public function order($order)
	{
		$this->order = $order;
		return $this;
	}
	//封装分页limit
	public function limit($m,$n=0)
	{
		if($n == 0){
			$this->limit = $m;
		}else{
			$this->limit = $m.','.$n;
		}
		return $this;
	}
}