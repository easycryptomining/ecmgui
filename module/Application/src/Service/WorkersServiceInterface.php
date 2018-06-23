<?php
namespace Application\Service;

interface WorkersServiceInterface 
{
	/**
	 * Gets Workers by ID
	 *
	 * @param int $id
	 * @return WorkersInterface
	 */
	public function getWorkersById($id);
        
        /**
	 * Gets all wallets or by filter
	 *
	 * @return UserInterface
	 */
	public function getWorkersByFilter();
        
        /**
	 * Insert new wallets
	 *
	 * @param array $data
	 * @return mixed|null
	 */
	public function insertWorkers($data);

	/**
	 * Updates Workers
	 *
	 * @param array $data
	 * @param Where|string|array|\Closure $where
	 * @return bool
	 */
	public function updateWorkers($data, $where);
	
        /**
	 * Deletes wallets
	 *
	 * @param Where|\Closure|string|array $where
	 * @param bool
	 */
	public function deleteWorkers($where);
}

