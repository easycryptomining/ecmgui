<?php
namespace Application\Service;

interface WalletsServiceInterface 
{
	/**
	 * Gets Wallets by ID
	 *
	 * @param int $id
	 * @return WalletsInterface
	 */
	public function getWalletsById($id);
        
        /**
	 * Gets all wallets or by filter
	 *
	 * @return UserInterface
	 */
	public function getWalletsByFilter();
        
        /**
	 * Insert new wallets
	 *
	 * @param array $data
	 * @return mixed|null
	 */
	public function insertWallets($data);

	/**
	 * Updates Wallets
	 *
	 * @param array $data
	 * @param Where|string|array|\Closure $where
	 * @return bool
	 */
	public function updateWallets($data, $where);
	
        /**
	 * Deletes wallets
	 *
	 * @param Where|\Closure|string|array $where
	 * @param bool
	 */
	public function deleteWallets($where);
}

