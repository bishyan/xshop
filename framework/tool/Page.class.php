<?php


// 分页类

class Page {
	private $total; 		// 总的记录数
	private $page_num;		// 总的页数
	private $page_size;		// 每页显示的记录数
	private $current;		// 当前页
	private $url;			// url
	private $first;			// 首页
	private $last;			// 尾页
	private $prev;			// 上一页
	private $next;			// 下一页

	public function __construct($total, $page_size, $current, $script = '', $params = array()) {
		$this->total = $total;
		$this->page_size = $page_size;
		$this->current = $current;
		$this->page_num = $this->getNum();

		$this->url = $script . '?' . http_build_query($params) . '&page=';

		$this->getFirst();
		$this->getLast();
		$this->getPrev();
		$this->getNext();
	}


	private function getNum() {
		return ceil($this->total / $this->page_size);
	}


	private function getFirst() {
		if ($this->current == 1) {
			$this->first = '[第一页]';
		} else {
			$this->first = "<a href='{$this->url}1'>[第一页]</a>";
		}
	}

	private function getLast() {
		if ($this->current == $this->page_num) {
			$this->last = '[最末页]';
		} else {
			$this->last = "<a href='{$this->url}{$this->page_num}'>[最末页]</a>";
		}
	}

	private function getPrev() {
		if ($this->current == 1) {
			$this->prev = '[上一页]';
		} else {
			$this->prev = "<a href='{$this->url}" . ($this->current - 1) . "'>[上一页]</a>";
		}
	}


	private function getNext() {
		if ($this->current == $this->page_num) {
			$this->next = '[下一页]';
		} else {
			$this->next = "<a href='{$this->url}" . ($this->current + 1) . "'>[下一页]</a>";
		}
	}


	public function showPage() {
		if ($this->page_num > 1) {
			return "总计 {$this->total} 条记录, 每页显示 {$this->page_size} 条记录. 当前每 {$this->current} 页 / 共 {$this->page_num} 页  |  {$this->first}  {$this->prev}  {$this->next} {$this->last}";			
		} else {
			return "总计 {$this->total} 条记录.";
		}
	}
}