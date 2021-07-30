<?php

namespace App\Models;

use CodeIgniter\Model;

class UrlsModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'urls';
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


	public function getShortUrls($path = false)
	{
		if ($path) {
			return $this->where('urls_short', $path)->get()->getRowArray();
		}
	}

	public function getShortUrlsIDByShort($path = false)
	{
		if ($path) {
			return $this->select('urls_id')->where('urls_short', $path)->get()->getRowArray();
		}
	}

	public function listShortUrlsTop5ByCID($cid = false)
	{
		if ($cid) {
			$qr = $this->where('urls_cid', $cid)->orderBy('urls_id', 'DESC')->get(5)->getResultArray();
			return $qr;
		}
	}

	public function listShortUrlsTop20()
	{
		return $this->orderBy('urls_id', 'DESC')->get(20)->getResultArray();
	}

	public function update_ClickUrls($path)
	{
		return $this->where('urls_short', $path)->increment('urls_click');
	}

	public function insert_urls($data)
	{
		$this->db->table($this->table)->insert($data);
		return $this->insertID();
	}

	public function update_urls($data, $id)
	{
		return $this->db->table($this->table)->update($data, ['urls_id' => $id]);
	}

	public function delete_urls($id)
	{
		return $this->db->table($this->table)->delete(['urls_id' => $id]);
	}
}
