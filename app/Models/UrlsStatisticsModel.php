<?php

namespace App\Models;

use CodeIgniter\Model;

class UrlsStatisticsModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'urls_statistics';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];


	public function getStatisticsByUrlsIDAndDate($urls_id, $ust_date)
	{
		return $this->where('urls_id', $urls_id)->where('ust_date', $ust_date)->get()->getRowArray();
	}

	public function listDateDayByShort($path, $day = 7)
	{
		return $this->db->table($this->table)->join('urls', $this->table . '.urls_id = urls.urls_id', 'left')->where('urls.urls_short', $path)->where($this->table . '.ust_date BETWEEN DATE_SUB(NOW(), INTERVAL ' . $day . ' DAY) AND NOW()')->orderBy('urls.urls_id', 'DESC')->select($this->table . '.*')->get()->getResultArray();
	}

	public function update_ClickUrlsStatistics($urls_id)
	{
		return $this->where('urls_id', $urls_id)->where('ust_date', date('Y-m-d'))->increment('ust_click');
	}

	public function insert_urlsStatistics($data)
	{
		return $this->db->table($this->table)->insert($data);
	}
}
