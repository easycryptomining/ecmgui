<?php
namespace Application\Service;

interface WemosServiceInterface 
{
	/**
	 * Gets Wemos by ID
	 *
	 * @param int $id
	 * @return WemosInterface
	 */
	public function getWemosById($id);
        
        /**
	 * Gets all wallets or by filter
	 *
	 * @return UserInterface
	 */
	public function getWemosByFilter();
        
        /**
	 * Insert new wallets
	 *
	 * @param array $data
	 * @return mixed|null
	 */
	public function insertWemos($data);

	/**
	 * Updates Wemos
	 *
	 * @param array $data
	 * @param Where|string|array|\Closure $where
	 * @return bool
	 */
	public function updateWemos($data, $where);
	
        /**
	 * Deletes wallets
	 *
	 * @param Where|\Closure|string|array $where
	 * @param bool
	 */
	public function deleteWemos($where);
}

